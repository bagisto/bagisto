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
            'adjust_to_percent' => 'Adjust whole cart to percent',
            'adjust_to_fixed_amount' => 'Adjust whole cart to fixed amount'
        ],

        'validation' => [
            0 => 'percent_of_product',
            1 => 'fixed_amount',
            2 => 'adjust_to_percent',
            3 => 'adjust_to_fixed_amount'
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
            ],

            'symbols' => [
                '=' => 'equals',
                '>=' => 'greater_or_equals',
                '<=' => 'lesser_or_equals',
                '>' => 'greater_than',
                '<' => 'lesser_than',
                '{}' => 'contains',
                '={}' => 'is_one_f',
                '!={}' => 'is_not_one_of',
                '!{}' => 'does_not_contains'
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
            'percent_of_product' => 'Percentage of product',
            'fixed_amount' => 'Apply as fixed amount',
            'adjust_to_percent' => 'Adjust price to percentage',
            'adjust_to_fixed_amount' => 'Adjust price to given amount'
        ],

        'validation' => [
            0 => 'percent_of_product',
            1 => 'fixed_amount',
            2 => 'buy_a_get_b',
            3 => 'fixed_amount_cart'
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
            ],

            'symbols' => [
                '=' => 'equals',
                '>=' => 'greater_or_equals',
                '<=' => 'lesser_or_equals',
                '>' => 'greater_than',
                '<' => 'lesser_than',
                '{}' => 'contains',
                '={}' => 'is_one_f',
                '!={}' => 'is_not_one_of',
                '!{}' => 'does_not_contains'
            ]
        ]
    ]
];