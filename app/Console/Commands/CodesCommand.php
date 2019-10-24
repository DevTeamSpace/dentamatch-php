<?php

namespace App\Console\Commands;


use App\Enums\SubscriptionType;
use App\Models\PromoCode;
use Carbon\Carbon;
use Illuminate\Console\Command;


class CodesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'codes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create or update promo codes';

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
     */
    public function handle()
    {

        PromoCode::updateOrInsert(['code' => '4_FREE'], [
            'name' => 'free 4 4 months',
            'valid_days_from_sign_up' => null,
            'valid_until' => Carbon::today()->addMonth(5),
            'free_days' => 120,
            'subscription' => SubscriptionType::MONTHLY_NICKNAME,
        ]);

        PromoCode::updateOrInsert(['code' => '2_FREE'], [
            'name' => '<strong>Free trial</strong> for two months',
            'valid_days_from_sign_up' => null,
            'valid_until' => Carbon::today()->addMonth(1),
            'free_days' => 60,
            'subscription' => SubscriptionType::SEMI_ANNUAL_NICKNAME,
        ]);

        PromoCode::updateOrInsert(['code' => 'MONTH_FREE'], [
            'name' => '100% discount off first month',
            'valid_days_from_sign_up' => 30,
            'discount_on_subscription' => 0,
            'free_days' => 30,
            'subscription' => SubscriptionType::MONTHLY_NICKNAME,
        ]);

        PromoCode::updateOrInsert(['code' => 'EXTRA_MONTH'], [
            'name' => 'One month trial',
            'valid_days_from_sign_up' => 360,
            'free_days' => 30,
            'subscription' => SubscriptionType::ANNUAL_NICKNAME,
        ]);

        PromoCode::updateOrInsert(['code' => 'RARE'], [
            'name' => '100% discount off first month',
            'free_days' => 5,
            'subscription' => SubscriptionType::MONTHLY_NICKNAME,
        ]);

        PromoCode::updateOrInsert(['code' => 'NOT_ACTIVE'], [
            'name' => '20% discount off',
            'valid_days_from_sign_up' => 60,
            'discount_on_subscription' => 20,
            'active' => false,
            'subscription' => SubscriptionType::SEMI_ANNUAL_NICKNAME,
        ]);


    }
}
