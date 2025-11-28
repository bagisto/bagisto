<?php

return [
    'description' => 'Pay securely with your credit/debit card via Stripe.',
    'title'       => 'Stripe',

    'response' => [
        'cart-not-found'      => 'Cart not found or invalid.',
        'cart-processed'      => 'This cart has already been processed.',
        'invalid-session'     => 'Payment session is invalid.',
        'payment-cancelled'   => 'Payment was cancelled.',
        'payment-failed'      => 'Payment failed.',
        'payment-success'     => 'Payment completed successfully.',
        'provide-credentials' => 'Please provide valid Stripe credentials.',
        'session-invalid'     => 'Payment session has expired or is invalid.',
        'session-not-found'   => 'Payment session not found.',
        'verification-failed' => 'Payment verification failed.',
    ],
];
