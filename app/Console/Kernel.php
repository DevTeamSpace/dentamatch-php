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
        Commands\UnverifiedJobseekerCommand::class,
        Commands\SetAvailabilityCommand::class,
        Commands\InvitedJobseekerCommand::class,
        Commands\SubscriptionOneDayCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('user:profileCompletion')->daily()->withoutOverlapping();
        $schedule->command('user:certificateExpiry')->daily()->withoutOverlapping();
        //$schedule->command('notify:adminNotification')->everyTenMinutes()->withoutOverlapping();
        $schedule->command('notify:tempJobExpiryNotification')->daily()->withoutOverlapping();
        $schedule->command('notify:tempJobRatingNotification')->daily()->withoutOverlapping();
        $schedule->command('jobseeker:unverified')->daily()->withoutOverlapping();
        $schedule->command('user:availability')->daily()->withoutOverlapping();
        $schedule->command('user:pendinginvites')->daily()->withoutOverlapping();
        $schedule->command('user:check-subscription')->everyTenMinutes()->withoutOverlapping();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
        $this->load(__DIR__.'/Commands');
    }
}
