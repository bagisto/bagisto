<?php

/**
 * For parent sales key, check the sales package config file,
 * i.e. `packages/Webkul/Sales/src/Config/system.php`
 */
return [
    /**
     * Shipping.
     */
    [
        'key'  => 'sales.shipping',
        'name' => 'admin::app.admin.system.shipping',
        'sort' => 1,
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
                'name'          => 'city',
                'title'         => 'admin::app.admin.system.city',
                'type'          => 'text',
                'validation'    => 'required',
                'channel_based' => true,
                'locale_based'  => false,
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
                'name'          => 'store_name',
                'title'         => 'admin::app.admin.system.store-name',
                'type'          => 'text',
                'channel_based' => true,
            ], [
                'name'          => 'vat_number',
                'title'         => 'admin::app.admin.system.vat-number',
                'type'          => 'text',
                'channel_based' => true,
            ], [
                'name'          => 'contact',
                'title'         => 'admin::app.admin.system.contact-number',
                'type'          => 'text',
                'channel_based' => true,
            ], [
                'name'          => 'bank_details',
                'title'         => 'admin::app.admin.system.bank-details',
                'type'          => 'textarea',
                'channel_based' => true,
            ],
        ],
    ],

    /**
     * Shipping method.
     */
    [
        'key'  => 'sales.carriers',
        'name' => 'admin::app.admin.system.shipping-methods',
        'sort' => 2,
    ], [
        'key'    => 'sales.carriers.free',
        'name'   => 'admin::app.admin.system.free-shipping',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'title',
                'title'         => 'admin::app.admin.system.title',
                'type'          => 'depends',
                'depend'        => 'active:1',
                'validation'    => 'required_if:active,1',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'description',
                'title'         => 'admin::app.admin.system.description',
                'type'          => 'textarea',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'active',
                'title'         => 'admin::app.admin.system.status',
                'type'          => 'boolean',
                'validation'    => 'required',
                'channel_based' => true,
                'locale_based'  => false,
            ],
        ],
    ], [
        'key'    => 'sales.carriers.flatrate',
        'name'   => 'admin::app.admin.system.flate-rate-shipping',
        'sort'   => 2,
        'fields' => [
            [
                'name'          => 'title',
                'title'         => 'admin::app.admin.system.title',
                'type'          => 'depends',
                'depend'        => 'active:1',
                'validation'    => 'required_if:active,1',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'description',
                'title'         => 'admin::app.admin.system.description',
                'type'          => 'textarea',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'default_rate',
                'title'         => 'admin::app.admin.system.rate',
                'type'          => 'depends',
                'depend'        => 'active:1',
                'validation'    => 'required_if:active,1',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'    => 'type',
                'title'   => 'admin::app.admin.system.type',
                'type'    => 'select',
                'options' => [
                    [
                        'title' => 'Per Unit',
                        'value' => 'per_unit',
                    ], [
                        'title' => 'Per Order',
                        'value' => 'per_order',
                    ],
                ],
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'          => 'active',
                'title'         => 'admin::app.admin.system.status',
                'type'          => 'boolean',
                'validation'    => 'required',
                'channel_based' => true,
                'locale_based'  => false,
            ],
        ],
    ],
];
