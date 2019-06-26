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
            ], [
                'name' => 'stripefees',
                'title' => 'Stripe fee to be paid by customer or seller',
                'type' => 'select',
                'options' => [
                    [
                        'title' => 'Seller',
                        'value' => 'seller'
                    ], [
                        'title' => 'Customer',
                        'value' => 'customer'
                    ]
                ],
                'validation' => 'required'
            ],  [
                'name' => 'statementdescriptor',
                'title' => 'Statement Descriptor',
                'type' => 'text',
                'validation' => 'required'
            ]
        ]
    ],
    // [
    //     'key' => 'stripe.connect.stripe',
    //     'name' => 'Stripe Payments',
    //     'sort' => 2,
    //     'fields' => [
    //         [
    //             'name' => 'active',
    //             'title' => 'Enable For Checkout',
    //             'type' => 'select',
    //             'options' => [
    //                 [
    //                     'title' => 'True',
    //                     'value' => true
    //                 ], [
    //                     'title' => 'False',
    //                     'value' => false
    //                 ]
    //             ],
    //             'validation' => 'required'
    //         ], [
    //             'name' => 'title',
    //             'title' => 'Title',
    //             'type' => 'text',
    //             'validation' => 'required',
    //             'channel_based' => false,
    //             'locale_based' => true
    //         ], [
    //             'name' => 'description',
    //             'title' => 'Description',
    //             'type' => 'textarea',
    //             'channel_based' => false,
    //             'locale_based' => true
    //         ], [
    //             'name' => 'enabletesting',
    //             'title' => 'Enable Testing',
    //             'type' => 'select',
    //             'channel_based' => false,
    //             'locale_based' => true,
    //             'options' => [
    //                 [
    //                     'title' => 'Active',
    //                     'value' => true
    //                 ], [
    //                     'title' => 'Inactive',
    //                     'value' => false
    //                 ]
    //             ],
    //             'validation' => 'required'
    //         ], [
    //             'name' => 'statement_descriptor',
    //             'title' => 'Statement Descriptor',
    //             'type' => 'text',
    //             'type' => 'text',
    //             // 'validation' => 'required'
    //         ]
    //     ]
    // ]
];