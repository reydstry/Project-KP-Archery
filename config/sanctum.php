<?php

use Laravel\Sanctum\Sanctum;

return [
    'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', (function () {
        $defaults = 'localhost,localhost:3000,127.0.0.1,127.0.0.1:8000,::1';

        $appUrl = env('APP_URL');
        if (!$appUrl) {
            return $defaults;
        }

        $host = parse_url($appUrl, PHP_URL_HOST);
        $port = parse_url($appUrl, PHP_URL_PORT);

        $extra = [];
        if ($host) {
            $extra[] = $host;
        }
        if ($host && $port) {
            $extra[] = $host . ':' . $port;
        }

        return $defaults . (count($extra) ? ',' . implode(',', $extra) : '');
    })())),

    'guard' => ['web'],

    'expiration' => null,

    'middleware' => [
        'verify_csrf_token' => Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
        'encrypt_cookies' => Illuminate\Cookie\Middleware\EncryptCookies::class,
    ],

];
