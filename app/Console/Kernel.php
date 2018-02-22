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
        Commands\SubscriptionCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('user:profileCompletion')->everyThirtyMinutes()->withoutOverlapping();
        $schedule->command('user:certificateExpiry')->everyTenMinutes()->withoutOverlapping();
        $schedule->command('notify:adminNotification')->everyTenMinutes()->withoutOverlapping();
        $schedule->command('notify:tempJobExpiryNotification')->daily()->withoutOverlapping();
        $schedule->command('notify:tempJobRatingNotification')->daily()->withoutOverlapping();
        $schedule->command('jobseeker:unverified')->everyThirtyMinutes()->withoutOverlapping();
        $schedule->command('user:availability')->everyThirtyMinutes()->withoutOverlapping();
        $schedule->command('user:pendinginvites')->everyThirtyMinutes()->withoutOverlapping();
        $schedule->command('user:subscription')->everyThirtyMinutes()->withoutOverlapping();
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
