<?php

namespace App\Services\Parser;

use App\Services\Cache\StockCacheService;

class AggregateParser
{
    public function __construct(
        private readonly StockCacheService $stocks
    ) {}

    public function parse(array $message): ?array
    {
        if (($message['ev'] ?? null) !== 'AM') {
            return null;
        }

        return [

            'ticker' => $message['sym'],

            'minute' => date('Y-m-d H:i:s', intval($message['s'] / 1000)),

            'open' => isset($message['o']) ? $this->price($message['o']) : null,

            'high' => isset($message['h']) ? $this->price($message['h']) : null,

            'low' => isset($message['l']) ? $this->price($message['l']) : null,

            'close' => isset($message['c']) ? $this->price($message['c']) : null,

            'volume' => $message['v'] ?? 0,

            'accumulated_volume' => $message['av'] ?? null,

            'vwap' => isset($message['vw']) ? $this->price($message['vw']) : null,

            'transactions' => $message['z'] ?? 0,
        ];
    }

    private function price(float $price): int
    {
        return (int) round(
            $price * config('history.price_multiplier')
        );
    }
}
