<?php

return [
    'configuration' => [
        'index' => [
            'general' => [
                'two_factor_auth' => [
                    'title'    => "Autenticazione a Due Fattori",
                    'info'     => "Gestisci le impostazioni dell'autenticazione a due fattori per gli utenti amministratori.",

                    'settings' => [
                        'title'   => "Impostazioni",
                        'info'    => "Gestisci l'autenticazione a due fattori per gli utenti amministratori.",
                        'enabled' => "Abilita Autenticazione a Due Fattori",
                    ],
                ],
            ],
        ],
    ],

    'emails' => [
        'backup-codes' => [
            'greeting'            => "Hai abilitato correttamente l'autenticazione a due fattori per il tuo account amministratore.",
            'description'         => "Per la tua sicurezza, abbiamo generato dei codici di backup che puoi usare se perdi l'accesso alla tua app di autenticazione. Ogni codice può essere usato una sola volta.",
            'codes-title'         => "I tuoi codici di backup",
            'codes-subtitle'      => "Conserva questi codici in un luogo sicuro — ciascuno può essere usato una sola volta.",
            'warning-title'       => "Avviso di sicurezza importante",
            'warning-description' => "Mantieni questi codici al sicuro e non condividerli con nessuno. Conservali offline in un luogo sicuro.",
        ],
    ],

    'messages' => [
        'enabled_success'  => "L'autenticazione a due fattori è stata abilitata con successo.",
        'invalid_code'     => "Codice di verifica non valido.",
        'disabled_success' => "L'autenticazione a due fattori è stata disabilitata.",
        'verified_success' => "L'autenticazione a due fattori è stata verificata con successo.",
        'email_failed'     => "Invio dei codici di backup non riuscito",
    ],

    'setup' => [
        'title'            => "Abilita Autenticazione a Due Fattori",
        'scan_qr'          => "Scansiona questo codice QR nella tua app Google Authenticator, quindi inserisci il codice a 6 cifre qui sotto.",
        'code_label'       => "Codice di Verifica",
        'code_placeholder' => "Inserisci il codice a 6 cifre",
        'back'             => "Indietro",
        'verify_enable'    => "Verifica e Abilita",
    ],

    'verify' => [
        'title'                 => "Verifica Autenticazione a Due Fattori",
        'enter_code'            => "Inserisci il codice a 6 cifre dalla tua app di autenticazione per continuare.",
        'code_label'            => "Codice di Verifica",
        'code_placeholder'      => "Inserisci il codice a 6 cifre",
        'back'                  => "Indietro",
        'verify_code'           => "Verifica Codice",
        'disabled_message'      => "La verifica dell'autenticazione a due fattori è attualmente disabilitata dall'amministratore.",
    ],
];
