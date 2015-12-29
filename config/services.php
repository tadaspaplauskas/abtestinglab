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
        'client_id' => '1639486112957971',
        'client_secret' => '05182b3be962d1014a37f8e78abe0fef',
        'redirect' => '',
    ],
    'google' => [
        'client_id' => '',
        'client_secret' => '',
        'redirect' => '',//http://greatdest.dk.dev.fkly.dk/auth/google/callback',
    ],

];
