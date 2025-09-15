<?php

return [
    'configuration' => [
        'index' => [
            'general' => [
                'two_factor_auth' => [
                    'title'    => 'Authentification à deux facteurs',
                    'info'     => "Gérez les paramètres d'authentification à deux facteurs pour les utilisateurs administrateurs.",

                    'settings' => [
                        'title'   => 'Paramètres',
                        'info'    => "Gérez l'authentification à deux facteurs pour les utilisateurs administrateurs.",
                        'enabled' => "Activer l'authentification à deux facteurs",
                    ],
                ],
            ],
        ],
    ],

    'emails' => [
        'backup-codes' => [
            'greeting'            => "Vous avez activé avec succès l'authentification à deux facteurs pour votre compte administrateur.",
            'description'         => "Pour votre sécurité, nous avons généré des codes de secours que vous pouvez utiliser si vous perdez l'accès à votre application d'authentification. Chaque code ne peut être utilisé qu'une seule fois.",
            'codes-title'         => 'Vos codes de secours',
            'codes-subtitle'      => "Conservez ces codes dans un endroit sûr - chacun ne peut être utilisé qu'une seule fois.",
            'warning-title'       => 'Avis de sécurité important',
            'warning-description' => 'Gardez ces codes en sécurité et ne les partagez avec personne. Stockez-les hors ligne dans un endroit sûr.',
        ],
    ],

    'messages' => [
        'enabled_success'  => "L'authentification à deux facteurs a été activée avec succès.",
        'invalid_code'     => 'Code de vérification invalide.',
        'disabled_success' => "L'authentification à deux facteurs a été désactivée.",
        'verified_success' => "L'authentification à deux facteurs a été vérifiée avec succès.",
        'email_failed'     => "Échec de l'envoi des codes de secours",
    ],

    'setup' => [
        'title'            => "Activer l'authentification à deux facteurs",
        'scan_qr'          => 'Scannez ce code QR dans votre application Google Authenticator, puis entrez le code à 6 chiffres ci-dessous.',
        'code_label'       => 'Code de vérification',
        'code_placeholder' => 'Entrez le code à 6 chiffres',
        'back'             => 'Retour',
        'verify_enable'    => 'Vérifier et activer',
    ],

    'verify' => [
        'title'                 => "Vérifier l'authentification à deux facteurs",
        'enter_code'            => "Entrez le code à 6 chiffres de votre application d'authentification pour continuer.",
        'code_label'            => 'Code de vérification',
        'code_placeholder'      => 'Entrez le code à 6 chiffres',
        'back'                  => 'Retour',
        'verify_code'           => 'Vérifier le code',
        'disabled_message'      => "La vérification de l'authentification à deux facteurs est actuellement désactivée par l'administrateur.",
    ],
];
