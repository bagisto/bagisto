<?php

return [
    'description' => 'Zapłać bezpiecznie kartą kredytową lub debetową za pośrednictwem PayGlocal.',
    'title' => 'PayGlocal',

    'response' => [
        'cart-not-found' => 'Nie znaleziono koszyka lub jest nieprawidłowy.',
        'order-creation-failed' => 'Płatność powiodła się, ale nie udało się utworzyć zamówienia. Skontaktuj się z pomocą techniczną.',
        'payment-cancelled' => 'Płatność została anulowana.',
        'payment-failed' => 'Płatność nie powiodła się. Spróbuj ponownie.',
        'payment-pending' => 'Twoja płatność PayGlocal jest nadal w toku. Proszę uzupełnij ją lub czekaj chwilę i sprawdź ponownie.',
        'payment-success' => 'Płatność została zrealizowana pomyślnie.',
        'provide-credentials' => 'Podaj prawidłowe dane uwierzytelniające PayGlocal.',
        'supported-currency-error' => 'Waluta :currency nie jest obsługiwana. Obsługiwane waluty: :supportedCurrencies.',
        'transaction-not-found' => 'Nie znaleziono transakcji PayGlocal dla tej płatności.',
        'verification-failed' => 'Nie udało się zweryfikować płatności.',
    ],
];
