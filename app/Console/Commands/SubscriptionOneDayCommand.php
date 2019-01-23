<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SubscriptionPayments;
use App\Models\User;
use App\Models\Notification;
use App\Models\RecruiterProfile;
use DB;

class SubscriptionOneDayCommand extends Command
{
    const NOTIFICATION_INTERVAL = 600; //in sec
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:onedaysubscription';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cron to send push notification for subscription one day left';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
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
            $recruiterModel = SubscriptionPayments::select('subscription_payments.payment_id', 'subscription_payments.subscription_expiry_date','subscription_payments.trial_end', 'recruiter_profiles.user_id','recruiter_profiles.customer_id')
                                ->join('recruiter_profiles', 'recruiter_profiles.user_id','=','subscription_payments.recruiter_id')
                                ->where(DB::raw("TIMESTAMPDIFF(SECOND, subscription_payments.subscription_expiry_date, now())"),'<=', static::NOTIFICATION_INTERVAL)
                                ->where("TIMESTAMPDIFF(SECOND, subscription_payments.subscription_expiry_date, now())>0")
                                //->where('recruiter_profiles.is_subscribed',1)
                                ->get();
            $list = $recruiterModel->toArray();
            if(!empty($list)) {
                $insertData = [];
                foreach($list as $listValue)
                {
                    $customer = \Stripe\Customer::retrieve($listValue['customer_id']);
                    $isSubscribed=0;
                    if(!empty($customer->subscriptions['data'])){
                        foreach($customer->subscriptions['data'] as $subscription){
                            $current_period_end = date('Y-m-d H:i:s',strtotime($subscription['current_period_end']));
                        }
                        if($current_period_end>$listValue['subscription_expiry_date']){
                            if($listValue['trial_end']!=$listValue['subscription_expiry_date']){
                                $data = ['image' => url('web/images/dentaMatchLogo.png'),'message' => 'You subscription has been renewed to'.' '.$listValue['subscription_expiry_date']];
                                $insertData = ['sender_id' => $senderId->id, 'receiver_id' => $listValue['user_id'], 'notification_data'=> json_encode($data)];  
                                Notification::insert($insertData);
                            }
                            $isSubscribed=1;
                            SubscriptionPayments::where('recruiter_id',$listValue['user_id'])
                                    ->update(['subscription_expiry_date' => date('Y-m-d  H:i:s', $current_period_end), 'payment_response' => json_encode($customer)]);
                        }
                    }
                    RecruiterProfile::where(['user_id' => $listValue['user_id']])
                            ->update(['is_subscribed'=>$isSubscribed]);
                }
             
             $this->info("Records added successfully");
            } else {
                $this->info("No records for insert");
            }
        } catch(\Exception $e) {
            $this->info($e->getMessage());
        }
    }
}
