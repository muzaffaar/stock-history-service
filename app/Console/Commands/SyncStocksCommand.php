<?php

namespace App\Console\Commands;

use App\Services\Import\StockSyncService;
use Illuminate\Console\Command;

class SyncStocksCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'history:sync-stocks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(StockSyncService $service): int
    {
        $service->sync();

        $this->info('Stocks synchronized.');

        return self::SUCCESS;
    }
}
