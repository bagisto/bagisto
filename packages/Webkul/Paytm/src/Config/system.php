<?php

return [
    [
        'key' => 'sales.payment_methods.paytm',
        'name' => 'paytm::app.admin.configuration.index.sales.payment-methods.paytm',
        'info' => 'paytm::app.admin.configuration.index.sales.payment-methods.paytm-info',
        'sort' => 5,
        'fields' => [
            [
                'name' => 'active',
                'title' => 'paytm::app.admin.configuration.index.sales.payment-methods.status',
                'type' => 'boolean',
                'default_value' => false,
                'channel_based' => true,
            ],
            [
                'name' => 'title',
                'title' => 'paytm::app.admin.configuration.index.sales.payment-methods.title',
                'type' => 'text',
                'channel_based' => true,
                'locale_based' => true,
            ],
            [
                'name' => 'description',
                'title' => 'paytm::app.admin.configuration.index.sales.payment-methods.description',
                'type' => 'textarea',
                'channel_based' => true,
                'locale_based' => true,
            ],
            [
                'name' => 'image',
                'title' => 'paytm::app.admin.configuration.index.sales.payment-methods.logo',
                'type' => 'image',
                'info' => 'paytm::app.admin.configuration.index.sales.payment-methods.logo-information',
                'channel_based' => true,
                'locale_based' => false,
                'validation' => 'mimes:bmp,jpeg,jpg,png,webp',
            ],
            [
                'name' => 'merchant_id',
                'title' => 'paytm::app.admin.configuration.index.sales.payment-methods.merchant-id',
                'type' => 'text',
                'validation' => 'required',
                'channel_based' => true,
            ],
            [
                'name' => 'merchant_key',
                'title' => 'paytm::app.admin.configuration.index.sales.payment-methods.merchant-key',
                'type' => 'password',
                'validation' => 'required',
                'channel_based' => true,
            ],
            [
                'name' => 'environment',
                'title' => 'paytm::app.admin.configuration.index.sales.payment-methods.environment',
                'type' => 'select',
                'default_value' => 'sandbox',
                'options' => [
                    [
                        'title' => 'paytm::app.admin.configuration.index.sales.payment-methods.environment-sandbox',
                        'value' => 'sandbox',
                    ],
                    [
                        'title' => 'paytm::app.admin.configuration.index.sales.payment-methods.environment-production',
                        'value' => 'production',
                    ],
                ],
                'channel_based' => true,
            ],
            [
                'name' => 'sort',
                'title' => 'paytm::app.admin.configuration.index.sales.payment-methods.sort-order',
                'type' => 'text',
                'default_value' => '5',
            ],
        ],
    ],
];
