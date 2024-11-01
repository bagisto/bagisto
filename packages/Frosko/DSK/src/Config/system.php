<?php

return [
    [
        'key'    => 'sales.payment_methods.dsk',
        'name'   => 'DSK Bank',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'title',
                'title'         => 'Payment Method Title',
                'type'          => 'text',
                'validation'    => 'required',
                'channel_based' => false,
                'locale_based'  => true,
            ], [
                'name'          => 'description',
                'title'         => 'Payment Method Description',
                'type'          => 'textarea',
                'channel_based' => false,
                'locale_based'  => true,
            ], [
                'name'          => 'active',
                'title'         => 'Status',
                'type'          => 'boolean',
                'validation'    => 'required',
                'channel_based' => false,
            ], [
                'name'          => 'test_mode',
                'title'         => 'Test Mode',
                'type'          => 'boolean',
                'validation'    => 'required',
                'channel_based' => true,
            ], [
                // username
                'name'          => 'username',
                'title'         => 'API Username',
                'type'          => 'text',
                'validation'    => 'required',
                'channel_based' => true,
            ], [
                // username
                'name'          => 'password',
                'title'         => 'API Password',
                'type'          => 'password',
                'validation'    => 'required',
                'channel_based' => true,
            ],
        ],
    ],
];
