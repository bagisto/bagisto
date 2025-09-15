<?php

return [
    'configuration' => [
        'index' => [
            'general' => [
                'two_factor_auth' => [
                    'title'    => 'Zwei-Faktor-Authentifizierung',
                    'info'     => 'Verwalten Sie die Einstellungen für die Zwei-Faktor-Authentifizierung für Administratoren.',

                    'settings' => [
                        'title'   => 'Einstellungen',
                        'info'    => 'Verwalten Sie die Zwei-Faktor-Authentifizierung für Administratoren.',
                        'enabled' => 'Zwei-Faktor-Authentifizierung aktivieren',
                    ],
                ],
            ],
        ],
    ],

    'emails' => [
        'backup-codes' => [
            'greeting'            => 'Sie haben die Zwei-Faktor-Authentifizierung für Ihr Administratorkonto erfolgreich aktiviert.',
            'description'         => 'Zu Ihrer Sicherheit haben wir Backup-Codes erstellt, die Sie verwenden können, falls Sie den Zugriff auf Ihre Authenticator-App verlieren. Jeder Code kann nur einmal verwendet werden.',
            'codes-title'         => 'Ihre Backup-Codes',
            'codes-subtitle'      => 'Bewahren Sie diese Codes an einem sicheren Ort auf – jeder Code kann nur einmal verwendet werden.',
            'warning-title'       => 'Wichtiger Sicherheitshinweis',
            'warning-description' => 'Bewahren Sie diese Codes sicher auf und teilen Sie sie mit niemandem. Speichern Sie sie offline an einem sicheren Ort.',
        ],
    ],

    'messages' => [
        'enabled_success'  => 'Die Zwei-Faktor-Authentifizierung wurde erfolgreich aktiviert.',
        'invalid_code'     => 'Ungültiger Bestätigungscode.',
        'disabled_success' => 'Die Zwei-Faktor-Authentifizierung wurde deaktiviert.',
        'verified_success' => 'Die Zwei-Faktor-Authentifizierung wurde erfolgreich überprüft.',
        'email_failed'     => 'Fehler beim Senden der Backup-Codes',
    ],

    'setup' => [
        'title'            => 'Zwei-Faktor-Authentifizierung aktivieren',
        'scan_qr'          => 'Scannen Sie diesen QR-Code in Ihrer Google Authenticator-App und geben Sie anschließend den 6-stelligen Code unten ein.',
        'code_label'       => 'Bestätigungscode',
        'code_placeholder' => '6-stelligen Code eingeben',
        'back'             => 'Zurück',
        'verify_enable'    => 'Überprüfen & Aktivieren',
    ],

    'verify' => [
        'title'                 => 'Zwei-Faktor-Authentifizierung überprüfen',
        'enter_code'            => 'Geben Sie den 6-stelligen Code aus Ihrer Authenticator-App ein, um fortzufahren.',
        'code_label'            => 'Bestätigungscode',
        'code_placeholder'      => '6-stelligen Code eingeben',
        'back'                  => 'Zurück',
        'verify_code'           => 'Code überprüfen',
        'disabled_message'      => 'Die Zwei-Faktor-Authentifizierung ist derzeit vom Administrator deaktiviert.',
    ],
];
