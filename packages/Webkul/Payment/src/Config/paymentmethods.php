<?php
return [
    'cashondelivery'  => [
        'code'        => 'cashondelivery',
        'title'       => 'Cash on delivery',
        'description' => 'Cash on delivery',
        'class'       => 'Webkul\Payment\Payment\CashOnDelivery',
        'active'      => true,
        'sort'        => 1,
    ],

    'moneytransfer'   => [
        'code'        => 'moneytransfer',
        'title'       => 'Money transfer',
        'description' => 'Money transfer',
        'class'       => 'Webkul\Payment\Payment\MoneyTransfer',
        'active'      => true,
        'sort'        => 2,
    ]
];