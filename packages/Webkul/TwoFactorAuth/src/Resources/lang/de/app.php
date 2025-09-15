<?php

return [
    'configuration' => [
        'index' => [
            'general' => [
                'two_factor_auth' => [
                    'title'    => "Zwei-Faktor-Authentifizierung",
                    'info'     => "Verwalten Sie die Einstellungen der Zwei-Faktor-Authentifizierung für Admin-Benutzer.",

                    'settings' => [
                        'title'   => "Einstellungen",
                        'info'    => "Verwalten Sie die Zwei-Faktor-Authentifizierung für Admin-Benutzer.",
                        'enabled' => "Zwei-Faktor-Authentifizierung aktivieren",
                    ],
                ],
            ],
        ],
    ],

    'messages' => [
        'enabled_success'  => "Die Zwei-Faktor-Authentifizierung wurde erfolgreich aktiviert.",
        'invalid_code'     => "Ungültiger Verifizierungscode.",
        'disabled_success' => "Die Zwei-Faktor-Authentifizierung wurde deaktiviert.",
        'verified_success' => "Die Zwei-Faktor-Authentifizierung wurde erfolgreich verifiziert.",
    ],

    'setup' => [
        'title'        => "Zwei-Faktor-Authentifizierung aktivieren",
        'scan_qr'      => "Scannen Sie diesen QR-Code in Ihrer Google Authenticator-App und geben Sie anschließend den 6-stelligen Code unten ein.",
        'code_label'   => "Verifizierungscode",
        'code_placeholder' => "6-stelligen Code eingeben",
        'back'         => "Zurück",
        'verify_enable'=> "Überprüfen & Aktivieren",
    ],

    'verify' => [
        'title'                 => "Zwei-Faktor-Authentifizierung überprüfen",
        'enter_code'            => "Geben Sie den 6-stelligen Code aus Ihrer Authenticator-App ein, um fortzufahren.",
        'code_label'            => "Verifizierungscode",
        'code_placeholder'      => "6-stelligen Code eingeben",
        'back'                  => "Zurück",
        'verify_code'           => "Code überprüfen",
        'disabled_message'      => "Die Zwei-Faktor-Authentifizierung ist derzeit vom Admin deaktiviert.",
    ],
];
