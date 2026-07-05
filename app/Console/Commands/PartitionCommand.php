<?php

namespace App\Console\Commands;

use App\Services\Database\PartitionService;
use Illuminate\Console\Command;

class PartitionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'history:partition';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(PartitionService $service)
    {
        $service->createToday();

        $service->createTomorrow();

        $service->cleanup();

        $this->info('Partitions synchronized.');

        return self::SUCCESS;
    }
}
