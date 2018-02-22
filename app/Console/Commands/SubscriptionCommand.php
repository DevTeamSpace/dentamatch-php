<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SubscriptionPayments;
use App\Models\User;
use App\Models\Notification;
use App\Models\Device;
use DB;
use App\Providers\NotificationServiceProvider;

class SubscriptionCommand extends Command
{
    const NOTIFICATION_INTERVAL = 7;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:subscription';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cron to send push notification for subscription';

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
            $senderId = User::getAdminUserDetailsForNotification();
            $recruiterModel = SubscriptionPayments::select('subscription_payments.payment_id', 'subscription_payments.subscription_expiry_date', 'recruiter_profiles.user_id')
                                ->join('recruiter_profiles', 'recruiter_profiles.user_id','=','subscription_payments.recruiter_id')
                                ->where(DB::raw("DATEDIFF(subscription_payments.subscription_expiry_date, now())"),'=', static::NOTIFICATION_INTERVAL)
                                ->where('recruiter_profiles.is_subscribed',1)
                                ->get();
            $list = $recruiterModel->toArray();
            if(!empty($list)) {
                $insertData = [];
                foreach($list as $listValue)
                {
                    $data = ['image' => url('web/images/dentaMatchLogo.png'),'message' => 'You subscription will expire on'.' '.$listValue['subscription_expiry_date']];
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
