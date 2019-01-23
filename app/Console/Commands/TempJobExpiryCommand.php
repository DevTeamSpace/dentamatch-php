<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Notification;
use App\Models\TempJobDates;
use App\Models\Configs;
use DB;

class TempJobExpiryCommand extends Command
{
    const NOTIFICATION_INTERVAL = 30;
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
        try {
            $pushList = [];
            $configModel = Configs::where('config_name', 'RECURITERNOTIFY')->first();
            $notificationDays = $configModel->config_data;
            
            $senderId = User::getAdminUserDetailsForNotification();
            $tempJobModel = TempJobDates::select('recruiter_jobs.id', 'job_templates.user_id', 'job_titles.jobtitle_name')
                                ->selectRaw(DB::raw("DATEDIFF(max(temp_job_dates.job_date),now()) as maxDate"))
                                ->selectRaw(DB::raw("max(temp_job_dates.job_date) as job_date"))
                                ->join('recruiter_jobs', 'recruiter_jobs.id', '=', 'temp_job_dates.recruiter_job_id')
                                ->join('job_templates', 'job_templates.id','=','recruiter_jobs.job_template_id')
                                ->join('job_titles', 'job_titles.id', '=', 'job_templates.job_title_id')
                                ->leftjoin('job_lists', 'job_lists.recruiter_job_id', '=', 'recruiter_jobs.id')
                                //->where(DB::raw("DATEDIFF(temp_job_dates.job_date,now())"),'=', $notificationDays)                
                                ->groupBy('temp_job_dates.recruiter_job_id')
                                ->orderBy('temp_job_dates.job_date', 'desc')
                                ->having('maxdate','=',$notificationDays)
                                ->get();
            $list = $tempJobModel->toArray();
            if(!empty($list)) {
                $pushList = array_map(function ($value) {
                                    return  $value;
                            }, $list);
            }
            
            if(!empty($pushList)) {
                $insertData = [];
                foreach($pushList as $listValue)
                {
                    $data = ['image' => url('web/images/dentaMatchLogo.png'),'message' => "Temporary job for ".'<a href="/job/details/'.$listValue['id'].'"><b>'.$listValue['jobtitle_name']."</b></a> is expiring on ".date('l, d M Y',strtotime($listValue['job_date']))];                  
                    $insertData[] = ['sender_id' => $senderId->id, 'receiver_id' => $listValue['user_id'], 'notification_data'=> json_encode($data)];
                }
                Notification::insert($insertData);
                $this->info("Records added successfully");
            } else {
                $this->info("No records for insert");
            }
        } catch(\Exception $e) {
            $this->info($e->getMessage());
        }
    }
}
