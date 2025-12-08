<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

// Controller notifikasi untuk user (lihat dan tandai dibaca)
class NotificationController extends Controller
{
    // Menampilkan semua notifikasi milik user yang login (view sederhana)
    public function index(): View
    {
        $user = Auth::user();

        $notifications = Notification::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();

        return view('user.notifications.index', compact('notifications'));
    }

    // Menandai satu notifikasi sebagai read
    public function markAsRead(int $id): RedirectResponse
    {
        $user = Auth::user();

        $notification = Notification::where('user_id', $user->id)
            ->findOrFail($id);

        if ($notification->status !== 'read') {
            $notification->status = 'read';
            $notification->save();
        }

        return redirect()->back();
    }
}
