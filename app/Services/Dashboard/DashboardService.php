<?php

namespace App\Services\Dashboard;

use App\Models\DatabaseStat;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function data(): array
    {
        return [

            'live' => $this->live(),

            'today' => $this->today(),

            'history' => $this->history(),

            'database' => [
                'size_mb' => round(
                    DB::selectOne("
                        SELECT pg_database_size(current_database()) AS size
                    ")->size / 1024 / 1024,
                    2
                ),
            ],

        ];
    }

    private function live(): array
    {
        return Cache::get('history.status', []);
    }

    private function today(): ?DatabaseStat
    {
        return DatabaseStat::query()
            ->latest('date')
            ->first();
    }

    private function history()
    {
        return DatabaseStat::query()
            ->latest('date')
            ->limit(30)
            ->get();
    }
}
