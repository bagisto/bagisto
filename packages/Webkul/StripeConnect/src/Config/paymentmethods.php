<?php

return [
    'stripe' => [
        'code' => 'stripe',
        'title' => 'RazzoPay',
        'description' => 'RazzoPay Payments',
        'class' => 'Webkul\StripeConnect\Payment\StripePayment',
        'sandbox' => true,
        'active' => true
    ]
];