<?php

return [
    'admin' => [
        'install' => [
            'success' => 'Paytm payment gateway installed successfully.',
        ],
        'configuration' => [
            'index' => [
                'sales' => [
                    'payment-methods' => [
                        'paytm' => 'Paytm',
                        'paytm-info' => 'Paytm Payment Gateway Configuration',
                        'status' => 'Status',
                        'title' => 'Title',
                        'description' => 'Description',
                        'logo' => 'Logo',
                        'logo-information' => 'Upload payment method logo.',
                        'merchant-id' => 'Merchant ID',
                        'merchant-key' => 'Merchant Key',
                        'environment' => 'Environment',
                        'environment-sandbox' => 'Sandbox',
                        'environment-production' => 'Production',
                        'sort-order' => 'Sort Order',
                    ],
                ],
            ],
        ],
    ],

    'shop' => [
        'payment' => [
            'title' => 'Paytm',
            'description' => 'Paytm Payment Gateway',
            'redirecting' => 'You will be redirected to Paytm in a few seconds.',
            'redirect-fallback' => 'Click here if you are not redirected within 10 seconds...',
            'cart-empty' => 'Your cart is empty.',
            'general-error' => 'Something went wrong. Please try again.',
            'missing-cart-id' => 'Missing cart id.',
            'cart-not-found' => 'Cart not found.',
            'checksum-failed' => 'Checksum validation failed.',
            'payment-failed' => 'Paytm payment failed or cancelled. Please try again.',
            'payment-success' => 'Paytm payment completed successfully.',
            'minimum-order-message' => 'Minimum order amount is :amount.',
            'check-shipping-address' => 'Please select shipping address.',
            'check-billing-address' => 'Please select billing address.',
            'specify-shipping-method' => 'Please specify shipping method.',
            'specify-payment-method' => 'Please specify payment method.',
        ],
    ],
];
