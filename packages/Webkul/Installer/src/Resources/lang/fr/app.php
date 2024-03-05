<?php

return [
    'seeders'   => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'Défaut',
            ],

            'attribute-groups'   => [
                'description'       => 'Description',
                'general'           => 'Général',
                'inventories'       => 'Inventaires',
                'meta-description'  => 'Méta-description',
                'price'             => 'Prix',
                'settings'          => 'Paramètres',
                'shipping'          => 'Expédition',
            ],

            'attributes'         => [
                'brand'                => 'Marque',
                'color'                => 'Couleur',
                'cost'                 => 'Coût',
                'description'          => 'Description',
                'featured'             => 'En vedette',
                'guest-checkout'       => 'Commande en tant qu\'invité',
                'height'               => 'Hauteur',
                'length'               => 'Longueur',
                'manage-stock'         => 'Gestion des stocks',
                'meta-description'     => 'Méta-description',
                'meta-keywords'        => 'Mots-clés méta',
                'meta-title'           => 'Méta-titre',
                'name'                 => 'Nom',
                'new'                  => 'Nouveau',
                'price'                => 'Prix',
                'product-number'       => 'Numéro de produit',
                'short-description'    => 'Brève description',
                'size'                 => 'Taille',
                'sku'                  => 'SKU',
                'special-price-from'   => 'Prix spécial (à partir de)',
                'special-price-to'     => 'Prix spécial (jusqu\'à)',
                'special-price'        => 'Prix spécial',
                'status'               => 'Statut',
                'tax-category'         => 'Catégorie de taxe',
                'url-key'              => 'Clé d\'URL',
                'visible-individually' => 'Visible individuellement',
                'weight'               => 'Poids',
                'width'                => 'Largeur',
            ],

            'attribute-options'  => [
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

        'category'  => [
            'categories' => [
                'description' => 'Description de la catégorie racine',
                'name'        => 'Racine',
            ],
        ],

        'cms'       => [
            'pages' => [
                'about-us'         => [
                    'content' => 'Contenu de la page À propos de nous',
                    'title'   => 'À propos de nous',
                ],

                'contact-us'       => [
                    'content' => 'Contenu de la page Contactez-nous',
                    'title'   => 'Contactez-nous',
                ],

                'customer-service' => [
                    'content' => 'Contenu de la page Service client',
                    'title'   => 'Service client',
                ],

                'payment-policy'   => [
                    'content' => 'Contenu de la page Politique de paiement',
                    'title'   => 'Politique de paiement',
                ],

                'privacy-policy'   => [
                    'content' => 'Contenu de la page Politique de confidentialité',
                    'title'   => 'Politique de confidentialité',
                ],

                'refund-policy'    => [
                    'content' => 'Contenu de la page Politique de remboursement',
                    'title'   => 'Politique de remboursement',
                ],

                'return-policy'    => [
                    'content' => 'Contenu de la page Politique de retour',
                    'title'   => 'Politique de retour',
                ],

                'shipping-policy'  => [
                    'content' => 'Contenu de la page Politique d\'expédition',
                    'title'   => 'Politique d\'expédition',
                ],

                'terms-conditions' => [
                    'content' => 'Contenu de la page Termes et conditions',
                    'title'   => 'Termes et conditions',
                ],

                'terms-of-use'     => [
                    'content' => 'Contenu de la page Conditions d\'utilisation',
                    'title'   => 'Conditions d\'utilisation',
                ],

                'whats-new'        => [
                    'content' => 'Contenu de la page Quoi de neuf',
                    'title'   => 'Quoi de neuf',
                ],
            ],
        ],

        'core'      => [
            'channels'   => [
                'meta-description' => 'Méta-description de la boutique de démonstration',
                'meta-keywords'    => 'Mots-clés méta de la boutique de démonstration',
                'meta-title'       => 'Boutique de démonstration',
                'name'             => 'Défaut',
            ],

            'currencies' => [
                'AED' => 'Dirham',
                'AFN' => 'Shekel israélien',
                'CNY' => 'Yuan chinois',
                'EUR' => 'EURO',
                'GBP' => 'Livre sterling',
                'INR' => 'Roupie indienne',
                'IRR' => 'Rial iranien',
                'JPY' => 'Yen japonais',
                'RUB' => 'Rouble russe',
                'SAR' => 'Riyal saoudien',
                'TRY' => 'Lire turque',
                'UAH' => 'Hryvnia ukrainienne',
                'USD' => 'Dollar américain',
            ],

            'locales'    => [
                'ar'    => 'Arabe',
                'bn'    => 'Bengali',
                'de'    => 'Allemand',
                'en'    => 'Anglais',
                'es'    => 'Espagnol',
                'fa'    => 'Persan',
                'fr'    => 'Français',
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

        'customer'  => [
            'customer-groups' => [
                'general'   => 'Général',
                'guest'     => 'Invité',
                'wholesale' => 'En gros',
            ],
        ],

        'inventory' => [
            'inventory-sources' => [
                'name' => 'Par défaut',
            ],
        ],

        'shop'      => [
            'theme-customizations' => [
                'all-products'           => [
                    'name'    => 'Tous les produits',

                    'options' => [
                        'title' => 'Tous les produits',
                    ],
                ],

                'bold-collections'       => [
                    'content' => [
                        'btn-title'   => 'Voir tout',
                        'description' => 'Découvrez nos nouvelles collections audacieuses ! Élevez votre style avec des designs audacieux et des déclarations vibrantes. Explorez des motifs saisissants et des couleurs audacieuses qui redéfinissent votre garde-robe. Préparez-vous à embrasser l\'extraordinaire !',
                        'title'       => 'Préparez-vous pour nos nouvelles collections audacieuses !',
                    ],

                    'name'    => 'Collections audacieuses',
                ],

                'categories-collections' => [
                    'name' => 'Collections de catégories',
                ],

                'featured-collections'   => [
                    'name'    => 'Collections en vedette',

                    'options' => [
                        'title' => 'Produits en vedette',
                    ],
                ],

                'footer-links'           => [
                    'name'    => 'Liens du pied de page',

                    'options' => [
                        'about-us'         => 'À propos de nous',
                        'contact-us'       => 'Contactez-nous',
                        'customer-service' => 'Service client',
                        'payment-policy'   => 'Politique de paiement',
                        'privacy-policy'   => 'Politique de confidentialité',
                        'refund-policy'    => 'Politique de remboursement',
                        'return-policy'    => 'Politique de retour',
                        'shipping-policy'  => 'Politique d\'expédition',
                        'terms-conditions' => 'Termes et conditions',
                        'terms-of-use'     => 'Conditions d\'utilisation',
                        'whats-new'        => 'Nouveautés',
                    ],
                ],

                'game-container'         => [
                    'content' => [
                        'sub-title-1' => 'Nos collections',
                        'sub-title-2' => 'Nos collections',
                        'title'       => 'Le jeu avec nos nouvelles additions !',
                    ],

                    'name'    => 'Conteneur de jeu',
                ],

                'image-carousel'         => [
                    'name'  => 'Carrousel d\'images',

                    'sliders' => [
                        'title' => 'Préparez-vous pour la nouvelle collection',
                    ],
                ],

                'new-products'           => [
                    'name'    => 'Nouveaux produits',

                    'options' => [
                        'title' => 'Nouveaux produits',
                    ],
                ],

                'offer-information'      => [
                    'content' => [
                        'title' => 'Jusqu\'à 40% de réduction sur votre 1ère commande, ACHETEZ MAINTENANT',
                    ],

                    'name'    => 'Informations sur les offres',
                ],

                'services-content'       => [
                    'description' => [
                        'emi-available-info'   => 'EMI sans frais disponible sur toutes les principales cartes de crédit',
                        'free-shipping-info'   => 'Profitez de la livraison gratuite sur toutes les commandes',
                        'product-replace-info' => 'Remplacement facile de produit disponible !',
                        'time-support-info'    => 'Support dédié 24/7 via chat et e-mail',
                    ],

                    'name'        => 'Contenu des services',

                    'title'       => [
                        'emi-available'   => 'EMI disponible',
                        'free-shipping'   => 'Livraison gratuite',
                        'product-replace' => 'Remplacement de produit',
                        'time-support'    => 'Support 24/7',
                    ],
                ],

                'top-collections'        => [
                    'content' => [
                        'sub-title-1' => 'Nos collections',
                        'sub-title-2' => 'Nos collections',
                        'sub-title-3' => 'Nos collections',
                        'sub-title-4' => 'Nos collections',
                        'sub-title-5' => 'Nos collections',
                        'sub-title-6' => 'Nos collections',
                        'title'       => 'Le jeu avec nos nouvelles additions !',
                    ],

                    'name'    => 'Collections phares',
                ],
            ],
        ],

        'user'      => [
            'roles' => [
                'description' => 'Les utilisateurs de ce rôle auront tous les accès',
                'name'        => 'Administrateur',
            ],

            'users' => [
                'name' => 'Exemple',
            ],
        ],
    ],

    'installer' => [
        'index' => [
            'create-administrator'      => [
                'admin'            => 'Administrateur',
                'bagisto'          => 'Bagisto',
                'confirm-password' => 'Confirmez le mot de passe',
                'email-address'    => 'admin@example.com',
                'email'            => 'E-mail',
                'password'         => 'Mot de passe',
                'title'            => 'Créer un administrateur',
            ],

            'environment-configuration' => [
                'allowed-currencies'  => 'Devises autorisées',
                'allowed-locales'     => 'Langues autorisées',
                'application-name'    => 'Nom de l’application',
                'bagisto'             => 'Bagisto',
                'chinese-yuan'        => 'Yuan chinois (CNY)',
                'database-connection' => 'Connexion à la base de données',
                'database-hostname'   => 'Nom de l’hôte de la base de données',
                'database-name'       => 'Nom de la base de données',
                'database-password'   => 'Mot de passe de la base de données',
                'database-port'       => 'Port de la base de données',
                'database-prefix'     => 'Préfixe de la base de données',
                'database-username'   => 'Nom d’utilisateur de la base de données',
                'default-currency'    => 'Devise par défaut',
                'default-locale'      => 'Langue par défaut',
                'default-timezone'    => 'Fuseau horaire par défaut',
                'default-url-link'    => 'https://localhost',
                'default-url'         => 'URL par défaut',
                'dirham'              => 'Dirham (AED)',
                'euro'                => 'Euro (EUR)',
                'iranian'             => 'Rial iranien (IRR)',
                'israeli'             => 'Shekel israélien (AFN)',
                'japanese-yen'        => 'Yen japonais (JPY)',
                'mysql'               => 'Mysql',
                'pgsql'               => 'pgSQL',
                'pound'               => 'Livre sterling (GBP)',
                'rupee'               => 'Roupie indienne (INR)',
                'russian-ruble'       => 'Rouble russe (RUB)',
                'saudi'               => 'Riyal saoudien (SAR)',
                'select-timezone'     => 'Sélectionnez le fuseau horaire',
                'sqlsrv'              => 'SQLSRV',
                'title'               => 'Configuration de l’environnement',
                'turkish-lira'        => 'Lire turque (TRY)',
                'ukrainian-hryvnia'   => 'Hryvnia ukrainienne (UAH)',
                'usd'                 => 'Dollar américain (USD)',
                'warning-message'     => 'Attention ! Les paramètres de vos langues système par défaut ainsi que la devise par défaut sont permanents et ne peuvent plus être modifiés.',
            ],

            'installation-processing'   => [
                'bagisto-info'     => 'Création des tables de la base de données, cela peut prendre quelques instants',
                'bagisto'          => 'Installation de Bagisto',
                'title'            => 'Installation',
            ],

            'installation-completed'    => [
                'admin-panel'                => 'Panneau d\'administration',
                'bagisto-forums'             => 'Forum Bagisto',
                'customer-panel'             => 'Panneau du client',
                'explore-bagisto-extensions' => 'Explorer les extensions Bagisto',
                'title-info'                 => 'Bagisto est installé avec succès sur votre système.',
                'title'                      => 'Installation terminée',
            ],

            'ready-for-installation'    => [
                'create-database-table'   => 'Créer la table de la base de données',
                'install-info-button'     => 'Cliquez sur le bouton ci-dessous pour',
                'install-info'            => 'Bagisto pour l’installation',
                'install'                 => 'Installation',
                'populate-database-table' => 'Remplir les tables de la base de données',
                'start-installation'      => 'Démarrer l’installation',
                'title'                   => 'Prêt pour l’installation',
            ],

            'start'                     => [
                'locale'        => 'Locale',
                'main'          => 'Début',
                'select-locale' => 'Sélectionner la langue',
                'title'         => 'Votre installation de Bagisto',
                'welcome-title' => 'Bienvenue sur Bagisto 2.0.',
            ],

            'server-requirements'       => [
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
                'pcre'        => 'PCRE',
                'pdo'         => 'PDO',
                'php-version' => '8.1 ou supérieure',
                'php'         => 'PHP',
                'session'     => 'Session',
                'title'       => 'Configuration requise du serveur',
                'tokenizer'   => 'Tokenizer',
                'xml'         => 'XML',
            ],

            'arabic'                    => 'Arabe',
            'back'                      => 'Retour',
            'bagisto-info'              => 'Un projet communautaire par',
            'bagisto-logo'              => 'Logo de Bagisto',
            'bagisto'                   => 'Bagisto',
            'bengali'                   => 'Bengali',
            'chinese'                   => 'Chinois',
            'continue'                  => 'Continuer',
            'dutch'                     => 'Néerlandais',
            'english'                   => 'Anglais',
            'french'                    => 'Français',
            'german'                    => 'Allemand',
            'hebrew'                    => 'Hébreu',
            'hindi'                     => 'Hindi',
            'installation-description'  => 'L\'installation de Bagisto implique généralement plusieurs étapes. Voici un  aperçu général du processus d\'installation pour Bagisto :',
            'installation-info'         => 'Nous sommes heureux de vous voir ici !',
            'installation-title'        => 'Bienvenue dans l\'installation',
            'italian'                   => 'Italien',
            'japanese'                  => 'Japonais',
            'persian'                   => 'Persan',
            'polish'                    => 'Polonais',
            'portuguese'                => 'Portugais brésilien',
            'russian'                   => 'Russe',
            'save-configuration'        => 'Enregistrer la configuration',
            'sinhala'                   => 'Sinhala',
            'skip'                      => 'Passer',
            'spanish'                   => 'Espagnol',
            'title'                     => 'Installeur de Bagisto',
            'turkish'                   => 'Turc',
            'ukrainian'                 => 'Ukrainien',
            'webkul'                    => 'Webkul',
        ],
    ],
];
