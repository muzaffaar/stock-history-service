<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Import\MarketDataImporter;

class TestImporterCommand extends Command
{
    protected $signature = 'history:test-importer';

    protected $description = 'Test importer';

    public function handle(MarketDataImporter $importer)
    {
        $importer->import([

            "ev" => "AM",
            "sym" => "AAPL",

            "o" => 201.12,
            "h" => 202.01,
            "l" => 200.90,
            "c" => 201.55,

            "v" => 15421,
            "vw" => 201.40,
            "n" => 341,

            "s" => now()->startOfMinute()->timestamp * 1000,
        ]);

        $importer->flush();

        $this->info("Imported successfully.");

        return self::SUCCESS;
    }
}
