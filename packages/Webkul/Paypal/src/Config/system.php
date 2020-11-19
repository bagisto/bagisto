<?php

return [
    [
        'key'    => 'sales.paymentmethods.paypal_standard',
        'name'   => 'admin::app.admin.system.paypal-standard',
        'sort'   => 3,
        'fields' => [
            [
                'name'          => 'title',
                'title'         => 'admin::app.admin.system.title',
                'type'          => 'depends',
                'depend'        => 'active:1',
                'validation'    => 'required_if:active,1',
                'channel_based' => false,
                'locale_based'  => true,
            ], [
                'name'          => 'description',
                'title'         => 'admin::app.admin.system.description',
                'type'          => 'textarea',
                'channel_based' => false,
                'locale_based'  => true,
            ],  [
                'name'          => 'business_account',
                'title'         => 'admin::app.admin.system.business-account',
                'type'          => 'depends',
                'depend'        => 'active:1',
                'validation'    => 'required_if:active,1',
            ],  [
                'name'          => 'active',
                'title'         => 'admin::app.admin.system.status',
                'type'          => 'boolean',
                'validation'    => 'required',
                'channel_based' => false,
                'locale_based'  => true
            ], [
                'name'          => 'sandbox',
                'title'         => 'admin::app.admin.system.sandbox',
                'type'          => 'boolean',
                'channel_based' => false,
                'locale_based'  => true,
            ], [
                'name'    => 'sort',
                'title'   => 'admin::app.admin.system.sort_order',
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
        'name'   => 'admin::app.admin.system.paypal-smart-button',
        'sort'   => 0,
        'fields' => [
            [
                'name'          => 'title',
                'title'         => 'admin::app.admin.system.title',
                'type'          => 'depends',
                'depend'        => 'active:1',
                'validation'    => 'required_if:active,1',
                'channel_based' => false,
                'locale_based'  => true,
            ], [
                'name'          => 'description',
                'title'         => 'admin::app.admin.system.description',
                'type'          => 'textarea',
                'channel_based' => false,
                'locale_based'  => true,
            ],  [
                'name'       => 'client_id',
                'title'      => 'admin::app.admin.system.client-id',
                'info'          => 'admin::app.admin.system.client-id-info',
                'type'          => 'depends',
                'depend'        => 'active:1',
                'validation'    => 'required_if:active,1',
            ],  [
                'name'       => 'client_secret',
                'title'      => 'admin::app.admin.system.client-secret',
                'info'          => 'admin::app.admin.system.client-secret-info',
                'type'          => 'depends',
                'depend'        => 'active:1',
                'validation'    => 'required_if:active,1',
            ],  [
                'name'       => 'accepted_currencies',
                'title'      => 'admin::app.admin.system.accepted-currencies',
                'info'          => 'admin::app.admin.system.accepted-currencies-info',
                'type'          => 'depends',
                'depend'        => 'active:1',
                'validation'    => 'required_if:active,1',
            ],  [
                'name'          => 'active',
                'title'         => 'admin::app.admin.system.status',
                'type'          => 'boolean',
                'validation'    => 'required',
                'channel_based' => false,
                'locale_based'  => true
            ],  [
                'name'          => 'sandbox',
                'title'         => 'admin::app.admin.system.sandbox',
                'type'          => 'boolean',
                'channel_based' => false,
                'locale_based'  => true,
            ],  [
                'name'    => 'sort',
                'title'   => 'admin::app.admin.system.sort_order',
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