<?php

namespace App\Services\Monitoring;

use App\Services\Import\AggregateBuffer;
use App\Services\Massive\CollectorMetrics;
use Illuminate\Support\Facades\Cache;

class MonitoringService
{
    public function __construct(
        private readonly CollectorMetrics $metrics,
        private readonly AggregateBuffer $buffer,
    ) {}

    public function publish(): void
    {
        Cache::forever('history.status', [

            'connected' => true,

            'market' => 'OPEN',

            'messages' => $this->metrics->messages,

            'aggregates' => $this->metrics->aggregates,

            'inserted' => $this->metrics->inserted,

            'buffered' => $this->buffer->count(),

            'memory_mb' => round(memory_get_usage(true) / 1024 / 1024,2),

            'uptime' => $this->metrics->uptime(),

            'reconnects' => $this->metrics->reconnects,

            'last_message' => optional($this->metrics->lastMessageAt)
                ?->toDateTimeString(),

            'updated_at' => now()->toDateTimeString(),

        ]);
    }
}
