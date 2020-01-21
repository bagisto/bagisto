<?php
return [
    'cashondelivery' => [
        'code' => 'cashondelivery',
        'title' => 'Cash On Delivery',
        'description' => 'shop::app.checkout.onepage.cash-desc',
        'class' => 'Webkul\Payment\Payment\CashOnDelivery',
        'active' => true,
        'sort' => 1
    ],

    'moneytransfer' => [
        'code' => 'moneytransfer',
        'title' => 'Money Transfer',
        'description' => 'shop::app.checkout.onepage.money-desc',
        'class' => 'Webkul\Payment\Payment\MoneyTransfer',
        'active' => true,
        'sort' => 2
    ],

    'paypal_standard' => [
        'code' => 'paypal_standard',
        'title' => 'Paypal Standard',
        'description' => 'shop::app.checkout.onepage.paypal-desc',
        'class' => 'Webkul\Paypal\Payment\Standard',
        'sandbox' => true,
        'active' => true,
        'business_account' => 'test@webkul.com',
        'sort' => 3
    ]
];