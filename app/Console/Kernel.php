<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('task:dueDateChecking')->dailyAt('08:00');
        $schedule->command('memo:scheduling')->everyMinute();
        $schedule->command('trainingProgram:checking')->everyMinute();
        //$schedule->command('leave:carriedForwardLeave')->yearlyOn(12, 31, '23:59');
        $schedule->command('leave:carriedForwardLeave')->yearlyOn(8, 30, '21:18');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
