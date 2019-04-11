<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Notification;
use App\Models\RecruiterProfile;
use Illuminate\Support\Facades\DB;

class SubscriptionOneDayCommand extends Command
{
    const NOTIFICATION_INTERVAL = 600; //in sec
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:check-subscription';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manual check for subscription renewal';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $senderId = User::getAdminUserDetailsForNotification();
            $subscriptions = Subscription::select(['subscriptions.subscription_id', 'subscriptions.subscription_expiry_date', 'subscriptions.trial_end',
                'recruiter_profiles.user_id', 'recruiter_profiles.customer_id'])
                ->join('recruiter_profiles', 'recruiter_profiles.user_id', '=', 'subscriptions.recruiter_id')
                ->where('subscriptions.cancel_at_period_end', 0)
//                ->where(DB::raw("TIMESTAMPDIFF(SECOND, subscriptions.subscription_expiry_date, now())"), '<=', static::NOTIFICATION_INTERVAL)
                ->where(DB::raw("TIMESTAMPDIFF(SECOND, subscriptions.subscription_expiry_date, now())"), '>', 0)
                ->get()->toArray();

            foreach ($subscriptions as $subscription) {
                $customer = \Stripe\Customer::retrieve($subscription['customer_id']);
                $isSubscribed = 0;
                if ($customer->subscriptions['data']) {
                    foreach ($customer->subscriptions['data'] as $s) {
                        $current_period_end = date('Y-m-d H:i:s', strtotime($s['current_period_end']));
                    }
                    if ($current_period_end > $subscription['subscription_expiry_date']) {
                        if ($subscription['trial_end'] != $subscription['subscription_expiry_date']) {
                            $data = ['image' => url('web/images/dentaMatchLogo.png'), 'message' => 'You subscription has been renewed to' . ' ' . $subscription['subscription_expiry_date']];
                            $insertData = ['sender_id' => $senderId->id, 'receiver_id' => $subscription['user_id'], 'notification_data' => json_encode($data)];
                            Notification::createNotification($insertData);
                        }
                        $isSubscribed = 1;
                        Subscription::where('recruiter_id', $subscription['user_id'])
                            ->update(['subscription_expiry_date' => date('Y-m-d  H:i:s', $current_period_end)]);
                    }
                }
                RecruiterProfile::where(['user_id' => $subscription['user_id']])
                    ->update(['is_subscribed' => $isSubscribed]);
            }

        } catch (\Exception $e) {
            $this->info($e->getMessage());
        }
    }
}
