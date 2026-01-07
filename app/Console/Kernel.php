<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('notifications:cleanup')
            ->weeklyOn(0, '03:00')
            ->withoutOverlapping();

        $schedule->command('ocorrencias:cleanup')
            ->weeklyOn(0, '03:10')
            ->withoutOverlapping();

        $schedule->command('messages:cleanup')
            ->weeklyOn(0, '03:20')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/messages-cleanup.log'));
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
