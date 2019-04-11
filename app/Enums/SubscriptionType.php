<?php

namespace App\Enums;

abstract class SubscriptionType  {
    const MONTHLY = 1;
    const SEMI_ANNUAL = 2;
    const ANNUAL = 3;

    const MONTHLY_NICKNAME = 'Monthly';
    const SEMI_ANNUAL_NICKNAME = 'Semi-Annual';
    const ANNUAL_NICKNAME = 'Annual';
}