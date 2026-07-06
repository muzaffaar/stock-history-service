<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared("
            CREATE TABLE minute_aggregates
            (
                ticker VARCHAR(10) NOT NULL,

                minute TIMESTAMP NOT NULL,

                open INTEGER NOT NULL,
                high INTEGER NOT NULL,
                low INTEGER NOT NULL,
                close INTEGER NOT NULL,

                volume BIGINT NOT NULL,

                accumulated_volume BIGINT,

                vwap INTEGER,

                transactions INTEGER,

                PRIMARY KEY (ticker, minute)
            )
            PARTITION BY RANGE (minute);
        ");
    }

    public function down(): void
    {
        DB::unprepared("
            DROP TABLE IF EXISTS minute_aggregates CASCADE;
        ");
    }
};
