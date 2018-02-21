<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserProfile;
use App\Models\Notification;
use App\Models\Device;
use DB;
use App\Providers\NotificationServiceProvider;
use Mail;

class UserProfileCompletionCommand extends Command
{
    const IS_COMPLETED = 0;
    const NOTIFICATION_INTERVAL = [1,2,3];
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
                    'notificationData' => "The profile completion is still pending.",
                    'notification_title'=>'Profile Completion Reminder',
                    'sender_id' => "",
                    'type' => 1,
                    'notificationType'=>Notification::OTHER
                );
        
        $userModel = UserProfile::select('jobseeker_profiles.user_id', 'users.email', 'jobseeker_profiles.first_name')
                        ->join('users', 'users.id', '=', 'jobseeker_profiles.user_id')
                        ->where('is_completed',static::IS_COMPLETED)
                        ->whereIn(DB::raw("DATEDIFF(now(), users.created_at)"), static::NOTIFICATION_INTERVAL)
                        ->get();
        
        if(!empty($userModel)) {
            foreach($userModel as $value) {
                $userId = $value->user_id;
                
                $notificationData['receiver_id'] = $userId;
                $params['data'] = $notificationData;
                
                $deviceModel = Device::getDeviceToken($userId);
                if($deviceModel) {
                    NotificationServiceProvider::sendPushNotification($deviceModel, $notificationData['notificationData'], $params);
                    $data = ['receiver_id'=>$userId, 'notification_data'=>$notificationData['notificationData'],'notification_type'=>Notification::OTHER];
                    Notification::createNotification($data);
                } else {
                    $name = $value->first_name;
                    $email = $value->email;
                    Mail::queue('email.incomplete-profile', ['name' => $name, 'email' => $email], function($message ) use($email,$name) {
                        $message->to($email, $name)->subject('Pending Profile');
                    });
                }
            }
        }
    }
}
