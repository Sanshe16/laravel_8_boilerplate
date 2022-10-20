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

    'backend' => [
        'api' => [
            'url' => env('BACKEND_API_URL'),
            'version' => env('API_VERSION', 'v1'),
            'current_version' => env('API_CURRENT_VERSION', 'v1'),
        ]
    ],

    'mailjet' => [
        'key' => env('MAILJET_APIKEY'),
        'secret' => env('MAILJET_APISECRET'),
    ],

    'facebook' => [
        'client_id' => '379353360882903',
        'client_secret' => 'c03f7f5e9fbf33ce3fbb8c613d75e9c1',
        'redirect' => 'https://gifter.trztechnologies.com/auth/facebook/callback'
    ],

    'google' => [
        'client_id' => '576587292484-4ma8uli65s7evn74qrr0nj02ahv0vipv.apps.googleusercontent.com',
        'client_secret' => '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',
        'redirect' => 'https://gifter.trztechnologies.com/auth/google/callback'
    ],

];
