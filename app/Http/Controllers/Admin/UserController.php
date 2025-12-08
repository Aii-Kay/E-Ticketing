<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Tampilkan semua user.
     */
    public function index(): View
    {
        // Semua user, urut terbaru dulu
        $users = User::orderBy('id', 'desc')->get();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Menyetujui organizer (ubah status pending -> approved).
     */
    public function approveOrganizer(int $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        // Validasi: harus organizer
        if ($user->role !== 'organizer') {
            abort(422, 'User is not an organizer.');
        }

        // Validasi status: hanya boleh approve dari pending
        if ($user->status !== 'pending') {
            abort(422, 'Only pending organizers can be approved.');
        }

        $user->status = 'approved';
        $user->save();

        // Notifikasi ke organizer bahwa akunnya sudah disetujui
        Notification::create([
            'user_id' => $user->id,
            'title'   => 'Akun Organizer Disetujui',
            'message' => 'Pengajuan akun organizer kamu telah disetujui oleh admin. ' .
                         'Sekarang kamu sudah bisa mengakses dashboard organizer.',
            'status'  => 'unread',
        ]);

        return redirect()->route('admin.users.index');
    }

    /**
     * Menolak organizer (ubah status pending -> rejected).
     */
    public function rejectOrganizer(int $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        // Validasi: harus organizer
        if ($user->role !== 'organizer') {
            abort(422, 'User is not an organizer.');
        }

        // Validasi status: hanya boleh reject dari pending
        if ($user->status !== 'pending') {
            abort(422, 'Only pending organizers can be rejected.');
        }

        $user->status = 'rejected';
        $user->save();

        // Notifikasi ke organizer bahwa pengajuan ditolak
        Notification::create([
            'user_id' => $user->id,
            'title'   => 'Akun Organizer Ditolak',
            'message' => 'Pengajuan akun organizer kamu ditolak oleh admin. ' .
                         'Silakan hubungi admin bila membutuhkan informasi lebih lanjut.',
            'status'  => 'unread',
        ]);

        return redirect()->route('admin.users.index');
    }

    /**
     * Menghapus user (kecuali dirinya sendiri).
     */
    public function destroy(int $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        // Tidak boleh hapus diri sendiri
        if ($user->id === Auth::id()) {
            abort(403, 'You cannot delete yourself.');
        }

        $user->delete();

        return redirect()->route('admin.users.index');
    }
}
