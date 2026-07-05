<?php

namespace App\Console\Commands;

use App\Services\Database\PartitionService;
use Illuminate\Console\Command;

class CleanupPartitionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'history:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(PartitionService $service): int
    {
        $service->cleanup();

        $this->info('Old partitions removed.');

        return self::SUCCESS;
    }
}
