<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Notification;
use App\Models\TicketType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BookingController extends Controller
{
    /**
     * Daftar semua booking (seluruh sistem).
     */
    public function index(): View
    {
        $bookings = Booking::with(['event', 'ticketType', 'user'])
            ->orderByDesc('created_at')
            ->get();

        return view('admin.bookings.index', compact('bookings'));
    }

    /**
     * Detail satu booking (JSON, bisa untuk debug / API ringan).
     */
    public function show(Booking $booking)
    {
        $booking->load(['event', 'ticketType', 'user']);

        return response()->json($booking);
    }

    /**
     * Admin menyetujui booking:
     * - ubah status -> approved
     * - kurangi quota ticket_type
     * - kirim notifikasi ke user.
     */
    public function approve(Booking $booking): RedirectResponse
    {
        // Kalau sudah cancelled, tidak bisa approve lagi
        if ($booking->status === 'cancelled') {
            return redirect()
                ->back()
                ->with('error', 'Booking yang sudah dibatalkan tidak bisa disetujui.');
        }

        // Kalau sudah approved, tidak perlu diproses lagi
        if ($booking->status === 'approved') {
            return redirect()
                ->back()
                ->with('status', 'Booking ini sudah dalam status approved.');
        }

        $booking->load(['ticketType', 'event', 'user']);

        // Cek quota sebelum approve
        if ($booking->ticketType->quota < $booking->quantity) {
            return redirect()
                ->back()
                ->with('error', 'Quota tiket tidak mencukupi untuk menyetujui booking ini.');
        }

        DB::transaction(function () use ($booking) {
            // lock row ticket_types
            $ticketType = TicketType::lockForUpdate()->findOrFail($booking->ticket_type_id);

            if ($ticketType->quota < $booking->quantity) {
                // safety double-check
                throw new \RuntimeException('Quota tidak cukup.');
            }

            $ticketType->quota -= $booking->quantity;
            $ticketType->save();

            $booking->status = 'approved';
            $booking->save();
        });

        // Notifikasi ke user
        Notification::create([
            'user_id' => $booking->user_id,
            'title'   => 'Booking Disetujui (Admin)',
            'message' => sprintf(
                'Booking #%d untuk event "%s" dengan tiket "%s" telah disetujui oleh admin.',
                $booking->id,
                $booking->event->name ?? '-',
                $booking->ticketType->name ?? '-'
            ),
            'status'  => 'unread',
        ]);

        return redirect()
            ->back()
            ->with('status', 'Booking berhasil disetujui dan quota tiket telah dikurangi.');
    }

    /**
     * Admin membatalkan booking:
     * - ubah status -> cancelled
     * - jika sebelumnya approved, kembalikan quota ticket_type
     * - kirim notifikasi ke user.
     */
    public function cancel(Booking $booking): RedirectResponse
    {
        // Kalau sudah cancelled, tidak perlu apa-apa
        if ($booking->status === 'cancelled') {
            return redirect()
                ->back()
                ->with('status', 'Booking ini sudah berstatus cancelled.');
        }

        $booking->load(['ticketType', 'event', 'user']);

        DB::transaction(function () use ($booking) {
            // Kalau sebelumnya approved, kembalikan quota
            if ($booking->status === 'approved') {
                $ticketType = TicketType::lockForUpdate()->findOrFail($booking->ticket_type_id);
                $ticketType->quota += $booking->quantity;
                $ticketType->save();
            }

            $booking->status = 'cancelled';
            $booking->save();
        });

        // Notifikasi ke user
        Notification::create([
            'user_id' => $booking->user_id,
            'title'   => 'Booking Dibatalkan (Admin)',
            'message' => sprintf(
                'Booking #%d untuk event "%s" telah dibatalkan oleh admin.',
                $booking->id,
                $booking->event->name ?? '-'
            ),
            'status'  => 'unread',
        ]);

        return redirect()
            ->back()
            ->with('status', 'Booking berhasil dibatalkan.');
    }
}
