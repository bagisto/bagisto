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
        'name' => 'admin::app.configuration.payment-methods',
        'info' => 'admin::app.configuration.payment-methods-info',
        'icon' => 'payment-method.png',
        'sort' => 3,
    ], [
        'key'    => 'sales.paymentmethods.cashondelivery',
        'name'   => 'admin::app.configuration.cash-on-delivery',
        'info'   => 'admin::app.configuration.cash-on-delivery-info',
        'sort'   => 1,
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
            ], [
                'name'          => 'instructions',
                'title'         => 'admin::app.configuration.instructions',
                'type'          => 'textarea',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'generate_invoice',
                'title'         => 'admin::app.configuration.generate-invoice',
                'type'          => 'boolean',
                'default_value' => false,
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'          => 'invoice_status',
                'title'         => 'admin::app.configuration.set-invoice-status',
                'validation'    => 'required_if:generate_invoice,1',
                'type'          => 'select',
                'options'       => [
                    [
                        'title' => 'admin::app.sales.invoices.status.pending',
                        'value' => 'pending',
                    ], [
                        'title' => 'admin::app.sales.invoices.status.paid',
                        'value' => 'paid',
                    ],
                ],
                'info'          => 'admin::app.configuration.generate-invoice-applicable',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'          => 'order_status',
                'title'         => 'admin::app.configuration.set-order-status',
                'type'          => 'select',
                'options'       => [
                    [
                        'title' => 'admin::app.sales.orders.status.pending',
                        'value' => 'pending',
                    ], [
                        'title' => 'admin::app.sales.orders.status.pending-payment',
                        'value' => 'pending_payment',
                    ], [
                        'title' => 'admin::app.sales.orders.status.processing',
                        'value' => 'processing',
                    ],
                ],
                'info'          => 'admin::app.configuration.generate-invoice-applicable',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'          => 'active',
                'title'         => 'admin::app.configuration.status',
                'type'          => 'boolean',
                'validation'    => 'required',
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
            ],
        ],
    ], [
        'key'    => 'sales.paymentmethods.moneytransfer',
        'name'   => 'admin::app.configuration.money-transfer',
        'info'   => 'admin::app.configuration.money-transfer-info',
        'sort'   => 2,
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
                        'title' => 'admin::app.sales.invoices.status.pending',
                        'value' => 'pending',
                    ], [
                        'title' => 'admin::app.sales.invoices.status.paid',
                        'value' => 'paid',
                    ],
                ],
                'info'          => 'admin::app.configuration.generate-invoice-applicable',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'    => 'order_status',
                'title'   => 'Order status after creating the invoice',
                'type'    => 'select',
                'options' => [
                    [
                        'title' => 'admin::app.sales.orders.status.pending',
                        'value' => 'pending',
                    ], [
                        'title' => 'admin::app.sales.orders.status.pending-payment',
                        'value' => 'pending_payment',
                    ], [
                        'title' => 'admin::app.sales.orders.status.processing',
                        'value' => 'processing',
                    ],
                ],
                'info'          => 'admin::app.configuration.generate-invoice-applicable',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'          => 'mailing_address',
                'title'         => 'admin::app.configuration.mailing-address',
                'type'          => 'textarea',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'active',
                'title'         => 'admin::app.configuration.status',
                'type'          => 'boolean',
                'validation'    => 'required',
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
            ],
        ],
    ],
];
