<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserProfile;
use App\Models\Notification;
use App\Models\Device;
use DB;
use App\Providers\NotificationServiceProvider;

class UserProfileCompletionCommand extends Command
{
    const IS_COMPLETED = 0;
    const NOTIFICATION_INTERVAL = 15;
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
                    'message' => "The profile completion is still pending.",
                    'notification_title'=>'Registration Completion Reminder',
                    'sender_id' => "",
                    'type' => 1
                );
        
        $userModel = UserProfile::select('jobseeker_profiles.user_id')
                        ->join('users', 'users.id', '=', 'jobseeker_profiles.user_id')
                        ->where('is_completed',static::IS_COMPLETED)
                        ->where(DB::raw("DATEDIFF(now(), users.created_at)"),'=', 16)
                        ->get();
        
        if(!empty($userModel)) {
            foreach($userModel as $value) {
                $userId = $value->user_id;
                
                $notificationData['receiver_id'] = $userId;
                $params['data'] = $notificationData;
                
                $deviceModel = Device::getDeviceToken($userId);
                if($deviceModel) {
                    $this->info($userId);
                    NotificationServiceProvider::sendPushNotification($deviceModel, $notificationData['message'], $params);
                    
                    $data = ['receiver_id'=>$userId, 'notification_data'=>$notificationData['message']];
                    Notification::createNotification($data);
                }
            }
        }
    }
}
