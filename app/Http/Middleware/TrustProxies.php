<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

/**
 * Middleware untuk menangani proxy (biasanya dibiarkan default).
 */
class TrustProxies extends Middleware
{
    /**
     * Proxy yang dipercaya.
     *
     * @var array<int, string>|string|null
     */
    protected $proxies;

    /**
     * Header yang digunakan untuk mendeteksi IP client.
     * Konfigurasi ini kompatibel dengan versi Laravel kamu.
     */
    protected $headers =
        Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PORT |
        Request::HEADER_X_FORWARDED_PROTO;
}
