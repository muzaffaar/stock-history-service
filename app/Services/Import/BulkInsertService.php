<?php

namespace App\Services\Import;

use App\Services\Massive\CollectorMetrics;
use Illuminate\Support\Facades\DB;

class BulkInsertService
{
    public function __construct(private readonly CollectorMetrics $metrics)
    {
        
    }

    public function insert(array $rows): void
    {
        if (empty($rows)) {
            return;
        }

        DB::table('minute_aggregates')->insert($rows);
        $this->metrics->inserted(count($rows));
    }
}
