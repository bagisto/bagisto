<?php

return [
    'seeders' => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'Défaut',
            ],

            'attribute-groups' => [
                'description'      => 'Description',
                'general'          => 'Général',
                'inventories'      => 'Inventaires',
                'meta-description' => 'Méta-description',
                'price'            => 'Prix',
                'settings'         => 'Paramètres',
                'shipping'         => 'Expédition',
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
                'special-price'        => 'Prix spécial',
                'special-price-from'   => 'Prix spécial (à partir de)',
                'special-price-to'     => 'Prix spécial (jusqu\'à)',
                'status'               => 'Statut',
                'tax-category'         => 'Catégorie de taxe',
                'url-key'              => 'Clé d\'URL',
                'visible-individually' => 'Visible individuellement',
                'weight'               => 'Poids',
                'width'                => 'Largeur',
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

                'contact-us' => [
                    'content' => 'Contenu de la page Contactez-nous',
                    'title'   => 'Contactez-nous',
                ],

                'customer-service' => [
                    'content' => 'Contenu de la page Service client',
                    'title'   => 'Service client',
                ],

                'payment-policy' => [
                    'content' => 'Contenu de la page Politique de paiement',
                    'title'   => 'Politique de paiement',
                ],

                'privacy-policy' => [
                    'content' => 'Contenu de la page Politique de confidentialité',
                    'title'   => 'Politique de confidentialité',
                ],

                'refund-policy' => [
                    'content' => 'Contenu de la page Politique de remboursement',
                    'title'   => 'Politique de remboursement',
                ],

                'return-policy' => [
                    'content' => 'Contenu de la page Politique de retour',
                    'title'   => 'Politique de retour',
                ],

                'shipping-policy' => [
                    'content' => 'Contenu de la page Politique d\'expédition',
                    'title'   => 'Politique d\'expédition',
                ],

                'terms-conditions' => [
                    'content' => 'Contenu de la page Termes et conditions',
                    'title'   => 'Termes et conditions',
                ],

                'terms-of-use' => [
                    'content' => 'Contenu de la page Conditions d\'utilisation',
                    'title'   => 'Conditions d\'utilisation',
                ],

                'whats-new' => [
                    'content' => 'Contenu de la page Quoi de neuf',
                    'title'   => 'Quoi de neuf',
                ],
            ],
        ],

        'core' => [
            'channels' => [
                'name'             => 'Défaut',
                'meta-title'       => 'Boutique de démonstration',
                'meta-keywords'    => 'Mots-clés méta de la boutique de démonstration',
                'meta-description' => 'Méta-description de la boutique de démonstration',
            ],

            'currencies' => [
                'AED' => 'Dirham des Émirats arabes unis',
                'ARS' => 'Peso argentin',
                'AUD' => 'Dollar australien',
                'BDT' => 'Taka bangladais',
                'BRL' => 'Réel brésilien',
                'CAD' => 'Dollar canadien',
                'CHF' => 'Franc suisse',
                'CLP' => 'Peso chilien',
                'CNY' => 'Yuan chinois',
                'COP' => 'Peso colombien',
                'CZK' => 'Couronne tchèque',
                'DKK' => 'Couronne danoise',
                'DZD' => 'Dinar algérien',
                'EGP' => 'Livre égyptienne',
                'EUR' => 'Euro',
                'FJD' => 'Dollar fidjien',
                'GBP' => 'Livre sterling',
                'HKD' => 'Dollar de Hong Kong',
                'HUF' => 'Forint hongrois',
                'IDR' => 'Roupie indonésienne',
                'ILS' => 'Nouveau shekel israélien',
                'INR' => 'Roupie indienne',
                'JOD' => 'Dinar jordanien',
                'JPY' => 'Yen japonais',
                'KRW' => 'Won sud-coréen',
                'KWD' => 'Dinar koweïtien',
                'KZT' => 'Tenge kazakh',
                'LBP' => 'Livre libanaise',
                'LKR' => 'Roupie srilankaise',
                'LYD' => 'Dinar libyen',
                'MAD' => 'Dirham marocain',
                'MUR' => 'Roupie mauricienne',
                'MXN' => 'Peso mexicain',
                'MYR' => 'Ringgit malaisien',
                'NGN' => 'Naira nigérian',
                'NOK' => 'Couronne norvégienne',
                'NPR' => 'Roupie népalaise',
                'NZD' => 'Dollar néo-zélandais',
                'OMR' => 'Rial omanais',
                'PAB' => 'Balboa panaméen',
                'PEN' => 'Nuevo sol péruvien',
                'PHP' => 'Peso philippin',
                'PKR' => 'Roupie pakistanaise',
                'PLN' => 'Zloty polonais',
                'PYG' => 'Guarani paraguayen',
                'QAR' => 'Rial qatari',
                'RON' => 'Leu roumain',
                'RUB' => 'Rouble russe',
                'SAR' => 'Riyal saoudien',
                'SEK' => 'Couronne suédoise',
                'SGD' => 'Dollar de Singapour',
                'THB' => 'Baht thaïlandais',
                'TND' => 'Dinar tunisien',
                'TRY' => 'Livre turque',
                'TWD' => 'Nouveau dollar taïwanais',
                'UAH' => 'Hryvnia ukrainienne',
                'USD' => 'Dollar américain',
                'UZS' => 'Som ouzbek',
                'VEF' => 'Bolivar vénézuélien',
                'VND' => 'Dong vietnamien',
                'XAF' => 'Franc CFA BEAC',
                'XOF' => 'Franc CFA BCEAO',
                'ZAR' => 'Rand sud-africain',
                'ZMW' => 'Kwacha zambien',
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

        'customer' => [
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

        'shop' => [
            'theme-customizations' => [
                'all-products' => [
                    'name' => 'Tous les produits',

                    'options' => [
                        'title' => 'Tous les produits',
                    ],
                ],

                'bold-collections' => [
                    'content' => [
                        'btn-title'   => 'Voir les collections',
                        'description' => 'Découvrez nos nouvelles collections audacieuses ! Élevez votre style avec des designs audacieux et des déclarations vibrantes. Explorez des motifs saisissants et des couleurs audacieuses qui redéfinissent votre garde-robe. Préparez-vous à embrasser l\'extraordinaire !',
                        'title'       => 'Préparez-vous pour nos nouvelles collections audacieuses !',
                    ],

                    'name' => 'Collections audacieuses',
                ],

                'categories-collections' => [
                    'name' => 'Collections de catégories',
                ],

                'featured-collections' => [
                    'name' => 'Collections en vedette',

                    'options' => [
                        'title' => 'Produits en vedette',
                    ],
                ],

                'footer-links' => [
                    'name' => 'Liens du pied de page',

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

                'game-container' => [
                    'content' => [
                        'sub-title-1' => 'Nos collections',
                        'sub-title-2' => 'Nos collections',
                        'title'       => 'Le jeu avec nos nouvelles additions !',
                    ],

                    'name' => 'Conteneur de jeu',
                ],

                'image-carousel' => [
                    'name' => 'Carrousel d\'images',

                    'sliders' => [
                        'title' => 'Préparez-vous pour la nouvelle collection',
                    ],
                ],

                'new-products' => [
                    'name' => 'Nouveaux produits',

                    'options' => [
                        'title' => 'Nouveaux produits',
                    ],
                ],

                'offer-information' => [
                    'content' => [
                        'title' => 'Jusqu\'à 40% de réduction sur votre 1ère commande, ACHETEZ MAINTENANT',
                    ],

                    'name' => 'Informations sur les offres',
                ],

                'services-content' => [
                    'description' => [
                        'emi-available-info'   => 'EMI sans frais disponible sur toutes les principales cartes de crédit',
                        'free-shipping-info'   => 'Profitez de la livraison gratuite sur toutes les commandes',
                        'product-replace-info' => 'Remplacement facile de produit disponible !',
                        'time-support-info'    => 'Support dédié 24/7 via chat et e-mail',
                    ],

                    'name' => 'Contenu des services',

                    'title' => [
                        'emi-available'   => 'EMI disponible',
                        'free-shipping'   => 'Livraison gratuite',
                        'product-replace' => 'Remplacement de produit',
                        'time-support'    => 'Support 24/7',
                    ],
                ],

                'top-collections' => [
                    'content' => [
                        'sub-title-1' => 'Nos collections',
                        'sub-title-2' => 'Nos collections',
                        'sub-title-3' => 'Nos collections',
                        'sub-title-4' => 'Nos collections',
                        'sub-title-5' => 'Nos collections',
                        'sub-title-6' => 'Nos collections',
                        'title'       => 'Le jeu avec nos nouvelles additions !',
                    ],

                    'name' => 'Collections phares',
                ],
            ],
        ],

        'user' => [
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
            'create-administrator' => [
                'admin'            => 'Admin',
                'bagisto'          => 'Bagisto',
                'confirm-password' => 'Confirmer le mot de passe',
                'download-sample'  => 'Télécharger un exemple',
                'email'            => 'Email',
                'email-address'    => 'admin@example.com',
                'password'         => 'Mot de passe',
                'sample-products'  => 'Produits d\'exemple',
                'title'            => 'Créer un administrateur',
            ],

            'environment-configuration' => [
                'algerian-dinar'              => 'Dinar algérien (DZD)',
                'allowed-currencies'          => 'Devises autorisées',
                'allowed-locales'             => 'Langues autorisées',
                'application-name'            => 'Nom de l\'application',
                'argentine-peso'              => 'Peso argentin (ARS)',
                'australian-dollar'           => 'Dollar australien (AUD)',
                'bagisto'                     => 'Bagisto',
                'bangladeshi-taka'            => 'Taka bangladais (BDT)',
                'brazilian-real'              => 'Réel brésilien (BRL)',
                'british-pound-sterling'      => 'Livre sterling britannique (GBP)',
                'canadian-dollar'             => 'Dollar canadien (CAD)',
                'cfa-franc-bceao'             => 'Franc CFA BCEAO (XOF)',
                'cfa-franc-beac'              => 'Franc CFA BEAC (XAF)',
                'chilean-peso'                => 'Peso chilien (CLP)',
                'chinese-yuan'                => 'Yuan chinois (CNY)',
                'colombian-peso'              => 'Peso colombien (COP)',
                'czech-koruna'                => 'Couronne tchèque (CZK)',
                'danish-krone'                => 'Couronne danoise (DKK)',
                'database-connection'         => 'Connexion à la base de données',
                'database-hostname'           => 'Nom d\'hôte de la base de données',
                'database-name'               => 'Nom de la base de données',
                'database-password'           => 'Mot de passe de la base de données',
                'database-port'               => 'Port de la base de données',
                'database-prefix'             => 'Préfixe de la base de données',
                'database-username'           => 'Nom d\'utilisateur de la base de données',
                'default-currency'            => 'Devise par défaut',
                'default-locale'              => 'Langue par défaut',
                'default-timezone'            => 'Fuseau horaire par défaut',
                'default-url'                 => 'URL par défaut',
                'default-url-link'            => 'https://localhost',
                'egyptian-pound'              => 'Livre égyptienne (EGP)',
                'euro'                        => 'Euro (EUR)',
                'fijian-dollar'               => 'Dollar fidjien (FJD)',
                'hong-kong-dollar'            => 'Dollar de Hong Kong (HKD)',
                'hungarian-forint'            => 'Forint hongrois (HUF)',
                'indian-rupee'                => 'Roupie indienne (INR)',
                'indonesian-rupiah'           => 'Roupie indonésienne (IDR)',
                'israeli-new-shekel'          => 'Nouveau shekel israélien (ILS)',
                'japanese-yen'                => 'Yen japonais (JPY)',
                'jordanian-dinar'             => 'Dinar jordanien (JOD)',
                'kazakhstani-tenge'           => 'Tenge kazakh (KZT)',
                'kuwaiti-dinar'               => 'Dinar koweïtien (KWD)',
                'lebanese-pound'              => 'Livre libanaise (LBP)',
                'libyan-dinar'                => 'Dinar libyen (LYD)',
                'malaysian-ringgit'           => 'Ringgit malaisien (MYR)',
                'mauritian-rupee'             => 'Roupie mauricienne (MUR)',
                'mexican-peso'                => 'Peso mexicain (MXN)',
                'moroccan-dirham'             => 'Dirham marocain (MAD)',
                'mysql'                       => 'Mysql',
                'nepalese-rupee'              => 'Roupie népalaise (NPR)',
                'new-taiwan-dollar'           => 'Dollar taïwanais (TWD)',
                'new-zealand-dollar'          => 'Dollar néo-zélandais (NZD)',
                'nigerian-naira'              => 'Naira nigérian (NGN)',
                'norwegian-krone'             => 'Couronne norvégienne (NOK)',
                'omani-rial'                  => 'Rial omanais (OMR)',
                'pakistani-rupee'             => 'Roupie pakistanaise (PKR)',
                'panamanian-balboa'           => 'Balboa panaméen (PAB)',
                'paraguayan-guarani'          => 'Guarani paraguayen (PYG)',
                'peruvian-nuevo-sol'          => 'Nuevo sol péruvien (PEN)',
                'pgsql'                       => 'pgSQL',
                'philippine-peso'             => 'Peso philippin (PHP)',
                'polish-zloty'                => 'Zloty polonais (PLN)',
                'qatari-rial'                 => 'Rial qatari (QAR)',
                'romanian-leu'                => 'Leu roumain (RON)',
                'russian-ruble'               => 'Rouble russe (RUB)',
                'saudi-riyal'                 => 'Riyal saoudien (SAR)',
                'select-timezone'             => 'Sélectionnez le fuseau horaire',
                'singapore-dollar'            => 'Dollar de Singapour (SGD)',
                'south-african-rand'          => 'Rand sud-africain (ZAR)',
                'south-korean-won'            => 'Won sud-coréen (KRW)',
                'sqlsrv'                      => 'SQLSRV',
                'sri-lankan-rupee'            => 'Roupie srilankaise (LKR)',
                'swedish-krona'               => 'Couronne suédoise (SEK)',
                'swiss-franc'                 => 'Franc suisse (CHF)',
                'thai-baht'                   => 'Baht thaïlandais (THB)',
                'title'                       => 'Configuration du magasin',
                'tunisian-dinar'              => 'Dinar tunisien (TND)',
                'turkish-lira'                => 'Livre turque (TRY)',
                'ukrainian-hryvnia'           => 'Hryvnia ukrainienne (UAH)',
                'united-arab-emirates-dirham' => 'Dirham des Émirats arabes unis (AED)',
                'united-states-dollar'        => 'Dollar américain (USD)',
                'uzbekistani-som'             => 'Som ouzbek (UZS)',
                'venezuelan-bolívar'          => 'Bolívar vénézuélien (VEF)',
                'vietnamese-dong'             => 'Dong vietnamien (VND)',
                'warning-message'             => 'Attention ! Les paramètres de vos langues système par défaut ainsi que de la devise par défaut sont permanents et ne peuvent plus être modifiés.',
                'zambian-kwacha'              => 'Kwacha zambien (ZMW)',
            ],

            'installation-processing' => [
                'bagisto'      => 'Installation de Bagisto',
                'bagisto-info' => 'Création des tables de la base de données, cela peut prendre quelques instants',
                'title'        => 'Installation',
            ],

            'installation-completed' => [
                'admin-panel'                => 'Panneau d\'administration',
                'bagisto-forums'             => 'Forum Bagisto',
                'customer-panel'             => 'Panneau du client',
                'explore-bagisto-extensions' => 'Explorer les extensions Bagisto',
                'title'                      => 'Installation terminée',
                'title-info'                 => 'Bagisto est installé avec succès sur votre système.',
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
                'pcre'        => 'PCRE',
                'pdo'         => 'PDO',
                'php'         => 'PHP',
                'php-version' => '8.1 ou supérieure',
                'session'     => 'Session',
                'title'       => 'Configuration requise du serveur',
                'tokenizer'   => 'Tokenizer',
                'xml'         => 'XML',
            ],

            'arabic'                   => 'Arabe',
            'back'                     => 'Retour',
            'bagisto'                  => 'Bagisto',
            'bagisto-info'             => 'Un projet communautaire par',
            'bagisto-logo'             => 'Logo de Bagisto',
            'bengali'                  => 'Bengali',
            'chinese'                  => 'Chinois',
            'continue'                 => 'Continuer',
            'dutch'                    => 'Néerlandais',
            'english'                  => 'Anglais',
            'french'                   => 'Français',
            'german'                   => 'Allemand',
            'hebrew'                   => 'Hébreu',
            'hindi'                    => 'Hindi',
            'installation-description' => 'L\'installation de Bagisto implique généralement plusieurs étapes. Voici un  aperçu général du processus d\'installation pour Bagisto :',
            'installation-info'        => 'Nous sommes heureux de vous voir ici !',
            'installation-title'       => 'Bienvenue dans l\'installation',
            'italian'                  => 'Italien',
            'japanese'                 => 'Japonais',
            'persian'                  => 'Persan',
            'polish'                   => 'Polonais',
            'portuguese'               => 'Portugais brésilien',
            'russian'                  => 'Russe',
            'sinhala'                  => 'Sinhala',
            'spanish'                  => 'Espagnol',
            'title'                    => 'Installeur de Bagisto',
            'turkish'                  => 'Turc',
            'ukrainian'                => 'Ukrainien',
            'webkul'                   => 'Webkul',
        ],
    ],
];
