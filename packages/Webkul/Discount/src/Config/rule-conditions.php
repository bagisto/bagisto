<?php

return [
    'test_mode' => [
        'all_are_true' => 'All conditions are true',
        // 'all_are_false' => 'All conditions are false',
        'any_is_true' => 'Any condition is true',
        // 'any_is_false' => 'Any condition is false'
    ],

    'conditions' => [
        'numeric' => [
            0 => 'Equals',
            1 => 'Equals or greater',
            2 => 'Equals or lesser',
            3 => 'Greater than',
            4 => 'Lesser than'
        ],

        'text' => [
            0 => 'is',
            1 => 'is not',
            2 => 'contains',
            3 => 'does not contains'
        ],

        'boolean' => [
            0 => 'True/Yes',
            1 => 'False/No',
        ]
    ],

    'cart' => [
        'actions' => [
            'percent_of_product' => 'Percentage of product',
            'fixed_amount' => 'Apply as fixed amount',
            // 'buy_a_get_b' => 'Buy A get B'
        ],

        'validation' => [
            0 => 'percent_of_product',
            1 => 'fixed_amount',
            2 => 'buy_a_get_b',
            // 3 => 'fixed_amount_cart'
        ],

        'conditions' => [
            'numeric' => [
                '=' => 'Equals',
                '>=' => 'Greater or equals',
                '<=' => 'Lesser or equals',
                '>' => 'Greater than',
                '<' => 'Lesser than',
            ],

            'string' => [
                '=' => 'Equals',
                // '>=' => 'Greater or equals',
                // '<=' => 'Lesser or equals',
                // '>' => 'Greater than',
                // '<' => 'Lesser than',
                '{}' => 'Contains',
                '!{}' => 'Does not contains'
            ],

            'boolean' => [
                0 => 'True/Yes',
                1 => 'False/No'
            ]
        ],

        'attributes' => [
            0 => [
                'code' => 'sub_total',
                'name' => 'Sub Total',
                'type' =>  'numeric'
            ],
            1 => [
                'code' => 'total_items',
                'name' => 'Total Items',
                'type' => 'numeric'
            ],
            2 => [
                'code' => 'total_weight',
                'name' => 'Total Weight',
                'type' => 'numeric'
            ],
            3 => [
                'code' => 'shipping_method',
                'name' => 'Shipping Method',
                'type' => 'string'
            ],
            4 => [
                'code' => 'payment_method',
                'name' => 'Payment Method',
                'type' => 'string'
            ],
            5 => [
                'code' => 'shipping_postcode',
                'name' => 'Shipping Postcode',
                'type' => 'string'
            ],
            6 => [
                'code' => 'shipping_state',
                'name' => 'Shipping State',
                'type' => 'string'
            ],
            7 => [
                'code' => 'shipping_country',
                'name' => 'Shipping Country',
                'type' => 'string'
            ],
            8 => [
                'code' => 'shipping_city',
                'name' => 'Shipping City',
                'type' => 'string'
            ]
        ]
    ],

    'catalog' => [
        'actions' => [
            0 => 'admin::app.promotion.catalog.apply-percent',
            1 => 'admin::app.promotion.catalog.apply-fixed',
            2 => 'admin::app.promotion.catalog.adjust-to-percent',
            // 3 => 'admin::app.promotion.catalog.adjust-to-value'
        ],

        'attributes' => [
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
        ]
    ],
];