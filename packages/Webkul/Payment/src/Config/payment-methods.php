<?php

use Webkul\Payment\Payment\CashOnDelivery;
use Webkul\Payment\Payment\MoneyTransfer;

return [
    'cashondelivery'  => [
        'class'            => CashOnDelivery::class,
        'code'             => 'cashondelivery',
        'title'            => 'Cash On Delivery',
        'description'      => 'Cash On Delivery',
        'active'           => true,
        'generate_invoice' => false,
        'sort'             => 6,
    ],

    'moneytransfer'   => [
        'class'            => MoneyTransfer::class,
        'code'             => 'moneytransfer',
        'title'            => 'Money Transfer',
        'description'      => 'Money Transfer',
        'active'           => true,
        'generate_invoice' => false,
        'sort'             => 7,
    ],
];
