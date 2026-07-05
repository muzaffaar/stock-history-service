<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Massive\MassiveWebSocket;

class StreamMarketDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'history:stream';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start Massive websocket stream';

    /**
     * Execute the console command.
     */
    public function handle(MassiveWebSocket $socket)
    {
        $socket->run();

        return self::SUCCESS;
    }
}
