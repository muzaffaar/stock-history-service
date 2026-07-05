<?php

namespace App\Services\Database;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PartitionService
{
    private const PREFIX = 'minute_aggregates_';

    public function create(Carbon $date): void
    {
        if ($this->exists($date)) {
            return;
        }

        $table = $this->tableName($date);

        $from = $date->copy()->startOfDay()->format('Y-m-d H:i:s');

        $to = $date->copy()->addDay()->startOfDay()->format('Y-m-d H:i:s');

        DB::statement("
            CREATE TABLE {$table}
            PARTITION OF minute_aggregates
            FOR VALUES FROM ('{$from}')
            TO ('{$to}');
        ");
    }

    public function exists(Carbon $date): bool
    {
        return DB::table('pg_tables')
            ->where('tablename', $this->tableName($date))
            ->exists();
    }

    public function drop(Carbon $date): void
    {
        DB::statement(
            "DROP TABLE IF EXISTS {$this->tableName($date)};"
        );
    }

    public function cleanup(): void
    {
        $this->drop(
            now()->subDays(
                config('history.retention_days')
            )
        );
    }

    public function createToday(): void
    {
        $this->create(now());
    }

    public function createTomorrow(): void
    {
        $this->create(
            now()->addDay()
        );
    }

    private function tableName(Carbon $date): string
    {
        return self::PREFIX . $date->format('Ymd');
    }
}
