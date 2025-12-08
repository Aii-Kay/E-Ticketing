<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Event;
use App\Models\Review;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Controller untuk fitur rating & review event oleh user
class ReviewController extends Controller
{
    /**
     * Menampilkan semua review untuk satu event (public, JSON).
     */
    public function index(Event $event): JsonResponse
    {
        $reviews = $event->reviews()
            ->with('user')
            ->latest()
            ->get();

        return response()->json($reviews);
    }

    /**
     * Menyimpan review baru dari user.
     * Aturan:
     * - Harus punya booking dengan status approved untuk event tsb.
     * - Tanggal event sudah lewat (event < hari ini).
     * - Rating 1–5, comment wajib.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'event_id' => 'required|exists:events,id',
            'rating'   => 'required|integer|min:1|max:5',
            'comment'  => 'required|string',
        ]);

        $user = Auth::user();
        $event = Event::findOrFail($data['event_id']);

        // Pastikan event sudah lewat (tanggal < sekarang)
        $eventDate = Carbon::parse($event->date);

        if (! $eventDate->isPast()) {
            return redirect()->back()->withErrors([
                'review' => 'Event ini belum selesai sehingga belum bisa direview.',
            ]);
        }

        // Pastikan user punya booking approved untuk event ini
        $hasApprovedBooking = Booking::where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->where('status', 'approved')
            ->exists();

        if (! $hasApprovedBooking) {
            return redirect()->back()->withErrors([
                'review' => 'Anda hanya bisa mereview event yang pernah Anda hadiri.',
            ]);
        }

        // Simpan review
        Review::create([
            'user_id'  => $user->id,
            'event_id' => $event->id,
            'rating'   => $data['rating'],
            'comment'  => $data['comment'],
        ]);

        return redirect()->back();
    }
}
