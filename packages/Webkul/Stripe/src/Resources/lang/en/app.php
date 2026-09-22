<?php

return [
    'description' => 'Pay securely with your credit/debit card via Stripe.',
    'title' => 'Stripe',

    'line-items' => [
        'shipping' => 'Shipping',
        'tax' => 'Tax',
    ],

    'response' => [
        'cart-changed' => 'Your cart changed after the payment was started, so no order was placed. Please contact us about your payment.',
        'cart-not-found' => 'Cart not found or invalid.',
        'cart-processed' => 'This cart has already been processed.',
        'invalid-session' => 'Payment session is invalid.',
        'payment-cancelled' => 'Payment was cancelled.',
        'payment-failed' => 'Payment failed.',
        'payment-success' => 'Payment completed successfully.',
        'provide-credentials' => 'Please provide valid Stripe credentials.',
        'session-invalid' => 'Payment session has expired or is invalid.',
        'session-not-found' => 'Payment session not found.',
        'verification-failed' => 'Payment verification failed.',
    ],

    'webhook' => [
        'dispute-closed' => 'Stripe dispute :id closed with the outcome: :status.',
        'dispute-opened' => 'The customer disputed :amount of this payment in Stripe (:id), for the reason: :reason.',
        'refund-recorded' => 'A refund of :amount made in Stripe was recorded; :total has been refunded in Stripe in all.',
    ],
];
