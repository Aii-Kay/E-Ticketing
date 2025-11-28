<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Event;
use App\Models\TicketType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// Controller booking untuk user (membuat, melihat, dan membatalkan booking miliknya)
class BookingController extends Controller
{
    // Riwayat booking milik user yang sedang login
    public function index(): JsonResponse
    {
        $bookings = Booking::with(['event', 'ticketType'])
            ->where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->get();

        return response()->json($bookings);
    }

    // Detail satu booking milik user
    public function show(Booking $booking): JsonResponse
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'You can only view your own bookings.');
        }

        $booking->load(['event', 'ticketType']);

        return response()->json($booking);
    }

    // Membuat booking baru dengan status pending (quota belum berkurang)
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'event_id'       => 'required|exists:events,id',
            'ticket_type_id' => 'required|exists:ticket_types,id',
            'quantity'       => 'required|integer|min:1',
        ]);

        $event = Event::findOrFail($data['event_id']);

        // Pastikan ticket type memang milik event tersebut
        $ticketType = TicketType::where('id', $data['ticket_type_id'])
            ->where('event_id', $event->id)
            ->firstOrFail();

        Booking::create([
            'user_id'        => Auth::id(),
            'event_id'       => $event->id,
            'ticket_type_id' => $ticketType->id,
            'quantity'       => $data['quantity'],
            'status'         => 'pending',
        ]);

        // Backend saja, redirect balik (nanti bisa diarahkan ke halaman riwayat)
        return redirect()->back();
    }

    // User membatalkan booking miliknya
    // Jika status approved, kuota tiket dikembalikan; jika pending, hanya ubah status
    public function cancel(Booking $booking): RedirectResponse
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'You can only cancel your own bookings.');
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
