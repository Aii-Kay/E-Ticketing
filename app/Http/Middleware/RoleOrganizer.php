<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleOrganizer
{
    public function handle(Request $request, Closure $next)
    {
        // Harus login
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Hanya role organizer + status approved yang boleh lanjut
        if ($user->role !== 'organizer' || $user->status !== 'approved') {
            abort(403, 'Akun organizer kamu belum disetujui oleh admin.');
        }

        return $next($request);
    }
}
