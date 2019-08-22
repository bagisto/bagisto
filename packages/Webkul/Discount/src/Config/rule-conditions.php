<?php

return [
    'test_mode' => [
        'all_are_true' => 'All conditions are true',
        'any_is_true' => 'Any condition is true',
    ],

    'cart' => [
        'actions' => [
            'fixed_amount' => 'Apply as fixed amount',
            'percent_of_product' => 'Percentage of product',
            // 'whole_cart_to_fixed' => 'Adjust whole cart to fixed amount',
            'whole_cart_to_percent' => 'Adjust whole cart to percent'
        ],

        'validation' => [
            0 => 'percent_of_product',
            1 => 'fixed_amount',
            2 => 'whole_cart_to_percent',
            3 => 'whole_cart_to_fixed'
        ],

        'conditions' => [
            'numeric' => [
                '=' => 'Equals',
                '>=' => 'Greater or equals',
                '<=' => 'Lesser or equals',
                '>' => 'Greater than',
                '<' => 'Lesser than'
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
                '={}' => 'is_one_of',
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
            'percent_of_original' => 'Percentage of product',
            'fixed_amount' => 'Apply as fixed amount',
            'final_price_to_percent' => 'Adjust price to percentage',
            'to_discount_value' => 'Adjust price to given amount'
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
                '>=' => 'Greater or equals',
                '<=' => 'Lesser or equals',
                '>' => 'Greater than',
                '<' => 'Lesser than',
                '{}' => 'Contains',
                '!{}' => 'Does not contains'
            ],

            'select' => [
                '=' => 'Equals',
                '{}' => 'Contains',
                '!{}' => 'Does not contains'
            ],

            'multiselect' => [
                '=' => 'Equals',
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
                '={}' => 'is_one_of',
                '!={}' => 'is_not_one_of',
                '!{}' => 'does_not_contains'
            ]
        ]
    ]
];