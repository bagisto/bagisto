<?php

return [
    'configuration' => [
        'index' => [
            'general' => [
                'two_factor_auth' => [
                    'title'    => "Autenticazione a due fattori",
                    'info'     => "Gestisci le impostazioni di autenticazione a due fattori per gli utenti amministratori.",

                    'settings' => [
                        'title'   => "Impostazioni",
                        'info'    => "Gestisci l'autenticazione a due fattori per gli utenti amministratori.",
                        'enabled' => "Abilita l'autenticazione a due fattori",
                    ],
                ],
            ],
        ],
    ],

    'messages' => [
        'enabled_success'  => "L'autenticazione a due fattori è stata abilitata con successo.",
        'invalid_code'     => "Codice di verifica non valido.",
        'disabled_success' => "L'autenticazione a due fattori è stata disabilitata.",
        'verified_success' => "L'autenticazione a due fattori è stata verificata con successo.",
    ],

    'setup' => [
        'title'        => "Abilita l'autenticazione a due fattori",
        'scan_qr'      => "Scansiona questo codice QR nella tua app Google Authenticator, quindi inserisci il codice a 6 cifre qui sotto.",
        'code_label'   => "Codice di verifica",
        'code_placeholder' => "Inserisci il codice a 6 cifre",
        'back'         => "Indietro",
        'verify_enable'=> "Verifica e abilita",
    ],

    'verify' => [
        'title'                 => "Verifica l'autenticazione a due fattori",
        'enter_code'            => "Inserisci il codice a 6 cifre dalla tua app Authenticator per continuare.",
        'code_label'            => "Codice di verifica",
        'code_placeholder'      => "Inserisci il codice a 6 cifre",
        'back'                  => "Indietro",
        'verify_code'           => "Verifica codice",
        'disabled_message'      => "La verifica dell'autenticazione a due fattori è attualmente disabilitata dall'amministratore.",
    ],
];
