<?php

return [
    [
        'key' => 'sales',
        'name' => 'Sales',
        'sort' => 1
    ], [
        'key' => 'sales.carriers',
        'name' => 'Shipping Methods',
        'sort' => 1,
    ], [
        'key' => 'sales.carriers.free',
        'name' => 'Free Shipping',
        'sort' => 1,
        'fields' => [
            [
                'name' => 'title',
                'title' => 'Title',
                'type' => 'text',
                'validation' => 'required',
                'channel_based' => false,
                'locale_based' => true
            ], [
                'name' => 'description',
                'title' => 'Description',
                'type' => 'textarea',
                'channel_based' => false,
                'locale_based' => true
            ], [
                'name' => 'active',
                'title' => 'Status',
                'type' => 'select',
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
            ]
        ]
    ], [
        'key' => 'sales.carriers.flatrate',
        'name' => 'Flat Rate Shipping',
        'sort' => 2,
        'fields' => [
            [
                'name' => 'title',
                'title' => 'Title',
                'type' => 'text',
                'validation' => 'required',
                'channel_based' => true,
                'locale_based' => true
            ], [
                'name' => 'description',
                'title' => 'Description',
                'type' => 'textarea',
                'channel_based' => true,
                'locale_based' => false
            ], [
                'name' => 'default_rate',
                'title' => 'Rate',
                'type' => 'text',
                'channel_based' => true,
                'locale_based' => false
            ], [
                'name' => 'type',
                'title' => 'Type',
                'type' => 'select',
                'options' => [
                    [
                        'title' => 'Per Unit',
                        'value' => 'Per Order'
                    ], [
                        'title' => 'Inactive',
                        'value' => 'per_order'
                    ]
                ],
                'validation' => 'required'
            ], [
                'name' => 'active',
                'title' => 'Status',
                'type' => 'select',
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
            ]
        ]
    ]
];