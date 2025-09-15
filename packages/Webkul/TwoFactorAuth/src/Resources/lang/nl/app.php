<?php

return [
    'configuration' => [
        'index' => [
            'general' => [
                'two_factor_auth' => [
                    'title'    => 'Twee-Factor Authenticatie',
                    'info'     => 'Beheer de instellingen voor twee-factor authenticatie voor beheerdersgebruikers.',

                    'settings' => [
                        'title'   => 'Instellingen',
                        'info'    => 'Beheer twee-factor authenticatie voor beheerdersgebruikers.',
                        'enabled' => 'Schakel Twee-Factor Authenticatie in',
                    ],
                ],
            ],
        ],
    ],

    'emails' => [
        'backup-codes' => [
            'greeting'            => 'U heeft met succes twee-factor authenticatie ingeschakeld voor uw beheerdersaccount.',
            'description'         => 'Voor uw veiligheid hebben we back-upcodes gegenereerd die u kunt gebruiken als u de toegang tot uw authenticator-app verliest. Elke code kan slechts één keer worden gebruikt.',
            'codes-title'         => 'Uw back-upcodes',
            'codes-subtitle'      => 'Bewaar deze codes op een veilige plek - elke code kan slechts één keer worden gebruikt.',
            'warning-title'       => 'Belangrijke beveiligingsmelding',
            'warning-description' => 'Houd deze codes veilig en deel ze niet met anderen. Bewaar ze offline op een veilige locatie.',
        ],
    ],

    'messages' => [
        'enabled_success'  => 'Twee-factor authenticatie is succesvol ingeschakeld.',
        'invalid_code'     => 'Ongeldige verificatiecode.',
        'disabled_success' => 'Twee-factor authenticatie is uitgeschakeld.',
        'verified_success' => 'Twee-factor authenticatie is succesvol geverifieerd.',
        'email_failed'     => 'Het verzenden van back-upcodes is mislukt',
    ],

    'setup' => [
        'title'            => 'Schakel Twee-Factor Authenticatie in',
        'scan_qr'          => 'Scan deze QR-code in uw Google Authenticator-app en voer vervolgens de 6-cijferige code hieronder in.',
        'code_label'       => 'Verificatiecode',
        'code_placeholder' => 'Voer de 6-cijferige code in',
        'back'             => 'Terug',
        'verify_enable'    => 'Verifiëren & Inschakelen',
    ],

    'verify' => [
        'title'                 => 'Verifieer Twee-Factor Authenticatie',
        'enter_code'            => 'Voer de 6-cijferige code van uw authenticator-app in om door te gaan.',
        'code_label'            => 'Verificatiecode',
        'code_placeholder'      => 'Voer de 6-cijferige code in',
        'back'                  => 'Terug',
        'verify_code'           => 'Code Verifiëren',
        'disabled_message'      => 'Verificatie van twee-factor authenticatie is momenteel uitgeschakeld door de beheerder.',
    ],
];
