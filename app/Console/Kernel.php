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
        Commands\Inspire::class,
        Commands\UserProfileCompletionCommand::class,
        Commands\CertificateExpiryCommand::class,
        Commands\AppMessageCommand::class,
        Commands\InactiveJobCommand::class,
        Commands\TempJobExpiryCommand::class,
        Commands\TempJobRatingCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('user:profileCompletion')->hourly();
        $schedule->command('user:certificateExpiry')->hourly();
        $schedule->command('notify:adminNotification')->hourly();
        $schedule->command('notify:tempJobExpiryNotification')->hourly();
        $schedule->command('notify:tempJobRatingNotification')->hourly();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
