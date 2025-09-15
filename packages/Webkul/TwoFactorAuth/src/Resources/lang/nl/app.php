<?php

return [
    'configuration' => [
        'index' => [
            'general' => [
                'two_factor_auth' => [
                    'title'    => "Twee-factor-authenticatie",
                    'info'     => "Beheer de instellingen van twee-factor-authenticatie voor beheerdersgebruikers.",

                    'settings' => [
                        'title'   => "Instellingen",
                        'info'    => "Beheer twee-factor-authenticatie voor beheerdersgebruikers.",
                        'enabled' => "Schakel twee-factor-authenticatie in",
                    ],
                ],
            ],
        ],
    ],

    'messages' => [
        'enabled_success'  => "Twee-factor-authenticatie is succesvol ingeschakeld.",
        'invalid_code'     => "Ongeldige verificatiecode.",
        'disabled_success' => "Twee-factor-authenticatie is uitgeschakeld.",
        'verified_success' => "Twee-factor-authenticatie is succesvol geverifieerd.",
    ],

    'setup' => [
        'title'        => "Schakel twee-factor-authenticatie in",
        'scan_qr'      => "Scan deze QR-code in uw Google Authenticator-app en voer vervolgens de 6-cijferige code hieronder in.",
        'code_label'   => "Verificatiecode",
        'code_placeholder' => "Voer de 6-cijferige code in",
        'back'         => "Terug",
        'verify_enable'=> "Verifiëren & Inschakelen",
    ],

    'verify' => [
        'title'                 => "Verifieer twee-factor-authenticatie",
        'enter_code'            => "Voer de 6-cijferige code van uw authenticator-app in om door te gaan.",
        'code_label'            => "Verificatiecode",
        'code_placeholder'      => "Voer de 6-cijferige code in",
        'back'                  => "Terug",
        'verify_code'           => "Code verifiëren",
        'disabled_message'      => "Het verifiëren van twee-factor-authenticatie is momenteel uitgeschakeld door de beheerder.",
    ],
];
