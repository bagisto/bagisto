<?php

return [
    'description' => 'Płać bezpiecznie kartą kredytową/debetową przez Stripe.',
    'title' => 'Stripe',

    'line-items' => [
        'shipping' => 'Wysyłka',
        'tax' => 'Podatek',
    ],

    'response' => [
        'cart-changed' => 'Koszyk zmienił się po rozpoczęciu płatności, więc zamówienie nie zostało złożone. Skontaktuj się z nami w sprawie płatności.',
        'cart-not-found' => 'Koszyk nie został znaleziony lub jest nieprawidłowy.',
        'cart-processed' => 'Ten koszyk został już przetworzony.',
        'invalid-session' => 'Sesja płatności jest nieprawidłowa.',
        'payment-cancelled' => 'Płatność została anulowana.',
        'payment-failed' => 'Płatność nie powiodła się.',
        'payment-success' => 'Płatność została pomyślnie zakończona.',
        'provide-credentials' => 'Proszę podać prawidłowe dane uwierzytelniające Stripe.',
        'session-invalid' => 'Sesja płatności wygasła lub jest nieprawidłowa.',
        'session-not-found' => 'Nie znaleziono sesji płatności.',
        'verification-failed' => 'Weryfikacja płatności nie powiodła się.',
    ],

    'webhook' => [
        'dispute-closed' => 'Spór Stripe :id został zamknięty z wynikiem: :status.',
        'dispute-opened' => 'Klient zakwestionował :amount tej płatności w Stripe (:id), z powodu: :reason.',
        'refund-recorded' => 'Zarejestrowano zwrot :amount wykonany w Stripe; łącznie w Stripe zwrócono :total.',
    ],
];
