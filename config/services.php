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
        'domain' => env('MAILGUN_DOMAIN', ''),
        'secret' => env('MAILGUN_SECRET', ''),
    ],

    'mandrill' => [
        'secret' => '',
    ],

    'ses' => [
        'key'    => '',
        'secret' => '',
        'region' => 'us-east-1',
    ],

    'stripe' => [
        'model'  => App\User::class,
        'key'    => '',
        'secret' => '',
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_APP_ID', ''),
        'client_secret' => env('FACEBOOK_APP_SECRET', ''),
        'redirect' => env('FACEBOOK_REDIRECT_CALLBACK', ''),
    ],
    'google' => [
        'client_id' => env('GOOGLE_APP_ID', ''),
        'client_secret' => env('GOOGLE_APP_SECRET', ''),
        'redirect' => env('GOOGLE_REDIRECT_CALLBACK', ''),
    ],

];
