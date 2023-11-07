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
                'ILS' => 'Shekel israélien',
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
                'php-version' => '8.1 ou supérieur',
                'pcre'        => 'pcre',
                'pdo'         => 'pdo',
                'session'     => 'Session',
                'title'       => 'Configuration requise du serveur',
                'tokenizer'   => 'Tokenizer',
                'xml'         => 'XML',
            ],

            'environment-configuration' => [
                'application-name'    => 'Nom de l\'application',
                'arabic'              => 'Arabe',
                'bagisto'             => 'Bagisto',
                'bengali'             => 'Bengali',
                'chinese-yuan'        => 'Yuan chinois (CNY)',
                'chinese'             => 'Chinois',
                'dirham'              => 'Dirham (AED)',
                'default-url'         => 'URL par défaut',
                'default-url-link'    => 'https://localhost',
                'default-currency'    => 'Devise par défaut',
                'default-timezone'    => 'Fuseau horaire par défaut',
                'default-locale'      => 'Paramètres régionaux par défaut',
                'dutch'               => 'Néerlandais',
                'database-connection' => 'Connexion à la base de données',
                'database-hostname'   => 'Nom de l\'hôte de la base de données',
                'database-port'       => 'Port de la base de données',
                'database-name'       => 'Nom de la base de données',
                'database-username'   => 'Nom d\utilisateur de la base de données',
                'database-prefix'     => 'Préfixe de la base de données',
                'database-password'   => 'Mot de passe de la base de données',
                'euro'                => 'Euro (EUR)',
                'english'             => 'Anglais',
                'french'              => 'Français',
                'hebrew'              => 'Hébreu',
                'hindi'               => 'Hindi',
                'iranian'             => 'Rial iranien (IRR)',
                'israeli'             => 'Shekel israélien (ILS)',
                'italian'             => 'Italien',
                'japanese-yen'        => 'Yen japonais (JPY)',
                'japanese'            => 'Japonais',
                'mysql'               => 'Mysql',
                'pgsql'               => 'pgSQL',
                'pound'               => 'Livre sterling (GBP)',
                'persian'             => 'Persan',
                'polish'              => 'Polonais',
                'portuguese'          => 'Portugais brésilien',
                'rupee'               => 'Roupie indienne (INR)',
                'russian-ruble'       => 'Rouble russe (RUB)',
                'russian'             => 'Russe',
                'sqlsrv'              => 'SQLSRV',
                'saudi'               => 'Riyal saoudien (SAR)',
                'spanish'             => 'Espagnol',
                'sinhala'             => 'Cinghalais',
                'title'               => 'Configuration de l\'environnement',
                'turkish-lira'        => 'Livre turque (TRY)',
                'turkish'             => 'Turc',
                'usd'                 => 'Dollar américain (USD)',
                'ukrainian-hryvnia'   => 'Hryvnia ukrainienne (UAH)',
                'ukrainian'           => 'Ukrainien',
            ],

            'ready-for-installation' => [
                'create-database-table'   => 'Créer la table de la base de données',
                'install'                 => 'Installation',
                'install-info'            => 'Bagisto pour l\'installation',
                'install-info-button'     => 'Cliquez sur le bouton ci-dessous pour continuer',
                'populate-database-table' => 'Remplir les tables de la base de données',
                'start-installation'      => 'Démarrer l\'installation',
                'title'                   => 'Prêt pour l\'installation',
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

            'email-configuration' => [
                'encryption'           => 'Chiffrement',
                'enter-username'       => 'Entrez le nom d\'utilisateur',
                'enter-password'       => 'Entrez le mot de passe',
                'outgoing-mail-server' => 'Serveur de messagerie sortant',
                'outgoing-email'       => 'smpt.mailtrap.io',
                'password'             => 'Mot de passe',
                'store-email'          => 'Adresse e-mail du magasin',
                'enter-store-email'    => 'Entrez l\'adresse e-mail du magasin',
                'server-port'          => 'Port du serveur',
                'server-port-code'     => '3306',
                'title'                => 'Configuration de l\'e-mail',
                'username'             => 'Nom d\'utilisateur',
            ],

            'installation-completed' => [
                'admin-panel'                => 'Panneau d\'administration',
                'bagisto-forums'             => 'Forum Bagisto',
                'customer-panel'             => 'Panneau du client',
                'explore-bagisto-extensions' => 'Explorer les extensions Bagisto',
                'title'                      => 'Installation terminée',
                'title-info'                 => 'Bagisto est installé avec succès sur votre système.',
            ],

            'bagisto-logo'             => 'Logo Bagisto',
            'back'                     => 'Retour',
            'bagisto-info'             => 'Un projet communautaire par',
            'bagisto'                  => 'Bagisto',
            'continue'                 => 'Continuer',
            'installation-title'       => 'Bienvenue à l\'installation',
            'installation-info'        => 'Nous sommes heureux de vous voir ici !',
            'installation-description' => "L'installation de Bagisto implique généralement plusieurs étapes. Voici un aperçu général du processus d'installation de Bagisto :",
            'skip'                     => 'Passer',
            'save-configuration'       => 'Enregistrer la configuration',
            'title'                    => 'Installateur Bagisto',
            'webkul'                   => 'Webkul',
        ],
    ],
];
