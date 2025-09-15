<?php

return [
    'configuration' => [
        'index' => [
            'general' => [
                'two_factor_auth' => [
                    'title'    => "Uwierzytelnianie dwuskładnikowe",
                    'info'     => "Zarządzaj ustawieniami uwierzytelniania dwuskładnikowego dla użytkowników administratora.",

                    'settings' => [
                        'title'   => "Ustawienia",
                        'info'    => "Zarządzaj uwierzytelnianiem dwuskładnikowym dla użytkowników administratora.",
                        'enabled' => "Włącz uwierzytelnianie dwuskładnikowe",
                    ],
                ],
            ],
        ],
    ],

    'messages' => [
        'enabled_success'  => "Uwierzytelnianie dwuskładnikowe zostało pomyślnie włączone.",
        'invalid_code'     => "Nieprawidłowy kod weryfikacyjny.",
        'disabled_success' => "Uwierzytelnianie dwuskładnikowe zostało wyłączone.",
        'verified_success' => "Uwierzytelnianie dwuskładnikowe zostało pomyślnie zweryfikowane.",
    ],

    'setup' => [
        'title'        => "Włącz uwierzytelnianie dwuskładnikowe",
        'scan_qr'      => "Zeskanuj ten kod QR w aplikacji Google Authenticator, a następnie wprowadź poniżej 6-cyfrowy kod.",
        'code_label'   => "Kod weryfikacyjny",
        'code_placeholder' => "Wprowadź 6-cyfrowy kod",
        'back'         => "Powrót",
        'verify_enable'=> "Weryfikuj i włącz",
    ],

    'verify' => [
        'title'                 => "Zweryfikuj uwierzytelnianie dwuskładnikowe",
        'enter_code'            => "Wprowadź 6-cyfrowy kod z aplikacji uwierzytelniającej, aby kontynuować.",
        'code_label'            => "Kod weryfikacyjny",
        'code_placeholder'      => "Wprowadź 6-cyfrowy kod",
        'back'                  => "Powrót",
        'verify_code'           => "Zweryfikuj kod",
        'disabled_message'      => "Weryfikacja uwierzytelniania dwuskładnikowego jest obecnie wyłączona przez administratora.",
    ],
];
