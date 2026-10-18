<?php

return [
    'redirect' => [
        'title'          => 'Redirecting to Cashfree Payments...',
        'connecting'     => 'Connecting to Cashfree Payments',
        'do-not-refresh' => 'Please do not refresh or close this window while we securely transfer you to the checkout.',
    ],
    'response' => [
        'provide-credentials'      => 'Please provide valid Cashfree credentials.',
        'cart-not-found'           => 'No active cart found. Please add items to your cart before proceeding to payment.',
        'payment-failed'           => 'Your Cashfree payment has failed. Please try again.',
        'payment-cancelled'        => 'Your Cashfree payment was cancelled.',
        'payment-success'          => 'Your payment was successful. Your order has been placed.',
        'order-creation-failed'    => 'There was an issue creating your order after payment. Please contact support.',
        'supported-currency-error' => 'The currency :currency is not supported. Supported Currencies: :supportedCurrencies.',
    ],
];
