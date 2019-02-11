<?php

namespace App\Console\Commands;

use App\Utils\NotificationUtils;
use Illuminate\Console\Command;
use App\Models\SubscriptionPayments;
use Illuminate\Support\Facades\DB;

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

    private $utils;

    /**
     * Create a new command instance.
     *
     * @param NotificationUtils $utils
     */
    public function __construct(NotificationUtils $utils)
    {
        $this->utils = $utils;
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $recruiterModel = SubscriptionPayments::select('subscription_payments.payment_id', 'subscription_payments.subscription_expiry_date', 'recruiter_profiles.user_id')
            ->join('recruiter_profiles', 'recruiter_profiles.user_id', '=', 'subscription_payments.recruiter_id')
            ->where(DB::raw("DATEDIFF(subscription_payments.subscription_expiry_date, now())"), '=', static::NOTIFICATION_INTERVAL)
            ->where('recruiter_profiles.is_subscribed', 1)
            ->get();
        $list = $recruiterModel->toArray();

        $this->utils->notifySubscriptionExpire($list);

    }
}
