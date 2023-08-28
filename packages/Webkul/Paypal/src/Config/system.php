<?php

return [
    [
        'key'    => 'sales.paymentmethods.paypal_standard',
        'name'   => 'admin::app.configuration.paypal-standard',
        'info'   => 'admin::app.configuration.paypal-standard-info',
        'sort'   => 3,
        'fields' => [
            [
                'name'          => 'title',
                'title'         => 'admin::app.configuration.title',
                'type'          => 'depends',
                'depend'        => 'active:1',
                'validation'    => 'required_if:active,1',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'description',
                'title'         => 'admin::app.configuration.description',
                'type'          => 'textarea',
                'channel_based' => true,
                'locale_based'  => true,
            ],  [
                'name'          => 'business_account',
                'title'         => 'admin::app.configuration.business-account',
                'type'          => 'depends',
                'depend'        => 'active:1',
                'validation'    => 'required_if:active,1',
                'channel_based' => true,
                'locale_based'  => false,
            ],  [
                'name'          => 'active',
                'title'         => 'admin::app.configuration.status',
                'type'          => 'boolean',
                'validation'    => 'required',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'          => 'sandbox',
                'title'         => 'admin::app.configuration.sandbox',
                'type'          => 'boolean',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'    => 'sort',
                'title'   => 'admin::app.configuration.sort_order',
                'type'    => 'select',
                'options' => [
                    [
                        'title' => '1',
                        'value' => 1,
                    ], [
                        'title' => '2',
                        'value' => 2,
                    ], [
                        'title' => '3',
                        'value' => 3,
                    ], [
                        'title' => '4',
                        'value' => 4,
                    ],
                ],
            ]
        ]
    ], [
        'key'    => 'sales.paymentmethods.paypal_smart_button',
        'name'   => 'admin::app.configuration.paypal-smart-button',
        'info'   => 'admin::app.configuration.paypal-smart-button-info',
        'sort'   => 0,
        'fields' => [
            [
                'name'          => 'title',
                'title'         => 'admin::app.configuration.title',
                'type'          => 'depends',
                'depend'        => 'active:1',
                'validation'    => 'required_if:active,1',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'description',
                'title'         => 'admin::app.configuration.description',
                'type'          => 'textarea',
                'channel_based' => true,
                'locale_based'  => true,
            ],  [
                'name'          => 'client_id',
                'title'         => 'admin::app.configuration.client-id',
                'info'          => 'admin::app.configuration.client-id-info',
                'type'          => 'depends',
                'depend'        => 'active:1',
                'validation'    => 'required_if:active,1',
                'channel_based' => true,
                'locale_based'  => false,
            ],  [
                'name'          => 'client_secret',
                'title'         => 'admin::app.configuration.client-secret',
                'info'          => 'admin::app.configuration.client-secret-info',
                'type'          => 'depends',
                'depend'        => 'active:1',
                'validation'    => 'required_if:active,1',
                'channel_based' => true,
                'locale_based'  => false,
            ],  [
                'name'          => 'accepted_currencies',
                'title'         => 'admin::app.configuration.accepted-currencies',
                'info'          => 'admin::app.configuration.accepted-currencies-info',
                'type'          => 'depends',
                'depend'        => 'active:1',
                'validation'    => 'required_if:active,1',
                'channel_based' => true,
                'locale_based'  => false,
            ],  [
                'name'          => 'active',
                'title'         => 'admin::app.configuration.status',
                'type'          => 'boolean',
                'validation'    => 'required',
                'channel_based' => true,
                'locale_based'  => false,
            ],  [
                'name'          => 'sandbox',
                'title'         => 'admin::app.configuration.sandbox',
                'type'          => 'boolean',
                'channel_based' => true,
                'locale_based'  => false,
            ],  [
                'name'    => 'sort',
                'title'   => 'admin::app.configuration.sort_order',
                'type'    => 'select',
                'options' => [
                    [
                        'title' => '1',
                        'value' => 1,
                    ], [
                        'title' => '2',
                        'value' => 2,
                    ], [
                        'title' => '3',
                        'value' => 3,
                    ], [
                        'title' => '4',
                        'value' => 4,
                    ],
                ],
            ]
        ]
    ]
];