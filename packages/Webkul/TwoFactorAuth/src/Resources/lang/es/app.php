<?php

return [
    'configuration' => [
        'index' => [
            'general' => [
                'two_factor_auth' => [
                    'title'    => 'Autenticación en dos pasos',
                    'info'     => 'Administra la configuración de la autenticación en dos pasos para los usuarios administradores.',

                    'settings' => [
                        'title'   => 'Configuración',
                        'info'    => 'Administra la autenticación en dos pasos para los usuarios administradores.',
                        'enabled' => 'Habilitar autenticación en dos pasos',
                    ],
                ],
            ],
        ],
    ],

    'emails' => [
        'backup-codes' => [
            'greeting'            => 'Has habilitado correctamente la autenticación en dos pasos para tu cuenta de administrador.',
            'description'         => 'Por tu seguridad, hemos generado códigos de respaldo que puedes usar si pierdes el acceso a tu aplicación de autenticación. Cada código solo se puede usar una vez.',
            'codes-title'         => 'Tus códigos de respaldo',
            'codes-subtitle'      => 'Guarda estos códigos en un lugar seguro: cada uno solo se puede usar una vez.',
            'warning-title'       => 'Aviso importante de seguridad',
            'warning-description' => 'Mantén estos códigos seguros y no los compartas con nadie. Guárdalos fuera de línea en un lugar seguro.',
        ],
    ],

    'messages' => [
        'enabled_success'  => 'La autenticación en dos pasos se habilitó correctamente.',
        'invalid_code'     => 'Código de verificación inválido.',
        'disabled_success' => 'La autenticación en dos pasos se ha deshabilitado.',
        'verified_success' => 'La autenticación en dos pasos se verificó correctamente.',
        'email_failed'     => 'Error al enviar los códigos de respaldo',
    ],

    'setup' => [
        'title'            => 'Habilitar autenticación en dos pasos',
        'scan_qr'          => 'Escanea este código QR en tu aplicación Google Authenticator y luego introduce el código de 6 dígitos a continuación.',
        'code_label'       => 'Código de verificación',
        'code_placeholder' => 'Introduce el código de 6 dígitos',
        'back'             => 'Atrás',
        'verify_enable'    => 'Verificar y habilitar',
    ],

    'verify' => [
        'title'                 => 'Verificar autenticación en dos pasos',
        'enter_code'            => 'Introduce el código de 6 dígitos de tu aplicación de autenticación para continuar.',
        'code_label'            => 'Código de verificación',
        'code_placeholder'      => 'Introduce el código de 6 dígitos',
        'back'                  => 'Atrás',
        'verify_code'           => 'Verificar código',
        'disabled_message'      => 'La verificación de la autenticación en dos pasos está deshabilitada actualmente por el administrador.',
    ],
];
