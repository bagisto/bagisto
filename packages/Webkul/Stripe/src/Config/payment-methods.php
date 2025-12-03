<?php

use Webkul\Stripe\Payment\Stripe;

return [
    'stripe' => [
        'class'                     => Stripe::class,
        'code'                      => 'stripe',
        'title'                     => 'Stripe',
        'description'               => 'Stripe',
        'active'                    => true,
        'sandbox'                   => true,
        'api_test_key'              => 'API_TEST_KEY',
        'api_test_publishable_key'  => 'API_TEST_PUBLISHABLE_KEY',
        'sort'                      => 1,
    ],
];
