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

                open BIGINT NOT NULL,
                high BIGINT NOT NULL,
                low BIGINT NOT NULL,
                close BIGINT NOT NULL,

                volume BIGINT NOT NULL,
                accumulated_volume BIGINT NOT NULL,

                vwap BIGINT NOT NULL,

                transactions BIGINT NOT NULL,

                PRIMARY KEY (ticker, minute)
            )
            PARTITION BY RANGE (minute);
        ");
    }

    public function down(): void
    {
        DB::unprepared('
            DROP TABLE IF EXISTS minute_aggregates CASCADE;
        ');
    }
};
