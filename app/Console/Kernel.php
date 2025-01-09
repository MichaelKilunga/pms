<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {
        // Add the contract update task
        $schedule->call(function () {
            Log::info('Scheduler task executed at: ' . now());
            \Illuminate\Support\Facades\Http::get('http://127.0.0.1:8000/update-contracts');
        })->everyMinute(); // Adjust the frequency as needed
    }



    /**
     * Register the commands for the application.
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
