<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BackupPartitionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'history:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = now()->toDateString();

        $table = 'minute_aggregates_' . now()->format('Ymd');

        $filename = storage_path(
            "backups/{$date}.sql"
        );

        $command = sprintf(
            'pg_dump -U %s -h %s -d %s -t %s > %s',
            env('DB_USERNAME'),
            env('DB_HOST'),
            env('DB_DATABASE'),
            $table,
            $filename
        );

        exec($command);

        exec("gzip -f {$filename}");
    }
}
