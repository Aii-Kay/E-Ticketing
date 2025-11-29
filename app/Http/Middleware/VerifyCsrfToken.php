<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

/**
 * Middleware proteksi CSRF untuk route web.
 */
class VerifyCsrfToken extends Middleware
{
    /**
     * URL yang dikecualikan dari verifikasi CSRF.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];
}
