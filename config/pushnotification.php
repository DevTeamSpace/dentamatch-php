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
        'server_key' => 'AAAAzB0ZeR8:APA91bH1FaZKM9gpMGwa7lDdOA-PiEkQAvxvqPLfmCjrgCjYBaNd-nVGmRJaeHQuoBX6sIlROlJp_AIc8IvinLKN0Z2DOVGypIytbi0eOz2qxLWi9OpWy01rx4D66O1TQIul_gkZyoUJ',
        'url' => 'https://fcm.googleapis.com/fcm/send'
    ]
];
