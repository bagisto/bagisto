<?php

use Webkul\PayU\Payment\PayU;

return [
    'payu' => [
        'class' => PayU::class,
        'code' => 'payu',
        'title' => 'PayU',
        'description' => 'PayU',
        'active' => true,
        'sandbox' => true,
        'merchant_key' => 'MERCHANT_KEY',
        'merchant_salt' => 'MERCHANT_SALT',
        'sort' => 3,
    ],
];
