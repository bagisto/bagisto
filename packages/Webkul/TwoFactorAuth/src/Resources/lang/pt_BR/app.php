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
                        'enabled' => "Habilitar Autenticação de Dois Fatores",
                    ],
                ],
            ],
        ],
    ],

    'emails' => [
        'backup-codes' => [
            'greeting'            => "Você habilitou com sucesso a autenticação de dois fatores para sua conta de administrador.",
            'description'         => "Para sua segurança, geramos códigos de backup que você pode usar caso perca o acesso ao seu aplicativo autenticador. Cada código só pode ser usado uma vez.",
            'codes-title'         => "Seus Códigos de Backup",
            'codes-subtitle'      => "Guarde estes códigos em um local seguro — cada código só pode ser usado uma vez.",
            'warning-title'       => "Aviso Importante de Segurança",
            'warning-description' => "Mantenha estes códigos seguros e não os compartilhe com ninguém. Armazene-os offline em um local seguro.",
        ],
    ],

    'messages' => [
        'enabled_success'  => "Autenticação de dois fatores habilitada com sucesso.",
        'invalid_code'     => "Código de verificação inválido.",
        'disabled_success' => "Autenticação de dois fatores foi desabilitada.",
        'verified_success' => "Autenticação de dois fatores verificada com sucesso.",
        'email_failed'     => "Falha ao enviar códigos de backup",
    ],

    'setup' => [
        'title'            => "Habilitar Autenticação de Dois Fatores",
        'scan_qr'          => "Escaneie este código QR no seu aplicativo Google Authenticator e, em seguida, insira o código de 6 dígitos abaixo.",
        'code_label'       => "Código de Verificação",
        'code_placeholder' => "Digite o código de 6 dígitos",
        'back'             => "Voltar",
        'verify_enable'    => "Verificar & Habilitar",
    ],

    'verify' => [
        'title'                 => "Verificar Autenticação de Dois Fatores",
        'enter_code'            => "Digite o código de 6 dígitos do seu aplicativo autenticador para continuar.",
        'code_label'            => "Código de Verificação",
        'code_placeholder'      => "Digite o código de 6 dígitos",
        'back'                  => "Voltar",
        'verify_code'           => "Verificar Código",
        'disabled_message'      => "A verificação da autenticação de dois fatores está atualmente desabilitada pelo administrador.",
    ],
];
