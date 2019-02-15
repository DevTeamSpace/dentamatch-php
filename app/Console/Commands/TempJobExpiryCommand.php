<?php

namespace App\Console\Commands;

use App\Utils\NotificationUtils;
use Illuminate\Console\Command;
use App\Models\TempJobDates;
use App\Models\Configs;
use Illuminate\Support\Facades\DB;

class TempJobExpiryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:tempJobExpiryNotification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cron to notify X days before the temp job expires';

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
        $configModel = Configs::where('config_name', 'RECURITERNOTIFY')->first(); // todo refactor config
        $notificationDays = $configModel->config_data;

        $tempJobs = TempJobDates::select('recruiter_jobs.id', 'job_templates.user_id', 'job_titles.jobtitle_name')
            ->selectRaw(DB::raw("DATEDIFF(max(temp_job_dates.job_date),now()) as maxDate"))
            ->selectRaw(DB::raw("max(temp_job_dates.job_date) as job_date"))
            ->join('recruiter_jobs', 'recruiter_jobs.id', '=', 'temp_job_dates.recruiter_job_id')
            ->join('job_templates', 'job_templates.id', '=', 'recruiter_jobs.job_template_id')
            ->join('job_titles', 'job_titles.id', '=', 'job_templates.job_title_id')
            ->leftjoin('job_lists', 'job_lists.recruiter_job_id', '=', 'recruiter_jobs.id')
//            ->where(DB::raw("DATEDIFF(temp_job_dates.job_date,now())"),'=', $notificationDays) // todo old
            ->groupBy('temp_job_dates.recruiter_job_id')
            ->orderBy('temp_job_dates.job_date', 'desc')
            ->having('maxdate', '=', $notificationDays)
            ->get()->toArray();

        $this->utils->notifyTempJobsExpire($tempJobs);

    }
}
