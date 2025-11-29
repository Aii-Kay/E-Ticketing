<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\TicketType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// Controller booking untuk organizer (hanya untuk event yang dia buat)
class BookingController extends Controller
{
    // List booking untuk semua event milik organizer ini
    public function index(): JsonResponse
    {
        $bookings = Booking::with(['user', 'event', 'ticketType'])
            ->whereHas('event', function ($query) {
                $query->where('created_by', Auth::id());
            })
            ->orderByDesc('created_at')
            ->get();

        return response()->json($bookings);
    }

    // Detail booking untuk event milik organizer
    public function show(Booking $booking): JsonResponse
    {
        if ($booking->event->created_by !== Auth::id()) {
            abort(403, 'You can only view bookings for your own events.');
        }

        $booking->load(['user', 'event', 'ticketType']);

        return response()->json($booking);
    }

    // Menyetujui booking untuk event milik organizer ini
    public function approve(Booking $booking): RedirectResponse
    {
        if ($booking->event->created_by !== Auth::id()) {
            abort(403, 'You can only approve bookings for your own events.');
        }

        if ($booking->status !== 'pending') {
            abort(422, 'Only pending bookings can be approved.');
        }

        DB::transaction(function () use ($booking) {
            $ticketType = TicketType::lockForUpdate()->findOrFail($booking->ticket_type_id);

            if ($ticketType->quota < $booking->quantity) {
                abort(422, 'Not enough ticket quota.');
            }

            $ticketType->quota -= $booking->quantity;
            $ticketType->save();

            $booking->status = 'approved';
            $booking->save();
        });

        return redirect()->back();
    }

    // Membatalkan booking untuk event milik organizer
    public function cancel(Booking $booking): RedirectResponse
    {
        if ($booking->event->created_by !== Auth::id()) {
            abort(403, 'You can only cancel bookings for your own events.');
        }

        if ($booking->status === 'cancelled') {
            return redirect()->back();
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

        return redirect()->back();
    }
}
