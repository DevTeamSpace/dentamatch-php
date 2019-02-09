<?php

namespace App\Console\Commands;

use App\Enums\JobAppliedStatus;
use App\Enums\JobType;
use App\Utils\NotificationUtils;
use Illuminate\Console\Command;
use App\Models\TempJobDates;
use App\Models\Configs;
use DB;

class TempJobRatingCommand extends Command
{
    const NOTIFICATION_INTERVAL = 30;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:tempJobRatingNotification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cron to notify the recruiter X days after the temp job completion to rate the job seekers';

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
        $configModel = Configs::where('config_name', 'RATESEEKER')->first(); // todo config
        $notificationDays = $configModel->config_data;

        $cronDate = date('Y-m-d', strtotime("-" . $notificationDays . " days"));
        $tempJobs = TempJobDates::select('recruiter_jobs.id', 'job_templates.user_id', 'job_titles.jobtitle_name')
            ->addSelect(DB::raw("max(temp_job_dates.job_date) as max_date"))
            ->join('recruiter_jobs', 'recruiter_jobs.id', '=', 'temp_job_dates.recruiter_job_id')
            ->join('job_templates', 'job_templates.id', '=', 'recruiter_jobs.job_template_id')
            ->join('job_titles', 'job_titles.id', '=', 'job_templates.job_title_id')
            ->leftjoin('job_lists', 'job_lists.recruiter_job_id', '=', 'recruiter_jobs.id')
            //->where('temp_job_dates.job_date','=', $cronDate) // todo old
            ->having('max_date', '=', $cronDate)
            ->where("recruiter_jobs.job_type", JobType::TEMPORARY)
            ->where('job_lists.applied_status', JobAppliedStatus::HIRED)
            ->groupBy('temp_job_dates.recruiter_job_id')
            ->orderBy('temp_job_dates.job_date', 'desc')
            ->get()->toArray();

        $this->utils->notifyTempJobsRating($tempJobs);

    }
}
