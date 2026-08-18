<?php

return [
    'importers' => [
        'customers' => [
            'title' => 'Clients',

            'validation' => [
                'errors' => [
                    'duplicate-email' => 'L\'email: \'%s\' est trouvé plusieurs fois dans le fichier d\'importation.',
                    'duplicate-phone' => 'Le téléphone: \'%s\' est trouvé plusieurs fois dans le fichier d\'importation.',
                    'email-not-found' => 'L\'email: \'%s\' n\'a pas été trouvé dans le système.',
                    'invalid-customer-group' => 'Le groupe de clients est invalide ou non pris en charge',
                ],
            ],
        ],

        'products' => [
            'title' => 'Produits',

            'validation' => [
                'errors' => [
                    'duplicate-url-key' => 'La clé d\'URL: \'%s\' a déjà été générée pour un élément avec le SKU: \'%s\'.',
                    'image-not-file' => 'Image : \'%s\' est une adresse web, alors que cet import est configuré pour prendre les images dans des fichiers. Choisissez « Liens d\'image dans le fichier » comme source, ou remplacez la valeur par un nom de fichier.',
                    'image-not-found' => 'Image : \'%s\' est introuvable à l\'emplacement où cet import attend ses images.',
                    'image-not-url' => 'Image : \'%s\' n\'est pas une adresse web, alors que cet import est configuré pour récupérer les images depuis des liens du fichier. Choisissez une autre source d\'images ou remplacez la valeur par une adresse https:// complète.',
                    'invalid-attribute-family' => 'Valeur non valide pour la colonne de famille d\'attributs (la famille d\'attributs n\'existe pas ?)',
                    'invalid-type' => 'Le type de produit est invalide ou non pris en charge',
                    'sku-not-found' => 'Produit avec le SKU spécifié introuvable',
                    'super-attribute-not-found' => 'Super attribut avec le code: \'%s\' non trouvé ou n\'appartient pas à la famille d\'attributs: \'%s\'',
                ],
            ],
        ],

        'tax-rates' => [
            'title' => 'Taux de taxe',

            'validation' => [
                'errors' => [
                    'duplicate-identifier' => 'L\'identifiant : \'%s\' a été trouvé plusieurs fois dans le fichier d\'importation.',
                    'identifier-not-found' => 'L\'identifiant : \'%s\' n\'a pas été trouvé dans le système.',
                ],
            ],
        ],
    ],

    'validation' => [
        'errors' => [
            'column-empty-headers' => 'Le nombre de colonnes "%s" a des en-têtes vides.',
            'column-name-invalid' => 'Noms de colonnes non valides: "%s".',
            'column-not-found' => 'Colonnes requises non trouvées: %s.',
            'column-numbers' => 'Le nombre de colonnes ne correspond pas au nombre de lignes dans l\'en-tête.',
            'invalid-attribute' => 'L\'en-tête contient des attributs non valides: "%s".',
            'more-issues' => 'et :count autre(s) problème(s) — téléchargez le rapport complet pour la liste intégrale.',
            'more-rows' => '(+:count lignes supplémentaires)',
            'system' => 'Une erreur système inattendue s\'est produite.',
            'wrong-quotes' => 'Des guillemets courbes ont été utilisés au lieu de guillemets droits.',
        ],
    ],
];
