<?php

return [
    'catalog' => [
        0 => 'Apply as percentage',
        1 => 'Apply as fixed amount',
        2 => 'Adjust to percentage',
        3 => 'Adjust to discount value'
    ],

    'cart' => [
        0 => [
                'name' => 'Sub-total',
                'type' =>  'numeric'
            ],
        1 => [
                'name' => 'Total Items Quantity',
                'type' => 'numeric'
            ],
        2 => [
                'name' => 'Total Weight',
                'type' => 'numeric'
            ],
        3 => [
                'name' => 'Payment Method',
                'type' => 'string'
            ],
        4 => [
                'name' => 'Shipping Postcode',
                'type' => 'string'
            ],
        5 => [
                'name' => 'Shipping State',
                'type' => 'string'
            ],
        6 => [
                'name' => 'Shipping Country',
                'type' => 'string'
            ]
    ],

    'conditions' => [
        'numeric' => [
            0 => 'Greater than',
            1 => 'Lesser than',
            2 => 'Equals'
        ],

        'text' => [
            0 => 'is',
            1 => 'is any of',
            2 => 'contains'
        ],
    ]
];