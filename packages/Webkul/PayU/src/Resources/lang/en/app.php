<?php

return [
    'description' => 'Pay securely using Credit Card, Debit Card, Net Banking, UPI & Wallets via PayU',
    'title'       => 'PayU Money',

    'redirect' => [
        'click-if-not-redirected' => 'Click here to continue',
        'please-wait'             => 'Please wait while we redirect you to the payment gateway...',
        'redirect-message'        => 'If you are not redirected automatically, click the button below.',
        'redirecting'             => 'Redirecting to PayU...',
        'redirecting-to-payment'  => 'Redirecting to PayU Payment',
        'secure-payment'          => 'Secure Payment Gateway',
    ],

    'response' => [
        'cart-not-found'            => 'Cart not found. Please try again.',
        'hash-mismatch'             => 'Payment verification failed. Hash mismatch.',
        'invalid-transaction'       => 'Invalid transaction. Please try again.',
        'order-creation-failed'     => 'Failed to create order. Please contact support.',
        'payment-already-processed' => 'Payment already processed.',
        'payment-cancelled'         => 'Payment was cancelled. You can try again.',
        'payment-failed'            => 'Payment failed. Please try again.',
        'payment-success'           => 'Payment completed successfully!',
        'provide-credentials'       => 'Please configure PayU Merchant Key and Salt in the admin panel.',
    ],
];
