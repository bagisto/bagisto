<?php

return [
    'seeders' => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'Standard',
            ],

            'attribute-groups' => [
                'description'       => 'Beschreibung',
                'general'           => 'Allgemein',
                'inventories'       => 'Bestände',
                'meta-description'  => 'Meta-Beschreibung',
                'price'             => 'Preis',
                'shipping'          => 'Versand',
                'settings'          => 'Einstellungen',
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
                'meta-title'           => 'Meta-Titel',
                'meta-keywords'        => 'Meta-Schlüsselwörter',
                'meta-description'     => 'Meta-Beschreibung',
                'manage-stock'         => 'Bestand verwalten',
                'new'                  => 'Neu',
                'name'                 => 'Name',
                'product-number'       => 'Produktnummer',
                'price'                => 'Preis',
                'sku'                  => 'Artikelnummer (SKU)',
                'status'               => 'Status',
                'short-description'    => 'Kurzbeschreibung',
                'special-price'        => 'Sonderpreis',
                'special-price-from'   => 'Sonderpreis Von',
                'special-price-to'     => 'Sonderpreis Bis',
                'size'                 => 'Größe',
                'tax-category'         => 'Steuerkategorie',
                'url-key'              => 'URL-Schlüssel',
                'visible-individually' => 'Einzeln sichtbar',
                'width'                => 'Breite',
                'weight'               => 'Gewicht',
            ],

            'attribute-options' => [
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

                'refund-policy' => [
                    'content' => 'Rückgaberecht Seitensinhalt',
                    'title'   => 'Rückgaberecht',
                ],

                'return-policy' => [
                    'content' => 'Rückgaberecht Seitensinhalt',
                    'title'   => 'Rückgaberecht',
                ],

                'terms-conditions' => [
                    'content' => 'Nutzungsbedingungen Seitensinhalt',
                    'title'   => 'Nutzungsbedingungen',
                ],

                'terms-of-use' => [
                    'content' => 'Nutzungsbedingungen Seitensinhalt',
                    'title'   => 'Nutzungsbedingungen',
                ],

                'contact-us' => [
                    'content' => 'Kontaktiere uns Seitensinhalt',
                    'title'   => 'Kontaktiere uns',
                ],

                'customer-service' => [
                    'content' => 'Kundenservice Seitensinhalt',
                    'title'   => 'Kundenservice',
                ],

                'whats-new' => [
                    'content' => 'Inhaltsseite für Neuigkeiten',
                    'title'   => 'Was gibt es Neues',
                ],

                'payment-policy' => [
                    'content' => 'Zahlungsrichtlinie Seitensinhalt',
                    'title'   => 'Zahlungsrichtlinie',
                ],

                'shipping-policy' => [
                    'content' => 'Versandrichtlinie Seitensinhalt',
                    'title'   => 'Versandrichtlinie',
                ],

                'privacy-policy' => [
                    'content' => 'Datenschutzrichtlinie Seitensinhalt',
                    'title'   => 'Datenschutzrichtlinie',
                ],
            ],
        ],

        'core' => [
            'channels' => [
                'meta-title'       => 'Demo-Shop',
                'meta-keywords'    => 'Demo-Shop Meta-Stichwörter',
                'meta-description' => 'Demo-Shop Meta-Beschreibung',
                'name'             => 'Standard',
            ],

            'currencies' => [
                'CNY' => 'Chinesischer Yuan',
                'AED' => 'Dirham',
                'EUR' => 'EURO',
                'INR' => 'Indische Rupie',
                'IRR' => 'Iranischer Rial',
                'ILS' => 'Israelischer Schekel',
                'JPY' => 'Japanischer Yen',
                'GBP' => 'Pfund Sterling',
                'RUB' => 'Russischer Rubel',
                'SAR' => 'Saudi-Riyal',
                'TRY' => 'Türkische Lira',
                'USD' => 'US-Dollar',
                'UAH' => 'Ukrainische Hrywnja',
            ],

            'locales' => [
                'ar'    => 'Arabisch',
                'bn'    => 'Bengali',
                'de'    => 'Deutsch',
                'es'    => 'Spanisch',
                'en'    => 'Englisch',
                'fr'    => 'Französisch',
                'fa'    => 'Persisch',
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
                'guest'     => 'Gast',
                'general'   => 'Allgemein',
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
                'image-carousel' => [
                    'name'  => 'Bildkarussell',

                    'sliders' => [
                        'title' => 'Bereit für die neue Kollektion',
                    ],
                ],

                'offer-information' => [
                    'name' => 'Angebotsinformation',

                    'content' => [
                        'title' => 'Bis zu 40% Rabatt auf Ihre erste Bestellung JETZT KAUFEN',
                    ],
                ],

                'categories-collections' => [
                    'name' => 'Kategorienkollektionen',
                ],

                'new-products' => [
                    'name' => 'Neue Produkte',

                    'options' => [
                        'title' => 'Neue Produkte',
                    ],
                ],

                'top-collections' => [
                    'name' => 'Top-Kollektionen',

                    'content' => [
                        'sub-title-1' => 'Unsere Kollektionen',
                        'sub-title-2' => 'Unsere Kollektionen',
                        'sub-title-3' => 'Unsere Kollektionen',
                        'sub-title-4' => 'Unsere Kollektionen',
                        'sub-title-5' => 'Unsere Kollektionen',
                        'sub-title-6' => 'Unsere Kollektionen',
                        'title'       => 'Das Spiel mit unseren neuen Ergänzungen!',
                    ],
                ],

                'bold-collections' => [
                    'name' => 'Fette Kollektionen',

                    'content' => [
                        'btn-title'   => 'Alle anzeigen',
                        'description' => 'Einführung unserer neuen fetten Kollektionen! Heben Sie Ihren Stil mit gewagten Designs und lebhaften Statements hervor. Erkunden Sie auffällige Muster und kräftige Farben, die Ihre Garderobe neu definieren. Machen Sie sich bereit, das Außergewöhnliche zu umarmen!',
                        'title'       => 'Machen Sie sich bereit für unsere neuen fetten Kollektionen!',
                    ],
                ],

                'featured-collections' => [
                    'name' => 'Hervorgehobene Kollektionen',

                    'options' => [
                        'title' => 'Hervorgehobene Produkte',
                    ],
                ],

                'game-container' => [
                    'name' => 'Spielercontainer',

                    'content' => [
                        'sub-title-1' => 'Unsere Kollektionen',
                        'sub-title-2' => 'Unsere Kollektionen',
                        'title'       => 'Das Spiel mit unseren neuen Ergänzungen!',
                    ],
                ],

                'all-products' => [
                    'name' => 'Alle Produkte',

                    'options' => [
                        'title' => 'Alle Produkte',
                    ],
                ],

                'footer-links' => [
                    'name' => 'Fußzeilenlinks',

                    'options' => [
                        'about-us'         => 'Über uns',
                        'contact-us'       => 'Kontaktiere uns',
                        'customer-service' => 'Kundenservice',
                        'privacy-policy'   => 'Datenschutzrichtlinie',
                        'payment-policy'   => 'Zahlungsrichtlinie',
                        'return-policy'    => 'Rückgaberichtlinie',
                        'refund-policy'    => 'Erstattungsrichtlinie',
                        'shipping-policy'  => 'Versandrichtlinie',
                        'terms-of-use'     => 'Nutzungsbedingungen',
                        'terms-conditions' => 'Allgemeine Geschäftsbedingungen',
                        'whats-new'        => 'Was gibt es Neues',
                    ],
                ],
            ],
        ],

        'user' => [
            'users' => [
                'name' => 'Beispiel',
            ],

            'roles' => [
                'description' => 'Benutzer mit dieser Rolle haben vollen Zugriff.',
                'name'        => 'Administrator',
            ],
        ],
    ],
];
