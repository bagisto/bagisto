<?php

return [
    'stripe' => [
        'code'          => 'stripe',
        'title'         => 'Stripe',
        'description'   => 'Stripe Payments',
        'class'         => 'Webkul\Stripe\Payment\StripePayment',
        'debug'         => true,
        'active'        => false,
    ],
];
