<?php

namespace App\Services\Import;

use App\Models\Stock;
use App\Services\Cache\StockCacheService;
use App\Services\Massive\MassiveClient;

class StockSyncService
{
    public function __construct(
        private MassiveClient $client
    ) {}

    public function sync(): void
    {
        $response = $this->client->get('/v3/reference/tickers', [
            'market' => 'stocks',
            'active' => true,
            'limit' => 1000,
        ]);

        while (true) {

            $this->store($response['results']);

            if (! isset($response['next_url'])) {
                break;
            }

            $response = $this->client->getNext(
                $response['next_url']
            );
        }
    }

    private function store(array $stocks): void
    {
        $rows = collect($stocks)
            ->map(fn ($stock) => [

                'ticker' => $stock['ticker'],

                'name' => $stock['name'] ?? null,

                'exchange' => $stock['primary_exchange'] ?? null,

                'created_at' => now(),

                'updated_at' => now(),

            ])
            ->toArray();

        Stock::upsert(
            $rows,
            ['ticker'],
            ['name', 'exchange', 'updated_at']
        );
    }
}
