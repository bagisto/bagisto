<?php

return [
    'description' => 'Płać bezpiecznie przy użyciu karty kredytowej, karty debetowej, bankowości internetowej, UPI i portfeli za pośrednictwem PayU',
    'title'       => 'PayU Money',

    'redirect' => [
        'click-if-not-redirected' => 'Kliknij tutaj, aby kontynuować',
        'please-wait'             => 'Proszę czekać, przekierowujemy Cię do bramy płatności...',
        'redirect-message'        => 'Jeśli nie zostaniesz automatycznie przekierowany, kliknij przycisk poniżej.',
        'redirecting'             => 'Przekierowywanie do PayU...',
        'redirecting-to-payment'  => 'Przekierowywanie do płatności PayU',
        'secure-payment'          => 'Bezpieczna bramka płatności',
    ],

    'response' => [
        'cart-not-found'        => 'Koszyk nie został znaleziony. Spróbuj ponownie.',
        'hash-mismatch'         => 'Weryfikacja płatności nie powiodła się. Niezgodność hash.',
        'invalid-transaction'   => 'Nieprawidłowa transakcja. Spróbuj ponownie.',
        'order-creation-failed' => 'Utworzenie zamówienia nie powiodło się. Skontaktuj się z pomocą techniczną.',
        'payment-cancelled'     => 'Płatność została anulowana. Możesz spróbować ponownie.',
        'payment-failed'        => 'Płatność nie powiodła się. Spróbuj ponownie.',
        'payment-success'       => 'Płatność zakończona pomyślnie!',
        'provide-credentials'   => 'Skonfiguruj klucz handlowca PayU i Salt w panelu administracyjnym.',
    ],
];
