<?php

return [

    'flatrate' => [
        [
            'code' => 'flatrate_one',
            'title' => 'Flatrate One',
            'name' => 'fixed 20% discount for today',
            'description' => 'this is a flat rate',
            'status' => '1',
            'price' => '10',
            'type' => [
                    'per_unit' => 'Per Unit',
                    'per order' => 'Per Order',
                ],
            'class' => 'Webkul\Shipping\Helper\Rate',
        ],

        [
        'code' => 'flatrate_two',
        'title' => 'Flatrate Two',
        'name' => 'fixed 50% discount till 10/10/2018',
        'description' => 'this is a flat rate',
        'status' => '1',
        'price' => '100',
        'type' => [
                'per unit' => 'Per Unit',
                'per order' => 'Per Order',
            ],
        'class' => 'Webkul\Shipping\Helper\Rate',
        ],

        [
        'code' => 'flatrate_three',
        'title' => 'Flatrate Three',
        'name' => 'fixed 30% discount',
        'description' => 'this is a flat rate',
        'status' => '1',
        'price' => '1000',
        'type' => [
                'per unit' => 'Per Unit',
                'per order' => 'Per Order',
            ],
        'class' => 'Webkul\Shipping\Helper\Rate',
        ]
    ]

]

?>