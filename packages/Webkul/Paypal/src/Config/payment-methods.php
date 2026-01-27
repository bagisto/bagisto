<?php

use Webkul\Paypal\Payment\SmartButton;
use Webkul\Paypal\Payment\Standard;

return [
    'paypal_smart_button' => [
        'class' => SmartButton::class,
        'code' => 'paypal_smart_button',
        'title' => 'PayPal Smart Button',
        'description' => 'PayPal',
        'active' => true,
        'client_id' => 'sb',
        'accepted_currencies' => 'USD',
        'sort' => 4,
    ],

    'paypal_standard' => [
        'class' => Standard::class,
        'code' => 'paypal_standard',
        'title' => 'PayPal Standard',
        'description' => 'PayPal Standard',
        'active' => true,
        'business_account' => 'test@webkul.com',
        'sort' => 5,
    ],
];
