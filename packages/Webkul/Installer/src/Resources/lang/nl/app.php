<?php

return [
    'seeders'   => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'Standaard',
            ],

            'attribute-groups'   => [
                'description'       => 'Beschrijving',
                'general'           => 'Algemeen',
                'inventories'       => 'Voorraden',
                'meta-description'  => 'Meta Beschrijving',
                'price'             => 'Prijs',
                'settings'          => 'Instellingen',
                'shipping'          => 'Verzending',
            ],

            'attributes'         => [
                'brand'                => 'Merk',
                'color'                => 'Kleur',
                'cost'                 => 'Kostprijs',
                'description'          => 'Beschrijving',
                'featured'             => 'Uitgelicht',
                'guest-checkout'       => 'Gast Uitchecken',
                'height'               => 'Hoogte',
                'length'               => 'Lengte',
                'manage-stock'         => 'Voorraad Beheren',
                'meta-description'     => 'Meta Beschrijving',
                'meta-keywords'        => 'Meta Sleutelwoorden',
                'meta-title'           => 'Meta Titel',
                'name'                 => 'Naam',
                'new'                  => 'Nieuw',
                'price'                => 'Prijs',
                'product-number'       => 'Productnummer',
                'short-description'    => 'Korte Beschrijving',
                'size'                 => 'Maat',
                'sku'                  => 'SKU',
                'special-price-from'   => 'Speciale Prijs Vanaf',
                'special-price-to'     => 'Speciale Prijs Tot',
                'special-price'        => 'Speciale Prijs',
                'status'               => 'Status',
                'tax-category'         => 'Belastingcategorie',
                'url-key'              => 'URL Sleutel',
                'visible-individually' => 'Individueel Zichtbaar',
                'weight'               => 'Gewicht',
                'width'                => 'Breedte',
            ],

            'attribute-options'  => [
                'black'  => 'Zwart',
                'green'  => 'Groen',
                'l'      => 'L',
                'm'      => 'M',
                'red'    => 'Rood',
                's'      => 'S',
                'white'  => 'Wit',
                'xl'     => 'XL',
                'yellow' => 'Geel',
            ],
        ],

        'category'  => [
            'categories' => [
                'description' => 'Root Categorie Beschrijving',
                'name'        => 'Root',
            ],
        ],

        'cms'       => [
            'pages' => [
                'about-us'         => [
                    'content' => 'Over Ons Pagina Inhoud',
                    'title'   => 'Over Ons',
                ],

                'contact-us'       => [
                    'content' => 'Contacteer Ons Pagina Inhoud',
                    'title'   => 'Contacteer Ons',
                ],

                'customer-service' => [
                    'content' => 'Klantenservice Pagina Inhoud',
                    'title'   => 'Klantenservice',
                ],

                'payment-policy'   => [
                    'content' => 'Betalingsbeleid Pagina Inhoud',
                    'title'   => 'Betalingsbeleid',
                ],

                'privacy-policy'   => [
                    'content' => 'Privacybeleid Pagina Inhoud',
                    'title'   => 'Privacybeleid',
                ],

                'refund-policy'    => [
                    'content' => 'Retourbeleid Pagina Inhoud',
                    'title'   => 'Retourbeleid',
                ],

                'return-policy'    => [
                    'content' => 'Terugstuurbeleid Pagina Inhoud',
                    'title'   => 'Terugstuurbeleid',
                ],

                'shipping-policy'  => [
                    'content' => 'Verzendingsbeleid Pagina Inhoud',
                    'title'   => 'Verzendingsbeleid',
                ],

                'terms-conditions' => [
                    'content' => 'Algemene Voorwaarden Pagina Inhoud',
                    'title'   => 'Algemene Voorwaarden',
                ],

                'terms-of-use'     => [
                    'content' => 'Gebruiksvoorwaarden Pagina Inhoud',
                    'title'   => 'Gebruiksvoorwaarden',
                ],

                'whats-new'        => [
                    'content' => 'Wat is nieuw pagina inhoud',
                    'title'   => 'Wat is nieuw',
                ],
            ],
        ],

        'core'      => [
            'channels'   => [
                'meta-description' => 'Demo winkel meta beschrijving',
                'meta-keywords'    => 'Demo winkel meta trefwoorden',
                'meta-title'       => 'Demo winkel',
                'name'             => 'Standaard',
            ],

            'currencies' => [
                'AED' => 'Dirham',
                'AFN' => 'Israëlische Sjekel',
                'CNY' => 'Chinese Yuan',
                'EUR' => 'EURO',
                'GBP' => 'Britse Pond Sterling',
                'INR' => 'Indiase Roepie',
                'IRR' => 'Iraanse Rial',
                'JPY' => 'Japanse Yen',
                'RUB' => 'Russische Roebel',
                'SAR' => 'Saoedi-Arabische Riyal',
                'TRY' => 'Turkse Lira',
                'UAH' => 'Oekraïense Hryvnia',
                'USD' => 'Amerikaanse Dollar',
            ],

            'locales'    => [
                'ar'    => 'Arabisch',
                'bn'    => 'Bengali',
                'de'    => 'Duits',
                'en'    => 'Engels',
                'es'    => 'Spaans',
                'fa'    => 'Perzisch',
                'fr'    => 'Frans',
                'he'    => 'Hebreeuws',
                'hi_IN' => 'Hindi',
                'it'    => 'Italiaans',
                'ja'    => 'Japans',
                'nl'    => 'Nederlands',
                'pl'    => 'Pools',
                'pt_BR' => 'Braziliaans Portugees',
                'ru'    => 'Russisch',
                'sin'   => 'Singalees',
                'tr'    => 'Turks',
                'uk'    => 'Oekraïens',
                'zh_CN' => 'Chinees',
            ],
        ],

        'customer'  => [
            'customer-groups' => [
                'general'   => 'Algemeen',
                'guest'     => 'Gast',
                'wholesale' => 'Groothandel',
            ],
        ],

        'inventory' => [
            'inventory-sources' => [
                'name' => 'Default',
            ],
        ],

        'shop'      => [
            'theme-customizations' => [
                'all-products'           => [
                    'name'    => 'All Products',

                    'options' => [
                        'title' => 'All Products',
                    ],
                ],

                'bold-collections'       => [
                    'content' => [
                        'btn-title'   => 'View All',
                        'description' => 'Introducing Our New Bold Collections! Elevate your style with daring designs and vibrant statements. Explore striking patterns and bold colors that redefine your wardrobe. Get ready to embrace the extraordinary!',
                        'title'       => 'Get Ready for our new Bold Collections!',
                    ],

                    'name'    => 'Bold Collections',
                ],

                'categories-collections' => [
                    'name' => 'Categories Collections',
                ],

                'featured-collections'   => [
                    'name'    => 'Featured Collections',

                    'options' => [
                        'title' => 'Featured Products',
                    ],
                ],

                'footer-links'           => [
                    'name'    => 'Footer Links',

                    'options' => [
                        'about-us'         => 'About Us',
                        'contact-us'       => 'Contact Us',
                        'customer-service' => 'Customer Service',
                        'payment-policy'   => 'Payment Policy',
                        'privacy-policy'   => 'Privacy Policy',
                        'refund-policy'    => 'Refund Policy',
                        'return-policy'    => 'Return Policy',
                        'shipping-policy'  => 'Shipping Policy',
                        'terms-conditions' => 'Terms & Conditions',
                        'terms-of-use'     => 'Terms of Use',
                        'whats-new'        => 'What\'s New',
                    ],
                ],

                'game-container'         => [
                    'name'    => 'Game Container',

                    'content' => [
                        'sub-title-1' => 'Our Collections',
                        'sub-title-2' => 'Our Collections',
                        'title'       => 'The game with our new additions!',
                    ],
                ],

                'image-carousel'         => [
                    'name'    => 'Image Carousel',

                    'sliders' => [
                        'title' => 'Get Ready For New Collection',
                    ],
                ],

                'new-products'           => [
                    'name'    => 'New Products',

                    'options' => [
                        'title' => 'New Products',
                    ],
                ],

                'offer-information'      => [
                    'name'    => 'Offer Information',

                    'content' => [
                        'title' => 'Get UP TO 40% OFF on your 1st order SHOP NOW',
                    ],
                ],

                'services-content'       => [
                    'description' => [
                        'emi-available-info'   => 'Geen kosten EMI beschikbaar op alle belangrijke creditcards',
                        'free-shipping-info'   => 'Geniet van gratis verzending op alle bestellingen',
                        'product-replace-info' => 'Eenvoudige productvervanging beschikbaar!',
                        'time-support-info'    => 'Toegewijde 24/7 ondersteuning via chat en e-mail',
                    ],

                    'name'        => 'Diensteninhoud',

                    'title'       => [
                        'emi-available'   => 'EMI beschikbaar',
                        'free-shipping'   => 'Gratis verzending',
                        'product-replace' => 'Product vervangen',
                        'time-support'    => '24/7 ondersteuning',
                    ],
                ],

                'top-collections'        => [
                    'content' => [
                        'sub-title-1' => 'Our Collections',
                        'sub-title-2' => 'Our Collections',
                        'sub-title-3' => 'Our Collections',
                        'sub-title-4' => 'Our Collections',
                        'sub-title-5' => 'Our Collections',
                        'sub-title-6' => 'Our Collections',
                        'title'       => 'The game with our new additions!',
                    ],

                    'name'    => 'Top Collections',
                ],
            ],
        ],

        'user'      => [
            'roles' => [
                'description' => 'This role users will have all the access',
                'name'        => 'Administrator',
            ],

            'users' => [
                'name' => 'Example',
            ],
        ],
    ],

    'installer' => [
        'index' => [
            'create-administrator'      => [
                'admin'            => 'Beheerder',
                'bagisto'          => 'Bagisto',
                'confirm-password' => 'Bevestig wachtwoord',
                'email-address'    => 'admin@example.com',
                'email'            => 'E-mail',
                'password'         => 'Wachtwoord',
                'title'            => 'Beheerder aanmaken',
            ],

            'environment-configuration' => [
                'allowed-currencies'  => 'Toegestane valuta\'s',
                'allowed-locales'     => 'Toegestane talen',
                'application-name'    => 'Toepassingsnaam',
                'bagisto'             => 'Bagisto',
                'chinese-yuan'        => 'Chinese Yuan (CNY)',
                'database-connection' => 'Databaseverbinding',
                'database-hostname'   => 'Database Hostnaam',
                'database-name'       => 'Databasenaam',
                'database-password'   => 'Database Wachtwoord',
                'database-port'       => 'Database Poort',
                'database-prefix'     => 'Database Voorvoegsel',
                'database-username'   => 'Database Gebruikersnaam',
                'default-currency'    => 'Standaard Valuta',
                'default-locale'      => 'Standaard Locatie',
                'default-timezone'    => 'Standaard Tijdzone',
                'default-url-link'    => 'https://localhost',
                'default-url'         => 'Standaard URL',
                'dirham'              => 'Dirham (AED)',
                'euro'                => 'Euro (EUR)',
                'iranian'             => 'Iraanse Rial (IRR)',
                'israeli'             => 'Israëlische Sjekel (AFN)',
                'japanese-yen'        => 'Japanse Yen (JPY)',
                'mysql'               => 'Mysql',
                'pgsql'               => 'pgSQL',
                'pound'               => 'Brits Pond (GBP)',
                'rupee'               => 'Indiase Roepie (INR)',
                'russian-ruble'       => 'Russische Roebel (RUB)',
                'saudi'               => 'Saoedi-Riyal (SAR)',
                'select-timezone'     => 'Selecteer Tijdzone',
                'sqlsrv'              => 'SQLSRV',
                'title'               => 'Omgevingsconfiguratie',
                'turkish-lira'        => 'Turkse Lira (TRY)',
                'ukrainian-hryvnia'   => 'Oekraïense Hryvnia (UAH)',
                'usd'                 => 'Amerikaanse Dollar (USD)',
                'warning-message'     => 'Let op! De instellingen voor uw standaardsysteemtalen en de standaardvaluta zijn permanent en kunnen nooit meer worden gewijzigd.',
            ],

            'installation-processing'   => [
                'bagisto-info'     => 'Het maken van database tabellen kan even duren',
                'bagisto'          => 'Bagisto installatie',
                'title'            => 'Installatie',
            ],

            'installation-completed'    => [
                'admin-panel'                => 'Beheerderspaneel',
                'bagisto-forums'             => 'Bagisto Forum',
                'customer-panel'             => 'Klantenpaneel',
                'explore-bagisto-extensions' => 'Verken Bagisto-extensies',
                'title-info'                 => 'Bagisto is succesvol geïnstalleerd op uw systeem.',
                'title'                      => 'Installatie voltooid',
            ],

            'ready-for-installation'    => [
                'create-databsae-table'   => 'Maak de databasetabel aan',
                'install-info-button'     => 'Klik op de knop hieronder om',
                'install-info'            => 'Bagisto Voor Installatie',
                'install'                 => 'Installatie',
                'populate-database-table' => 'Vul de databasetabellen',
                'start-installation'      => 'Start Installatie',
                'title'                   => 'Klaar voor Installatie',
            ],

            'start'                     => [
                'locale'        => 'Locatie',
                'main'          => 'Start',
                'select-locale' => 'Selecteer Locatie',
                'title'         => 'Uw Bagisto-installatie',
                'welcome-title' => 'Welkom bij Bagisto 2.0.',
            ],

            'server-requirements'       => [
                'calendar'    => 'Kalender',
                'ctype'       => 'cType',
                'curl'        => 'cURL',
                'dom'         => 'DOM',
                'fileinfo'    => 'Bestandsinformatie',
                'filter'      => 'Filter',
                'gd'          => 'GD',
                'hash'        => 'Hash',
                'intl'        => 'intl',
                'json'        => 'JSON',
                'mbstring'    => 'mbstring',
                'openssl'     => 'OpenSSL',
                'pcre'        => 'PCRE',
                'pdo'         => 'PDO',
                'php-version' => '8.1 of hoger',
                'php'         => 'PHP',
                'session'     => 'Sessie',
                'title'       => 'Serververeisten',
                'tokenizer'   => 'Tokenizer',
                'xml'         => 'XML',
            ],

            'arabic'                    => 'Arabisch',
            'back'                      => 'Terug',
            'bagisto-info'              => 'Een communityproject van',
            'bagisto-logo'              => 'Bagisto Logo',
            'bagisto'                   => 'Bagisto',
            'bengali'                   => 'Bengaals',
            'chinese'                   => 'Chinees',
            'continue'                  => 'Doorgaan',
            'dutch'                     => 'Nederlands',
            'english'                   => 'Engels',
            'french'                    => 'Frans',
            'german'                    => 'Duits',
            'hebrew'                    => 'Hebreeuws',
            'hindi'                     => 'Hindi',
            'installation-description'  => 'De installatie van Bagisto omvat meestal verschillende stappen. Hier is een algemene uiteenzetting van het insta llatieproces voor Bagisto:',
            'installation-info'         => 'We zijn blij je hier te zien!',
            'installation-title'        => 'Welkom bij de Bagisto-installatie',
            'italian'                   => 'Italiaans',
            'japanese'                  => 'Japans',
            'persian'                   => 'Perzisch',
            'polish'                    => 'Pools',
            'portuguese'                => 'Braziliaans Portugees',
            'russian'                   => 'Russisch',
            'save-configuration'        => 'Configuratie opslaan',
            'sinhala'                   => 'Singalees',
            'skip'                      => 'Overslaan',
            'spanish'                   => 'Spaans',
            'title'                     => 'Bagisto Installer',
            'turkish'                   => 'Turks',
            'ukrainian'                 => 'Oekraïens',
            'webkul'                    => 'Webkul',
        ],
    ],
];
