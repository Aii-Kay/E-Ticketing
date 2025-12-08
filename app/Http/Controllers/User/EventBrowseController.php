<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class EventBrowseController extends Controller
{
    /**
     * Halaman detail event + form booking.
     */
    public function show(Event $event)
    {
        $event->load(['category', 'ticketTypes']);

        return view('user.events.show', [
            'event' => $event,
            'user'  => Auth::user(),
        ]);
    }
}
