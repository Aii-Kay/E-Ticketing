<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Event;
use App\Models\Notification;
use App\Models\TicketType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf; // Pastikan package dompdf terinstall jika ingin fitur download PDF

class BookingController extends Controller
{
    // Riwayat booking user
    public function index(): View
    {
        $user = Auth::user();

        // Mengambil booking dengan relasi event dan ticketType
        $bookings = Booking::with(['event', 'ticketType'])
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();

        return view('user.bookings.index', compact('user', 'bookings'));
    }

    // Form booking untuk event tertentu
    public function create(Request $request): View
    {
        $user = Auth::user();
        $eventId = $request->query('event_id');

        // Pastikan event ada
        $event = Event::with('ticketTypes')
            ->where('id', $eventId)
            ->firstOrFail();

        return view('user.bookings.create', compact('user', 'event'));
    }

    // Simpan booking baru
    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();

        // 1. Validasi Input
        $validated = $request->validate([
            'event_id'       => ['required', 'integer', 'exists:events,id'],
            'ticket_type_id' => ['required', 'integer', 'exists:ticket_types,id'],
            'quantity'       => ['required', 'integer', 'min:1'],
        ]);

        // 2. Gunakan Transaction untuk konsistensi data (Kuota & Booking)
        try {
            DB::beginTransaction();

            $event = Event::findOrFail($validated['event_id']);

            // Lock row ticket_type untuk mencegah race condition saat pengurangan kuota
            $ticketType = TicketType::where('id', $validated['ticket_type_id'])
                ->where('event_id', $event->id)
                ->lockForUpdate()
                ->firstOrFail();

            // 3. Cek Kuota Cukup
            if ($ticketType->quota < $validated['quantity']) {
                DB::rollBack();
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['quantity' => 'Maaf, kuota tiket tidak mencukupi. Sisa: ' . $ticketType->quota]);
            }

            // 4. Hitung Total Harga
            $totalPrice = $ticketType->price * $validated['quantity'];

            // 5. Kurangi Kuota
            $ticketType->quota -= $validated['quantity'];
            $ticketType->save();

            // 6. Buat Data Booking
            $booking = Booking::create([
                'user_id'        => $user->id,
                'event_id'       => $event->id,
                'ticket_type_id' => $ticketType->id,
                'quantity'       => $validated['quantity'],
                'total_price'    => $totalPrice, // Simpan harga total saat transaksi
                'status'         => 'pending', // Default pending menunggu organizer
            ]);

            // 7. Buat Notifikasi untuk Organizer
            if ($event->created_by) {
                Notification::create([
                    'user_id' => $event->created_by,
                    'title'   => 'Booking Baru Masuk! 🎫',
                    'message' => sprintf(
                        '%s memesan %d tiket %s untuk event "%s". Total: Rp %s',
                        $user->name,
                        $booking->quantity,
                        $ticketType->name,
                        $event->name,
                        number_format($totalPrice, 0, ',', '.')
                    ),
                    'status'  => 'unread',
                    'type'    => 'booking', // Opsional, jika kolom type ada
                ]);
            }

            // 8. Buat Notifikasi untuk User Sendiri (Konfirmasi)
            Notification::create([
                'user_id' => $user->id,
                'title'   => 'Pesanan Berhasil Dibuat',
                'message' => sprintf(
                    'Anda telah memesan %d tiket untuk event "%s". Mohon tunggu konfirmasi organizer.',
                    $booking->quantity,
                    $event->name
                ),
                'status'  => 'unread',
                'type'    => 'system',
            ]);

            DB::commit();

            return redirect()
                ->route('user.bookings.index')
                ->with('status', 'Booking berhasil dibuat! Menunggu persetujuan organizer.');

        } catch (\Exception $e) {
            DB::rollBack();
            // Log error jika perlu: \Log::error($e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat memproses booking. Silakan coba lagi.']);
        }
    }

    // Detail booking (JSON / debug)
    public function show(Booking $booking): JsonResponse
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Anda hanya bisa melihat booking milik sendiri.');
        }

        $booking->load(['event', 'ticketType']);

        return response()->json($booking);
    }

    // Cancel booking oleh user
    public function cancel(Booking $booking): RedirectResponse
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Anda hanya bisa membatalkan booking milik sendiri.');
        }

        // Jika sudah cancelled atau rejected, tidak perlu proses
        if (in_array($booking->status, ['cancelled', 'rejected'])) {
            return redirect()->back()->with('error', 'Booking sudah dibatalkan sebelumnya.');
        }

        $booking->load(['event', 'ticketType', 'user']);

        DB::transaction(function () use ($booking) {
            // Jika status sebelumnya approved atau pending, KEMBALIKAN KUOTA
            // (Asumsi: pending pun sudah mengurangi kuota di awal agar tidak oversold)
            if (in_array($booking->status, ['approved', 'pending'])) {
                $ticketType = TicketType::lockForUpdate()->find($booking->ticket_type_id);
                if ($ticketType) {
                    $ticketType->quota += $booking->quantity;
                    $ticketType->save();
                }
            }

            $booking->status = 'cancelled';
            $booking->save();
        });

        // Notifikasi ke User
        Notification::create([
            'user_id' => $booking->user_id,
            'title'   => 'Booking Dibatalkan',
            'message' => sprintf('Booking #%d untuk event "%s" berhasil dibatalkan.', $booking->id, $booking->event->name),
            'status'  => 'unread',
        ]);

        // Notifikasi ke Organizer
        if ($booking->event->created_by) {
            Notification::create([
                'user_id' => $booking->event->created_by,
                'title'   => 'Pembatalan Booking ❌',
                'message' => sprintf(
                    '%s membatalkan pesanan #%d untuk event "%s". Stok tiket dikembalikan.',
                    $booking->user->name,
                    $booking->id,
                    $booking->event->name
                ),
                'status'  => 'unread',
            ]);
        }

        return redirect()->back()->with('status', 'Booking berhasil dibatalkan.');
    }

    // Fitur Download Tiket (Opsional, jika menggunakan dompdf)
    public function downloadTicket(Booking $booking)
    {
        // Pastikan user pemilik booking dan status approved
        if ($booking->user_id !== Auth::id() || $booking->status !== 'approved') {
            abort(403, 'Tiket tidak tersedia atau Anda tidak memiliki akses.');
        }

        // Load view tiket PDF (Anda perlu membuat view ini: resources/views/pdf/ticket.blade.php)
        // Jika belum ada view pdf, bisa return view biasa atau string HTML sederhana

        $pdf = Pdf::loadView('pdf.ticket', compact('booking'));
        return $pdf->download('e-ticket-' . $booking->id . '.pdf');

        // Alternatif jika belum setup dompdf, redirect back aja dulu
        // return redirect()->back()->with('status', 'Fitur download PDF belum dikonfigurasi.');
    }
}
