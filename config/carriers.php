<?php

return [
    'flatrate' => [
        'code' => 'flatrate',
        'name' => 'Flat Rate',
        'description' => '',
        'default_type' => 'per_order',
        'types' => [
            'per_unit' => 'Per Unit',
            'per_order' => 'Per Order',
        ],
        'price' => 10,
        'class' => 'Webkul\Shipping\Calculators\FlatRate',
        'status' => 1
    ], 
    
    'fedex' => [
        'code' => 'fedex',
        'name' => 'Fedex',
        'description' => '',
        'class' => 'Webkul\Shipping\Calculators\FedexRate',
        'status' => 1
    ]
];

?>