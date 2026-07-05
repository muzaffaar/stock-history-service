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
        Schema::create('trading_days', function (Blueprint $table) {
            $table->id();

            $table->date('date')->unique();
            $table->timestamp('market_open')->nullable();
            $table->timestamp('market_close')->nullable();

            $table->unsignedInteger('ticker_count')->default(0);

            $table->unsignedBigInteger('minute_rows')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trading_days');
    }
};
