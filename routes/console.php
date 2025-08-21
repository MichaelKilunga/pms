<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Schedule daily pharmacy reports at 22:00 (10 PM)
// Schedule::command('pharmacy:send-daily-report')->dailyAt('22:00');
// SCHEDULE AFTER EVERY MINUTE
Schedule::command('app:send-daily-report')->everyMinute();
