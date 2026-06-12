<?php

return [

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key'    => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel'              => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | reCAPTCHA v2 (Google)
    |--------------------------------------------------------------------------
    |
    | Adicione ao .env:
    |   RECAPTCHA_SITE_KEY=sua_chave_publica
    |   RECAPTCHA_SECRET_KEY=sua_chave_secreta
    |
    | Chaves de TESTE para localhost:
    |   Site key:   6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI
    |   Secret key: 6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe
    |
    | Gere chaves de produção em:
    |   https://www.google.com/recaptcha/admin/create
    */
    'recaptcha' => [
        'key'    => env('RECAPTCHA_SITE_KEY'),
        'secret' => env('RECAPTCHA_SECRET_KEY'),
    ],

    /* GA4, TAG MANAGER E HOTJAR */
    'ga_measurement_id' => env('GA_MEASUREMENT_ID'),
    
    'gtm' => [
    'id' => env('GTM-TRDMX2K5'),
    ],

    'google_analytics' => [
        'id' => env('G-56BXHJ5LNW'),
    ],

    'hotjar' => [
        'id' => env('HOTJAR_ID'),
    ],

    'google' => [
        'client_id'     => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect'      => env('GOOGLE_REDIRECT_URI', '/auth/google/callback'),
    ],

    'azure' => [
        'client_id'     => env('AZURE_CLIENT_ID'),
        'client_secret' => env('AZURE_CLIENT_SECRET'),
        'redirect'      => env('AZURE_REDIRECT_URI', '/auth/azure/callback'),
        'tenant'        => env('AZURE_TENANT_ID', 'common'),
    ],
];
