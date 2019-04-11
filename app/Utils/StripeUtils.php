<?php

namespace App\Utils;

use App\Enums\SubscriptionType;

class StripeUtils
{
    /**
     * @param $subscriptionType
     * @return string|null
     */
    public function getPlanId($subscriptionType) {
        switch ($subscriptionType) {
            case SubscriptionType::MONTHLY:
                return config('services.stripePlans.monthly');
            case SubscriptionType::SEMI_ANNUAL:
                return config('services.stripePlans.semiAnnual');
            case SubscriptionType::ANNUAL:
                return config('services.stripePlans.annual');
        }
        return null;
    }

    /**
     * @param $subscriptionType
     * @return string|null
     */
    public function getPlanIdByNickname($subscriptionType) {
        switch ($subscriptionType) {
            case SubscriptionType::MONTHLY_NICKNAME:
                return config('services.stripePlans.monthly');
            case SubscriptionType::SEMI_ANNUAL_NICKNAME:
                return config('services.stripePlans.semiAnnual');
            case SubscriptionType::ANNUAL_NICKNAME:
                return config('services.stripePlans.annual');
        }
        return null;
    }

}

