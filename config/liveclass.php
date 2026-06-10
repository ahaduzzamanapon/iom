<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Zoom Server-to-Server OAuth
    |--------------------------------------------------------------------------
    */
    'zoom' => [
        'account_id'    => env('ZOOM_ACCOUNT_ID', ''),
        'client_id'     => env('ZOOM_CLIENT_ID', ''),
        'client_secret' => env('ZOOM_CLIENT_SECRET', ''),
        'token_url'     => 'https://zoom.us/oauth/token',
        'api_base'      => 'https://api.zoom.us/v2',
    ],

    /*
    |--------------------------------------------------------------------------
    | Google Meet via Calendar API (Service Account)
    |--------------------------------------------------------------------------
    */
    'google' => [
        'service_account_json' => env('GOOGLE_SERVICE_ACCOUNT_JSON', storage_path('app/google-service-account.json')),
        'calendar_id'          => env('GOOGLE_CALENDAR_ID', 'primary'),
        'scopes'               => [
            'https://www.googleapis.com/auth/calendar',
        ],
    ],

];
