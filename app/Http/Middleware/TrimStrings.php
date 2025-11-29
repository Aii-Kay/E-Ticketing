<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TrimStrings as Middleware;

/**
 * Middleware untuk menghapus spasi di awal/akhir input string.
 */
class TrimStrings extends Middleware
{
    /**
     * Field yang tidak akan di-trim.
     *
     * @var array<int, string>
     */
    protected $except = [
        'password',
        'password_confirmation',
    ];
}
