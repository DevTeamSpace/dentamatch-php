<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RecruiterJobs;
use App\Models\User;
use App\Models\Notification;
use DB;

class InactiveJobCommand extends Command
{
    const NOTIFICATION_INTERVAL = 30;
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
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $pushList = [];
        $senderId = User::getAdminUserDetailsForNotification();
        $recruiterModel = RecruiterJobs::select('recruiter_jobs.id', 'job_templates.user_id', 'job_titles.jobtitle_name', DB::raw('count(seeker_id) as numberOfJobs'))
                            ->join('job_templates', 'job_templates.id','=','recruiter_jobs.job_template_id')
                            ->join('job_titles', 'job_titles.id', '=', 'job_templates.job_title_id')
                            ->leftjoin('job_lists', 'job_lists.recruiter_job_id', '=', 'recruiter_jobs.id')
                            ->where(DB::raw("DATEDIFF(now(), recruiter_jobs.created_at)"),'=', static::NOTIFICATION_INTERVAL)
                            ->groupBy('recruiter_jobs.id')
                            ->get();
        $list = $recruiterModel->toArray();
        if(!empty($list)) {
            $pushList = array_map(function ($value) {
                            if($value['numberOfJobs']==0) {
                                return  $value;
                            }
                        }, $list);
        }
        if(!empty($pushList)) {
            $insertData = [];
            foreach($pushList as $listValue)
            {
                $data[] = ['image' => url('web/images/dentaMatchLogo.png'),'message' => "No job has been applied for last 30 days on ".$listValue['jobtitle_name']];
                $insertData[] = ['sender_id' => $senderId->id, 'receiver_id' => $listValue['user_id'], 'notification_data'=> json_encode($data)];
            }
            Notification::insert($insertData);
            $this->info("Records added successfully");
        } else {
            $this->info("No records for insert");
        }
        
    }
}
