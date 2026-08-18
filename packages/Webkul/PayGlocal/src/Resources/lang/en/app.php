<?php

return [
    'description' => 'Pay securely with your credit or debit card via PayGlocal.',
    'title' => 'PayGlocal',

    'response' => [
        'cart-not-found' => 'Cart not found or invalid.',
        'order-creation-failed' => 'The payment succeeded but the order could not be created. Please contact support.',
        'payment-cancelled' => 'The payment was cancelled.',
        'payment-failed' => 'The payment failed. Please try again.',
        'payment-pending' => 'Your PayGlocal payment is still pending. Please complete it or wait a moment and check again.',
        'payment-success' => 'The payment completed successfully.',
        'provide-credentials' => 'Please provide valid PayGlocal credentials.',
        'supported-currency-error' => 'The currency :currency is not supported. Supported Currencies: :supportedCurrencies.',
        'transaction-not-found' => 'No PayGlocal transaction was found for this payment.',
        'verification-failed' => 'The payment could not be verified.',
    ],
];
