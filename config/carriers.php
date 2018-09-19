<?php

return [
    'flatrate' => [
        'code' => 'flatrate',
        'title' => 'Flatrate',
        'description' => 'This is a flat rate',
        'status' => '1',
        'default_rate' => '10',
        'type' => [
                'per_unit' => 'Per Unit',
                'per order' => 'Per Order',
            ],
        'class' => 'Webkul\Shipping\Carriers\FlatRate',
    ]
];

?>