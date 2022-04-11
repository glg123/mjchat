<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'facebook' => [
        'client_id' => '498410165069313',
        'client_secret' => 'f6d26d48288e7ab3ae28ec569e42f958',
        'redirect' => 'https://dev-abdallah-shaba.online/mjchat/callback',
    ],
    'google' => [
        'client_id' => '1031449564650-ua97743bn601nlb8m139it5emsmugjr5.apps.googleusercontent.com',
        'client_secret' => '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',
        'redirect' => 'https://dev-abdallah-shaba.online/chatmjsystem/api/auth/google/callback',
    ],
];
