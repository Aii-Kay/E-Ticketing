<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        $notifications = Notification::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();

        return view('organizer.notifications.index', [
            'user'          => $user,
            'notifications' => $notifications,
        ]);
    }

    public function markAsRead(int $id): RedirectResponse
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($notification->status !== 'read') {
            $notification->status = 'read';
            $notification->save();
        }

        return redirect()->back();
    }
}
