<?php

return [
    'cashondelivery' => [
        'code' => 'cashondelivery',
        'title' => 'Cash On Delivery',
        'description' => 'Cash On Delivery',
        'class' => 'Webkul\Payment\Payment\CashOnDelivery',
        'order_status' => 'Pending',
        'active' => true
    ],

    'moneytransfer' => [
        'code' => 'moneytransfer',
        'title' => 'Money Transfer',
        'description' => 'Money Transfer',
        'class' => 'Webkul\Payment\Payment\MoneyTransfer',
        'order_status' => 'Pending',
        'active' => true
    ]
];

?>