<?php

namespace App\Console\Commands;

use App\Enums\JobType;
use App\Utils\NotificationUtils;
use Illuminate\Console\Command;
use App\Models\RecruiterJobs;
use DB;

class InactiveJobCommand extends Command
{
    const NOTIFICATION_INTERVAL = 30;

    private $utils;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:inactiveJobNotification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cron to notify if a particular job posting is inactive for 30 days';

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
        $jobs = RecruiterJobs::with(['jobTemplate.jobTitle'])
            ->where(DB::raw("DATEDIFF(now(), created_at)"), '=', static::NOTIFICATION_INTERVAL)
            ->whereIn('job_type', [JobType::FULLTIME, JobType::PARTTIME])
            ->whereDoesntHave('jobLists')
            ->get();

        $this->utils->notifyJobsInactive($jobs);

    }
}
