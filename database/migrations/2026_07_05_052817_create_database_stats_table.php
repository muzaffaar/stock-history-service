<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('database_stats', function (Blueprint $table) {
            $table->id();

            $table->date('date')->unique();

            $table->unsignedBigInteger('database_size_mb')->default(0);

            $table->unsignedBigInteger('rows_total')->default(0);

            $table->unsignedBigInteger('rows_today')->default(0);

            $table->unsignedInteger('ticker_count')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('database_stats');
    }
};
