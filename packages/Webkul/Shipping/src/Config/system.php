<?php

return [
    [
        'key'  => 'sales',
        'name' => 'admin::app.admin.system.sales',
        'sort' => 5,
    ], [
        'key'  => 'sales.carriers',
        'name' => 'admin::app.admin.system.shipping-methods',
        'sort' => 1,
    ], [
        'key'    => 'sales.carriers.free',
        'name'   => 'admin::app.admin.system.free-shipping',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'title',
                'title'         => 'admin::app.admin.system.title',
                'type'          => 'text',
                'validation'    => 'required',
                'channel_based' => false,
                'locale_based'  => true,
            ], [
                'name'          => 'description',
                'title'         => 'admin::app.admin.system.description',
                'type'          => 'textarea',
                'channel_based' => false,
                'locale_based'  => true,
            ], [
                'name'          => 'active',
                'title'         => 'admin::app.admin.system.status',
                'type'          => 'boolean',
                'validation'    => 'required',
                'channel_based' => false,
                'locale_based'  => true,
            ]
        ]
    ], [
        'key'    => 'sales.carriers.flatrate',
        'name'   => 'admin::app.admin.system.flate-rate-shipping',
        'sort'   => 2,
        'fields' => [
            [
                'name'          => 'title',
                'title'         => 'admin::app.admin.system.title',
                'type'          => 'text',
                'validation'    => 'required',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'description',
                'title'         => 'admin::app.admin.system.description',
                'type'          => 'textarea',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'          => 'default_rate',
                'title'         => 'admin::app.admin.system.rate',
                'type'          => 'text',
                'validation'    => 'required',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'       => 'type',
                'title'      => 'admin::app.admin.system.type',
                'type'       => 'select',
                'options'    => [
                    [
                        'title' => 'Per Unit',
                        'value' => 'per_unit',
                    ], [
                        'title' => 'Per Order',
                        'value' => 'per_order',
                    ]
                ],
                'validation' => 'required'
            ], [
                'name'          => 'active',
                'title'         => 'admin::app.admin.system.status',
                'type'          => 'boolean',
                'validation'    => 'required',
                'channel_based' => false,
                'locale_based'  => true,
            ]
        ]
    ], [
        'key'  => 'sales.shipping',
        'name' => 'admin::app.admin.system.shipping',
        'sort' => 0,
    ], [
        'key'    => 'sales.shipping.origin',
        'name'   => 'admin::app.admin.system.origin',
        'sort'   => 0,
        'fields' => [
            [
                'name'          => 'country',
                'title'         => 'admin::app.admin.system.country',
                'type'          => 'country',
                'validation'    => 'required',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'state',
                'title'         => 'admin::app.admin.system.state',
                'type'          => 'state',
                'validation'    => 'required',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'address1',
                'title'         => 'admin::app.admin.system.street-address',
                'type'          => 'text',
                'validation'    => 'required',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'          => 'zipcode',
                'title'         => 'admin::app.admin.system.zip',
                'type'          => 'text',
                'validation'    => 'required',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'          => 'city',
                'title'         => 'admin::app.admin.system.city',
                'type'          => 'text',
                'validation'    => 'required',
                'channel_based' => true,
                'locale_based'  => false,
            ]
        ]
    ]
];