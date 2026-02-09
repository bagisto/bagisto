<?php

return [
    'seeders' => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'Standard',
            ],

            'attribute-groups' => [
                'description' => 'Beschreibung',
                'general' => 'Allgemein',
                'inventories' => 'Bestände',
                'meta-description' => 'Meta-Beschreibung',
                'price' => 'Preis',
                'rma' => 'RMA',
                'settings' => 'Einstellungen',
                'shipping' => 'Versand',
            ],

            'attributes' => [
                'allow-rma' => 'RMA erlauben',
                'brand' => 'Marke',
                'color' => 'Farbe',
                'cost' => 'Kosten',
                'description' => 'Beschreibung',
                'featured' => 'Hervorgehoben',
                'guest-checkout' => 'Gastkasse',
                'height' => 'Höhe',
                'length' => 'Länge',
                'manage-stock' => 'Bestand verwalten',
                'meta-description' => 'Meta-Beschreibung',
                'meta-keywords' => 'Meta-Schlüsselwörter',
                'meta-title' => 'Meta-Titel',
                'name' => 'Name',
                'new' => 'Neu',
                'price' => 'Preis',
                'product-number' => 'Produktnummer',
                'rma-rules' => 'RMA-Regeln',
                'short-description' => 'Kurzbeschreibung',
                'size' => 'Größe',
                'sku' => 'Artikelnummer (SKU)',
                'special-price' => 'Sonderpreis',
                'special-price-from' => 'Sonderpreis Von',
                'special-price-to' => 'Sonderpreis Bis',
                'status' => 'Status',
                'tax-category' => 'Steuerkategorie',
                'url-key' => 'URL-Schlüssel',
                'visible-individually' => 'Einzeln sichtbar',
                'weight' => 'Gewicht',
                'width' => 'Breite',
            ],

            'attribute-options' => [
                'black' => 'Schwarz',
                'green' => 'Grün',
                'l' => 'L',
                'm' => 'M',
                'red' => 'Rot',
                's' => 'S',
                'white' => 'Weiß',
                'xl' => 'XL',
                'yellow' => 'Gelb',
            ],
        ],

        'category' => [
            'categories' => [
                'description' => 'Beschreibung der Wurzelkategorie',
                'name' => 'Wurzel',
            ],
        ],

        'cms' => [
            'pages' => [
                'about-us' => [
                    'content' => 'Über uns Seitensinhalt',
                    'title' => 'Über uns',
                ],

                'contact-us' => [
                    'content' => 'Kontaktiere uns Seitensinhalt',
                    'title' => 'Kontaktiere uns',
                ],

                'customer-service' => [
                    'content' => 'Kundenservice Seitensinhalt',
                    'title' => 'Kundenservice',
                ],

                'payment-policy' => [
                    'content' => 'Zahlungsrichtlinie Seitensinhalt',
                    'title' => 'Zahlungsrichtlinie',
                ],

                'privacy-policy' => [
                    'content' => 'Datenschutzrichtlinie Seitensinhalt',
                    'title' => 'Datenschutzrichtlinie',
                ],

                'refund-policy' => [
                    'content' => 'Rückgaberecht Seitensinhalt',
                    'title' => 'Rückgaberecht',
                ],

                'return-policy' => [
                    'content' => 'Rückgaberecht Seitensinhalt',
                    'title' => 'Rückgaberecht',
                ],

                'shipping-policy' => [
                    'content' => 'Versandrichtlinie Seitensinhalt',
                    'title' => 'Versandrichtlinie',
                ],

                'terms-conditions' => [
                    'content' => 'Nutzungsbedingungen Seitensinhalt',
                    'title' => 'Nutzungsbedingungen',
                ],

                'terms-of-use' => [
                    'content' => 'Nutzungsbedingungen Seitensinhalt',
                    'title' => 'Nutzungsbedingungen',
                ],

                'whats-new' => [
                    'content' => 'Inhaltsseite für Neuigkeiten',
                    'title' => 'Was gibt es Neues',
                ],
            ],
        ],

        'core' => [
            'channels' => [
                'meta-description' => 'Demo-Shop Meta-Beschreibung',
                'meta-keywords' => 'Demo-Shop Meta-Stichwörter',
                'meta-title' => 'Demo-Shop',
                'name' => 'Standard',
            ],

            'currencies' => [
                'AED' => 'Vereinigte Arabische Emirate Dirham',
                'ARS' => 'Argentinischer Peso',
                'AUD' => 'Australischer Dollar',
                'BDT' => 'Bangladeschischer Taka',
                'BHD' => 'Bahreinse Dinar',
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
                'ar' => 'Arabisch',
                'bn' => 'Bengalisch',
                'ca' => 'Katalanisch',
                'de' => 'Deutsch',
                'en' => 'Englisch',
                'es' => 'Spanisch',
                'fa' => 'Persisch',
                'fr' => 'Französisch',
                'he' => 'Hebräisch',
                'hi_IN' => 'Hindi',
                'id' => 'Indonesisch',
                'it' => 'Italienisch',
                'ja' => 'Japanisch',
                'nl' => 'Niederländisch',
                'pl' => 'Polnisch',
                'pt_BR' => 'Brasilianisches Portugiesisch',
                'ru' => 'Russisch',
                'sin' => 'Sinhala',
                'tr' => 'Türkisch',
                'uk' => 'Ukrainisch',
                'zh_CN' => 'Chinesisch',
            ],
        ],

        'customer' => [
            'customer-groups' => [
                'general' => 'Allgemein',
                'guest' => 'Gast',
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
                        'btn-title' => 'Kollektionen anzeigen',
                        'description' => 'Einführung unserer neuen fetten Kollektionen! Heben Sie Ihren Stil mit gewagten Designs und lebhaften Statements hervor. Erkunden Sie auffällige Muster und kräftige Farben, die Ihre Garderobe neu definieren. Machen Sie sich bereit, das Außergewöhnliche zu umarmen!',
                        'title' => 'Machen Sie sich bereit für unsere neuen fetten Kollektionen!',
                    ],

                    'name' => 'Fette Kollektionen',
                ],

                'bold-collections-2' => [
                    'content' => [
                        'btn-title' => 'Kollektionen anzeigen',
                        'description' => 'Unsere Bold-Kollektionen sind hier, um Ihre Garderobe mit furchtlosen Designs und auffälligen, lebhaften Farben neu zu definieren. Von gewagten Mustern bis hin zu kraftvollen Farbtönen ist dies Ihre Chance, aus dem Gewöhnlichen auszubrechen und ins Außergewöhnliche einzutreten.',
                        'title' => 'Entfesseln Sie Ihre Kühnheit mit unserer neuen Kollektion!',
                    ],

                    'name' => 'Bold-Kollektionen',
                ],

                'booking-products' => [
                    'name' => 'Buchungsprodukte',

                    'options' => [
                        'title' => 'Tickets buchen',
                    ],
                ],

                'categories-collections' => [
                    'name' => 'Kategorienkollektionen',
                ],

                'featured-collections' => [
                    'name' => 'Hervorgehobene Kollektionen',

                    'options' => [
                        'title' => 'Hervorgehobene Produkte',
                    ],
                ],

                'footer-links' => [
                    'name' => 'Fußzeilenlinks',

                    'options' => [
                        'about-us' => 'Über uns',
                        'contact-us' => 'Kontaktiere uns',
                        'customer-service' => 'Kundenservice',
                        'payment-policy' => 'Zahlungsrichtlinie',
                        'privacy-policy' => 'Datenschutzrichtlinie',
                        'refund-policy' => 'Rückgaberichtlinie',
                        'return-policy' => 'Rückgaberichtlinie',
                        'shipping-policy' => 'Versandrichtlinie',
                        'terms-conditions' => 'Allgemeine Geschäftsbedingungen',
                        'terms-of-use' => 'Nutzungsbedingungen',
                        'whats-new' => 'Was gibt es Neues',
                    ],
                ],

                'game-container' => [
                    'content' => [
                        'sub-title-1' => 'Unsere Kollektionen',
                        'sub-title-2' => 'Unsere Kollektionen',
                        'title' => 'Das Spiel mit unseren neuen Ergänzungen!',
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
                        'emi-available-info' => 'Keine Kosten EMI verfügbar auf allen gängigen Kreditkarten',
                        'free-shipping-info' => 'Kostenloser Versand für alle Bestellungen',
                        'product-replace-info' => 'Einfacher Produktwechsel möglich!',
                        'time-support-info' => 'Dedizierte 24/7 Unterstützung über Chat und E-Mail',
                    ],

                    'name' => 'Services Content',

                    'title' => [
                        'emi-available' => 'EMI verfügbar',
                        'free-shipping' => 'Kostenloser Versand',
                        'product-replace' => 'Produkt austauschen',
                        'time-support' => '24/7 Unterstützung',
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
                        'title' => 'Das Spiel mit unseren neuen Ergänzungen!',
                    ],

                    'name' => 'Top-Kollektionen',
                ],
            ],
        ],

        'user' => [
            'roles' => [
                'description' => 'Benutzer mit dieser Rolle haben vollen Zugriff.',
                'name' => 'Administrator',
            ],

            'users' => [
                'name' => 'Beispiel',
            ],
        ],

        'sample-categories' => [
            'category-translation' => [
                '2' => [
                    'description' => '<p>Herren</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Herren',
                    'slug' => 'mens',
                    'url-path' => 'men',
                ],

                '3' => [
                    'description' => '<p>Kinder</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Kinder',
                    'slug' => 'kids',
                    'url-path' => 'kids',
                ],

                '4' => [
                    'description' => '<p>Damen</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Damen',
                    'slug' => 'womens',
                    'url-path' => 'woman',
                ],

                '5' => [
                    'description' => '<p>Formelle Kleidung</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Formelle Kleidung',
                    'slug' => 'formal-wear-men',
                    'url-path' => 'men/formal-wear-men',
                ],

                '6' => [
                    'description' => '<p>Freizeitkleidung</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Freizeitkleidung',
                    'slug' => 'casual-wear-men',
                    'url-path' => 'men/casual-wear-men',
                ],

                '7' => [
                    'description' => '<p>Sportbekleidung</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Sportbekleidung',
                    'slug' => 'active-wear',
                    'url-path' => 'men/active-wear',
                ],

                '8' => [
                    'description' => '<p>Schuhe</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Schuhe',
                    'slug' => 'footwear',
                    'url-path' => 'men/footwear',
                ],

                '9' => [
                    'description' => '<p><span>Formelle Kleidung</span></p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Formelle Kleidung',
                    'slug' => 'formal-wear-female',
                    'url-path' => 'woman/formal-wear-female',
                ],

                '10' => [
                    'description' => '<p>Freizeitkleidung</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Freizeitkleidung',
                    'slug' => 'casual-wear-female',
                    'url-path' => 'woman/casual-wear-female',
                ],

                '11' => [
                    'description' => '<p>Sportbekleidung</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Sportbekleidung',
                    'slug' => 'active-wear-female',
                    'url-path' => 'woman/active-wear-female',
                ],

                '12' => [
                    'description' => '<p>Schuhe</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Schuhe',
                    'slug' => 'footwear-female',
                    'url-path' => 'woman/footwear-female',
                ],

                '13' => [
                    'description' => '<p>Mädchenkleidung</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => 'Mädchenkleidung',
                    'name' => 'Mädchenkleidung',
                    'slug' => 'girls-clothing',
                    'url-path' => 'kids/girls-clothing',
                ],

                '14' => [
                    'description' => '<p>Jungenkleidung</p>',
                    'meta-description' => 'Jungenmode',
                    'meta-keywords' => '',
                    'meta-title' => 'Jungenkleidung',
                    'name' => 'Jungenkleidung',
                    'slug' => 'boys-clothing',
                    'url-path' => 'kids/boys-clothing',
                ],

                '15' => [
                    'description' => '<p>Mädchenschuhe</p>',
                    'meta-description' => 'Modische Mädchenschuhe Kollektion',
                    'meta-keywords' => '',
                    'meta-title' => 'Mädchenschuhe',
                    'name' => 'Mädchenschuhe',
                    'slug' => 'girls-footwear',
                    'url-path' => 'kids/girls-footwear',
                ],

                '16' => [
                    'description' => '<p>Jungenschuhe</p>',
                    'meta-description' => 'Stilvolle Jungenschuhe Kollektion',
                    'meta-keywords' => '',
                    'meta-title' => 'Jungenschuhe',
                    'name' => 'Jungenschuhe',
                    'slug' => 'boys-footwear',
                    'url-path' => 'kids/boys-footwear',
                ],

                '17' => [
                    'description' => '<p>Wellness</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Wellness',
                    'slug' => 'wellness',
                    'url-path' => 'wellness',
                ],

                '18' => [
                    'description' => '<p>Herunterladbare Yoga-Tutorials</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Herunterladbare Yoga-Tutorials',
                    'slug' => 'downloadable-yoga-tutorial',
                    'url-path' => 'wellness/downloadable-yoga-tutorial',
                ],

                '19' => [
                    'description' => '<p>E-Books Sammlung</p>',
                    'meta-description' => 'E-Books Sammlung',
                    'meta-keywords' => '',
                    'meta-title' => 'E-Books Sammlung',
                    'name' => 'E-Books',
                    'slug' => 'e-books',
                    'url-path' => 'wellness/e-books',
                ],

                '20' => [
                    'description' => '<p>Kinopass</p>',
                    'meta-description' => 'Tauchen Sie ein in die Magie von 10 Filmen pro Monat ohne zusätzliche Kosten. Landesweit gültig ohne Sperrdaten, bietet dieser Pass exklusive Vorteile und Rabatte.',
                    'meta-keywords' => '',
                    'meta-title' => 'CineXperience Monatlicher Kinopass',
                    'name' => 'Kinopass',
                    'slug' => 'movie-pass',
                    'url-path' => 'wellness/movie-pass',
                ],

                '21' => [
                    'description' => '<p>Verwalten und verkaufen Sie Ihre buchungsbasierten Produkte einfach mit unserem nahtlosen Buchungssystem. Ob Sie Termine, Vermietungen, Veranstaltungen oder Reservierungen anbieten, unsere Lösung sorgt für ein reibungsloses Erlebnis für Unternehmen und Kunden.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Buchungen',
                    'slug' => 'bookings',
                    'url-path' => '',
                ],

                '22' => [
                    'description' => '<p>Terminbuchung ermöglicht es Kunden, Zeitfenster für Dienstleistungen oder Beratungen bei Unternehmen oder Fachleuten zu planen.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Terminbuchung',
                    'slug' => 'appointment-booking',
                    'url-path' => '',
                ],

                '23' => [
                    'description' => '<p>Veranstaltungsbuchung ermöglicht es Einzelpersonen oder Gruppen, sich für öffentliche oder private Veranstaltungen wie Konzerte, Workshops, Konferenzen oder Partys anzumelden oder Plätze zu reservieren.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Veranstaltungsbuchung',
                    'slug' => 'event-booking',
                    'url-path' => '',
                ],

                '24' => [
                    'description' => '<p>Gemeindehallenbuchungen ermöglichen es Einzelpersonen, Organisationen oder Gruppen, Gemeinschaftsräume für verschiedene Veranstaltungen wie Hochzeiten, Meetings, Kulturprogramme oder gesellschaftliche Zusammenkünfte zu reservieren.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Gemeindehallenbuchungen',
                    'slug' => 'community-hall-bookings',
                    'url-path' => '',
                ],

                '25' => [
                    'description' => '<p>Tischreservierung ermöglicht es Kunden, im Voraus Tische in Restaurants, Cafés oder Gaststätten zu reservieren.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Tischreservierung',
                    'slug' => 'table-booking',
                    'url-path' => '',
                ],

                '26' => [
                    'description' => '<p>Mietbuchung erleichtert die Reservierung von Gegenständen oder Immobilien zur vorübergehenden Nutzung, wie Fahrzeuge, Ausrüstung, Ferienwohnungen oder Tagungsräume.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Mietbuchung',
                    'slug' => 'rental-booking',
                    'url-path' => '',
                ],

                '27' => [
                    'description' => '<p>Entdecken Sie die neueste Unterhaltungselektronik, die Sie verbunden, produktiv und unterhalten hält.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Elektronik',
                    'slug' => 'electronics',
                    'url-path' => '',
                ],

                '28' => [
                    'description' => '<p>Entdecken Sie Smartphones, Ladegeräte, Hüllen und andere wichtige Dinge, um unterwegs verbunden zu bleiben.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Mobiltelefone & Zubehör',
                    'slug' => 'mobile-phones-accessories',
                    'url-path' => '',
                ],

                '29' => [
                    'description' => '<p>Finden Sie leistungsstarke Laptops und tragbare Tablets für Arbeit, Studium und Freizeit.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Laptops & Tablets',
                    'slug' => 'laptops-tablets',
                    'url-path' => '',
                ],

                '30' => [
                    'description' => '<p>Kaufen Sie Kopfhörer, Ohrhörer und Lautsprecher für kristallklaren Sound und immersive Audioerlebnisse.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Audiogeräte',
                    'slug' => 'audio-devices',
                    'url-path' => '',
                ],

                '31' => [
                    'description' => '<p>Machen Sie das Leben einfacher mit intelligenter Beleuchtung, Thermostaten, Sicherheitssystemen und mehr.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Smart Home & Automatisierung',
                    'slug' => 'smart-home-automation',
                    'url-path' => '',
                ],

                '32' => [
                    'description' => '<p>Verbessern Sie Ihren Wohnraum mit funktionalen und stilvollen Haus- und Küchenutensilien.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Haushalt',
                    'slug' => 'household',
                    'url-path' => '',
                ],

                '33' => [
                    'description' => '<p>Durchstöbern Sie Mixer, Heißluftfritteusen, Kaffeemaschinen und mehr zur Vereinfachung der Essenszubereitung.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Küchengeräte',
                    'slug' => 'kitchen-appliances',
                    'url-path' => '',
                ],

                '34' => [
                    'description' => '<p>Entdecken Sie Kochgeschirr-Sets, Utensilien, Geschirr und Servierwaren für Ihre kulinarischen Bedürfnisse.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Kochgeschirr & Esswaren',
                    'slug' => 'cookware-dining',
                    'url-path' => '',
                ],

                '35' => [
                    'description' => '<p>Fügen Sie Komfort und Charme mit Sofas, Tischen, Wandkunst und Wohnaccessoires hinzu.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Möbel & Dekoration',
                    'slug' => 'furniture-decor',
                    'url-path' => '',
                ],

                '36' => [
                    'description' => '<p>Halten Sie Ihren Raum makellos mit Staubsaugern, Reinigungssprays, Besen und Organizern.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Reinigungsmittel',
                    'slug' => 'cleaning-supplies',
                    'url-path' => '',
                ],

                '37' => [
                    'description' => '<p>Entfachen Sie Ihre Fantasie oder organisieren Sie Ihren Arbeitsplatz mit einer großen Auswahl an Büchern und Schreibwaren.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Bücher & Schreibwaren',
                    'slug' => 'books-stationery',
                    'url-path' => '',
                ],

                '38' => [
                    'description' => '<p>Tauchen Sie ein in Bestseller-Romane, Biografien, Selbsthilfe und mehr.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Belletristik & Sachbücher',
                    'slug' => 'fiction-non-fiction-books',
                    'url-path' => '',
                ],

                '39' => [
                    'description' => '<p>Finden Sie Lehrbücher, Nachschlagewerke und Lernhilfen für alle Altersgruppen.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Bildung & Akademisch',
                    'slug' => 'educational-academic',
                    'url-path' => '',
                ],

                '40' => [
                    'description' => '<p>Kaufen Sie Stifte, Notizbücher, Planer und Bürobedarf für Produktivität.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Bürobedarf',
                    'slug' => 'office-supplies',
                    'url-path' => '',
                ],

                '41' => [
                    'description' => '<p>Entdecken Sie Farben, Pinsel, Skizzenbücher und DIY-Bastelsets für Kreative.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Kunst- & Bastelmaterialien',
                    'slug' => 'art-craft-materials',
                    'url-path' => '',
                ],
            ],
        ],
    ],

    'installer' => [
        'middleware' => [
            'already-installed' => 'Anwendung ist bereits installiert.',
        ],

        'index' => [
            'create-administrator' => [
                'admin' => 'Administrator',
                'bagisto' => 'Bagisto',
                'confirm-password' => 'Passwort bestätigen',
                'email' => 'E-Mail',
                'email-address' => 'admin@example.com',
                'password' => 'Passwort',
                'title' => 'Administrator erstellen',
            ],

            'environment-configuration' => [
                'algerian-dinar' => 'Algerischer Dinar (DZD)',
                'allowed-currencies' => 'Zugelassene Währungen',
                'allowed-locales' => 'Zugelassene Sprachen',
                'application-name' => 'Anwendungsname',
                'argentine-peso' => 'Argentinischer Peso (ARS)',
                'australian-dollar' => 'Australischer Dollar (AUD)',
                'bagisto' => 'Bagisto',
                'bangladeshi-taka' => 'Bangladeschischer Taka (BDT)',
                'bahraini-dinar' => 'Bahreinse Dinar (BHD)',
                'brazilian-real' => 'Brasilianischer Real (BRL)',
                'british-pound-sterling' => 'Britisches Pfund Sterling (GBP)',
                'canadian-dollar' => 'Kanadischer Dollar (CAD)',
                'cfa-franc-bceao' => 'CFA-Franc BCEAO (XOF)',
                'cfa-franc-beac' => 'CFA-Franc BEAC (XAF)',
                'chilean-peso' => 'Chilenischer Peso (CLP)',
                'chinese-yuan' => 'Chinesischer Yuan (CNY)',
                'colombian-peso' => 'Kolumbianischer Peso (COP)',
                'czech-koruna' => 'Tschechische Krone (CZK)',
                'danish-krone' => 'Dänische Krone (DKK)',
                'database-connection' => 'Datenbankverbindung',
                'database-hostname' => 'Datenbank-Hostname',
                'database-name' => 'Datenbankname',
                'database-password' => 'Datenbankpasswort',
                'database-port' => 'Datenbank-Port',
                'database-prefix' => 'Datenbank-Präfix',
                'database-prefix-help' => 'Die Präfix sollte 4 Zeichen lang sein und darf nur Buchstaben, Zahlen und Unterstriche enthalten.',
                'database-username' => 'Datenbank-Benutzername',
                'default-currency' => 'Standardwährung',
                'default-locale' => 'Standardsprache',
                'default-timezone' => 'Standard-Zeitzone',
                'default-url' => 'Standard-URL',
                'default-url-link' => 'https://localhost',
                'egyptian-pound' => 'Ägyptisches Pfund (EGP)',
                'euro' => 'Euro (EUR)',
                'fijian-dollar' => 'Fidschi-Dollar (FJD)',
                'hong-kong-dollar' => 'Hongkong-Dollar (HKD)',
                'hungarian-forint' => 'Ungarischer Forint (HUF)',
                'indian-rupee' => 'Indische Rupie (INR)',
                'indonesian-rupiah' => 'Indonesische Rupiah (IDR)',
                'israeli-new-shekel' => 'Israelischer Neuer Schekel (ILS)',
                'japanese-yen' => 'Japanischer Yen (JPY)',
                'jordanian-dinar' => 'Jordanischer Dinar (JOD)',
                'kazakhstani-tenge' => 'Kasachischer Tenge (KZT)',
                'kuwaiti-dinar' => 'Kuwaitischer Dinar (KWD)',
                'lebanese-pound' => 'Libanesisches Pfund (LBP)',
                'libyan-dinar' => 'Libyscher Dinar (LYD)',
                'malaysian-ringgit' => 'Malaysischer Ringgit (MYR)',
                'mauritian-rupee' => 'Mauritius-Rupie (MUR)',
                'mexican-peso' => 'Mexikanischer Peso (MXN)',
                'moroccan-dirham' => 'Marokkanischer Dirham (MAD)',
                'mysql' => 'MySQL',
                'nepalese-rupee' => 'Nepalesische Rupie (NPR)',
                'new-taiwan-dollar' => 'Neuer Taiwan-Dollar (TWD)',
                'new-zealand-dollar' => 'Neuseeland-Dollar (NZD)',
                'nigerian-naira' => 'Nigerianische Naira (NGN)',
                'norwegian-krone' => 'Norwegische Krone (NOK)',
                'omani-rial' => 'Omanischer Rial (OMR)',
                'pakistani-rupee' => 'Pakistanische Rupie (PKR)',
                'panamanian-balboa' => 'Panamaischer Balboa (PAB)',
                'paraguayan-guarani' => 'Paraguayischer Guarani (PYG)',
                'peruvian-nuevo-sol' => 'Peruanischer Nuevo Sol (PEN)',
                'pgsql' => 'pgSQL',
                'philippine-peso' => 'Philippinischer Peso (PHP)',
                'polish-zloty' => 'Polnischer Zloty (PLN)',
                'qatari-rial' => 'Katarischer Rial (QAR)',
                'romanian-leu' => 'Rumänischer Leu (RON)',
                'russian-ruble' => 'Russischer Rubel (RUB)',
                'saudi-riyal' => 'Saudi-Riyal (SAR)',
                'select-timezone' => 'Zeitzone auswählen',
                'singapore-dollar' => 'Singapur-Dollar (SGD)',
                'south-african-rand' => 'Südafrikanischer Rand (ZAR)',
                'south-korean-won' => 'Südkoreanischer Won (KRW)',
                'sqlsrv' => 'SQLSRV',
                'sri-lankan-rupee' => 'Sri-Lanka-Rupie (LKR)',
                'swedish-krona' => 'Schwedische Krone (SEK)',
                'swiss-franc' => 'Schweizer Franken (CHF)',
                'thai-baht' => 'Thailändischer Baht (THB)',
                'title' => 'Store-Konfiguration',
                'tunisian-dinar' => 'Tunesischer Dinar (TND)',
                'turkish-lira' => 'Türkische Lira (TRY)',
                'ukrainian-hryvnia' => 'Ukrainische Hrywnja (UAH)',
                'united-arab-emirates-dirham' => 'Vereinigte Arabische Emirate Dirham (AED)',
                'united-states-dollar' => 'US-Dollar (USD)',
                'uzbekistani-som' => 'Usbekischer Som (UZS)',
                'venezuelan-bolívar' => 'Venezolanischer Bolívar (VEF)',
                'vietnamese-dong' => 'Vietnamesischer Dong (VND)',
                'warning-message' => 'Achtung! Die Einstellungen für Ihre Standardsystemsprache und Standardwährung sind dauerhaft und können nach der Festlegung nicht mehr geändert werden.',
                'zambian-kwacha' => 'Sambischer Kwacha (ZMW)',
            ],

            'sample-products' => [
                'no' => 'Nein',
                'sample-products' => 'Beispielfprodukte',
                'title' => 'Beispielfprodukte',
                'yes' => 'Ja',
            ],

            'installation-processing' => [
                'bagisto' => 'Installation Bagisto',
                'bagisto-info' => 'Erstellung der Datenbanktabellen, dies kann einige Momente dauern',
                'title' => 'Installation',
            ],

            'installation-completed' => [
                'admin-panel' => 'Admin-Panel',
                'bagisto-forums' => 'Bagisto Forum',
                'customer-panel' => 'Kundenpanel',
                'explore-bagisto-extensions' => 'Erkunden Sie Bagisto-Erweiterungen',
                'title' => 'Installation abgeschlossen',
                'title-info' => 'Bagisto wurde erfolgreich auf Ihrem System installiert.',
            ],

            'ready-for-installation' => [
                'create-database-table' => 'Datenbanktabelle erstellen',
                'install' => 'Installation',
                'install-info' => 'Bagisto zur Installation',
                'install-info-button' => 'Klicke auf die Schaltfläche unten, um',
                'populate-database-table' => 'Datenbanktabellen füllen',
                'start-installation' => 'Installation starten',
                'title' => 'Bereit zur Installation',
            ],

            'start' => [
                'locale' => 'Lokale',
                'main' => 'Start',
                'select-locale' => 'Wähle Lokale',
                'title' => 'Deine Bagisto-Installation',
                'welcome-title' => 'Willkommen bei Bagisto',
            ],

            'server-requirements' => [
                'calendar' => 'Kalender',
                'ctype' => 'cType',
                'curl' => 'cURL',
                'dom' => 'dom',
                'fileinfo' => 'Dateiinfo',
                'filter' => 'Filter',
                'gd' => 'GD',
                'hash' => 'Hash',
                'intl' => 'intl',
                'json' => 'JSON',
                'mbstring' => 'mbstring',
                'openssl' => 'openssl',
                'pcre' => 'pcre',
                'pdo' => 'pdo',
                'php' => 'PHP',
                'php-version' => '8.1 oder höher',
                'session' => 'Sitzung',
                'title' => 'Serveranforderungen',
                'tokenizer' => 'Tokenizer',
                'xml' => 'XML',
            ],

            'arabic' => 'Arabisch',
            'back' => 'Zurück',
            'bagisto' => 'Bagisto',
            'bagisto-info' => 'Ein Gemeinschaftsprojekt von',
            'bagisto-logo' => 'Bagisto Logo',
            'bengali' => 'Bengalisch',
            'catalan' => 'Katalanisch',
            'chinese' => 'Chinesisch',
            'continue' => 'Weiter',
            'dutch' => 'Niederländisch',
            'english' => 'Englisch',
            'french' => 'Französisch',
            'german' => 'Deutsch',
            'hebrew' => 'Hebräisch',
            'hindi' => 'Hindi',
            'indonesian' => 'Indonesisch',
            'installation-description' => 'Die Installation von Bagisto umfasst in der Regel mehrere Schritte. Hier ist eine allgemeine Übersicht über den Installationsprozess für Bagisto',
            'installation-info' => 'Wir freuen uns, Sie hier zu sehen!',
            'installation-title' => 'Willkommen zur Installation',
            'italian' => 'Italienisch',
            'japanese' => 'Japanisch',
            'persian' => 'Persisch',
            'polish' => 'Polnisch',
            'portuguese' => 'Brasilianisches Portugiesisch',
            'russian' => 'Russisch',
            'sinhala' => 'Singhalesisch',
            'spanish' => 'Spanisch',
            'title' => 'Bagisto Installer',
            'turkish' => 'Türkisch',
            'ukrainian' => 'Ukrainisch',
            'webkul' => 'Webkul',
        ],
    ],
];
