<?php

return [
    'configuration' => [
        'index' => [
            'general' => [
                'two_factor_auth' => [
                    'title'    => "Autenticación de Dos Factores",
                    'info'     => "Administra la configuración de la autenticación de dos factores para los usuarios administradores.",

                    'settings' => [
                        'title'   => "Configuración",
                        'info'    => "Administra la autenticación de dos factores para los usuarios administradores.",
                        'enabled' => "Activar autenticación de dos factores",
                    ],
                ],
            ],
        ],
    ],

    'messages' => [
        'enabled_success'  => "La autenticación de dos factores se ha activado correctamente.",
        'invalid_code'     => "Código de verificación inválido.",
        'disabled_success' => "La autenticación de dos factores ha sido desactivada.",
        'verified_success' => "La autenticación de dos factores se ha verificado correctamente.",
    ],

    'setup' => [
        'title'        => "Activar autenticación de dos factores",
        'scan_qr'      => "Escanea este código QR en tu aplicación Google Authenticator y luego ingresa el código de 6 dígitos a continuación.",
        'code_label'   => "Código de verificación",
        'code_placeholder' => "Ingresa el código de 6 dígitos",
        'back'         => "Volver",
        'verify_enable'=> "Verificar y activar",
    ],

    'verify' => [
        'title'                 => "Verificar autenticación de dos factores",
        'enter_code'            => "Ingresa el código de 6 dígitos de tu aplicación autenticadora para continuar.",
        'code_label'            => "Código de verificación",
        'code_placeholder'      => "Ingresa el código de 6 dígitos",
        'back'                  => "Volver",
        'verify_code'           => "Verificar código",
        'disabled_message'      => "La verificación de autenticación de dos factores está actualmente desactivada por el administrador.",
    ],
];
