<?php

return [
    'key' => env('PAY_PAY_KEY_FOR_TEST'),
    'secret' => env('PAY_APY_SECRET_FOR_TEST'),
    'merchant_id' => env('MERCHANT_ID'),
    'redirect_url' => env('REDIRECT_URL', 'http://localhost'),
    'execution_environment' => env('SANDBOX')
];