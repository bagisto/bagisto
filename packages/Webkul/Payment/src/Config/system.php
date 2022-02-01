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
        'name' => 'admin::app.admin.system.payment-methods',
        'sort' => 3,
    ], [
        'key'    => 'sales.paymentmethods.cashondelivery',
        'name'   => 'admin::app.admin.system.cash-on-delivery',
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
                'name'          => 'instructions',
                'title'         => 'admin::app.admin.system.instructions',
                'type'          => 'textarea',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'generate_invoice',
                'title'         => 'admin::app.admin.system.generate-invoice',
                'type'          => 'boolean',
                'default_value' => false,
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'          => 'invoice_status',
                'title'         => 'admin::app.admin.system.set-invoice-status',
                'validation'    => 'required_if:generate_invoice,1',
                'type'          => 'select',
                'options'       => [
                    [
                        'title' => 'admin::app.sales.invoices.status-pending',
                        'value' => 'pending',
                    ], [
                        'title' => 'admin::app.sales.invoices.status-paid',
                        'value' => 'paid',
                    ],
                ],
                'info'          => 'admin::app.admin.system.generate-invoice-applicable',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'          => 'order_status',
                'title'         => 'admin::app.admin.system.set-order-status',
                'type'          => 'select',
                'options'       => [
                    [
                        'title' => 'admin::app.sales.orders.order-status-pending',
                        'value' => 'pending',
                    ], [
                        'title' => 'admin::app.sales.orders.order-status-pending-payment',
                        'value' => 'pending_payment',
                    ], [
                        'title' => 'admin::app.sales.orders.order-status-processing',
                        'value' => 'processing',
                    ],
                ],
                'info'          => 'admin::app.admin.system.generate-invoice-applicable',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'          => 'active',
                'title'         => 'admin::app.admin.system.status',
                'type'          => 'boolean',
                'validation'    => 'required',
                'channel_based' => true,
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
            ],
        ],
    ], [
        'key'    => 'sales.paymentmethods.moneytransfer',
        'name'   => 'admin::app.admin.system.money-transfer',
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
                        'title' => 'admin::app.sales.invoices.status-pending',
                        'value' => 'pending',
                    ], [
                        'title' => 'admin::app.sales.invoices.status-paid',
                        'value' => 'paid',
                    ],
                ],
                'info'    => 'admin::app.admin.system.generate-invoice-applicable',
            ], [
                'name'    => 'order_status',
                'title'   => 'Order status after creating the invoice',
                'type'    => 'select',
                'options' => [
                    [
                        'title' => 'admin::app.sales.orders.order-status-pending',
                        'value' => 'pending',
                    ], [
                        'title' => 'admin::app.sales.orders.order-status-pending-payment',
                        'value' => 'pending_payment',
                    ], [
                        'title' => 'admin::app.sales.orders.order-status-processing',
                        'value' => 'processing',
                    ],
                ],
                'info'    => 'admin::app.admin.system.generate-invoice-applicable',
            ], [
                'name'          => 'mailing_address',
                'title'         => 'admin::app.admin.system.mailing-address',
                'type'          => 'textarea',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'active',
                'title'         => 'admin::app.admin.system.status',
                'type'          => 'boolean',
                'validation'    => 'required',
                'channel_based' => true,
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
            ],
        ],
    ],
];
