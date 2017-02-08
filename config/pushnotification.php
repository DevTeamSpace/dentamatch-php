<?php

return [

    'apple' => [
        'sandbox' => [
            'url' => 'ssl://gateway.sandbox.push.apple.com:2195',
            'pem_file' =>public_path('notification_pems') . '/DentaMatchDev.pem',
            'passphrase' => ''
        ],
        'production' => [
            'url' => 'ssl://gateway.push.apple.com:2195',
            'pem_file' => public_path('notification_pems') . '/DentaMatchDist.pem',
            'passphrase' => ''
        ]
    ],
    //using fcm (firebase cloud messaging)
    'android' => [
        'server_key' => 'AAAAvCDW54s:APA91bG5jXPH5bMt1CSYe_MJaQ045lLYxDpOeVvrNCP8Mj5cafT_RNnpUvuvbCKtt10ljivwstW-oG9RkPdziczKqqPrb98HGs03FWmI1zepogV4hJTJMJDFSYJjiFnoayQqSMh_4SJ_UnkCIzywR76mbJpkFctQNQ',
        'url' => 'https://fcm.googleapis.com/fcm/send'
    ]
];
