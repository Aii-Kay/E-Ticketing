<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Tampilkan form register.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Proses submit form register.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password'              => ['required', 'confirmed', Rules\Password::defaults()],
            'role'                  => ['required', 'in:registered_user,organizer'],
        ]);

        $role   = $request->input('role', 'registered_user');

        // registered_user: langsung approved
        // organizer: menunggu approval admin
        $status = $role === 'organizer' ? 'pending' : 'approved';

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $role,
            'status'   => $status,
        ]);

        event(new Registered($user));

        // Jika mendaftar sebagai ORGANIZER -> kirim notifikasi ke semua admin
        if ($role === 'organizer') {
            $admins = User::where('role', 'admin')->get();

            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'title'   => 'Pengajuan Akun Organizer Baru',
                    'message' => sprintf(
                        'User %s (%s) mendaftar sebagai organizer dan menunggu persetujuan.',
                        $user->name,
                        $user->email
                    ),
                    'status'  => 'unread',
                ]);
            }

            // Notifikasi ke user bahwa pengajuan sudah dikirim
            Notification::create([
                'user_id' => $user->id,
                'title'   => 'Pengajuan Organizer Dikirim',
                'message' => 'Pengajuan akun organizer kamu sudah diterima dan menunggu persetujuan admin.',
                'status'  => 'unread',
            ]);
        }

        Auth::login($user);

        // akan diarahkan ke role masing-masing lewat /dashboard
        return redirect()->route('dashboard');
    }
}
