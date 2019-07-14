<?php

return [
    [
        'key' => 'discount',
        'name' => 'admin::app.promotion.general-info.discount',
        'sort' => 3
    ], [
        'key' => 'discount.cart-rules',
        'name' => 'admin::app.promotion.cart-rule',
        'sort' => 1
    ], [
        'key' => 'discount.cart-rules.settings',
        'name' => 'admin::app.promotion.general-info.options',
        'sort' => 1,
        'fields' => [
            [
                'name' => 'if_applied_on_shipping',
                'title' => 'admin::app.promotion.general-info.on-shipping',
                'type' => 'select',
                'validation' => 'required',
                'channel_based' => true,
                'locale_based' => false,
                'options' => [
                    [
                        'title' => 'W.R.T grand total',
                        'value' => 'grand_total'
                    ], [
                        'title' => 'W.R.T sub total',
                        'value' => 1
                    ], [
                        'title' => 'W.R.T shipping total',
                        'value' => 2
                    ], [
                        'title' => 'Please select an option',
                        'value' => null
                    ]
                ],

                'info' => 'admin::app.promotion.general-info.shipping-apply-info'
            ]
        ]
    ],
];