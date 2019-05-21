<?php

return [
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

    'catalog' => [
        'actions' => [
            0 => 'admin::app.promotion.catalog.apply-percent',
            1 => 'admin::app.promotion.catalog.apply-fixed',
            2 => 'admin::app.promotion.catalog.adjust-to-percent',
            3 => 'admin::app.promotion.catalog.adjust-to-value'
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

    'cart' => [
        'actions' => [
            0 => 'Apply as percentage',
            1 => 'Apply as fixed amount',
            2 => 'Adjust to percentage',
            3 => 'Adjust to discount value'
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