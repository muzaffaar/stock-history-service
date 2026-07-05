<?php

namespace App\Services\Statistics;

use App\Models\DatabaseStat;
use App\Services\Massive\CollectorMetrics;
use Illuminate\Support\Facades\DB;

class StatisticsService
{
    public function __construct(
        private readonly CollectorMetrics $metrics,
    ) {}

    public function generate(): void
    {
        $table = 'minute_aggregates_' . now()->format('Ymd');

        $rowsToday = DB::table($table)->count();

        $tickerCount = DB::table('stocks')->count();

        $databaseSize = DB::selectOne("
            SELECT pg_database_size(current_database()) AS size
        ");

        $rowsTotal = $this->totalRows();

        DatabaseStat::updateOrCreate(

            [
                'date' => now()->toDateString(),
            ],

            [

                'rows_today' => $rowsToday,

                'rows_total' => $rowsTotal,

                'ticker_count' => $tickerCount,

                'database_size_mb' => round($databaseSize->size / 1024 / 1024, 2),

                'partition' => 'minute_aggregates_' . now()->format('Ymd'),

                'messages_received' => $this->metrics->messages,

                'aggregates_received' => $this->metrics->aggregates,

                'rows_inserted' => $this->metrics->inserted,

                'reconnects' => $this->metrics->reconnects,

                'peak_memory_mb' => round(memory_get_peak_usage(true) / 1024 / 1024, 2),

                'runtime_seconds' => $this->metrics->startedAt
                    ? now()->diffInSeconds($this->metrics->startedAt)
                    : 0,

            ]

        );
    }

    private function totalRows(): int
    {
        $tables = DB::select("
            SELECT tablename
            FROM pg_tables
            WHERE tablename LIKE 'minute_aggregates_%'
        ");

        $count = 0;

        foreach ($tables as $table) {

            $count += DB::table($table->tablename)->count();

        }

        return $count;
    }
}
