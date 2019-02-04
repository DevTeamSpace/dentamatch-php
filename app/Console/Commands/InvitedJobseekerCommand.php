<?php

namespace App\Console\Commands;

use App\Mail\PendingAccept;
use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Notification;
use App\Models\Device;
use DB;
use App\Providers\NotificationServiceProvider;
use Mail;

class InvitedJobseekerCommand extends Command
{
    const NOTIFICATION_INTERVAL = [1,2,3];
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
    protected $description = 'Cron to send push notification or email to set availability';

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
        $notificationData = array(
                    'notificationData' => "You have pending jobs to accept/reject",
                    'notification_title'=>'Pending Invites',
                    'sender_id' => "",
                    "type" =>1,
                    'notificationType'=>Notification::OTHER
                );
                
        $userModel = User::join('user_groups', 'user_groups.user_id', '=', 'users.id')
                        ->join('jobseeker_profiles','jobseeker_profiles.user_id' , '=','users.id')
                        ->join('job_lists','job_lists.seeker_id' , '=','users.id')
                        ->select(
                                'users.email','users.id',
                                'jobseeker_profiles.first_name',
                                'jobseeker_profiles.last_name',
                                'users.is_verified','users.is_active'
                                )
                        ->where('user_groups.group_id', 3)
                        ->where('job_lists.applied_status',1)
                        ->whereNotIn('job_lists.applied_status', [2,3,4,5])
                        ->whereIn(DB::raw("DATEDIFF(now(), job_lists.created_at)"), static::NOTIFICATION_INTERVAL)
                        ->groupBy('users.id')
                        ->orderBy('users.id', 'desc')
                        ->get();
        
        if(!empty($userModel)) {
            foreach($userModel as $value) {
                $userId = $value->id;
                
                $notificationData['receiver_id'] = $userId;
                $params['data'] = $notificationData;
                
                $deviceModel = Device::getDeviceToken($userId);
                if($deviceModel) {
                    NotificationServiceProvider::sendPushNotification($deviceModel, $notificationData['notificationData'], $params,$userId);
                    $data = ['receiver_id'=>$userId, 'notification_data'=>$notificationData['notificationData'],'notification_type'=>Notification::OTHER];
                    Notification::createNotification($data);
                } else {
                    $name = $value->first_name;
                    $email = $value->email;
                    Mail::to($email)->queue(new PendingAccept($name));
                }
            }
        }
    }
}
