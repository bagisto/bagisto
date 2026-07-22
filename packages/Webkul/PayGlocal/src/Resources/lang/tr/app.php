<?php

return [
    'description' => 'Pay securely with your credit or debit card via PayGlocal.',
    'title' => 'PayGlocal',

    'response' => [
        'cart-not-found' => 'Cart not found or invalid.',
        'order-creation-failed' => 'The payment succeeded but the order could not be created. Please contact support.',
        'payment-cancelled' => 'The payment was cancelled.',
        'payment-failed' => 'The payment failed. Please try again.',
        'payment-pending' => 'PayGlocal ödemeniz hala beklemeye alınmıştır. Lütfen tamamlayın veya bir an bekleyip tekrar kontrol edin.',
        'payment-success' => 'The payment completed successfully.',
        'provide-credentials' => 'Please provide valid PayGlocal credentials.',
        'supported-currency-error' => 'Para birimi :currency desteklenmiyor. Desteklenen para birimleri: :supportedCurrencies.',
        'transaction-not-found' => 'No PayGlocal transaction was found for this payment.',
        'verification-failed' => 'The payment could not be verified.',
    ],
];
