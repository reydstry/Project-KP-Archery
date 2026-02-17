<?php

return [
    'driver' => env('WHATSAPP_DRIVER', 'dummy'),

    'log_channel' => env('WHATSAPP_LOG_CHANNEL', env('LOG_CHANNEL', 'stack')),

    'drivers' => [
        'wablas' => [
            'base_url' => env('WABLAS_BASE_URL', 'https://tx.wablas.com/api'),
            'token' => env('WABLAS_TOKEN', ''),
            'secret_key' => env('WABLAS_SECRET_KEY', ''),
            'timeout' => env('WABLAS_TIMEOUT', 15),
            'send_message_path' => env('WABLAS_SEND_MESSAGE_PATH', '/send-message'),
            'send_bulk_path' => env('WABLAS_SEND_BULK_PATH', '/send-bulk-message'),
            'schedule_path' => env('WABLAS_SCHEDULE_PATH', '/send-schedule-message'),
        ],

        'dummy' => [],
    ],
];
