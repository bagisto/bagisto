<?php
return [
    'paypal_standard' => [
        'code'             => 'paypal_standard',
        'title'            => 'Paypal Standard',
        'description'      => 'Paypal Standard',
        'class'            => 'Webkul\Paypal\Payment\Standard',
        'sandbox'          => true,
        'active'           => true,
        'business_account' => 'test@webkul.com',
        'sort'             => 3,
    ],

    'paypal_smart_button' => [
        'code'             => 'paypal_smart_button',
        'title'            => 'Paypal Smart Button',
        'description'      => 'Paypal Smart Button',
        'client_id'        => 'sb',
        'class'            => 'Webkul\Paypal\Payment\SmartButton',
        'sandbox'          => true,
        'active'           => true,
        'sort'             => 4,
    ]
];