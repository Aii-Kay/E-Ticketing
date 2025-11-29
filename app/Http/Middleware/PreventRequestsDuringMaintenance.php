<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance as Middleware;

/**
 * Middleware untuk memblokir request saat aplikasi mode maintenance.
 */
class PreventRequestsDuringMaintenance extends Middleware
{
    /**
     * URL yang tetap boleh diakses saat maintenance.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];
}
