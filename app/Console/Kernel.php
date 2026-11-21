<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {

        $schedule->command('roi:calculate')->daily();
        $schedule->command('binary:roi-matching')->monthly();
        // Matching income closes weekly; already-paid pairs are skipped via MatchingBonusHistory.
        $schedule->command('matching:closing')->weekly()->mondays()->at('00:30');
        $schedule->command('rank:closing')->daily();
       
    }


    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
