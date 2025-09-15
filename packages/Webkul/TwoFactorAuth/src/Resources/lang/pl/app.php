<?php

return [
    'configuration' => [
        'index' => [
            'general' => [
                'two_factor_auth' => [
                    'title'    => 'Uwierzytelnianie Dwuskładnikowe',
                    'info'     => 'Zarządzaj ustawieniami uwierzytelniania dwuskładnikowego dla użytkowników administracyjnych.',

                    'settings' => [
                        'title'   => 'Ustawienia',
                        'info'    => 'Zarządzaj uwierzytelnianiem dwuskładnikowym dla użytkowników administracyjnych.',
                        'enabled' => 'Włącz uwierzytelnianie dwuskładnikowe',
                    ],
                ],
            ],
        ],
    ],

    'emails' => [
        'backup-codes' => [
            'greeting'            => 'Pomyślnie włączono uwierzytelnianie dwuskładnikowe dla Twojego konta administratora.',
            'description'         => 'Dla Twojego bezpieczeństwa wygenerowaliśmy kody zapasowe, których możesz użyć, jeśli utracisz dostęp do aplikacji uwierzytelniającej. Każdy kod można użyć tylko raz.',
            'codes-title'         => 'Twoje kody zapasowe',
            'codes-subtitle'      => 'Przechowuj te kody w bezpiecznym miejscu – każdy kod można użyć tylko raz.',
            'warning-title'       => 'Ważne powiadomienie bezpieczeństwa',
            'warning-description' => 'Trzymaj te kody w bezpiecznym miejscu i nie udostępniaj ich nikomu. Przechowuj je offline w bezpiecznej lokalizacji.',
        ],
    ],

    'messages' => [
        'enabled_success'  => 'Uwierzytelnianie dwuskładnikowe zostało pomyślnie włączone.',
        'invalid_code'     => 'Nieprawidłowy kod weryfikacyjny.',
        'disabled_success' => 'Uwierzytelnianie dwuskładnikowe zostało wyłączone.',
        'verified_success' => 'Uwierzytelnianie dwuskładnikowe zostało pomyślnie zweryfikowane.',
        'email_failed'     => 'Nie udało się dostarczyć kodów zapasowych',
    ],

    'setup' => [
        'title'            => 'Włącz uwierzytelnianie dwuskładnikowe',
        'scan_qr'          => 'Zeskanuj ten kod QR w aplikacji Google Authenticator, a następnie wprowadź poniżej 6-cyfrowy kod.',
        'code_label'       => 'Kod weryfikacyjny',
        'code_placeholder' => 'Wprowadź 6-cyfrowy kod',
        'back'             => 'Wstecz',
        'verify_enable'    => 'Weryfikuj i włącz',
    ],

    'verify' => [
        'title'                 => 'Weryfikacja uwierzytelniania dwuskładnikowego',
        'enter_code'            => 'Wprowadź 6-cyfrowy kod z aplikacji uwierzytelniającej, aby kontynuować.',
        'code_label'            => 'Kod weryfikacyjny',
        'code_placeholder'      => 'Wprowadź 6-cyfrowy kod',
        'back'                  => 'Wstecz',
        'verify_code'           => 'Weryfikuj kod',
        'disabled_message'      => 'Weryfikacja uwierzytelniania dwuskładnikowego jest obecnie wyłączona przez administratora.',
    ],
];
