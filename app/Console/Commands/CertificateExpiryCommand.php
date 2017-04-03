<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\JobseekerCertificates;
use App\Models\Notification;
use App\Models\User;
use App\Models\Device;
use DB;
use App\Providers\NotificationServiceProvider;

class CertificateExpiryCommand extends Command
{
    const NOTIFICATION_INTERVAL = 1;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:certificateExpiry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cron to send push notification on certificate expiry before 7 days';

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
        $todayDate = date('Y-m-d', strtotime("+". static::NOTIFICATION_INTERVAL." days"));
        $adminModel = User::getAdminUserDetailsForNotification();
        $certificateModel = JobseekerCertificates::select('jobseeker_certificates.certificate_id','jobseeker_certificates.user_id','certifications.certificate_name')
                        ->join('certifications', 'certifications.id', '=', 'jobseeker_certificates.certificate_id')
                        ->whereNull('jobseeker_certificates.deleted_at')
                        ->where('jobseeker_certificates.validity_date',$todayDate)
                        ->get();
        
        if(!empty($certificateModel)) {
            foreach($certificateModel as $value) {
                $userId = $value->user_id;
                $notificationData['receiver_id'] = $userId;
                $notificationData = array(
                    'message' => "7 days remaining for the expiry of ".$value->certificate_name,
                    'notification_title'=>'Certification Expiry Reminder',
                    'sender_id' => $adminModel->id,
                    'type' => Notification::OTHER
                );
                
                $params['data'] = $notificationData;
                
                $deviceModel = Device::getDeviceToken($userId);
                if($deviceModel) {
                    NotificationServiceProvider::sendPushNotification($deviceModel, $notificationData['message'], $params);
                    $data = ['sender_id'=>$adminModel->id,'receiver_id'=>$userId, 'notification_data'=>$notificationData['message'],'notification_type'=>Notification::OTHER];
                    Notification::createNotification($data);
                }
            }
        }
    }
}
