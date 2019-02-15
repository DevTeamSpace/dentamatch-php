<?php

namespace App\Console\Commands;

use App\Mail\PendingEmailVerification;
use App\Models\UserGroup;
use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class UnverifiedJobseekerCommand extends Command
{
    const IS_VERIFIED = 0;
    const NOTIFICATION_INTERVAL = [0, 3, 7];
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jobseeker:unverified';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cron to send email notification to unverified jobseekers';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::join('user_groups', 'user_groups.user_id', '=', 'users.id')
            ->join('jobseeker_profiles', 'jobseeker_profiles.user_id', '=', 'users.id')
            ->select(
                'users.email', 'users.id',
                'jobseeker_profiles.first_name',
                'jobseeker_profiles.last_name',
                'users.is_verified', 'users.is_active',
                'users.created_at', 'users.verification_code'
            )
            ->where('user_groups.group_id', UserGroup::JOBSEEKER)
            ->where('users.is_verified', 0)
            ->whereIn(DB::raw("DATEDIFF(now(), users.created_at)"), static::NOTIFICATION_INTERVAL)
            ->orderBy('users.id', 'desc')
            ->get()
            ->toArray();

        foreach ($users as $user) {
            $url = url("/verification-code/" . $user['verification_code']);
            $name = $user['first_name'];
            $email = $user['email'];
            Mail::to($email)->queue(new PendingEmailVerification($name, $url));
        }

    }
}
