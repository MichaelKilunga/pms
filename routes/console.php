<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Schedule daily pharmacy reports at 22:00 (10 PM)
// Schedule daily pharmacy reports at 22:00 (10 PM)
Schedule::command('pharmacy:send-daily-report')->dailyAt('23:59');
