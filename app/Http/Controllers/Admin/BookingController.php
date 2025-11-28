<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\TicketType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

// Controller booking untuk admin (lihat, approve, cancel semua booking)
class BookingController extends Controller
{
    // List semua booking
    public function index(): JsonResponse
    {
        $bookings = Booking::with(['user', 'event', 'ticketType'])
            ->orderByDesc('created_at')
            ->get();

        return response()->json($bookings);
    }

    // Detail satu booking
    public function show(Booking $booking): JsonResponse
    {
        $booking->load(['user', 'event', 'ticketType']);

        return response()->json($booking);
    }

    // Menyetujui booking: status pending -> approved, quota tiket berkurang
    public function approve(Booking $booking): RedirectResponse
    {
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

    // Membatalkan booking; jika sudah approved, kuota dikembalikan
    public function cancel(Booking $booking): RedirectResponse
    {
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
