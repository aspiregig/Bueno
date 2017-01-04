<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, Mandrill, and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'mandrill' => [
        'secret' => env('MANDRILL_SECRET'),
    ],

    'ses' => [
        'key'    => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    // localhost:8000
   // 'facebook' => [
   //     'client_id'     => '904142363027319',
   //     'client_secret' => 'd60b248207ef917651b6a65d2a7aa890',
   //     'redirect'      => 'http://localhost:8000/login/facebook',
   // ],

   // 'google'  => [
   //   'client_id'     => '778174670309-n4t4s9dh2c5cbhf82g819ppj4l8artto.apps.googleusercontent.com',
   //   'client_secret' => 'pjsMh3wLVPhT7AKeQuG8_s4z',
   //   'redirect'      => 'http://localhost:8000/login/google'
   // ],
 
   //testing.bueno.kitchen
     // 'facebook' => [
     //     'client_id'     => '1737351253151237',
     //     'client_secret' => 'a85b1c79ec285a101e548532c03907cb',
     //     'redirect'      => 'http://testing.bueno.kitchen/login/facebook',
     // ],

     // 'google'  => [
     //     'client_id'     => '513879219899-iflrk0470lmuff4n6rg761inui41flmd.apps.googleusercontent.com',
     //     'client_secret' => 'Oeg7A4V_ZIIQbfjVRqkrLDe4',
     //     'redirect'      => 'http://testing.bueno.kitchen/login/google'
     // ]

    // staging.bueno.kitchen
    'facebook' => [
        'client_id'     => '1577401355916311',
        'client_secret' => '40d5bba47329a57abdd97b79942210b4',
        'redirect'      => 'http://bueno.kitchen/login/facebook',
    ],

    'google'  => [
        'client_id'     => '778964478143-rqc6dp5mvjon0rud16fjg4tpk7mpmtnt.apps.googleusercontent.com',
        'client_secret' => 'iADrQRWt-1qETgkKPnuml25P',
        'redirect'      => 'http://bueno.kitchen/login/google'
    ]

];
