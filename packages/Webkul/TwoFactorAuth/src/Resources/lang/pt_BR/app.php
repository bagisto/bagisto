<?php

return [
    'configuration' => [
        'index' => [
            'general' => [
                'two_factor_auth' => [
                    'title'    => "Autenticação de Dois Fatores",
                    'info'     => "Gerencie as configurações de autenticação de dois fatores para usuários administradores.",

                    'settings' => [
                        'title'   => "Configurações",
                        'info'    => "Gerencie a autenticação de dois fatores para usuários administradores.",
                        'enabled' => "Ativar autenticação de dois fatores",
                    ],
                ],
            ],
        ],
    ],

    'messages' => [
        'enabled_success'  => "Autenticação de dois fatores ativada com sucesso.",
        'invalid_code'     => "Código de verificação inválido.",
        'disabled_success' => "Autenticação de dois fatores desativada.",
        'verified_success' => "Autenticação de dois fatores verificada com sucesso.",
    ],

    'setup' => [
        'title'        => "Ativar autenticação de dois fatores",
        'scan_qr'      => "Escaneie este código QR no seu aplicativo Google Authenticator e depois insira o código de 6 dígitos abaixo.",
        'code_label'   => "Código de Verificação",
        'code_placeholder' => "Digite o código de 6 dígitos",
        'back'         => "Voltar",
        'verify_enable'=> "Verificar e Ativar",
    ],

    'verify' => [
        'title'                 => "Verificar autenticação de dois fatores",
        'enter_code'            => "Digite o código de 6 dígitos do seu aplicativo autenticador para continuar.",
        'code_label'            => "Código de Verificação",
        'code_placeholder'      => "Digite o código de 6 dígitos",
        'back'                  => "Voltar",
        'verify_code'           => "Verificar Código",
        'disabled_message'      => "A verificação da autenticação de dois fatores está atualmente desativada pelo administrador.",
    ],
];
