<?php

return [
    'seeders' => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'Standaard',
            ],

            'attribute-groups' => [
                'description'       => 'Beschrijving',
                'general'           => 'Algemeen',
                'inventories'       => 'Voorraden',
                'meta-description'  => 'Meta Beschrijving',
                'price'             => 'Prijs',
                'shipping'          => 'Verzending',
                'settings'          => 'Instellingen',
            ],

            'attributes' => [
                'brand'                => 'Merk',
                'color'                => 'Kleur',
                'cost'                 => 'Kostprijs',
                'description'          => 'Beschrijving',
                'featured'             => 'Uitgelicht',
                'guest-checkout'       => 'Gast Uitchecken',
                'height'               => 'Hoogte',
                'length'               => 'Lengte',
                'meta-title'           => 'Meta Titel',
                'meta-keywords'        => 'Meta Sleutelwoorden',
                'meta-description'     => 'Meta Beschrijving',
                'manage-stock'         => 'Voorraad Beheren',
                'new'                  => 'Nieuw',
                'name'                 => 'Naam',
                'product-number'       => 'Productnummer',
                'price'                => 'Prijs',
                'sku'                  => 'SKU',
                'status'               => 'Status',
                'short-description'    => 'Korte Beschrijving',
                'special-price'        => 'Speciale Prijs',
                'special-price-from'   => 'Speciale Prijs Vanaf',
                'special-price-to'     => 'Speciale Prijs Tot',
                'size'                 => 'Maat',
                'tax-category'         => 'Belastingcategorie',
                'url-key'              => 'URL Sleutel',
                'visible-individually' => 'Individueel Zichtbaar',
                'width'                => 'Breedte',
                'weight'               => 'Gewicht',
            ],

            'attribute-options' => [
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

        'category' => [
            'categories' => [
                'description' => 'Root Categorie Beschrijving',
                'name'        => 'Root',
            ],
        ],

        'cms' => [
            'pages' => [
                'about-us' => [
                    'content' => 'Over Ons Pagina Inhoud',
                    'title'   => 'Over Ons',
                ],

                'refund-policy' => [
                    'content' => 'Retourbeleid Pagina Inhoud',
                    'title'   => 'Retourbeleid',
                ],

                'return-policy' => [
                    'content' => 'Terugstuurbeleid Pagina Inhoud',
                    'title'   => 'Terugstuurbeleid',
                ],

                'terms-conditions' => [
                    'content' => 'Algemene Voorwaarden Pagina Inhoud',
                    'title'   => 'Algemene Voorwaarden',
                ],

                'terms-of-use' => [
                    'content' => 'Gebruiksvoorwaarden Pagina Inhoud',
                    'title'   => 'Gebruiksvoorwaarden',
                ],

                'contact-us' => [
                    'content' => 'Contacteer Ons Pagina Inhoud',
                    'title'   => 'Contacteer Ons',
                ],

                'customer-service' => [
                    'content' => 'Klantenservice Pagina Inhoud',
                    'title'   => 'Klantenservice',
                ],

                'whats-new' => [
                    'content' => 'Wat is nieuw pagina inhoud',
                    'title'   => 'Wat is nieuw',
                ],

                'payment-policy' => [
                    'content' => 'Betalingsbeleid Pagina Inhoud',
                    'title'   => 'Betalingsbeleid',
                ],

                'shipping-policy' => [
                    'content' => 'Verzendingsbeleid Pagina Inhoud',
                    'title'   => 'Verzendingsbeleid',
                ],

                'privacy-policy' => [
                    'content' => 'Privacybeleid Pagina Inhoud',
                    'title'   => 'Privacybeleid',
                ],
            ],
        ],

        'core' => [
            'channels' => [
                'meta-title'       => 'Demo winkel',
                'meta-keywords'    => 'Demo winkel meta trefwoorden',
                'meta-description' => 'Demo winkel meta beschrijving',
                'name'             => 'Standaard',
            ],

            'currencies' => [
                'CNY' => 'Chinese Yuan',
                'AED' => 'Dirham',
                'EUR' => 'EURO',
                'INR' => 'Indiase Roepie',
                'IRR' => 'Iraanse Rial',
                'AFN' => 'Israëlische Sjekel',
                'JPY' => 'Japanse Yen',
                'GBP' => 'Britse Pond Sterling',
                'RUB' => 'Russische Roebel',
                'SAR' => 'Saoedi-Arabische Riyal',
                'TRY' => 'Turkse Lira',
                'USD' => 'Amerikaanse Dollar',
                'UAH' => 'Oekraïense Hryvnia',
            ],

            'locales' => [
                'ar'    => 'Arabisch',
                'bn'    => 'Bengali',
                'de'    => 'Duits',
                'es'    => 'Spaans',
                'en'    => 'Engels',
                'fr'    => 'Frans',
                'fa'    => 'Perzisch',
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

        'customer' => [
            'customer-groups' => [
                'guest'     => 'Gast',
                'general'   => 'Algemeen',
                'wholesale' => 'Groothandel',
            ],
        ],

        'inventory' => [
            'inventory-sources' => [
                'name' => 'Default',
            ],
        ],

        'shop' => [
            'theme-customizations' => [
                'image-carousel' => [
                    'name'  => 'Image Carousel',

                    'sliders' => [
                        'title' => 'Get Ready For New Collection',
                    ],
                ],

                'offer-information' => [
                    'name' => 'Offer Information',

                    'content' => [
                        'title' => 'Get UP TO 40% OFF on your 1st order SHOP NOW',
                    ],
                ],

                'categories-collections' => [
                    'name' => 'Categories Collections',
                ],

                'new-products' => [
                    'name' => 'New Products',

                    'options' => [
                        'title' => 'New Products',
                    ],
                ],

                'top-collections' => [
                    'name' => 'Top Collections',

                    'content' => [
                        'sub-title-1' => 'Our Collections',
                        'sub-title-2' => 'Our Collections',
                        'sub-title-3' => 'Our Collections',
                        'sub-title-4' => 'Our Collections',
                        'sub-title-5' => 'Our Collections',
                        'sub-title-6' => 'Our Collections',
                        'title'       => 'The game with our new additions!',
                    ],
                ],

                'bold-collections' => [
                    'name' => 'Bold Collections',

                    'content' => [
                        'btn-title'   => 'View All',
                        'description' => 'Introducing Our New Bold Collections! Elevate your style with daring designs and vibrant statements. Explore striking patterns and bold colors that redefine your wardrobe. Get ready to embrace the extraordinary!',
                        'title'       => 'Get Ready for our new Bold Collections!',
                    ],
                ],

                'featured-collections' => [
                    'name' => 'Featured Collections',

                    'options' => [
                        'title' => 'Featured Products',
                    ],
                ],

                'game-container' => [
                    'name' => 'Game Container',

                    'content' => [
                        'sub-title-1' => 'Our Collections',
                        'sub-title-2' => 'Our Collections',
                        'title'       => 'The game with our new additions!',
                    ],
                ],

                'all-products' => [
                    'name' => 'All Products',

                    'options' => [
                        'title' => 'All Products',
                    ],
                ],

                'footer-links' => [
                    'name' => 'Footer Links',

                    'options' => [
                        'about-us'         => 'About Us',
                        'contact-us'       => 'Contact Us',
                        'customer-service' => 'Customer Service',
                        'privacy-policy'   => 'Privacy Policy',
                        'payment-policy'   => 'Payment Policy',
                        'return-policy'    => 'Return Policy',
                        'refund-policy'    => 'Refund Policy',
                        'shipping-policy'  => 'Shipping Policy',
                        'terms-of-use'     => 'Terms of Use',
                        'terms-conditions' => 'Terms & Conditions',
                        'whats-new'        => 'What\'s New',
                    ],
                ],

                'services-content' => [
                    'name'  => 'Diensteninhoud',

                    'title' => [
                        'free-shipping'   => 'Gratis verzending',
                        'product-replace' => 'Product vervangen',
                        'emi-available'   => 'EMI beschikbaar',
                        'time-support'    => '24/7 ondersteuning',
                    ],

                    'description' => [
                        'free-shipping-info'   => 'Geniet van gratis verzending op alle bestellingen',
                        'product-replace-info' => 'Eenvoudige productvervanging beschikbaar!',
                        'emi-available-info'   => 'Geen kosten EMI beschikbaar op alle belangrijke creditcards',
                        'time-support-info'    => 'Toegewijde 24/7 ondersteuning via chat en e-mail',
                    ],
                ],
            ],
        ],

        'user' => [
            'users' => [
                'name' => 'Example',
            ],

            'roles' => [
                'description' => 'This role users will have all the access',
                'name'        => 'Administrator',
            ],
        ],
    ],

    'installer' => [
        'index' => [
            'start' => [
                'locale'        => 'Locatie',
                'main'          => 'Start',
                'select-locale' => 'Selecteer Locatie',
                'title'         => 'Uw Bagisto-installatie',
                'welcome-title' => 'Welkom bij Bagisto 2.0.',
            ],

            'server-requirements' => [
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
                'php'         => 'PHP',
                'php-version' => '8.1 of hoger',
                'pcre'        => 'PCRE',
                'pdo'         => 'PDO',
                'session'     => 'Sessie',
                'title'       => 'Serververeisten',
                'tokenizer'   => 'Tokenizer',
                'xml'         => 'XML',
            ],

            'environment-configuration' => [
                'allowed-locales'     => 'Toegestane talen',
                'allowed-currencies'  => 'Toegestane valuta\'s',
                'application-name'    => 'Toepassingsnaam',
                'bagisto'             => 'Bagisto',
                'chinese-yuan'        => 'Chinese Yuan (CNY)',
                'dirham'              => 'Dirham (AED)',
                'default-url'         => 'Standaard URL',
                'default-url-link'    => 'https://localhost',
                'default-currency'    => 'Standaard Valuta',
                'default-timezone'    => 'Standaard Tijdzone',
                'default-locale'      => 'Standaard Locatie',
                'database-connection' => 'Databaseverbinding',
                'database-hostname'   => 'Database Hostnaam',
                'database-port'       => 'Database Poort',
                'database-name'       => 'Databasenaam',
                'database-username'   => 'Database Gebruikersnaam',
                'database-prefix'     => 'Database Voorvoegsel',
                'database-password'   => 'Database Wachtwoord',
                'euro'                => 'Euro (EUR)',
                'iranian'             => 'Iraanse Rial (IRR)',
                'israeli'             => 'Israëlische Sjekel (AFN)',
                'japanese-yen'        => 'Japanse Yen (JPY)',
                'mysql'               => 'Mysql',
                'pgsql'               => 'pgSQL',
                'pound'               => 'Brits Pond (GBP)',
                'rupee'               => 'Indiase Roepie (INR)',
                'russian-ruble'       => 'Russische Roebel (RUB)',
                'sqlsrv'              => 'SQLSRV',
                'saudi'               => 'Saoedi-Riyal (SAR)',
                'title'               => 'Omgevingsconfiguratie',
                'turkish-lira'        => 'Turkse Lira (TRY)',
                'usd'                 => 'Amerikaanse Dollar (USD)',
                'ukrainian-hryvnia'   => 'Oekraïense Hryvnia (UAH)',
                'warning-message'     => 'Let op! De instellingen voor uw standaardsysteemtalen en de standaardvaluta zijn permanent en kunnen nooit meer worden gewijzigd.',
            ],

            'ready-for-installation' => [
                'create-databsae-table'   => 'Maak de databasetabel aan',
                'install'                 => 'Installatie',
                'install-info'            => 'Bagisto Voor Installatie',
                'install-info-button'     => 'Klik op de knop hieronder om',
                'populate-database-table' => 'Vul de databasetabellen',
                'start-installation'      => 'Start Installatie',
                'title'                   => 'Klaar voor Installatie',
            ],

            'installation-processing' => [
                'bagisto'          => 'Bagisto installatie',
                'bagisto-info'     => 'Het maken van database tabellen kan even duren',
                'title'            => 'Installatie',
            ],

            'create-administrator' => [
                'admin'            => 'Beheerder',
                'bagisto'          => 'Bagisto',
                'confirm-password' => 'Bevestig wachtwoord',
                'email'            => 'E-mail',
                'email-address'    => 'admin@example.com',
                'password'         => 'Wachtwoord',
                'title'            => 'Beheerder aanmaken',
            ],

            'installation-completed' => [
                'admin-panel'                => 'Beheerderspaneel',
                'bagisto-forums'             => 'Bagisto Forum',
                'customer-panel'             => 'Klantenpaneel',
                'explore-bagisto-extensions' => 'Verken Bagisto-extensies',
                'title'                      => 'Installatie voltooid',
                'title-info'                 => 'Bagisto is succesvol geïnstalleerd op uw systeem.',
            ],

            'arabic'                   => 'Arabisch',
            'bengali'                  => 'Bengaals',
            'bagisto-logo'             => 'Bagisto Logo',
            'back'                     => 'Terug',
            'bagisto-info'             => 'Een communityproject van',
            'bagisto'                  => 'Bagisto',
            'chinese'                  => 'Chinees',
            'continue'                 => 'Doorgaan',
            'dutch'                    => 'Nederlands',
            'english'                  => 'Engels',
            'french'                   => 'Frans',
            'german'                   => 'Duits',
            'hebrew'                   => 'Hebreeuws',
            'hindi'                    => 'Hindi',
            'installation-title'       => 'Welkom bij de Bagisto-installatie',
            'installation-info'        => 'We zijn blij je hier te zien!',
            'installation-description' => 'De installatie van Bagisto omvat meestal verschillende stappen. Hier is een algemene uiteenzetting van het installatieproces voor Bagisto:',
            'italian'                  => 'Italiaans',
            'japanese'                 => 'Japans',
            'persian'                  => 'Perzisch',
            'polish'                   => 'Pools',
            'portuguese'               => 'Braziliaans Portugees',
            'russian'                  => 'Russisch',
            'spanish'                  => 'Spaans',
            'sinhala'                  => 'Singalees',
            'skip'                     => 'Overslaan',
            'save-configuration'       => 'Configuratie opslaan',
            'title'                    => 'Bagisto Installer',
            'turkish'                  => 'Turks',
            'ukrainian'                => 'Oekraïens',
            'webkul'                   => 'Webkul',
        ],
    ],
];
