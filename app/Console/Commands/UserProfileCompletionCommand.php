<?php

namespace App\Console\Commands;

use App\Utils\NotificationUtils;
use Illuminate\Console\Command;
use App\Models\UserProfile;
use Illuminate\Support\Facades\DB;

class UserProfileCompletionCommand extends Command
{
    const NOT_COMPLETED = 0;
    const NOTIFICATION_INTERVAL = [1, 2, 3];
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:profileCompletion';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cron to send push notification on user profile completion';

    private $utils;

    /**
     * Create a new command instance.
     *
     * @param NotificationUtils $utils
     */
    public function __construct(NotificationUtils $utils)
    {
        $this->utils = $utils;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users = UserProfile::select('jobseeker_profiles.user_id', 'users.email', 'jobseeker_profiles.first_name')
            ->join('users', 'users.id', '=', 'jobseeker_profiles.user_id')
            ->where('is_completed', static::NOT_COMPLETED)
            ->whereIn(DB::raw("DATEDIFF(now(), users.created_at)"), static::NOTIFICATION_INTERVAL)
            ->get();

        foreach ($users as $user) {
            $this->utils->notifyProfileIncomplete($user);
        }
    }
}
