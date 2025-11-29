<?php

namespace App\Http\Middleware;

use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

/**
 * Middleware untuk enkripsi/dekripsi cookie.
 */
class EncryptCookies extends Middleware
{
    /**
     * Nama cookie yang tidak perlu dienkripsi.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];
}
