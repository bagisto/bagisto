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
        'name' => 'admin::app.configuration.index.sales.shipping.title',
        'info' => 'admin::app.configuration.index.sales.shipping.info',
        'icon' => 'shipping.png',
        'sort' => 1,
    ], [
        'key'    => 'sales.shipping.origin',
        'name'   => 'admin::app.configuration.index.sales.shipping.origin',
        'info'   => 'admin::app.configuration.index.sales.shipping.origin-info',
        'sort'   => 0,
        'fields' => [
            [
                'name'          => 'country',
                'title'         => 'admin::app.configuration.index.sales.shipping.country',
                'type'          => 'country',
                'validation'    => 'required',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'state',
                'title'         => 'admin::app.configuration.index.sales.shipping.state',
                'type'          => 'state',
                'validation'    => 'required',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'city',
                'title'         => 'admin::app.configuration.index.sales.shipping.city',
                'type'          => 'text',
                'validation'    => 'required',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'          => 'address1',
                'title'         => 'admin::app.configuration.index.sales.shipping.street-address',
                'type'          => 'text',
                'validation'    => 'required',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'          => 'zipcode',
                'title'         => 'admin::app.configuration.index.sales.shipping.zip',
                'type'          => 'text',
                'validation'    => 'required',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'          => 'store_name',
                'title'         => 'admin::app.configuration.index.sales.shipping.store-name',
                'type'          => 'text',
                'channel_based' => true,
            ], [
                'name'          => 'vat_number',
                'title'         => 'admin::app.configuration.index.sales.shipping.vat-number',
                'type'          => 'text',
                'channel_based' => true,
            ], [
                'name'          => 'contact',
                'title'         => 'admin::app.configuration.index.sales.shipping.contact-number',
                'type'          => 'text',
                'channel_based' => true,
            ], [
                'name'          => 'bank_details',
                'title'         => 'admin::app.configuration.index.sales.shipping.bank-details',
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
        'name' => 'admin::app.configuration.index.sales.shipping-methods.page-title',
        'info' => 'admin::app.configuration.index.sales.shipping-methods.info',
        'icon' => 'shipping-method.png',
        'sort' => 2,
    ], [
        'key'    => 'sales.carriers.free',
        'name'   => 'admin::app.configuration.index.sales.shipping-methods.free-shipping',
        'info'   => 'admin::app.configuration.index.sales.shipping-methods.free-shipping-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'title',
                'title'         => 'admin::app.configuration.index.sales.shipping-methods.title',
                'type'          => 'depends',
                'depend'        => 'active:1',
                'validation'    => 'required_if:active,1',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'description',
                'title'         => 'admin::app.configuration.index.sales.shipping-methods.description',
                'type'          => 'textarea',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'active',
                'title'         => 'admin::app.configuration.index.sales.shipping-methods.status',
                'type'          => 'boolean',
                'validation'    => 'required',
                'channel_based' => true,
                'locale_based'  => false,
            ],
        ],
    ], [
        'key'    => 'sales.carriers.flatrate',
        'name'   => 'admin::app.configuration.index.sales.shipping-methods.flat-rate-shipping',
        'info'   => 'admin::app.configuration.index.sales.shipping-methods.flat-rate-shipping-info',
        'sort'   => 2,
        'fields' => [
            [
                'name'          => 'title',
                'title'         => 'admin::app.configuration.index.sales.shipping-methods.title',
                'type'          => 'depends',
                'depend'        => 'active:1',
                'validation'    => 'required_if:active,1',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'description',
                'title'         => 'admin::app.configuration.index.sales.shipping-methods.description',
                'type'          => 'textarea',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'default_rate',
                'title'         => 'admin::app.configuration.index.sales.shipping-methods.rate',
                'type'          => 'depends',
                'depend'        => 'active:1',
                'validation'    => 'required_if:active,1',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'    => 'type',
                'title'   => 'admin::app.configuration.index.sales.shipping-methods.type',
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
                'title'         => 'admin::app.configuration.index.sales.shipping-methods.status',
                'type'          => 'boolean',
                'validation'    => 'required',
                'channel_based' => true,
                'locale_based'  => false,
            ],
        ],
    ],
];
