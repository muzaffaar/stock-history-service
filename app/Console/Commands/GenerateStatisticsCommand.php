<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Statistics\StatisticsService;

class GenerateStatisticsCommand extends Command
{
    protected $signature = 'history:stats';

    protected $description = 'Generate database statistics';

    public function handle(
        StatisticsService $service
    )
    {
        $service->generate();

        $this->info('Statistics generated.');

        return self::SUCCESS;
    }
}
