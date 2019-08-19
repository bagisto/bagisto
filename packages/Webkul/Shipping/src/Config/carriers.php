<?php

return [
    'flatrate' => [
        'code' => 'flatrate',
        'title' => 'Flat Rate',
        'description' => 'shop::app.checkout.onepage.flat-desc',
        'active' => true,
        'default_rate' => '10',
        'type' => 'per_unit',
        'class' => 'Webkul\Shipping\Carriers\FlatRate',
    ],

    'free' => [
        'code' => 'free',
        'title' => 'Free Shipping',
        'description' => 'shop::app.checkout.onepage.free-desc',
        'active' => true,
        'default_rate' => '0',
        'class' => 'Webkul\Shipping\Carriers\Free',
    ]
];