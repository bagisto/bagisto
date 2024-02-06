<?php

return [
    'importers'  => [
        'products'  => [
            'title'      => 'Produits',

            'validation' => [
                'errors' => [
                    'duplicate-url-key'        => 'La clé d\'URL: \'%s\' a déjà été générée pour un élément avec le SKU: \'%s\'.',
                    'invalid-attribute-family' => 'Valeur non valide pour la colonne de famille d\'attributs (la famille d\'attributs n\'existe pas ?)',
                    'invalid-type'             => 'Le type de produit est invalide ou non pris en charge',
                    'sku-not-found'            => 'Produit avec le SKU spécifié introuvable',
                ],
            ],
        ],

        'customers' => [
            'title'      => 'Clients',

            'validation' => [
                'errors' => [
                    'duplicate-email'        => 'L\'email: \'%s\' est trouvé plusieurs fois dans le fichier d\'importation.',
                    'duplicate-phone'        => 'Le téléphone: \'%s\' est trouvé plusieurs fois dans le fichier d\'importation.',
                    'invalid-customer-group' => 'Le groupe de clients est invalide ou non pris en charge',
                    'email-not-found'        => 'L\'email: \'%s\' n\'a pas été trouvé dans le système.',
                ],
            ],
        ],
    ],

    'validation' => [
        'errors' => [
            'column-empty-headers' => 'Le nombre de colonnes "%s" a des en-têtes vides.',
            'column-name-invalid'  => 'Noms de colonnes non valides: "%s".',
            'column-not-found'     => 'Colonnes requises non trouvées: %s.',
            'column-numbers'       => 'Le nombre de colonnes ne correspond pas au nombre de lignes dans l\'en-tête.',
            'invalid-attribute'    => 'L\'en-tête contient des attributs non valides: "%s".',
            'system'               => 'Une erreur système inattendue s\'est produite.',
            'wrong-quotes'         => 'Des guillemets courbes ont été utilisés au lieu de guillemets droits.',
        ],
    ],
];
