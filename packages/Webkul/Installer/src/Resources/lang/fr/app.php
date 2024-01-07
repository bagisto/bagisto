<?php

return [
    'seeders' => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'Défaut',
            ],

            'attribute-groups' => [
                'description'       => 'Description',
                'general'           => 'Général',
                'inventories'       => 'Inventaires',
                'meta-description'  => 'Méta-description',
                'price'             => 'Prix',
                'shipping'          => 'Expédition',
                'settings'          => 'Paramètres',
            ],

            'attributes' => [
                'brand'                => 'Marque',
                'color'                => 'Couleur',
                'cost'                 => 'Coût',
                'description'          => 'Description',
                'featured'             => 'En vedette',
                'guest-checkout'       => 'Commande en tant qu\'invité',
                'height'               => 'Hauteur',
                'length'               => 'Longueur',
                'meta-title'           => 'Méta-titre',
                'meta-keywords'        => 'Mots-clés méta',
                'meta-description'     => 'Méta-description',
                'manage-stock'         => 'Gestion des stocks',
                'new'                  => 'Nouveau',
                'name'                 => 'Nom',
                'product-number'       => 'Numéro de produit',
                'price'                => 'Prix',
                'sku'                  => 'SKU',
                'status'               => 'Statut',
                'short-description'    => 'Brève description',
                'special-price'        => 'Prix spécial',
                'special-price-from'   => 'Prix spécial (à partir de)',
                'special-price-to'     => 'Prix spécial (jusqu\'à)',
                'size'                 => 'Taille',
                'tax-category'         => 'Catégorie de taxe',
                'url-key'              => 'Clé d\'URL',
                'visible-individually' => 'Visible individuellement',
                'width'                => 'Largeur',
                'weight'               => 'Poids',
            ],

            'attribute-options' => [
                'black'  => 'Noir',
                'green'  => 'Vert',
                'l'      => 'L',
                'm'      => 'M',
                'red'    => 'Rouge',
                's'      => 'S',
                'white'  => 'Blanc',
                'xl'     => 'XL',
                'yellow' => 'Jaune',
            ],
        ],

        'category' => [
            'categories' => [
                'description' => 'Description de la catégorie racine',
                'name'        => 'Racine',
            ],
        ],

        'cms' => [
            'pages' => [
                'about-us' => [
                    'content' => 'Contenu de la page À propos de nous',
                    'title'   => 'À propos de nous',
                ],

                'refund-policy' => [
                    'content' => 'Contenu de la page Politique de remboursement',
                    'title'   => 'Politique de remboursement',
                ],

                'return-policy' => [
                    'content' => 'Contenu de la page Politique de retour',
                    'title'   => 'Politique de retour',
                ],

                'terms-conditions' => [
                    'content' => 'Contenu de la page Termes et conditions',
                    'title'   => 'Termes et conditions',
                ],

                'terms-of-use' => [
                    'content' => 'Contenu de la page Conditions d\'utilisation',
                    'title'   => 'Conditions d\'utilisation',
                ],

                'contact-us' => [
                    'content' => 'Contenu de la page Contactez-nous',
                    'title'   => 'Contactez-nous',
                ],

                'customer-service' => [
                    'content' => 'Contenu de la page Service client',
                    'title'   => 'Service client',
                ],

                'whats-new' => [
                    'content' => 'Contenu de la page Quoi de neuf',
                    'title'   => 'Quoi de neuf',
                ],

                'payment-policy' => [
                    'content' => 'Contenu de la page Politique de paiement',
                    'title'   => 'Politique de paiement',
                ],

                'shipping-policy' => [
                    'content' => 'Contenu de la page Politique d\'expédition',
                    'title'   => 'Politique d\'expédition',
                ],

                'privacy-policy' => [
                    'content' => 'Contenu de la page Politique de confidentialité',
                    'title'   => 'Politique de confidentialité',
                ],
            ],
        ],

        'core' => [
            'channels' => [
                'meta-title'       => 'Boutique de démonstration',
                'meta-keywords'    => 'Mots-clés méta de la boutique de démonstration',
                'meta-description' => 'Méta-description de la boutique de démonstration',
                'name'             => 'Défaut',
            ],

            'currencies' => [
                'CNY' => 'Yuan chinois',
                'AED' => 'Dirham',
                'EUR' => 'EURO',
                'INR' => 'Roupie indienne',
                'IRR' => 'Rial iranien',
                'AFN' => 'Shekel israélien',
                'JPY' => 'Yen japonais',
                'GBP' => 'Livre sterling',
                'RUB' => 'Rouble russe',
                'SAR' => 'Riyal saoudien',
                'TRY' => 'Lire turque',
                'USD' => 'Dollar américain',
                'UAH' => 'Hryvnia ukrainienne',
            ],

            'locales' => [
                'ar'    => 'Arabe',
                'bn'    => 'Bengali',
                'de'    => 'Allemand',
                'es'    => 'Espagnol',
                'en'    => 'Anglais',
                'fr'    => 'Français',
                'fa'    => 'Persan',
                'he'    => 'Hébreu',
                'hi_IN' => 'Hindi',
                'it'    => 'Italien',
                'ja'    => 'Japonais',
                'nl'    => 'Néerlandais',
                'pl'    => 'Polonais',
                'pt_BR' => 'Portugais brésilien',
                'ru'    => 'Russe',
                'sin'   => 'Sinhala',
                'tr'    => 'Turc',
                'uk'    => 'Ukrainien',
                'zh_CN' => 'Chinois',
            ],
        ],

        'customer' => [
            'customer-groups' => [
                'guest'     => 'Invité',
                'general'   => 'Général',
                'wholesale' => 'En gros',
            ],
        ],

        'inventory' => [
            'inventory-sources' => [
                'name' => 'Par défaut',
            ],
        ],

        'shop' => [
            'theme-customizations' => [
                'image-carousel' => [
                    'name'  => 'Carrousel d\'images',

                    'sliders' => [
                        'title' => 'Préparez-vous pour la nouvelle collection',
                    ],
                ],

                'offer-information' => [
                    'name' => 'Informations sur les offres',

                    'content' => [
                        'title' => 'Jusqu\'à 40% de réduction sur votre 1ère commande, ACHETEZ MAINTENANT',
                    ],
                ],

                'categories-collections' => [
                    'name' => 'Collections de catégories',
                ],

                'new-products' => [
                    'name' => 'Nouveaux produits',

                    'options' => [
                        'title' => 'Nouveaux produits',
                    ],
                ],

                'top-collections' => [
                    'name' => 'Collections phares',

                    'content' => [
                        'sub-title-1' => 'Nos collections',
                        'sub-title-2' => 'Nos collections',
                        'sub-title-3' => 'Nos collections',
                        'sub-title-4' => 'Nos collections',
                        'sub-title-5' => 'Nos collections',
                        'sub-title-6' => 'Nos collections',
                        'title'       => 'Le jeu avec nos nouvelles additions !',
                    ],
                ],

                'bold-collections' => [
                    'name' => 'Collections audacieuses',

                    'content' => [
                        'btn-title'   => 'Voir tout',
                        'description' => 'Découvrez nos nouvelles collections audacieuses ! Élevez votre style avec des designs audacieux et des déclarations vibrantes. Explorez des motifs saisissants et des couleurs audacieuses qui redéfinissent votre garde-robe. Préparez-vous à embrasser l\'extraordinaire !',
                        'title'       => 'Préparez-vous pour nos nouvelles collections audacieuses !',
                    ],
                ],

                'featured-collections' => [
                    'name' => 'Collections en vedette',

                    'options' => [
                        'title' => 'Produits en vedette',
                    ],
                ],

                'game-container' => [
                    'name' => 'Conteneur de jeu',

                    'content' => [
                        'sub-title-1' => 'Nos collections',
                        'sub-title-2' => 'Nos collections',
                        'title'       => 'Le jeu avec nos nouvelles additions !',
                    ],
                ],

                'all-products' => [
                    'name' => 'Tous les produits',

                    'options' => [
                        'title' => 'Tous les produits',
                    ],
                ],

                'footer-links' => [
                    'name' => 'Liens du pied de page',

                    'options' => [
                        'about-us'         => 'À propos de nous',
                        'contact-us'       => 'Contactez-nous',
                        'customer-service' => 'Service client',
                        'privacy-policy'   => 'Politique de confidentialité',
                        'payment-policy'   => 'Politique de paiement',
                        'return-policy'    => 'Politique de retour',
                        'refund-policy'    => 'Politique de remboursement',
                        'shipping-policy'  => 'Politique d\'expédition',
                        'terms-of-use'     => 'Conditions d\'utilisation',
                        'terms-conditions' => 'Termes et conditions',
                        'whats-new'        => 'Nouveautés',
                    ],
                ],

                'services-content' => [
                    'name'  => 'Contenu des services',

                    'title' => [
                        'free-shipping'   => 'Livraison gratuite',
                        'product-replace' => 'Remplacement de produit',
                        'emi-available'   => 'EMI disponible',
                        'time-support'    => 'Support 24/7',
                    ],

                    'description' => [
                        'free-shipping-info'   => 'Profitez de la livraison gratuite sur toutes les commandes',
                        'product-replace-info' => 'Remplacement facile de produit disponible !',
                        'emi-available-info'   => 'EMI sans frais disponible sur toutes les principales cartes de crédit',
                        'time-support-info'    => 'Support dédié 24/7 via chat et e-mail',
                    ],
                ],
            ],
        ],

        'user' => [
            'users' => [
                'name' => 'Exemple',
            ],

            'roles' => [
                'description' => 'Les utilisateurs de ce rôle auront tous les accès',
                'name'        => 'Administrateur',
            ],
        ],
    ],

    'installer' => [
        'index' => [
            'start' => [
                'locale'        => 'Locale',
                'main'          => 'Début',
                'select-locale' => 'Sélectionner la langue',
                'title'         => 'Votre installation de Bagisto',
                'welcome-title' => 'Bienvenue sur Bagisto 2.0.',
            ],

            'server-requirements' => [
                'calendar'    => 'Calendrier',
                'ctype'       => 'cType',
                'curl'        => 'cURL',
                'dom'         => 'DOM',
                'fileinfo'    => 'Fileinfo',
                'filter'      => 'Filtre',
                'gd'          => 'GD',
                'hash'        => 'Hachage',
                'intl'        => 'Intl',
                'json'        => 'JSON',
                'mbstring'    => 'mbstring',
                'openssl'     => 'OpenSSL',
                'php'         => 'PHP',
                'php-version' => '8.1 ou supérieure',
                'pcre'        => 'PCRE',
                'pdo'         => 'PDO',
                'session'     => 'Session',
                'title'       => 'Configuration requise du serveur',
                'tokenizer'   => 'Tokenizer',
                'xml'         => 'XML',
            ],

            'environment-configuration' => [
                'allowed-locales'     => 'Langues autorisées',
                'allowed-currencies'  => 'Devises autorisées',
                'application-name'    => 'Nom de l’application',
                'bagisto'             => 'Bagisto',
                'chinese-yuan'        => 'Yuan chinois (CNY)',
                'dirham'              => 'Dirham (AED)',
                'default-url'         => 'URL par défaut',
                'default-url-link'    => 'https://localhost',
                'default-currency'    => 'Devise par défaut',
                'default-timezone'    => 'Fuseau horaire par défaut',
                'default-locale'      => 'Langue par défaut',
                'database-connection' => 'Connexion à la base de données',
                'database-hostname'   => 'Nom de l’hôte de la base de données',
                'database-port'       => 'Port de la base de données',
                'database-name'       => 'Nom de la base de données',
                'database-username'   => 'Nom d’utilisateur de la base de données',
                'database-prefix'     => 'Préfixe de la base de données',
                'database-password'   => 'Mot de passe de la base de données',
                'euro'                => 'Euro (EUR)',
                'iranian'             => 'Rial iranien (IRR)',
                'israeli'             => 'Shekel israélien (AFN)',
                'japanese-yen'        => 'Yen japonais (JPY)',
                'mysql'               => 'Mysql',
                'pgsql'               => 'pgSQL',
                'pound'               => 'Livre sterling (GBP)',
                'rupee'               => 'Roupie indienne (INR)',
                'russian-ruble'       => 'Rouble russe (RUB)',
                'sqlsrv'              => 'SQLSRV',
                'saudi'               => 'Riyal saoudien (SAR)',
                'title'               => 'Configuration de l’environnement',
                'turkish-lira'        => 'Lire turque (TRY)',
                'usd'                 => 'Dollar américain (USD)',
                'ukrainian-hryvnia'   => 'Hryvnia ukrainienne (UAH)',
                'warning-message'     => 'Attention ! Les paramètres de vos langues système par défaut ainsi que la devise par défaut sont permanents et ne peuvent plus être modifiés.',
            ],

            'ready-for-installation' => [
                'create-database-table'   => 'Créer la table de la base de données',
                'install'                 => 'Installation',
                'install-info'            => 'Bagisto pour l’installation',
                'install-info-button'     => 'Cliquez sur le bouton ci-dessous pour',
                'populate-database-table' => 'Remplir les tables de la base de données',
                'start-installation'      => 'Démarrer l’installation',
                'title'                   => 'Prêt pour l’installation',
            ],

            'installation-processing' => [
                'bagisto'          => 'Installation de Bagisto',
                'bagisto-info'     => 'Création des tables de la base de données, cela peut prendre quelques instants',
                'title'            => 'Installation',
            ],

            'create-administrator' => [
                'admin'            => 'Administrateur',
                'bagisto'          => 'Bagisto',
                'confirm-password' => 'Confirmez le mot de passe',
                'email'            => 'E-mail',
                'email-address'    => 'admin@example.com',
                'password'         => 'Mot de passe',
                'title'            => 'Créer un administrateur',
            ],

            'installation-completed' => [
                'admin-panel'                => 'Panneau d\'administration',
                'bagisto-forums'             => 'Forum Bagisto',
                'customer-panel'             => 'Panneau du client',
                'explore-bagisto-extensions' => 'Explorer les extensions Bagisto',
                'title'                      => 'Installation terminée',
                'title-info'                 => 'Bagisto est installé avec succès sur votre système.',
            ],

            'arabic'                   => 'Arabe',
            'bengali'                  => 'Bengali',
            'bagisto-logo'             => 'Logo de Bagisto',
            'back'                     => 'Retour',
            'bagisto-info'             => 'Un projet communautaire par',
            'bagisto'                  => 'Bagisto',
            'chinese'                  => 'Chinois',
            'continue'                 => 'Continuer',
            'dutch'                    => 'Néerlandais',
            'english'                  => 'Anglais',
            'french'                   => 'Français',
            'german'                   => 'Allemand',
            'hebrew'                   => 'Hébreu',
            'hindi'                    => 'Hindi',
            'installation-title'       => 'Bienvenue dans l\'installation',
            'installation-info'        => 'Nous sommes heureux de vous voir ici !',
            'installation-description' => 'L\'installation de Bagisto implique généralement plusieurs étapes. Voici un aperçu général du processus d\'installation pour Bagisto :',
            'italian'                  => 'Italien',
            'japanese'                 => 'Japonais',
            'persian'                  => 'Persan',
            'polish'                   => 'Polonais',
            'portuguese'               => 'Portugais brésilien',
            'russian'                  => 'Russe',
            'spanish'                  => 'Espagnol',
            'sinhala'                  => 'Sinhala',
            'skip'                     => 'Passer',
            'save-configuration'       => 'Enregistrer la configuration',
            'title'                    => 'Installeur de Bagisto',
            'turkish'                  => 'Turc',
            'ukrainian'                => 'Ukrainien',
            'webkul'                   => 'Webkul',
        ],
    ],
];
