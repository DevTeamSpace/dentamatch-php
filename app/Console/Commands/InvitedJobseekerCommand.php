<?php

namespace App\Console\Commands;

use App\Enums\JobAppliedStatus;
use App\Models\UserGroup;
use App\Utils\NotificationUtils;
use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class InvitedJobseekerCommand extends Command
{
    const NOTIFICATION_INTERVAL = [1, 2, 3];
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:pendinginvites';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cron to send push notification or email about inactive job invite';

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
     */
    public function handle()
    {
        $userModel = User::join('user_groups', 'user_groups.user_id', '=', 'users.id')
            ->join('jobseeker_profiles', 'jobseeker_profiles.user_id', '=', 'users.id')
            ->join('job_lists', 'job_lists.seeker_id', '=', 'users.id')
            ->select(
                'users.email', 'users.id',
                'jobseeker_profiles.first_name',
                'jobseeker_profiles.last_name',
                'users.is_verified', 'users.is_active'
            )
            ->where('user_groups.group_id', UserGroup::JOBSEEKER)
            ->where('job_lists.applied_status', JobAppliedStatus::INVITED)
//                        ->whereNotIn('job_lists.applied_status', [2,3,4,5]) // todo why?
            ->whereIn(DB::raw("DATEDIFF(now(), job_lists.created_at)"), static::NOTIFICATION_INTERVAL)
            ->groupBy('users.id')
            ->orderBy('users.id', 'desc')
            ->get();

        foreach ($userModel as $value) {
            $this->utils->notifyInviteInactive($value);
        }
    }
}
