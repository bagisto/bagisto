<?php

return [
    'seeders' => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'Standard',
            ],

            'attribute-groups' => [
                'description'      => 'Beschreibung',
                'general'          => 'Allgemein',
                'inventories'      => 'Bestände',
                'meta-description' => 'Meta-Beschreibung',
                'price'            => 'Preis',
                'settings'         => 'Einstellungen',
                'shipping'         => 'Versand',
            ],

            'attributes' => [
                'brand'                => 'Marke',
                'color'                => 'Farbe',
                'cost'                 => 'Kosten',
                'description'          => 'Beschreibung',
                'featured'             => 'Hervorgehoben',
                'guest-checkout'       => 'Gastkasse',
                'height'               => 'Höhe',
                'length'               => 'Länge',
                'manage-stock'         => 'Bestand verwalten',
                'meta-description'     => 'Meta-Beschreibung',
                'meta-keywords'        => 'Meta-Schlüsselwörter',
                'meta-title'           => 'Meta-Titel',
                'name'                 => 'Name',
                'new'                  => 'Neu',
                'price'                => 'Preis',
                'product-number'       => 'Produktnummer',
                'short-description'    => 'Kurzbeschreibung',
                'size'                 => 'Größe',
                'sku'                  => 'Artikelnummer (SKU)',
                'special-price'        => 'Sonderpreis',
                'special-price-from'   => 'Sonderpreis Von',
                'special-price-to'     => 'Sonderpreis Bis',
                'status'               => 'Status',
                'tax-category'         => 'Steuerkategorie',
                'url-key'              => 'URL-Schlüssel',
                'visible-individually' => 'Einzeln sichtbar',
                'weight'               => 'Gewicht',
                'width'                => 'Breite',
            ],

            'attribute-options'  => [
                'black'  => 'Schwarz',
                'green'  => 'Grün',
                'l'      => 'L',
                'm'      => 'M',
                'red'    => 'Rot',
                's'      => 'S',
                'white'  => 'Weiß',
                'xl'     => 'XL',
                'yellow' => 'Gelb',
            ],
        ],

        'category' => [
            'categories' => [
                'description' => 'Beschreibung der Wurzelkategorie',
                'name'        => 'Wurzel',
            ],
        ],

        'cms' => [
            'pages' => [
                'about-us' => [
                    'content' => 'Über uns Seitensinhalt',
                    'title'   => 'Über uns',
                ],

                'contact-us' => [
                    'content' => 'Kontaktiere uns Seitensinhalt',
                    'title'   => 'Kontaktiere uns',
                ],

                'customer-service' => [
                    'content' => 'Kundenservice Seitensinhalt',
                    'title'   => 'Kundenservice',
                ],

                'payment-policy' => [
                    'content' => 'Zahlungsrichtlinie Seitensinhalt',
                    'title'   => 'Zahlungsrichtlinie',
                ],

                'privacy-policy' => [
                    'content' => 'Datenschutzrichtlinie Seitensinhalt',
                    'title'   => 'Datenschutzrichtlinie',
                ],

                'refund-policy' => [
                    'content' => 'Rückgaberecht Seitensinhalt',
                    'title'   => 'Rückgaberecht',
                ],

                'return-policy' => [
                    'content' => 'Rückgaberecht Seitensinhalt',
                    'title'   => 'Rückgaberecht',
                ],

                'shipping-policy' => [
                    'content' => 'Versandrichtlinie Seitensinhalt',
                    'title'   => 'Versandrichtlinie',
                ],

                'terms-conditions' => [
                    'content' => 'Nutzungsbedingungen Seitensinhalt',
                    'title'   => 'Nutzungsbedingungen',
                ],

                'terms-of-use' => [
                    'content' => 'Nutzungsbedingungen Seitensinhalt',
                    'title'   => 'Nutzungsbedingungen',
                ],

                'whats-new' => [
                    'content' => 'Inhaltsseite für Neuigkeiten',
                    'title'   => 'Was gibt es Neues',
                ],
            ],
        ],

        'core' => [
            'channels' => [
                'name'             => 'Standard',
                'meta-title'       => 'Demo-Shop',
                'meta-keywords'    => 'Demo-Shop Meta-Stichwörter',
                'meta-description' => 'Demo-Shop Meta-Beschreibung',
            ],

            'currencies' => [
                'AED' => 'Vereinigte Arabische Emirate Dirham',
                'ARS' => 'Argentinischer Peso',
                'AUD' => 'Australischer Dollar',
                'BDT' => 'Bangladeschischer Taka',
                'BRL' => 'Brasilianischer Real',
                'CAD' => 'Kanadischer Dollar',
                'CHF' => 'Schweizer Franken',
                'CLP' => 'Chilenischer Peso',
                'CNY' => 'Chinesischer Yuan',
                'COP' => 'Kolumbianischer Peso',
                'CZK' => 'Tschechische Krone',
                'DKK' => 'Dänische Krone',
                'DZD' => 'Algerischer Dinar',
                'EGP' => 'Ägyptisches Pfund',
                'EUR' => 'Euro',
                'FJD' => 'Fidschi-Dollar',
                'GBP' => 'Britisches Pfund Sterling',
                'HKD' => 'Hongkong-Dollar',
                'HUF' => 'Ungarischer Forint',
                'IDR' => 'Indonesische Rupiah',
                'ILS' => 'Israelischer Neuer Schekel',
                'INR' => 'Indische Rupie',
                'JOD' => 'Jordanischer Dinar',
                'JPY' => 'Japanischer Yen',
                'KRW' => 'Südkoreanischer Won',
                'KWD' => 'Kuwaitischer Dinar',
                'KZT' => 'Kasachischer Tenge',
                'LBP' => 'Libanesisches Pfund',
                'LKR' => 'Sri-Lanka-Rupie',
                'LYD' => 'Libyscher Dinar',
                'MAD' => 'Marokkanischer Dirham',
                'MUR' => 'Mauritius-Rupie',
                'MXN' => 'Mexikanischer Peso',
                'MYR' => 'Malaysischer Ringgit',
                'NGN' => 'Nigerianische Naira',
                'NOK' => 'Norwegische Krone',
                'NPR' => 'Nepalesische Rupie',
                'NZD' => 'Neuseeland-Dollar',
                'OMR' => 'Omanischer Rial',
                'PAB' => 'Panamaischer Balboa',
                'PEN' => 'Peruanischer Nuevo Sol',
                'PHP' => 'Philippinischer Peso',
                'PKR' => 'Pakistanische Rupie',
                'PLN' => 'Polnischer Złoty',
                'PYG' => 'Paraguayischer Guaraní',
                'QAR' => 'Katar-Riyal',
                'RON' => 'Rumänischer Leu',
                'RUB' => 'Russischer Rubel',
                'SAR' => 'Saudi-Riyal',
                'SEK' => 'Schwedische Krone',
                'SGD' => 'Singapur-Dollar',
                'THB' => 'Thailändischer Baht',
                'TND' => 'Tunesischer Dinar',
                'TRY' => 'Türkische Lira',
                'TWD' => 'Neuer Taiwan-Dollar',
                'UAH' => 'Ukrainische Hrywnja',
                'USD' => 'US-Dollar',
                'UZS' => 'Usbekischer Som',
                'VEF' => 'Venezolanischer Bolívar',
                'VND' => 'Vietnamesischer Đồng',
                'XAF' => 'CFA-Franc (BEAC)',
                'XOF' => 'CFA-Franc (BCEAO)',
                'ZAR' => 'Südafrikanischer Rand',
                'ZMW' => 'Sambischer Kwacha',
            ],

            'locales' => [
                'ar'    => 'Arabisch',
                'bn'    => 'Bengalisch',
                'de'    => 'Deutsch',
                'en'    => 'Englisch',
                'es'    => 'Spanisch',
                'fa'    => 'Persisch',
                'fr'    => 'Französisch',
                'he'    => 'Hebräisch',
                'hi_IN' => 'Hindi',
                'it'    => 'Italienisch',
                'ja'    => 'Japanisch',
                'nl'    => 'Niederländisch',
                'pl'    => 'Polnisch',
                'pt_BR' => 'Brasilianisches Portugiesisch',
                'ru'    => 'Russisch',
                'sin'   => 'Sinhala',
                'tr'    => 'Türkisch',
                'uk'    => 'Ukrainisch',
                'zh_CN' => 'Chinesisch',
            ],
        ],

        'customer' => [
            'customer-groups' => [
                'general'   => 'Allgemein',
                'guest'     => 'Gast',
                'wholesale' => 'Großhandel',
            ],
        ],

        'inventory' => [
            'inventory-sources' => [
                'name' => 'Standard',
            ],
        ],

        'shop' => [
            'theme-customizations' => [
                'all-products' => [
                    'name' => 'Alle Produkte',

                    'options' => [
                        'title' => 'Alle Produkte',
                    ],
                ],

                'bold-collections' => [
                    'content' => [
                        'btn-title'   => 'Kollektionen anzeigen',
                        'description' => 'Einführung unserer neuen fetten Kollektionen! Heben Sie Ihren Stil mit gewagten Designs und lebhaften Statements hervor. Erkunden Sie auffällige Muster und kräftige Farben, die Ihre Garderobe neu definieren. Machen Sie sich bereit, das Außergewöhnliche zu umarmen!',
                        'title'       => 'Machen Sie sich bereit für unsere neuen fetten Kollektionen!',
                    ],

                    'name' => 'Fette Kollektionen',
                ],

                'categories-collections' => [
                    'name' => 'Kategorienkollektionen',
                ],

                'footer-links' => [
                    'name' => 'Fußzeilenlinks',

                    'options' => [
                        'about-us'         => 'Über uns',
                        'contact-us'       => 'Kontaktiere uns',
                        'customer-service' => 'Kundenservice',
                        'payment-policy'   => 'Zahlungsrichtlinie',
                        'privacy-policy'   => 'Datenschutzrichtlinie',
                        'refund-policy'    => 'Rückgaberichtlinie',
                        'return-policy'    => 'Rückgaberichtlinie',
                        'shipping-policy'  => 'Versandrichtlinie',
                        'terms-conditions' => 'Allgemeine Geschäftsbedingungen',
                        'terms-of-use'     => 'Nutzungsbedingungen',
                        'whats-new'        => 'Was gibt es Neues',
                    ],
                ],

                'featured-collections' => [
                    'name' => 'Hervorgehobene Kollektionen',

                    'options' => [
                        'title' => 'Hervorgehobene Produkte',
                    ],
                ],

                'game-container' => [
                    'content' => [
                        'sub-title-1' => 'Unsere Kollektionen',
                        'sub-title-2' => 'Unsere Kollektionen',
                        'title'       => 'Das Spiel mit unseren neuen Ergänzungen!',
                    ],

                    'name' => 'Spielercontainer',
                ],

                'image-carousel' => [
                    'name' => 'Bildkarussell',

                    'sliders' => [
                        'title' => 'Bereit für die neue Kollektion',
                    ],
                ],

                'new-products' => [
                    'name' => 'Neue Produkte',

                    'options' => [
                        'title' => 'Neue Produkte',
                    ],
                ],

                'offer-information' => [
                    'content' => [
                        'title' => 'Bis zu 40% Rabatt auf Ihre erste Bestellung JETZT KAUFEN',
                    ],

                    'name' => 'Angebotsinformation',
                ],

                'services-content' => [
                    'description' => [
                        'emi-available-info'   => 'Keine Kosten EMI verfügbar auf allen gängigen Kreditkarten',
                        'free-shipping-info'   => 'Kostenloser Versand für alle Bestellungen',
                        'product-replace-info' => 'Einfacher Produktwechsel möglich!',
                        'time-support-info'    => 'Dedizierte 24/7 Unterstützung über Chat und E-Mail',
                    ],

                    'name' => 'Services Content',

                    'title' => [
                        'emi-available'   => 'EMI verfügbar',
                        'free-shipping'   => 'Kostenloser Versand',
                        'product-replace' => 'Produkt austauschen',
                        'time-support'    => '24/7 Unterstützung',
                    ],
                ],

                'top-collections' => [
                    'content' => [
                        'sub-title-1' => 'Unsere Kollektionen',
                        'sub-title-2' => 'Unsere Kollektionen',
                        'sub-title-3' => 'Unsere Kollektionen',
                        'sub-title-4' => 'Unsere Kollektionen',
                        'sub-title-5' => 'Unsere Kollektionen',
                        'sub-title-6' => 'Unsere Kollektionen',
                        'title'       => 'Das Spiel mit unseren neuen Ergänzungen!',
                    ],

                    'name' => 'Top-Kollektionen',
                ],
            ],
        ],

        'user' => [
            'roles' => [
                'description' => 'Benutzer mit dieser Rolle haben vollen Zugriff.',
                'name'        => 'Administrator',
            ],

            'users' => [
                'name' => 'Beispiel',
            ],
        ],
    ],

    'installer' => [
        'index' => [
            'create-administrator' => [
                'admin'            => 'Administrator',
                'bagisto'          => 'Bagisto',
                'confirm-password' => 'Passwort bestätigen',
                'email'            => 'E-Mail',
                'email-address'    => 'admin@example.com',
                'password'         => 'Passwort',
                'title'            => 'Administrator erstellen',
            ],

            'environment-configuration' => [
                'algerian-dinar'              => 'Algerischer Dinar (DZD)',
                'allowed-currencies'          => 'Zugelassene Währungen',
                'allowed-locales'             => 'Zugelassene Sprachen',
                'application-name'            => 'Anwendungsname',
                'argentine-peso'              => 'Argentinischer Peso (ARS)',
                'australian-dollar'           => 'Australischer Dollar (AUD)',
                'bagisto'                     => 'Bagisto',
                'bangladeshi-taka'            => 'Bangladeschischer Taka (BDT)',
                'brazilian-real'              => 'Brasilianischer Real (BRL)',
                'british-pound-sterling'      => 'Britisches Pfund Sterling (GBP)',
                'canadian-dollar'             => 'Kanadischer Dollar (CAD)',
                'cfa-franc-bceao'             => 'CFA-Franc BCEAO (XOF)',
                'cfa-franc-beac'              => 'CFA-Franc BEAC (XAF)',
                'chilean-peso'                => 'Chilenischer Peso (CLP)',
                'chinese-yuan'                => 'Chinesischer Yuan (CNY)',
                'colombian-peso'              => 'Kolumbianischer Peso (COP)',
                'czech-koruna'                => 'Tschechische Krone (CZK)',
                'danish-krone'                => 'Dänische Krone (DKK)',
                'database-connection'         => 'Datenbankverbindung',
                'database-hostname'           => 'Datenbank-Hostname',
                'database-name'               => 'Datenbankname',
                'database-password'           => 'Datenbankpasswort',
                'database-port'               => 'Datenbank-Port',
                'database-prefix'             => 'Datenbank-Präfix',
                'database-username'           => 'Datenbank-Benutzername',
                'default-currency'            => 'Standardwährung',
                'default-locale'              => 'Standardsprache',
                'default-timezone'            => 'Standard-Zeitzone',
                'default-url'                 => 'Standard-URL',
                'default-url-link'            => 'https://localhost',
                'egyptian-pound'              => 'Ägyptisches Pfund (EGP)',
                'euro'                        => 'Euro (EUR)',
                'fijian-dollar'               => 'Fidschi-Dollar (FJD)',
                'hong-kong-dollar'            => 'Hongkong-Dollar (HKD)',
                'hungarian-forint'            => 'Ungarischer Forint (HUF)',
                'indian-rupee'                => 'Indische Rupie (INR)',
                'indonesian-rupiah'           => 'Indonesische Rupiah (IDR)',
                'israeli-new-shekel'          => 'Israelischer Neuer Schekel (ILS)',
                'japanese-yen'                => 'Japanischer Yen (JPY)',
                'jordanian-dinar'             => 'Jordanischer Dinar (JOD)',
                'kazakhstani-tenge'           => 'Kasachischer Tenge (KZT)',
                'kuwaiti-dinar'               => 'Kuwaitischer Dinar (KWD)',
                'lebanese-pound'              => 'Libanesisches Pfund (LBP)',
                'libyan-dinar'                => 'Libyscher Dinar (LYD)',
                'malaysian-ringgit'           => 'Malaysischer Ringgit (MYR)',
                'mauritian-rupee'             => 'Mauritius-Rupie (MUR)',
                'mexican-peso'                => 'Mexikanischer Peso (MXN)',
                'moroccan-dirham'             => 'Marokkanischer Dirham (MAD)',
                'mysql'                       => 'MySQL',
                'nepalese-rupee'              => 'Nepalesische Rupie (NPR)',
                'new-taiwan-dollar'           => 'Neuer Taiwan-Dollar (TWD)',
                'new-zealand-dollar'          => 'Neuseeland-Dollar (NZD)',
                'nigerian-naira'              => 'Nigerianische Naira (NGN)',
                'norwegian-krone'             => 'Norwegische Krone (NOK)',
                'omani-rial'                  => 'Omanischer Rial (OMR)',
                'pakistani-rupee'             => 'Pakistanische Rupie (PKR)',
                'panamanian-balboa'           => 'Panamaischer Balboa (PAB)',
                'paraguayan-guarani'          => 'Paraguayischer Guarani (PYG)',
                'peruvian-nuevo-sol'          => 'Peruanischer Nuevo Sol (PEN)',
                'pgsql'                       => 'pgSQL',
                'philippine-peso'             => 'Philippinischer Peso (PHP)',
                'polish-zloty'                => 'Polnischer Zloty (PLN)',
                'qatari-rial'                 => 'Katarischer Rial (QAR)',
                'romanian-leu'                => 'Rumänischer Leu (RON)',
                'russian-ruble'               => 'Russischer Rubel (RUB)',
                'saudi-riyal'                 => 'Saudi-Riyal (SAR)',
                'select-timezone'             => 'Zeitzone auswählen',
                'singapore-dollar'            => 'Singapur-Dollar (SGD)',
                'south-african-rand'          => 'Südafrikanischer Rand (ZAR)',
                'south-korean-won'            => 'Südkoreanischer Won (KRW)',
                'sqlsrv'                      => 'SQLSRV',
                'sri-lankan-rupee'            => 'Sri-Lanka-Rupie (LKR)',
                'swedish-krona'               => 'Schwedische Krone (SEK)',
                'swiss-franc'                 => 'Schweizer Franken (CHF)',
                'thai-baht'                   => 'Thailändischer Baht (THB)',
                'title'                       => 'Store-Konfiguration',
                'tunisian-dinar'              => 'Tunesischer Dinar (TND)',
                'turkish-lira'                => 'Türkische Lira (TRY)',
                'ukrainian-hryvnia'           => 'Ukrainische Hrywnja (UAH)',
                'united-arab-emirates-dirham' => 'Vereinigte Arabische Emirate Dirham (AED)',
                'united-states-dollar'        => 'US-Dollar (USD)',
                'uzbekistani-som'             => 'Usbekischer Som (UZS)',
                'venezuelan-bolívar'          => 'Venezolanischer Bolívar (VEF)',
                'vietnamese-dong'             => 'Vietnamesischer Dong (VND)',
                'warning-message'             => 'Achtung! Die Einstellungen für die Standardsprachen des Systems sowie die Standardwährung sind dauerhaft und können nicht mehr geändert werden.',
                'zambian-kwach'               => 'Sambischer Kwacha (ZMW)',
            ],

            'installation-completed' => [
                'admin-panel'                => 'Admin-Panel',
                'bagisto-forums'             => 'Bagisto Forum',
                'customer-panel'             => 'Kundenpanel',
                'explore-bagisto-extensions' => 'Erkunden Sie Bagisto-Erweiterungen',
                'title'                      => 'Installation abgeschlossen',
                'title-info'                 => 'Bagisto wurde erfolgreich auf Ihrem System installiert.',
            ],

            'installation-processing' => [
                'title'            => 'Installation',
                'bagisto-info'     => 'Erstellung der Datenbanktabellen, dies kann einige Momente dauern',
                'bagisto'          => 'Installation Bagisto',
            ],

            'ready-for-installation' => [
                'create-databsae-table'   => 'Datenbanktabelle erstellen',
                'install'                 => 'Installation',
                'install-info'            => 'Bagisto zur Installation',
                'install-info-button'     => 'Klicke auf die Schaltfläche unten, um',
                'populate-database-table' => 'Datenbanktabellen füllen',
                'start-installation'      => 'Installation starten',
                'title'                   => 'Bereit zur Installation',
            ],

            'start' => [
                'locale'        => 'Lokale',
                'main'          => 'Start',
                'select-locale' => 'Wähle Lokale',
                'title'         => 'Deine Bagisto-Installation',
                'welcome-title' => 'Willkommen bei Bagisto 2.0.',
            ],

            'server-requirements' => [
                'calendar'    => 'Kalender',
                'ctype'       => 'cType',
                'curl'        => 'cURL',
                'dom'         => 'dom',
                'fileinfo'    => 'Dateiinfo',
                'filter'      => 'Filter',
                'gd'          => 'GD',
                'hash'        => 'Hash',
                'intl'        => 'intl',
                'json'        => 'JSON',
                'mbstring'    => 'mbstring',
                'openssl'     => 'openssl',
                'pcre'        => 'pcre',
                'pdo'         => 'pdo',
                'php'         => 'PHP',
                'php-version' => '8.1 oder höher',
                'session'     => 'Sitzung',
                'title'       => 'Serveranforderungen',
                'tokenizer'   => 'Tokenizer',
                'xml'         => 'XML',
            ],

            'arabic'                   => 'Arabisch',
            'back'                     => 'Zurück',
            'bagisto'                  => 'Bagisto',
            'bagisto-info'             => 'Ein Gemeinschaftsprojekt von',
            'bagisto-logo'             => 'Bagisto Logo',
            'bengali'                  => 'Bengalisch',
            'chinese'                  => 'Chinesisch',
            'continue'                 => 'Weiter',
            'dutch'                    => 'Niederländisch',
            'english'                  => 'Englisch',
            'french'                   => 'Französisch',
            'german'                   => 'Deutsch',
            'hebrew'                   => 'Hebräisch',
            'hindi'                    => 'Hindi',
            'installation-description' => 'Die Installation von Bagisto umfasst in der Regel mehrere Schritte. Hier ist ein grober Überblick über den Installationsprozess für Bagisto:',
            'installation-info'        => 'Wir freuen uns, Sie hier zu sehen!',
            'installation-title'       => 'Willkommen zur Installation',
            'italian'                  => 'Italienisch',
            'japanese'                 => 'Japanisch',
            'persian'                  => 'Persisch',
            'polish'                   => 'Polnisch',
            'portuguese'               => 'Brasilianisches Portugiesisch',
            'russian'                  => 'Russisch',
            'sinhala'                  => 'Singhalesisch',
            'spanish'                  => 'Spanisch',
            'title'                    => 'Bagisto Installer',
            'turkish'                  => 'Türkisch',
            'ukrainian'                => 'Ukrainisch',
            'webkul'                   => 'Webkul',
        ],
    ],
];
