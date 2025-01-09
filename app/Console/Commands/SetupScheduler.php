<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SetupScheduler extends Command
{
    protected $signature = 'setup:scheduler';
    protected $description = 'Set up the scheduler for your application';

    public function handle()
    {
        $this->info('Setting up the scheduler...');

        // Detect the platform (Windows vs Linux)
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $this->info('Running on Windows. Skipping cron job setup.');
        } else {
            // For Linux, proceed with crontab setup
            //detect root directory
            $cronJob = '* * * * * cd public_html/pillpoint && php artisan schedule:run >> /dev/null 2>&1';
            $cronTab = shell_exec('crontab -l');
            if (strpos($cronTab, $cronJob) === false) {
                $cronTab .= PHP_EOL . $cronJob;
                file_put_contents('/tmp/crontab.txt', $cronTab);
                shell_exec('crontab /tmp/crontab.txt');
                unlink('/tmp/crontab.txt');
                $this->info('Scheduler successfully set up on Linux.');
            } else {
                $this->info('Cron job already exists.');
            }
        }
    }
}
