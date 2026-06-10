<?php

return [
    'sandbox' => env("SSLCOMMERZ_SANDBOX", true), // Default to true (Sandbox) for local dev
    'middleware' => 'web',
    'store_id' => env("SSLCOMMERZ_STORE_ID"),
    'store_password' => env("SSLCOMMERZ__STORE_PASSWORD"),
    'success_url' => '/sslcommerz/success',
    'failed_url' => '/sslcommerz/fail',
    'cancel_url' => '/sslcommerz/cancel',
    'ipn_url' => '/sslcommerz/ipn',
    'return_response' => 'html', // html or json
];
