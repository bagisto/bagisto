<?php

return [
    'flatrate' => [
        'code' => 'flatrate',
        'title' => 'Flat Rate',
        'description' => 'This is a flat rate',
        'active' => true,
        'default_rate' => '10',
        'type' => 'per_unit',
        'class' => 'Webkul\Shipping\Carriers\FlatRate',
    ],

    'free' => [
        'code' => 'free',
        'title' => 'Free Shipping',
        'description' => 'This is a free shipping',
        'active' => true,
        'class' => 'Webkul\Shipping\Carriers\Free',
    ]
];