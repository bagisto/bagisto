<?php

return [
    'seeders'   => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'Standard',
            ],

            'attribute-groups'   => [
                'description'       => 'Beschreibung',
                'general'           => 'Allgemein',
                'inventories'       => 'Bestände',
                'meta-description'  => 'Meta-Beschreibung',
                'price'             => 'Preis',
                'settings'          => 'Einstellungen',
                'shipping'          => 'Versand',
            ],

            'attributes'         => [
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
                'special-price-from'   => 'Sonderpreis Von',
                'special-price-to'     => 'Sonderpreis Bis',
                'special-price'        => 'Sonderpreis',
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

        'category'  => [
            'categories' => [
                'description' => 'Beschreibung der Wurzelkategorie',
                'name'        => 'Wurzel',
            ],
        ],

        'cms'       => [
            'pages' => [
                'about-us'         => [
                    'content' => 'Über uns Seitensinhalt',
                    'title'   => 'Über uns',
                ],

                'contact-us'       => [
                    'content' => 'Kontaktiere uns Seitensinhalt',
                    'title'   => 'Kontaktiere uns',
                ],

                'customer-service' => [
                    'content' => 'Kundenservice Seitensinhalt',
                    'title'   => 'Kundenservice',
                ],

                'payment-policy'   => [
                    'content' => 'Zahlungsrichtlinie Seitensinhalt',
                    'title'   => 'Zahlungsrichtlinie',
                ],

                'privacy-policy'   => [
                    'content' => 'Datenschutzrichtlinie Seitensinhalt',
                    'title'   => 'Datenschutzrichtlinie',
                ],

                'refund-policy'    => [
                    'content' => 'Rückgaberecht Seitensinhalt',
                    'title'   => 'Rückgaberecht',
                ],

                'return-policy'    => [
                    'content' => 'Rückgaberecht Seitensinhalt',
                    'title'   => 'Rückgaberecht',
                ],

                'shipping-policy'  => [
                    'content' => 'Versandrichtlinie Seitensinhalt',
                    'title'   => 'Versandrichtlinie',
                ],

                'terms-conditions' => [
                    'content' => 'Nutzungsbedingungen Seitensinhalt',
                    'title'   => 'Nutzungsbedingungen',
                ],

                'terms-of-use'     => [
                    'content' => 'Nutzungsbedingungen Seitensinhalt',
                    'title'   => 'Nutzungsbedingungen',
                ],

                'whats-new'        => [
                    'content' => 'Inhaltsseite für Neuigkeiten',
                    'title'   => 'Was gibt es Neues',
                ],
            ],
        ],

        'core'      => [
            'channels'   => [
                'meta-description' => 'Demo-Shop Meta-Beschreibung',
                'meta-keywords'    => 'Demo-Shop Meta-Stichwörter',
                'meta-title'       => 'Demo-Shop',
                'name'             => 'Standard',
            ],

            'currencies' => [
                'AED' => 'Dirham',
                'AFN' => 'Israelischer Schekel',
                'CNY' => 'Chinesischer Yuan',
                'EUR' => 'EURO',
                'GBP' => 'Pfund Sterling',
                'INR' => 'Indische Rupie',
                'IRR' => 'Iranischer Rial',
                'JPY' => 'Japanischer Yen',
                'RUB' => 'Russischer Rubel',
                'SAR' => 'Saudi-Riyal',
                'TRY' => 'Türkische Lira',
                'UAH' => 'Ukrainische Hrywnja',
                'USD' => 'US-Dollar',
            ],

            'locales'    => [
                'ar'    => 'Arabisch',
                'bn'    => 'Bengali',
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

        'customer'  => [
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

        'shop'      => [
            'theme-customizations' => [
                'all-products'           => [
                    'name'    => 'Alle Produkte',

                    'options' => [
                        'title' => 'Alle Produkte',
                    ],
                ],

                'bold-collections'       => [
                    'content' => [
                        'btn-title'   => 'Alle anzeigen',
                        'description' => 'Einführung unserer neuen fetten Kollektionen! Heben Sie Ihren Stil mit gewagten Designs und lebhaften Statements hervor. Erkunden Sie auffällige Muster und kräftige Farben, die Ihre Garderobe neu definieren. Machen Sie sich bereit, das Außergewöhnliche zu umarmen!',
                        'title'       => 'Machen Sie sich bereit für unsere neuen fetten Kollektionen!',
                    ],

                    'name'    => 'Fette Kollektionen',
                ],

                'categories-collections' => [
                    'name' => 'Kategorienkollektionen',
                ],

                'footer-links'           => [
                    'name'    => 'Fußzeilenlinks',

                    'options' => [
                        'about-us'         => 'Über uns',
                        'contact-us'       => 'Kontaktiere uns',
                        'customer-service' => 'Kundenservice',
                        'payment-policy'   => 'Zahlungsrichtlinie',
                        'privacy-policy'   => 'Datenschutzrichtlinie',
                        'refund-policy'    => 'Erstattungsrichtlinie',
                        'return-policy'    => 'Rückgaberichtlinie',
                        'shipping-policy'  => 'Versandrichtlinie',
                        'terms-conditions' => 'Allgemeine Geschäftsbedingungen',
                        'terms-of-use'     => 'Nutzungsbedingungen',
                        'whats-new'        => 'Was gibt es Neues',
                    ],
                ],

                'featured-collections'   => [
                    'name'    => 'Hervorgehobene Kollektionen',

                    'options' => [
                        'title' => 'Hervorgehobene Produkte',
                    ],
                ],

                'game-container'         => [
                    'content' => [
                        'sub-title-1' => 'Unsere Kollektionen',
                        'sub-title-2' => 'Unsere Kollektionen',
                        'title'       => 'Das Spiel mit unseren neuen Ergänzungen!',
                    ],

                    'name'    => 'Spielercontainer',
                ],

                'image-carousel'         => [
                    'name'    => 'Bildkarussell',

                    'sliders' => [
                        'title' => 'Bereit für die neue Kollektion',
                    ],
                ],

                'new-products'           => [
                    'name'    => 'Neue Produkte',

                    'options' => [
                        'title' => 'Neue Produkte',
                    ],
                ],

                'offer-information'      => [
                    'content' => [
                        'title' => 'Bis zu 40% Rabatt auf Ihre erste Bestellung JETZT KAUFEN',
                    ],

                    'name'    => 'Angebotsinformation',
                ],

                'services-content'       => [
                    'description' => [
                        'emi-available-info'   => 'Keine Kosten EMI verfügbar auf allen gängigen Kreditkarten',
                        'free-shipping-info'   => 'Kostenloser Versand für alle Bestellungen',
                        'product-replace-info' => 'Einfacher Produktwechsel möglich!',
                        'time-support-info'    => 'Dedizierte 24/7 Unterstützung über Chat und E-Mail',
                    ],

                    'name'        => 'Services Content',

                    'title'       => [
                        'emi-available'   => 'EMI verfügbar',
                        'free-shipping'   => 'Kostenloser Versand',
                        'product-replace' => 'Produkt austauschen',
                        'time-support'    => '24/7 Unterstützung',
                    ],
                ],

                'top-collections'        => [
                    'content' => [
                        'sub-title-1' => 'Unsere Kollektionen',
                        'sub-title-2' => 'Unsere Kollektionen',
                        'sub-title-3' => 'Unsere Kollektionen',
                        'sub-title-4' => 'Unsere Kollektionen',
                        'sub-title-5' => 'Unsere Kollektionen',
                        'sub-title-6' => 'Unsere Kollektionen',
                        'title'       => 'Das Spiel mit unseren neuen Ergänzungen!',
                    ],

                    'name'    => 'Top-Kollektionen',
                ],
            ],
        ],

        'user'      => [
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
            'create-administrator'      => [
                'admin'            => 'Administrator',
                'bagisto'          => 'Bagisto',
                'confirm-password' => 'Passwort bestätigen',
                'email-address'    => 'admin@example.com',
                'email'            => 'E-Mail',
                'password'         => 'Passwort',
                'title'            => 'Administrator erstellen',
            ],

            'environment-configuration' => [
                'allowed-currencies'  => 'Erlaubte Währungen',
                'allowed-locales'     => 'Erlaubte Sprachen',
                'application-name'    => 'Anwendungsname',
                'bagisto'             => 'Bagisto',
                'chinese-yuan'        => 'Chinesischer Yuan (CNY)',
                'database-connection' => 'Datenbankverbindung',
                'database-hostname'   => 'Datenbank-Hostname',
                'database-name'       => 'Datenbankname',
                'database-password'   => 'Datenbank-Passwort',
                'database-port'       => 'Datenbank-Port',
                'database-prefix'     => 'Datenbank-Präfix',
                'database-username'   => 'Datenbank-Benutzername',
                'default-currency'    => 'Standardwährung',
                'default-locale'      => 'Standard-Sprachumgebung',
                'default-timezone'    => 'Standard-Zeitzone',
                'default-url-link'    => 'https://localhost',
                'default-url'         => 'Standard-URL',
                'dirham'              => 'Dirham (AED)',
                'euro'                => 'Euro (EUR)',
                'iranian'             => 'Iranischer Rial (IRR)',
                'israeli'             => 'Israelischer Schekel (AFN)',
                'japanese-yen'        => 'Japanischer Yen (JPY)',
                'mysql'               => 'Mysql',
                'pgsql'               => 'pgSQL',
                'pound'               => 'Pfund Sterling (GBP)',
                'rupee'               => 'Indische Rupie (INR)',
                'russian-ruble'       => 'Russischer Rubel (RUB)',
                'saudi'               => 'Saudi-Riyal (SAR)',
                'sqlsrv'              => 'SQLSRV',
                'select-timezone'     => 'Wähle Zeitzone aus',
                'title'               => 'Umgebungskonfiguration',
                'turkish-lira'        => 'Türkische Lira (TRY)',
                'ukrainian-hryvnia'   => 'Ukrainische Hrywnja (UAH)',
                'usd'                 => 'US-Dollar (USD)',
                'warning-message'     => 'Vorsicht! Die Einstellungen für Ihre Standardsystemsprachen sowie die Standardsystemwährung sind dauerhaft und können niemals wieder geändert werden.',
            ],

            'installation-completed'    => [
                'admin-panel'                => 'Admin-Panel',
                'bagisto-forums'             => 'Bagisto Forum',
                'customer-panel'             => 'Kundenpanel',
                'explore-bagisto-extensions' => 'Erkunden Sie Bagisto-Erweiterungen',
                'title-info'                 => 'Bagisto wurde erfolgreich auf Ihrem System installiert.',
                'title'                      => 'Installation abgeschlossen',
            ],

            'installation-processing'   => [
                'bagisto-info'     => 'Erstellung der Datenbanktabellen, dies kann einige Momente dauern',
                'bagisto'          => 'Installation Bagisto',
                'title'            => 'Installation',
            ],

            'ready-for-installation'    => [
                'create-databsae-table'   => 'Datenbanktabelle erstellen',
                'install-info-button'     => 'Klicke auf die Schaltfläche unten, um',
                'install-info'            => 'Bagisto zur Installation',
                'install'                 => 'Installation',
                'populate-database-table' => 'Datenbanktabellen füllen',
                'start-installation'      => 'Installation starten',
                'title'                   => 'Bereit zur Installation',
            ],

            'start'                     => [
                'locale'        => 'Lokale',
                'main'          => 'Start',
                'select-locale' => 'Wähle Lokale',
                'title'         => 'Deine Bagisto-Installation',
                'welcome-title' => 'Willkommen bei Bagisto 2.0.',
            ],

            'server-requirements'       => [
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
                'php-version' => '8.1 oder höher',
                'php'         => 'PHP',
                'session'     => 'Sitzung',
                'title'       => 'Serveranforderungen',
                'tokenizer'   => 'Tokenizer',
                'xml'         => 'XML',
            ],

            'arabic'                    => 'Arabisch',
            'back'                      => 'Zurück',
            'bagisto-info'              => 'Ein Gemeinschaftsprojekt von',
            'bagisto-logo'              => 'Bagisto Logo',
            'bagisto'                   => 'Bagisto',
            'bengali'                   => 'Bengalisch',
            'chinese'                   => 'Chinesisch',
            'continue'                  => 'Weiter',
            'dutch'                     => 'Niederländisch',
            'english'                   => 'Englisch',
            'french'                    => 'Französisch',
            'german'                    => 'Deutsch',
            'hebrew'                    => 'Hebräisch',
            'hindi'                     => 'Hindi',
            'installation-description'  => 'Die Installation von Bagisto umfasst in der Regel mehrere Schritte. Hier ist ein grober Überblick über den Installationsprozess für Bagisto:',
            'installation-info'         => 'Wir freuen uns, Sie hier zu sehen!',
            'installation-title'        => 'Willkommen zur Installation',
            'italian'                   => 'Italienisch',
            'japanese'                  => 'Japanisch',
            'persian'                   => 'Persisch',
            'polish'                    => 'Polnisch',
            'portuguese'                => 'Brasilianisches Portugiesisch',
            'russian'                   => 'Russisch',
            'save-configuration'        => 'Konfiguration speichern',
            'sinhala'                   => 'Singhalesisch',
            'skip'                      => 'Überspringen',
            'spanish'                   => 'Spanisch',
            'title'                     => 'Bagisto Installer',
            'turkish'                   => 'Türkisch',
            'ukrainian'                 => 'Ukrainisch',
            'webkul'                    => 'Webkul',
        ],
    ],
];
