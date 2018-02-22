<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserProfile;
use App\Models\JobSeekerTempAvailability;
use App\Models\User;
use App\Models\Notification;
use App\Models\Device;
use DB;
use App\Providers\NotificationServiceProvider;
use Mail;

class SetAvailabilityCommand extends Command
{
    const NOTIFICATION_INTERVAL = [1,2,3];
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:availability';

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
                    'notificationData' => "You had not yet set your availability dates",
                    'notification_title'=>'Set Availability',
                    'sender_id' => "",
                    'type' => 1,
                    'notificationType'=>Notification::OTHER
                );
        $availableUsers = JobSeekerTempAvailability::select('user_id')
                ->groupBy('user_id')
                ->get('user_id')
                ->map(function($query) {
                    return $query['user_id'];
                })->toArray();
        $userModel = User::join('user_groups', 'user_groups.user_id', '=', 'users.id')
                        ->join('jobseeker_profiles','jobseeker_profiles.user_id' , '=','users.id')
                        ->select(
                                'users.email','users.id',
                                'jobseeker_profiles.first_name',
                                'jobseeker_profiles.last_name',
                                'jobseeker_profiles.is_completed',
                                'users.is_active',
                                'users.created_at'
                                )
                        ->where('user_groups.group_id', 3)
                        ->whereNotIn('users.id', $availableUsers)
                        ->where('is_fulltime',0)
                        ->where('is_parttime_monday',0)
                        ->where('is_parttime_tuesday',0)
                        ->where('is_parttime_wednesday',0)
                        ->where('is_parttime_thursday',0)
                        ->where('is_parttime_friday',0)
                        ->where('is_parttime_saturday',0)
                        ->where('is_parttime_sunday',0)
                        ->whereIn(DB::raw("DATEDIFF(now(), users.created_at)"), static::NOTIFICATION_INTERVAL)
                        ->orderBy('users.id', 'desc')
                        ->get();

        if(!empty($userModel)) {
            foreach($userModel as $value) {
                $userId = $value->id;
                
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
                    Mail::queue('email.set-availability', ['name' => $name, 'email' => $email], function($message) use($email,$name) {
                        $message->to($email, $name)->subject('Set Availability');
                    });
                }
            }
        }
    }
}
