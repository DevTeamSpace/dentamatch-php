<?php

namespace App\Console\Commands;

use App\Models\UserGroup;
use App\Utils\NotificationUtils;
use Illuminate\Console\Command;
use App\Models\JobSeekerTempAvailability;
use App\Models\User;
use DB;

class SetAvailabilityCommand extends Command
{
    const NOTIFICATION_INTERVAL = [1, 2, 3];
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:availability';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cron to send push notification or email to set availability';

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
        $availableUsers = JobSeekerTempAvailability::select('user_id')
            ->distinct()
            ->get()
            ->pluck('user_id')
            ->toArray();

        $userModel = User::join('user_groups', 'user_groups.user_id', '=', 'users.id')
            ->join('jobseeker_profiles', 'jobseeker_profiles.user_id', '=', 'users.id')
            ->select(
                'users.email', 'users.id',
                'jobseeker_profiles.first_name',
                'jobseeker_profiles.last_name',
                'jobseeker_profiles.is_completed',
                'users.is_active',
                'users.created_at'
            )
            ->where('user_groups.group_id', UserGroup::JOBSEEKER)
            ->whereNotIn('users.id', $availableUsers)
            ->where('is_fulltime', 0)
            ->where('is_parttime_monday', 0)
            ->where('is_parttime_tuesday', 0)
            ->where('is_parttime_wednesday', 0)
            ->where('is_parttime_thursday', 0)
            ->where('is_parttime_friday', 0)
            ->where('is_parttime_saturday', 0)
            ->where('is_parttime_sunday', 0)
            ->whereIn(DB::raw("DATEDIFF(now(), users.created_at)"), static::NOTIFICATION_INTERVAL)
            ->orderBy('users.id', 'desc')
            ->get();

        foreach ($userModel as $value) {
            $this->utils->notifySetAvailability($value);
        }
    }
}
