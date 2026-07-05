<?php

return [

    "api_key" => env('MASSIVE_API_KEY'),

    "base_url" => env('MASSIVE_BASE_URL', 'https://api.massive.com'),

    "ws_url" => env(
        "MASSIVE_WS_URL",
        "wss://delayed.massive.com/stocks"
    ),

    "retention_days" => 30,

    "bulk_insert_size" => 5000,

    "price_multiplier" => 10000,

    "subscriptions" => [

        "AM.*",

    ],

    'market_timezone' => 'America/New_York',

    'schedule' => [

        'partition' => '08:00',

        'sync_stocks' => '09:20',

        'stats' => '16:05',

        'backup' => '16:30',

        'cleanup' => '17:00',
    ],

];
