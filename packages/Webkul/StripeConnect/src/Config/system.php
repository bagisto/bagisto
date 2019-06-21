<?php

return [
    [
        'key' => 'stripe',
        'name' => 'Stripe Connect',
        'sort' => 5
    ], [
        'key' => 'stripe.connect',
        'name' => 'Connect Account',
        'sort' => 1,
    ], [
        'key' => 'stripe.connect.details',
        'name' => 'Account Details',
        'sort' => 1,
        'fields' => [
            [
                'name' => 'clientid',
                'title' => 'Client ID',
                'type' => 'text',
                'validation' => 'required',
                'channel_based' => false,
                'locale_based' => false
            ]
        ]
    ], [
        'key' => 'sales.paymentmethods.stripe',
        'name' => 'Stripe Payments',
        'sort' => 4,
        'fields' => [
            [
                'name' => 'active',
                'title' => 'Enable For Checkout',
                'type' => 'select',
                'options' => [
                    [
                        'title' => 'True',
                        'value' => true
                    ], [
                        'title' => 'False',
                        'value' => false
                    ]
                ],
                'validation' => 'required'
            ], [
                'name' => 'title',
                'title' => 'Title',
                'type' => 'text',
                'validation' => 'required',
                'channel_based' => false,
                'locale_based' => true
            ],
            // [
            //     'name' => 'acceptedchannels',
            //     'title' => 'Accpeted Channels',
            //     'type' => 'multiselect',
            //     'validation' => 'required',
            //     'channel_based' => false,
            //     'locale_based' => false,
            //     'repository' => 'Webkul\Aramex\Repositories\ChannelRepository@getAllChannels'
            // ],
            [
                'name' => 'description',
                'title' => 'Description',
                'type' => 'textarea',
                'channel_based' => false,
                'locale_based' => true
            ], [
                'name' => 'enabletesting',
                'title' => 'Enable Testing',
                'type' => 'select',
                'channel_based' => false,
                'locale_based' => true,
                'options' => [
                    [
                        'title' => 'Active',
                        'value' => true
                    ], [
                        'title' => 'Inactive',
                        'value' => false
                    ]
                ],
                'validation' => 'required'
            ],
            // [
            //     'name' => 'test_publishable_key',
            //     'title' => 'Test Publishable Key',
            //     'type' => 'text',
            //     'type' => 'text',
            //     // 'validation' => 'required',
            //     'channel_based' => false,
            //     'locale_based' => false
            // ], [
            //     'name' => 'test_secret_key',
            //     'title' => 'Test Secret Key',
            //     'type' => 'text',
            //     'type' => 'text',
            //     // 'validation' => 'required',
            //     'channel_based' => false,
            //     'locale_based' => false
            // ], [
            //     'name' => 'live_publishable_key',
            //     'title' => 'Live Publishable Key',
            //     'type' => 'text',
            //     'type' => 'text',
            //     // 'validation' => 'required',
            //     'channel_based' => false,
            //     'locale_based' => false
            // ], [
            //     'name' => 'live_secret_key',
            //     'title' => 'Live Secret Key',
            //     'type' => 'text',
            //     'type' => 'text',
            //     // 'validation' => 'required',
            //     'channel_based' => false,
            //     'locale_based' => false
            // ],
            [
                'name' => 'statement_descriptor',
                'title' => 'Statement Descriptor',
                'type' => 'text',
                'type' => 'text',
                // 'validation' => 'required'
            ]
        ]
    ]
];