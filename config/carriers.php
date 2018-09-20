<?php

return [
    'flatrate' => [
        'code' => 'flatrate',
        'title' => 'Flatrate',
        'description' => 'This is a flat rate',
        'active' => true,
        'default_rate' => '10',
        'type' => 'per_unit',
        'class' => 'Webkul\Shipping\Carriers\FlatRate',
    ]
];

?>