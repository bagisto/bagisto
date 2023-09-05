<?php

/**
 * For parent sales key, check the sales package config file,
 * i.e. `packages/Webkul/Sales/src/Config/system.php`
 */
return [
    /**
     * Payment methods.
     */
    [
        'key'  => 'sales.paymentmethods',
        'name' => 'admin::app.configuration.index.sales.payment-methods.page-title',
        'info' => 'admin::app.configuration.index.sales.payment-methods.info',
        'icon' => 'payment-method.png',
        'sort' => 3,
    ], [
        'key'    => 'sales.paymentmethods.cashondelivery',
        'name'   => 'admin::app.configuration.index.sales.payment-methods.cash-on-delivery',
        'info'   => 'admin::app.configuration.index.sales.payment-methods.cash-on-delivery-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'title',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.title',
                'type'          => 'depends',
                'depend'        => 'active:1',
                'validation'    => 'required_if:active,1',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'description',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.description',
                'type'          => 'textarea',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'instructions',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.instructions',
                'type'          => 'textarea',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'generate_invoice',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.generate-invoice',
                'type'          => 'boolean',
                'default_value' => false,
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'          => 'invoice_status',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.set-invoice-status',
                'validation'    => 'required_if:generate_invoice,1',
                'type'          => 'select',
                'options'       => [
                    [
                        'title' => 'admin::app.configuration.index.sales.payment-methods.pending',
                        'value' => 'pending',
                    ], [
                        'title' => 'admin::app.configuration.index.sales.payment-methods.paid',
                        'value' => 'paid',
                    ],
                ],
                'info'          => 'admin::app.configuration.index.sales.payment-methods.set-order-status',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'          => 'order_status',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.set-order-status',
                'type'          => 'select',
                'options'       => [
                    [
                        'title' => 'admin::app.configuration.index.sales.payment-methods.pending',
                        'value' => 'pending',
                    ], [
                        'title' => 'admin::app.configuration.index.sales.payment-methods.pending-payment',
                        'value' => 'pending_payment',
                    ], [
                        'title' => 'admin::app.configuration.index.sales.payment-methods.processing',
                        'value' => 'processing',
                    ],
                ],
                'info'          => 'admin::app.configuration.index.sales.payment-methods.generate-invoice-applicable',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'          => 'active',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.status',
                'type'          => 'boolean',
                'validation'    => 'required',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'    => 'sort',
                'title'   => 'admin::app.configuration.index.sales.payment-methods.sort-order',
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
            ],
        ],
    ], [
        'key'    => 'sales.paymentmethods.moneytransfer',
        'name'   => 'admin::app.configuration.index.sales.payment-methods.money-transfer',
        'info'   => 'admin::app.configuration.index.sales.payment-methods.money-transfer-info',
        'sort'   => 2,
        'fields' => [
            [
                'name'          => 'title',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.title',
                'type'          => 'depends',
                'depend'        => 'active:1',
                'validation'    => 'required_if:active,1',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'description',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.description',
                'type'          => 'textarea',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'generate_invoice',
                'title'         => 'Automatically generate the invoice after placing an order',
                'type'          => 'boolean',
                'default_value' => false,
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'    => 'invoice_status',
                'title'   => 'Invoice status after creating the invoice',
                'type'    => 'select',
                'options' => [
                    [
                        'title' => 'admin::app.configuration.index.sales.payment-methods.pending',
                        'value' => 'pending',
                    ], [
                        'title' => 'admin::app.configuration.index.sales.payment-methods.paid',
                        'value' => 'paid',
                    ],
                ],
                'info'          => 'admin::app.configuration.index.sales.payment-methods.generate-invoice-applicable',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'    => 'order_status',
                'title'   => 'Order status after creating the invoice',
                'type'    => 'select',
                'options' => [
                    [
                        'title' => 'admin::app.configuration.index.sales.payment-methods.pending',
                        'value' => 'pending',
                    ], [
                        'title' => 'admin::app.configuration.index.sales.payment-methods.pending-payment',
                        'value' => 'pending_payment',
                    ], [
                        'title' => 'admin::app.configuration.index.sales.payment-methods.processing',
                        'value' => 'processing',
                    ],
                ],
                'info'          => 'admin::app.configuration.index.sales.payment-methods.generate-invoice-applicable',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'          => 'mailing_address',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.mailing-address',
                'type'          => 'textarea',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'active',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.status',
                'type'          => 'boolean',
                'validation'    => 'required',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'    => 'sort',
                'title'   => 'admin::app.configuration.index.sales.payment-methods.sort-order',
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
            ],
        ],
    ],
];
