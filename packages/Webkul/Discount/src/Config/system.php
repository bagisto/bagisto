<?php

return [
    [
        'key' => 'discount',
        'name' => 'admin::app.promotion.general-info.discount',
        'sort' => 1
    ], [
        'key' => 'discount.cart-rules',
        'name' => 'admin::app.promotion.cart-rule',
        'sort' => 1
    ], [
        'key' => 'discount.cart-rules.settings',
        'name' => 'admin::app.promotion.general-info.config',
        'sort' => 1,
        'fields' => [
            [
                'name' => 'applied_on',
                'title' => 'admin::app.promotion.general-info.to-be-applied',
                'type' => 'select',
                'validation' => 'required',
                'channel_based' => true,
                'locale_based' => false,
                'options' => [
                    [
                        'title' => 'Least Worth Item',
                        'value' => 0
                    ], [
                        'title' => 'Max Worth Item',
                        'value' => 1
                    ], [
                        'title' => 'All Items',
                        'value' => 2
                    ], [
                        'title' => 'Please select an option',
                        'value' => null
                    ]
                ],

                'info' => 'admin::app.promotion.general-info.applied-on-info'
            ]
        ]
    ],
];