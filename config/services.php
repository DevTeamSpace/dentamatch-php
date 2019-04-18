<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key'    => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model'  => \App\Models\RecruiterProfile::class,
        'key'    => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET_KEY'),
        'webhook' => [
            'secret' => env('STRIPE_WEBHOOK_SECRET'),
            'tolerance' => env('STRIPE_WEBHOOK_TOLERANCE', 300),
            'url' => env('STRIPE_WEBHOOK_URL')
        ],
    ],

    'stripePlans' => [
        'monthly'    => env('STRIPE_PLAN_MONTHLY'),
        'semiAnnual' => env('STRIPE_PLAN_SEMI_ANNUAL'),
        'annual'     => env('STRIPE_PLAN_ANNUAL'),
    ],

    'stripeCoupons' => [
        'semiAnnual' => env('STRIPE_COUPON_SEMI_ANNUAL'),
        'annual'     => env('STRIPE_COUPON_ANNUAL'),
    ],

    'zipcode' => [
        'key'    => env('ZIPCODE_KEY'),
    ],

    'aws' => [
        'region' => env('AWS_REGION'),
        'bucket' => env('AWS_BUCKET'),
        'access_key_id' => env('AWS_ACCESS_KEY_ID'),
        'secret_access_key' => env('AWS_SECRET_ACCESS_KEY'),
        'url' => env('AWS_URL'),
    ]

];
