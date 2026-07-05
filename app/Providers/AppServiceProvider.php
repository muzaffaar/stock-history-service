<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Cache\StockCacheService;
use App\Services\Massive\CollectorMetrics;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(StockCacheService::class, function () {

            $cache = new StockCacheService();

            $cache->load();

            return $cache;
        });
        $this->app->singleton(CollectorMetrics::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
