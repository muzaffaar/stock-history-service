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

        'partition' => '03:30',

        'sync_stocks' => '03:40',

        'stats' => '20:05',

        'backup' => '20:30',

        'cleanup' => '21:00',
    ],

];
