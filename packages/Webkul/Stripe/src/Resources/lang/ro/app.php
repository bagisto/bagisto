<?php

return [
    'description' => 'Pay securely with your credit/debit card via Stripe.',
    'title' => 'Stripe',

    'line-items' => [
        'shipping' => 'Livrare',
        'tax' => 'Taxe',
    ],

    'response' => [
        'cart-changed' => 'Coșul s-a modificat după începerea plății, așa că nu a fost plasată nicio comandă. Vă rugăm să ne contactați în legătură cu plata.',
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
        'dispute-closed' => 'Disputa Stripe :id a fost închisă cu rezultatul: :status.',
        'dispute-opened' => 'Clientul a contestat :amount din această plată în Stripe (:id), din motivul: :reason.',
        'refund-recorded' => 'A fost înregistrată o rambursare de :amount efectuată în Stripe; în total s-au rambursat :total în Stripe.',
    ],
];
