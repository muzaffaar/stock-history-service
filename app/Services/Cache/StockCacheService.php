<?php

namespace App\Services\Cache;

use App\Models\Stock;

class StockCacheService
{
    private array $stocks = [];

    public function load(): void
    {
        $this->stocks = Stock::pluck('id', 'ticker')->toArray();
    }

    public function id(string $ticker): ?int
    {
        return $this->stocks[$ticker] ?? null;
    }

    public function reload(): void
    {
        $this->load();
    }

    public function count(): int
    {
        return count($this->stocks);
    }
}
