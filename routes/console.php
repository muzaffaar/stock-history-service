<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


$tz = config('history.market_timezone');
$schedule = config('history.schedule');

Schedule::command('history:partition')
    ->timezone($tz)
    ->dailyAt($schedule['partition']);

Schedule::command('history:sync-stocks')
    ->timezone($tz)
    ->weekdays()
    ->dailyAt($schedule['sync_stocks']);

Schedule::command('history:stats')
    ->timezone($tz)
    ->weekdays()
    ->dailyAt($schedule['stats']);

Schedule::command('history:backup')
    ->timezone($tz)
    ->weekdays()
    ->dailyAt($schedule['backup']);

Schedule::command('history:cleanup')
    ->timezone($tz)
    ->dailyAt($schedule['cleanup']);
