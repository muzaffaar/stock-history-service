<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('database_stats', function (Blueprint $table) {

            $table->string('partition')->nullable();

            $table->bigInteger('messages_received')->default(0);

            $table->bigInteger('aggregates_received')->default(0);

            $table->bigInteger('rows_inserted')->default(0);

            $table->integer('reconnects')->default(0);

            $table->decimal('peak_memory_mb', 10, 2)->default(0);

            $table->integer('runtime_seconds')->default(0);

            $table->boolean('backup_completed')->default(false);

            $table->decimal('backup_size_mb', 10, 2)->nullable();

        });
    }

    public function down(): void
    {
        Schema::table('database_stats', function (Blueprint $table) {

            $table->dropColumn([

                'partition',

                'messages_received',

                'aggregates_received',

                'rows_inserted',

                'reconnects',

                'peak_memory_mb',

                'runtime_seconds',

                'backup_completed',

                'backup_size_mb',

            ]);

        });
    }
};
