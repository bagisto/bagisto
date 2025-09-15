<?php

return [
    'configuration' => [
        'index' => [
            'general' => [
                'two_factor_auth' => [
                    'title'    => "Autenticació de Dos Factors",
                    'info'     => "Gestiona la configuració de l'autenticació de dos factors per als usuaris administradors.",

                    'settings' => [
                        'title'   => "Configuració",
                        'info'    => "Gestiona l'autenticació de dos factors per als usuaris administradors.",
                        'enabled' => "Activa l'autenticació de dos factors",
                    ],
                ],
            ],
        ],
    ],

    'messages' => [
        'enabled_success'  => "L'autenticació de dos factors s'ha activat correctament.",
        'invalid_code'     => "Codi de verificació invàlid.",
        'disabled_success' => "L'autenticació de dos factors s'ha desactivat.",
        'verified_success' => "L'autenticació de dos factors s'ha verificat correctament.",
    ],

    'setup' => [
        'title'        => "Activa l'autenticació de dos factors",
        'scan_qr'      => "Escaneja aquest codi QR a l'aplicació Google Authenticator i després introdueix el codi de 6 dígits a continuació.",
        'code_label'   => "Codi de verificació",
        'code_placeholder' => "Introdueix el codi de 6 dígits",
        'back'         => "Enrere",
        'verify_enable'=> "Verifica i activa",
    ],

    'verify' => [
        'title'                 => "Verifica l'autenticació de dos factors",
        'enter_code'            => "Introdueix el codi de 6 dígits de la teva aplicació d'autenticació per continuar.",
        'code_label'            => "Codi de verificació",
        'code_placeholder'      => "Introdueix el codi de 6 dígits",
        'back'                  => "Enrere",
        'verify_code'           => "Verifica el codi",
        'disabled_message'      => "La verificació de l'autenticació de dos factors està desactivada actualment per l'administrador.",
    ],
];
