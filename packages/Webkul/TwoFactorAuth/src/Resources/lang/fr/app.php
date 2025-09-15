<?php

return [
    'configuration' => [
        'index' => [
            'general' => [
                'two_factor_auth' => [
                    'title'    => "Authentification à deux facteurs",
                    'info'     => "Gérez les paramètres de l'authentification à deux facteurs pour les utilisateurs administrateurs.",

                    'settings' => [
                        'title'   => "Paramètres",
                        'info'    => "Gérez l'authentification à deux facteurs pour les utilisateurs administrateurs.",
                        'enabled' => "Activer l'authentification à deux facteurs",
                    ],
                ],
            ],
        ],
    ],

    'messages' => [
        'enabled_success'  => "L'authentification à deux facteurs a été activée avec succès.",
        'invalid_code'     => "Code de vérification invalide.",
        'disabled_success' => "L'authentification à deux facteurs a été désactivée.",
        'verified_success' => "L'authentification à deux facteurs a été vérifiée avec succès.",
    ],

    'setup' => [
        'title'        => "Activer l'authentification à deux facteurs",
        'scan_qr'      => "Scannez ce code QR dans votre application Google Authenticator, puis saisissez le code à 6 chiffres ci-dessous.",
        'code_label'   => "Code de vérification",
        'code_placeholder' => "Saisissez le code à 6 chiffres",
        'back'         => "Retour",
        'verify_enable'=> "Vérifier et activer",
    ],

    'verify' => [
        'title'                 => "Vérifier l'authentification à deux facteurs",
        'enter_code'            => "Saisissez le code à 6 chiffres de votre application d'authentification pour continuer.",
        'code_label'            => "Code de vérification",
        'code_placeholder'      => "Saisissez le code à 6 chiffres",
        'back'                  => "Retour",
        'verify_code'           => "Vérifier le code",
        'disabled_message'      => "La vérification de l'authentification à deux facteurs est actuellement désactivée par l'administrateur.",
    ],
];
