<?php

return [
    'configuration' => [
        'index' => [
            'general' => [
                'two_factor_auth' => [
                    'title'    => 'Autenticació de dos factors',
                    'info'     => "Gestiona la configuració de l'autenticació de dos factors per als usuaris administradors.",

                    'settings' => [
                        'title'   => 'Configuració',
                        'info'    => "Gestiona l'autenticació de dos factors per als usuaris administradors.",
                        'enabled' => "Activa l'autenticació de dos factors",
                    ],
                ],
            ],
        ],
    ],

    'emails' => [
        'backup-codes' => [
            'greeting'            => "Has activat correctament l'autenticació de dos factors per al teu compte d'administrador.",
            'description'         => "Per a la teva seguretat, hem generat codis de seguretat que pots utilitzar si perds l'accés a l'aplicació d'autenticació. Cada codi només es pot utilitzar una vegada.",
            'codes-title'         => 'Els teus codis de seguretat',
            'codes-subtitle'      => 'Desa aquests codis en un lloc segur: cada codi només es pot utilitzar una vegada.',
            'warning-title'       => 'Avís important de seguretat',
            'warning-description' => "Mantingues aquests codis segurs i no els comparteixis amb ningú. Desa'ls fora de línia en un lloc segur.",
        ],
    ],

    'messages' => [
        'enabled_success'  => "L'autenticació de dos factors s'ha activat correctament.",
        'invalid_code'     => 'Codi de verificació no vàlid.',
        'disabled_success' => "L'autenticació de dos factors s'ha desactivat.",
        'verified_success' => "L'autenticació de dos factors s'ha verificat correctament.",
        'email_failed'     => "No s'han pogut enviar els codis de seguretat",
    ],

    'setup' => [
        'title'            => "Activa l'autenticació de dos factors",
        'scan_qr'          => "Escaneja aquest codi QR a l'aplicació Google Authenticator i després introdueix el codi de 6 dígits a continuació.",
        'code_label'       => 'Codi de verificació',
        'code_placeholder' => 'Introdueix el codi de 6 dígits',
        'back'             => 'Enrere',
        'verify_enable'    => 'Verifica i activa',
    ],

    'verify' => [
        'title'                 => "Verifica l'autenticació de dos factors",
        'enter_code'            => "Introdueix el codi de 6 dígits de la teva aplicació d'autenticació per continuar.",
        'code_label'            => 'Codi de verificació',
        'code_placeholder'      => 'Introdueix el codi de 6 dígits',
        'back'                  => 'Enrere',
        'verify_code'           => 'Verifica el codi',
        'disabled_message'      => "La verificació de l'autenticació de dos factors està actualment desactivada per l'administrador.",
    ],
];
