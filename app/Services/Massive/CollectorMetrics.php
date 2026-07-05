<?php

namespace App\Services\Massive;

use Carbon\Carbon;

class CollectorMetrics
{
    public int $messages = 0;

    public int $aggregates = 0;

    public int $inserted = 0;

    public int $reconnects = 0;

    public ?Carbon $startedAt = null;

    public ?Carbon $lastMessageAt = null;

    public function start(): void
    {
        $this->startedAt = now();
    }

    public function message(): void
    {
        $this->messages++;
        $this->lastMessageAt = now();
    }

    public function aggregate(): void
    {
        $this->aggregates++;
    }

    public function inserted(int $rows): void
    {
        $this->inserted += $rows;
    }

    public function reconnect(): void
    {
        $this->reconnects++;
    }

    public function uptime(): string
    {
        return $this->startedAt
            ? $this->startedAt->diffForHumans(now(), true)
            : '0 sec';
    }

    public function memory(): string
    {
        return round(memory_get_usage(true) / 1024 / 1024, 2) . ' MB';
    }

    public function status(): array
    {
        return [
            'messages'      => $this->messages,
            'aggregates'    => $this->aggregates,
            'inserted'      => $this->inserted,
            'reconnects'    => $this->reconnects,
            'memory'        => $this->memory(),
            'uptime'        => $this->uptime(),
            'last_message'  => $this->lastMessageAt?->diffForHumans(),
        ];
    }
}
