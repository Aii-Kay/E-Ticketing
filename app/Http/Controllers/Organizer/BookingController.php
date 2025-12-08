<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Notification;
use App\Models\TicketType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BookingController extends Controller
{
    /**
     * Daftar semua booking untuk event yang dimiliki organizer (created_by = organizer_id).
     */
    public function index(): View
    {
        $organizerId = Auth::id();

        $bookings = Booking::with(['event', 'ticketType', 'user'])
            ->whereHas('event', function ($q) use ($organizerId) {
                $q->where('created_by', $organizerId);
            })
            ->orderByDesc('created_at')
            ->get();

        return view('organizer.bookings.index', compact('bookings'));
    }

    /**
     * Detail satu booking (JSON) – tetapi dibatasi hanya
     * booking untuk event milik organizer.
     */
    public function show(Booking $booking)
    {
        $booking->load(['event', 'ticketType', 'user']);

        // Keamanan: hanya boleh lihat booking utk event yg dia miliki
        if (!$booking->event || $booking->event->created_by !== Auth::id()) {
            abort(403, 'You are not allowed to view this booking.');
        }

        return response()->json($booking);
    }

    /**
     * Organizer menyetujui booking:
     * - hanya untuk event miliknya
     * - ubah status -> approved
     * - kurangi quota ticket_type
     * - kirim notifikasi ke user.
     */
    public function approve(Booking $booking): RedirectResponse
    {
        $booking->load(['event', 'ticketType', 'user']);

        // Keamanan: booking harus milik event yang dibuat organizer ini
        if (!$booking->event || $booking->event->created_by !== Auth::id()) {
            abort(403, 'You are not allowed to approve this booking.');
        }

        if ($booking->status === 'cancelled') {
            return redirect()
                ->back()
                ->with('error', 'Booking yang sudah dibatalkan tidak bisa disetujui.');
        }

        if ($booking->status === 'approved') {
            return redirect()
                ->back()
                ->with('status', 'Booking ini sudah dalam status approved.');
        }

        // Cek quota
        if ($booking->ticketType->quota < $booking->quantity) {
            return redirect()
                ->back()
                ->with('error', 'Quota tiket tidak mencukupi untuk menyetujui booking ini.');
        }

        DB::transaction(function () use ($booking) {
            $ticketType = TicketType::lockForUpdate()->findOrFail($booking->ticket_type_id);

            if ($ticketType->quota < $booking->quantity) {
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
            'title'   => 'Booking Disetujui (Organizer)',
            'message' => sprintf(
                'Booking #%d untuk event "%s" dengan tiket "%s" telah disetujui oleh organizer.',
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
     * Organizer membatalkan booking:
     * - hanya untuk event miliknya
     * - ubah status -> cancelled
     * - kalau sebelumnya approved, quota ticket_type dikembalikan
     * - kirim notifikasi ke user.
     */
    public function cancel(Booking $booking): RedirectResponse
    {
        $booking->load(['event', 'ticketType', 'user']);

        if (!$booking->event || $booking->event->created_by !== Auth::id()) {
            abort(403, 'You are not allowed to cancel this booking.');
        }

        if ($booking->status === 'cancelled') {
            return redirect()
                ->back()
                ->with('status', 'Booking ini sudah berstatus cancelled.');
        }

        DB::transaction(function () use ($booking) {
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
            'title'   => 'Booking Dibatalkan (Organizer)',
            'message' => sprintf(
                'Booking #%d untuk event "%s" telah dibatalkan oleh organizer.',
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
