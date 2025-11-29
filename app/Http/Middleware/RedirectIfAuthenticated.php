<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware guest: kalau sudah login, redirect ke dashboard sesuai role.
 */
class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();

                if ($user?->role === 'admin') {
                    return redirect()->route('admin.dashboard');
                }

                if ($user?->role === 'organizer') {
                    return redirect()->route('organizer.dashboard');
                }

                // default: registered_user
                return redirect()->route('user.dashboard');
            }
        }

        return $next($request);
    }
}
