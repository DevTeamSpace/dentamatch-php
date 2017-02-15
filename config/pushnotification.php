<?php

return [

    'apple' => [
        'sandbox' => [
            'url' => 'ssl://gateway.sandbox.push.apple.com:2195',
            'pem_file' =>public_path('notification_pems') . '/DentaMatchDev.pem',
            'passphrase' => '1234'
        ],
        'production' => [
            'url' => 'ssl://gateway.push.apple.com:2195',
            'pem_file' => public_path('notification_pems') . '/DentaMatchDist.pem',
            'passphrase' => '1234'
        ]
    ],
    //using fcm (firebase cloud messaging)
    'android' => [
        'server_key' => 'AIzaSyDo_lbxhEr0i5PJmrUDzOhfDQQQvoVTKmI',
        'url' => 'https://android.googleapis.com/gcm/send'
    ]
];
