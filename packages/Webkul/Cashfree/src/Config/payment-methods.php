<?php

use Webkul\Cashfree\Payment\Cashfree;

return [
    'cashfree' => [
        'code'        => 'cashfree',
        'title'       => 'Cashfree Payments',
        'description' => 'Pay via UPI, Cards, Netbanking & Wallets with Cashfree',
        'class'       => Cashfree::class,
        'active'      => true,
        'sandbox'     => true,
        'app_id'      => 'APP_ID',
        'secret_key'  => 'SECRET_KEY',
        'sort'        => 5,
    ],
];
