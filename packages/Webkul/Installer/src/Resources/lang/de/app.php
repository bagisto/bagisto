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

        'sample-categories' => [
            'category-translation' => [
                '2' => [
                    'description'      => 'Beschreibung der Kategorie Herren',
                    'meta-description' => 'Meta-Beschreibung der Kategorie Herren',
                    'meta-keywords'    => 'Meta-Schlüsselwörter der Kategorie Herren',
                    'meta-title'       => 'Meta-Titel der Kategorie Herren',
                    'name'             => 'Männer',
                    'slug'             => 'maenner',
                ],

                '3' => [
                    'description'      => 'Beschreibung der Kategorie Winterkleidung',
                    'meta-description' => 'Meta-Beschreibung der Kategorie Winterkleidung',
                    'meta-keywords'    => 'Meta-Schlüsselwörter der Kategorie Winterkleidung',
                    'meta-title'       => 'Meta-Titel der Kategorie Winterkleidung',
                    'name'             => 'Winterkleidung',
                    'slug'             => 'winterkleidung',
                ],
            ],
        ],

        'sample-products' => [
            'product-flat' => [
                '1' => [
                    'description'       => 'Die Arctic Cozy Knit Beanie ist Ihre ideale Lösung, um in den kälteren Monaten warm, bequem und stilvoll zu bleiben. Hergestellt aus einer weichen und strapazierfähigen Mischung aus Acrylstrick, bietet diese Mütze eine gemütliche und eng anliegende Passform. Das klassische Design macht sie sowohl für Männer als auch für Frauen geeignet und bietet ein vielseitiges Accessoire, das zu verschiedenen Stilen passt. Egal, ob Sie einen lässigen Tag in der Stadt verbringen oder die Natur genießen, diese Mütze verleiht Ihrem Outfit einen Hauch von Komfort und Wärme. Das weiche und atmungsaktive Material sorgt dafür, dass Sie es gemütlich haben, ohne auf Stil verzichten zu müssen. Die Arctic Cozy Knit Beanie ist nicht nur ein Accessoire, sondern ein Statement der Wintermode. Ihre Schlichtheit macht es einfach, sie mit verschiedenen Outfits zu kombinieren, und sie ist ein Grundbestandteil Ihrer Wintergarderobe. Ideal als Geschenk oder als Belohnung für sich selbst, ist diese Mütze eine durchdachte Ergänzung für jedes Winteroutfit. Sie ist ein vielseitiges Accessoire, das über die Funktionalität hinausgeht und Ihrem Look einen Hauch von Wärme und Stil verleiht. Erleben Sie die Essenz des Winters mit der Arctic Cozy Knit Beanie. Egal, ob Sie einen lässigen Tag draußen genießen oder den Elementen trotzen, lassen Sie diese Mütze Ihr Begleiter für Komfort und Stil sein. Erhöhen Sie Ihre Wintergarderobe mit diesem klassischen Accessoire, das Wärme mit einem zeitlosen Sinn für Mode mühelos kombiniert.',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Meta-Titel',
                    'meta-description'  => 'Meta-Beschreibung',
                    'name'              => 'Arctic Cozy Knit Unisex Beanie',
                    'short-description' => 'Genießen Sie die kalten Tage stilvoll mit unserer Arctic Cozy Knit Beanie. Hergestellt aus einer weichen und strapazierfähigen Acrylmischung, bietet diese klassische Mütze Wärme und Vielseitigkeit. Geeignet für Männer und Frauen, ist sie das ideale Accessoire für den Alltag oder Outdoor-Aktivitäten. Erhöhen Sie Ihre Wintergarderobe oder machen Sie jemandem eine Freude mit dieser unverzichtbaren Mütze.',
                ],

                '2' => [
                    'description'       => 'Der Arctic Bliss Winter Schal ist mehr als nur ein Kaltwetter-Accessoire; er ist ein Ausdruck von Wärme, Komfort und Stil für die Wintersaison. Mit Sorgfalt aus einer luxuriösen Mischung aus Acryl und Wolle gefertigt, ist dieser Schal so konzipiert, dass er Sie auch bei den kältesten Temperaturen warm und gemütlich hält. Die weiche und flauschige Textur bietet nicht nur Isolierung gegen die Kälte, sondern verleiht Ihrer Wintergarderobe auch einen Hauch von Luxus. Das Design des Arctic Bliss Winter Schals ist sowohl stilvoll als auch vielseitig, was ihn zu einer perfekten Ergänzung für eine Vielzahl von Winteroutfits macht. Egal, ob Sie sich für einen besonderen Anlass anziehen oder Ihrem Alltagslook eine schicke Schicht hinzufügen, dieser Schal ergänzt Ihren Stil mühelos. Die extralange Länge des Schals bietet individuelle Styling-Optionen. Wickeln Sie ihn für zusätzliche Wärme um, tragen Sie ihn locker für einen lässigen Look oder experimentieren Sie mit verschiedenen Knoten, um Ihren einzigartigen Stil auszudrücken. Diese Vielseitigkeit macht ihn zu einem unverzichtbaren Accessoire für die Wintersaison. Auf der Suche nach dem perfekten Geschenk? Der Arctic Bliss Winter Schal ist eine ideale Wahl. Egal, ob Sie einen geliebten Menschen überraschen oder sich selbst verwöhnen möchten, dieser Schal ist ein zeitloses und praktisches Geschenk, das in den Wintermonaten geschätzt wird. Erleben Sie den Winter mit dem Arctic Bliss Winter Schal, wo Wärme und Stil in perfekter Harmonie zusammenkommen. Erhöhen Sie Ihre Wintergarderobe mit diesem unverzichtbaren Accessoire, das nicht nur warm hält, sondern auch Ihrem Kaltwetter-Outfit einen Hauch von Eleganz verleiht.',
                    'meta-description'  => 'Meta-Beschreibung',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Meta-Titel',
                    'name'              => 'Arctic Bliss Stilvoller Winterschal',
                    'short-description' => 'Erleben Sie die Umarmung von Wärme und Stil mit unserem Arctic Bliss Winterschal. Hergestellt aus einer luxuriösen Mischung aus Acryl und Wolle, ist dieser gemütliche Schal so konzipiert, dass er Sie an den kältesten Tagen warm hält. Sein stilvolles und vielseitiges Design in Kombination mit einer extralangen Länge bietet individuelle Styling-Optionen. Erhöhen Sie Ihre Wintergarderobe oder machen Sie jemandem eine Freude mit diesem unverzichtbaren Winter-Accessoire.',
                ],

                '3' => [
                    'description'       => 'Wir präsentieren die Arctic Touchscreen Winterhandschuhe – wo Wärme, Stil und Konnektivität aufeinandertreffen, um Ihr Wintererlebnis zu verbessern. Hergestellt aus hochwertigem Acryl, bieten diese Handschuhe außergewöhnliche Wärme und Haltbarkeit. Die touchscreen-kompatiblen Fingerspitzen ermöglichen es Ihnen, verbunden zu bleiben, ohne Ihre Hände der Kälte auszusetzen. Beantworten Sie Anrufe, senden Sie Nachrichten und navigieren Sie mühelos auf Ihren Geräten, während Sie Ihre Hände warm halten. Das isolierte Futter fügt eine zusätzliche Schicht Gemütlichkeit hinzu und macht diese Handschuhe zur ersten Wahl für die kalte Jahreszeit. Egal, ob Sie pendeln, Besorgungen machen oder Outdoor-Aktivitäten genießen, diese Handschuhe bieten die Wärme und den Schutz, den Sie brauchen. Elastische Bündchen sorgen für eine sichere Passform, verhindern kalte Zugluft und halten die Handschuhe bei Ihren täglichen Aktivitäten an Ort und Stelle. Das stilvolle Design verleiht Ihrem Winteroutfit eine Prise Eleganz und macht diese Handschuhe genauso modisch wie funktional. Ideal als Geschenk oder als Belohnung für sich selbst, sind die Arctic Touchscreen Winterhandschuhe ein unverzichtbares Accessoire für die moderne Person. Verabschieden Sie sich von der Unannehmlichkeit, Ihre Handschuhe ausziehen zu müssen, um Ihre Geräte zu nutzen, und genießen Sie die nahtlose Kombination aus Wärme, Stil und Konnektivität. Bleiben Sie verbunden, warm und stilvoll mit den Arctic Touchscreen Winterhandschuhen – Ihr zuverlässiger Begleiter, um die Wintersaison mit Zuversicht zu meistern.',
                    'meta-description'  => 'Meta-Beschreibung',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Meta-Titel',
                    'name'              => 'Arctic Touchscreen Winterhandschuhe',
                    'short-description' => 'Bleiben Sie verbunden und warm mit unseren Arctic Touchscreen Winterhandschuhen. Diese Handschuhe sind nicht nur aus hochwertigem Acryl gefertigt, um Wärme und Haltbarkeit zu gewährleisten, sondern verfügen auch über ein touchscreen-kompatibles Design. Mit einem isolierten Futter, elastischen Bündchen für eine sichere Passform und einem stilvollen Look sind diese Handschuhe perfekt für den täglichen Gebrauch bei kühlen Bedingungen.',
                ],

                '4' => [
                    'description'       => 'Wir präsentieren die Arctic Warmth Wollmischung Socken – Ihr unverzichtbarer Begleiter für warme und bequeme Füße in der kalten Jahreszeit. Hergestellt aus einer hochwertigen Mischung aus Merinowolle, Acryl, Nylon und Spandex, bieten diese Socken unvergleichliche Wärme und Komfort. Die Wollmischung sorgt dafür, dass Ihre Füße auch bei den kältesten Temperaturen warm bleiben, was diese Socken zur perfekten Wahl für Winterabenteuer oder einfach zum gemütlichen Verweilen zu Hause macht. Die weiche und gemütliche Textur der Socken bietet ein luxuriöses Gefühl auf der Haut. Verabschieden Sie sich von kalten Füßen und genießen Sie die kuschelige Wärme, die diese Wollmischung Socken bieten. Für Langlebigkeit konzipiert, verfügen die Socken über eine verstärkte Ferse und Spitze, die zusätzliche Stärke an stark beanspruchten Stellen bieten. Dies stellt sicher, dass Ihre Socken die Zeit überdauern und langanhaltenden Komfort und Gemütlichkeit bieten. Die atmungsaktive Natur des Materials verhindert Überhitzung und hält Ihre Füße den ganzen Tag über bequem und trocken. Egal, ob Sie für eine Winterwanderung nach draußen gehen oder drinnen entspannen, diese Socken bieten die perfekte Balance zwischen Wärme und Atmungsaktivität. Vielseitig und stilvoll, sind diese Wollmischung Socken für verschiedene Anlässe geeignet. Kombinieren Sie sie mit Ihren Lieblingsstiefeln für einen modischen Winterlook oder tragen Sie sie im Haus für ultimativen Komfort. Erhöhen Sie Ihre Wintergarderobe und priorisieren Sie Komfort mit den Arctic Warmth Wollmischung Socken. Verwöhnen Sie Ihre Füße mit dem Luxus, den sie verdienen, und treten Sie in eine Welt der Gemütlichkeit ein, die die ganze Saison über anhält.',
                    'meta-description'  => 'Meta-Beschreibung',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Meta-Titel',
                    'name'              => 'Arctic Warmth Wollmischung Socken',
                    'short-description' => 'Erleben Sie die unvergleichliche Wärme und den Komfort unserer Arctic Warmth Wollmischung Socken. Hergestellt aus einer Mischung aus Merinowolle, Acryl, Nylon und Spandex, bieten diese Socken ultimative Gemütlichkeit für kaltes Wetter. Mit einer verstärkten Ferse und Spitze für Langlebigkeit sind diese vielseitigen und stilvollen Socken perfekt für verschiedene Anlässe.',
                ],

                '5' => [
                    'description'       => 'Wir präsentieren das Arctic Frost Winter-Accessoire-Set, Ihre ideale Lösung, um an kalten Wintertagen warm, stilvoll und verbunden zu bleiben. Dieses sorgfältig zusammengestellte Set vereint vier essentielle Winter-Accessoires, um ein harmonisches Ensemble zu schaffen. Der luxuriöse Schal, gewebt aus einer Mischung aus Acryl und Wolle, bietet nicht nur eine zusätzliche Wärmequelle, sondern verleiht Ihrer Wintergarderobe auch einen Hauch von Eleganz. Die weiche Strickmütze, mit Sorgfalt gefertigt, verspricht, Sie gemütlich zu halten, während sie Ihrem Look eine modische Note verleiht. Doch damit nicht genug – unser Set enthält auch touchscreen-kompatible Handschuhe. Bleiben Sie verbunden, ohne auf Wärme zu verzichten, während Sie mühelos Ihre Geräte bedienen. Ob Sie Anrufe entgegennehmen, Nachrichten senden oder Wintermomente mit Ihrem Smartphone festhalten, diese Handschuhe bieten Komfort ohne Kompromisse bei Stil. Die weiche und gemütliche Textur der Socken sorgt für ein luxuriöses Gefühl auf Ihrer Haut. Verabschieden Sie sich von kalten Füßen, während Sie die plüschige Wärme dieser Wollmischungssocken genießen. Das Arctic Frost Winter-Accessoire-Set ist nicht nur funktional; es ist eine Aussage der Wintermode. Jedes Teil ist so gestaltet, dass es Sie nicht nur vor der Kälte schützt, sondern auch Ihren Stil in der frostigen Jahreszeit hebt. Die Materialien dieses Sets wurden sowohl auf Langlebigkeit als auch auf Komfort ausgewählt, sodass Sie den Winter in Stil genießen können. Ob Sie sich selbst verwöhnen oder das perfekte Geschenk suchen, das Arctic Frost Winter-Accessoire-Set ist eine vielseitige Wahl. Verwöhnen Sie jemanden Besonderen in der Weihnachtszeit oder ergänzen Sie Ihre eigene Wintergarderobe mit diesem stilvollen und funktionalen Ensemble. Stellen Sie sich der Kälte mit Zuversicht, in dem Wissen, dass Sie die perfekten Accessoires haben, um warm und schick zu bleiben.',
                    'meta-description'  => 'Meta-Beschreibung',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Meta-Titel',
                    'name'              => 'Arctic Frost Winter-Accessoire-Set',
                    'short-description' => 'Trotzen Sie der Winterkälte mit unserem Arctic Frost Winter-Accessoire-Set. Dieses sorgfältig zusammengestellte Set umfasst einen luxuriösen Schal, eine gemütliche Mütze, touchscreen-kompatible Handschuhe und Wollmischungssocken. Stilvoll und funktional, ist dieses Ensemble aus hochwertigen Materialien gefertigt, die sowohl Langlebigkeit als auch Komfort bieten. Ergänzen Sie Ihre Wintergarderobe oder machen Sie jemandem eine Freude mit dieser perfekten Geschenkoption.',
                ],

                '6' => [
                    'description'       => 'Vorstellung des Arctic Frost Winter-Accessoires-Bundles, Ihrer Lösung, um warm, stilvoll und verbunden zu bleiben an kalten Wintertagen. Dieses sorgfältig zusammengestellte Set vereint vier essentielle Winteraccessoires zu einem harmonischen Ensemble. Der luxuriöse Schal, gewebt aus einer Mischung aus Acryl und Wolle, fügt nicht nur eine Schicht Wärme hinzu, sondern verleiht auch Ihrem Winteroutfit eine Note von Eleganz. Die weiche Strickmütze, sorgfältig gefertigt, verspricht, Sie gemütlich zu halten, während sie Ihrem Look eine modische Note verleiht. Aber damit nicht genug - unser Bundle enthält auch Touchscreen-kompatible Handschuhe. Bleiben Sie verbunden, ohne Wärme zu opfern, während Sie mühelos Ihre Geräte bedienen. Ob Sie Anrufe entgegennehmen, Nachrichten senden oder winterliche Momente auf Ihrem Smartphone einfangen, diese Handschuhe sorgen für Bequemlichkeit, ohne den Stil zu beeinträchtigen. Die weiche und gemütliche Textur der Socken bietet ein luxuriöses Gefühl auf Ihrer Haut. Verabschieden Sie sich von kalten Füßen, während Sie die kuschelige Wärme dieser Wollmisch-Socken genießen. Das Arctic Frost Winter-Accessoires-Bundle geht nicht nur um Funktionalität; es ist eine Aussage der Wintermode. Jedes Stück ist nicht nur darauf ausgelegt, Sie vor der Kälte zu schützen, sondern auch Ihren Stil während der frostigen Jahreszeit zu verbessern. Die für dieses Bundle gewählten Materialien priorisieren sowohl Haltbarkeit als auch Komfort und gewährleisten, dass Sie die Winterlandschaft stilvoll genießen können. Ob Sie sich selbst etwas gönnen oder nach dem perfekten Geschenk suchen, das Arctic Frost Winter-Accessoires-Bundle ist eine vielseitige Wahl. Freuen Sie sich in der Ferienzeit über jemanden Besonderen oder erweitern Sie Ihre eigene Wintergarderobe mit diesem stilvollen und funktionalen Ensemble. Umarmen Sie die Kälte mit Zuversicht, in dem Wissen, dass Sie die perfekten Accessoires haben, um warm und schick zu bleiben.',
                    'meta-description'  => 'Meta-Beschreibung',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Meta-Titel',
                    'name'              => 'Arctic Frost Winter-Accessoires-Bundle',
                    'short-description' => 'Umarmen Sie die winterliche Kälte mit unserem Arctic Frost Winter-Accessoires-Bundle. Dieses kuratierte Set umfasst einen luxuriösen Schal, eine gemütliche Mütze, Touchscreen-kompatible Handschuhe und Wollmisch-Socken. Stilvoll und funktional, dieses Ensemble ist aus hochwertigen Materialien gefertigt und bietet sowohl Haltbarkeit als auch Komfort. Erweitern Sie Ihre Wintergarderobe oder erfreuen Sie jemand Besonderen mit dieser perfekten Geschenkoption.',
                ],

                '7' => [
                    'description'       => 'Vorstellung der OmniHeat Herren Kapuzen-Steppjacke, Ihre Lösung, um in den kälteren Jahreszeiten warm und modisch zu bleiben. Diese Jacke wurde mit Langlebigkeit und Wärme im Sinn gefertigt, um sicherzustellen, dass sie zu Ihrem vertrauenswürdigen Begleiter wird. Das Kapuzen-Design verleiht nicht nur einen Hauch von Stil, sondern bietet auch zusätzliche Wärme und schützt vor kaltem Wind und Wetter. Die langen Ärmel bieten vollständige Abdeckung und gewährleisten, dass Sie vom Schulter bis zum Handgelenk gemütlich bleiben. Ausgestattet mit Einschubtaschen bietet diese Steppjacke Bequemlichkeit, um Ihre Essentials zu tragen oder Ihre Hände warm zu halten. Die isolierte synthetische Füllung bietet zusätzliche Wärme und ist ideal, um kalte Tage und Nächte zu bekämpfen. Aus einem langlebigen Polyester-Äußeren und -Futter gefertigt, ist diese Jacke robust und hält den Elementen stand. Erhältlich in 5 attraktiven Farben, können Sie diejenige wählen, die Ihrem Stil und Ihren Vorlieben entspricht. Vielseitig und funktional ist die OmniHeat Herren Kapuzen-Steppjacke für verschiedene Anlässe geeignet, egal ob Sie zur Arbeit gehen, sich zu einem lockeren Ausflug entscheiden oder an einem Outdoor-Event teilnehmen. Erleben Sie die perfekte Kombination aus Stil, Komfort und Funktionalität mit der OmniHeat Herren Kapuzen-Steppjacke. Heben Sie Ihre Wintergarderobe auf ein neues Level und bleiben Sie gemütlich, während Sie die Natur genießen. Besiegen Sie die Kälte mit Stil und setzen Sie mit diesem unverzichtbaren Stück ein Statement.',
                    'meta-description'  => 'Meta-Beschreibung',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Meta-Titel',
                    'name'              => 'OmniHeat Herren Kapuzen-Steppjacke',
                    'short-description' => 'Bleiben Sie warm und stilvoll mit unserer OmniHeat Herren Kapuzen-Steppjacke. Diese Jacke wurde entwickelt, um ultimative Wärme zu bieten und verfügt über Einschubtaschen für zusätzlichen Komfort. Das isolierte Material sorgt dafür, dass Sie bei kaltem Wetter gemütlich bleiben. Erhältlich in 5 attraktiven Farben, macht sie zu einer vielseitigen Wahl für verschiedene Anlässe.',
                ],

                '8' => [
                    'description'       => 'Vorstellung der OmniHeat Herren Kapuzen-Steppjacke, Ihre Lösung, um in den kälteren Jahreszeiten warm und modisch zu bleiben. Diese Jacke wurde mit Langlebigkeit und Wärme im Sinn gefertigt, um sicherzustellen, dass sie zu Ihrem vertrauenswürdigen Begleiter wird. Das Kapuzen-Design verleiht nicht nur einen Hauch von Stil, sondern bietet auch zusätzliche Wärme und schützt vor kaltem Wind und Wetter. Die langen Ärmel bieten vollständige Abdeckung und gewährleisten, dass Sie vom Schulter bis zum Handgelenk gemütlich bleiben. Ausgestattet mit Einschubtaschen bietet diese Steppjacke Bequemlichkeit, um Ihre Essentials zu tragen oder Ihre Hände warm zu halten. Die isolierte synthetische Füllung bietet zusätzliche Wärme und ist ideal, um kalte Tage und Nächte zu bekämpfen. Aus einem langlebigen Polyester-Äußeren und -Futter gefertigt, ist diese Jacke robust und hält den Elementen stand. Erhältlich in 5 attraktiven Farben, können Sie diejenige wählen, die Ihrem Stil und Ihren Vorlieben entspricht. Vielseitig und funktional ist die OmniHeat Herren Kapuzen-Steppjacke für verschiedene Anlässe geeignet, egal ob Sie zur Arbeit gehen, sich zu einem lockeren Ausflug entscheiden oder an einem Outdoor-Event teilnehmen. Erleben Sie die perfekte Kombination aus Stil, Komfort und Funktionalität mit der OmniHeat Herren Kapuzen-Steppjacke. Heben Sie Ihre Wintergarderobe auf ein neues Level und bleiben Sie gemütlich, während Sie die Natur genießen. Besiegen Sie die Kälte mit Stil und setzen Sie mit diesem unverzichtbaren Stück ein Statement.',
                    'meta-description'  => 'Meta-Beschreibung',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Meta-Titel',
                    'name'              => 'OmniHeat Herren Kapuzen-Steppjacke-Blau-Gelb-M',
                    'short-description' => 'Bleiben Sie warm und stilvoll mit unserer OmniHeat Herren Kapuzen-Steppjacke. Diese Jacke wurde entwickelt, um ultimative Wärme zu bieten und verfügt über Einschubtaschen für zusätzlichen Komfort. Das isolierte Material sorgt dafür, dass Sie bei kaltem Wetter gemütlich bleiben. Erhältlich in 5 attraktiven Farben, macht sie zu einer vielseitigen Wahl für verschiedene Anlässe.',
                ],

                '9' => [
                    'description'       => 'Vorstellung der OmniHeat Herren Kapuzen-Steppjacke, Ihre Lösung, um in den kälteren Jahreszeiten warm und modisch zu bleiben. Diese Jacke wurde mit Langlebigkeit und Wärme im Sinn gefertigt, um sicherzustellen, dass sie zu Ihrem vertrauenswürdigen Begleiter wird. Das Kapuzen-Design verleiht nicht nur einen Hauch von Stil, sondern bietet auch zusätzliche Wärme und schützt vor kaltem Wind und Wetter. Die langen Ärmel bieten vollständige Abdeckung und gewährleisten, dass Sie vom Schulter bis zum Handgelenk gemütlich bleiben. Ausgestattet mit Einschubtaschen bietet diese Steppjacke Bequemlichkeit, um Ihre Essentials zu tragen oder Ihre Hände warm zu halten. Die isolierte synthetische Füllung bietet zusätzliche Wärme und ist ideal, um kalte Tage und Nächte zu bekämpfen. Aus einem langlebigen Polyester-Äußeren und -Futter gefertigt, ist diese Jacke robust und hält den Elementen stand. Erhältlich in 5 attraktiven Farben, können Sie diejenige wählen, die Ihrem Stil und Ihren Vorlieben entspricht. Vielseitig und funktional ist die OmniHeat Herren Kapuzen-Steppjacke für verschiedene Anlässe geeignet, egal ob Sie zur Arbeit gehen, sich zu einem lockeren Ausflug entscheiden oder an einem Outdoor-Event teilnehmen. Erleben Sie die perfekte Kombination aus Stil, Komfort und Funktionalität mit der OmniHeat Herren Kapuzen-Steppjacke. Heben Sie Ihre Wintergarderobe auf ein neues Level und bleiben Sie gemütlich, während Sie die Natur genießen. Besiegen Sie die Kälte mit Stil und setzen Sie mit diesem unverzichtbaren Stück ein Statement.',
                    'meta-description'  => 'Meta-Beschreibung',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Meta-Titel',
                    'name'              => 'OmniHeat Herren Kapuzen-Steppjacke-Blau-Gelb-L',
                    'short-description' => 'Bleiben Sie warm und stilvoll mit unserer OmniHeat Herren Kapuzen-Steppjacke. Diese Jacke wurde entwickelt, um ultimative Wärme zu bieten und verfügt über Einschubtaschen für zusätzlichen Komfort. Das isolierte Material sorgt dafür, dass Sie bei kaltem Wetter gemütlich bleiben. Erhältlich in 5 attraktiven Farben, macht sie zu einer vielseitigen Wahl für verschiedene Anlässe.',
                ],

                '10' => [
                    'description'       => 'Vorstellung der OmniHeat Herren Kapuzen-Steppjacke, Ihre Lösung, um in den kälteren Jahreszeiten warm und modisch zu bleiben. Diese Jacke wurde mit Langlebigkeit und Wärme im Sinn gefertigt, um sicherzustellen, dass sie zu Ihrem vertrauenswürdigen Begleiter wird. Das Kapuzen-Design verleiht nicht nur einen Hauch von Stil, sondern bietet auch zusätzliche Wärme und schützt vor kaltem Wind und Wetter. Die langen Ärmel bieten vollständige Abdeckung und gewährleisten, dass Sie vom Schulter bis zum Handgelenk gemütlich bleiben. Ausgestattet mit Einschubtaschen bietet diese Steppjacke Bequemlichkeit, um Ihre Essentials zu tragen oder Ihre Hände warm zu halten. Die isolierte synthetische Füllung bietet zusätzliche Wärme und ist ideal, um kalte Tage und Nächte zu bekämpfen. Aus einem langlebigen Polyester-Äußeren und -Futter gefertigt, ist diese Jacke robust und hält den Elementen stand. Erhältlich in 5 attraktiven Farben, können Sie diejenige wählen, die Ihrem Stil und Ihren Vorlieben entspricht. Vielseitig und funktional ist die OmniHeat Herren Kapuzen-Steppjacke für verschiedene Anlässe geeignet, egal ob Sie zur Arbeit gehen, sich zu einem lockeren Ausflug entscheiden oder an einem Outdoor-Event teilnehmen. Erleben Sie die perfekte Kombination aus Stil, Komfort und Funktionalität mit der OmniHeat Herren Kapuzen-Steppjacke. Heben Sie Ihre Wintergarderobe auf ein neues Level und bleiben Sie gemütlich, während Sie die Natur genießen. Besiegen Sie die Kälte mit Stil und setzen Sie mit diesem unverzichtbaren Stück ein Statement.',
                    'meta-description'  => 'Meta-Beschreibung',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Meta-Titel',
                    'name'              => 'OmniHeat Herren Kapuzen-Steppjacke-Blau-Grün-M',
                    'short-description' => 'Bleiben Sie warm und stilvoll mit unserer OmniHeat Herren Kapuzen-Steppjacke. Diese Jacke wurde entwickelt, um ultimative Wärme zu bieten und verfügt über Einschubtaschen für zusätzlichen Komfort. Das isolierte Material sorgt dafür, dass Sie bei kaltem Wetter gemütlich bleiben. Erhältlich in 5 attraktiven Farben, macht sie zu einer vielseitigen Wahl für verschiedene Anlässe.',
                ],

                '11' => [
                    'description'       => 'Einführung der OmniHeat Herren Kapuzen-Steppjacke, Ihre Lösung für Wärme und Stil in den kälteren Jahreszeiten. Diese Jacke ist auf Langlebigkeit und Wärme ausgelegt, sodass sie Ihr treuer Begleiter wird. Das Design mit Kapuze verleiht nicht nur Stil, sondern bietet auch zusätzliche Wärme und schützt vor kaltem Wind und Wetter. Die langen Ärmel bieten vollständige Abdeckung und sorgen dafür, dass Sie von der Schulter bis zum Handgelenk warm bleiben. Ausgestattet mit Einschubtaschen bietet diese Steppjacke praktischen Platz für Ihre Essentials oder hält Ihre Hände warm. Die isolierende synthetische Füllung bietet zusätzliche Wärme und ist ideal für kalte Tage und Nächte. Hergestellt aus einem langlebigen Polyester-Außenmaterial und -Futter, ist diese Jacke robust und wetterfest. Erhältlich in 5 attraktiven Farben, können Sie diejenige auswählen, die Ihrem Stil und Vorlieben entspricht. Vielseitig und funktional ist die OmniHeat Herren Kapuzen-Steppjacke für verschiedene Anlässe geeignet, sei es für die Arbeit, einen lockeren Ausflug oder ein Outdoor-Event. Erleben Sie die perfekte Kombination aus Stil, Komfort und Funktionalität mit der OmniHeat Herren Kapuzen-Steppjacke. Erweitern Sie Ihre Wintergarderobe und bleiben Sie warm, während Sie die Natur genießen. Besiegen Sie die Kälte mit Stil und setzen Sie mit diesem unverzichtbaren Kleidungsstück ein Statement.',
                    'meta-description'  => 'Meta-Beschreibung',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Meta-Titel',
                    'name'              => 'OmniHeat Herren Kapuzen-Steppjacke-Blau-Grün-L',
                    'short-description' => 'Bleiben Sie warm und stilvoll mit unserer OmniHeat Herren Kapuzen-Steppjacke. Diese Jacke ist darauf ausgelegt, maximale Wärme zu bieten, und verfügt über Einschubtaschen für zusätzlichen Komfort. Das isolierte Material sorgt dafür, dass Sie bei kaltem Wetter warm bleiben. Erhältlich in 5 attraktiven Farben und somit eine vielseitige Wahl für verschiedene Anlässe.',
                ],
            ],

            'product-attribute-values' => [
                '1' => [
                    'description'      => 'Die Arctic Cozy Knit Beanie ist die Lösung, um während der kälteren Monate warm, bequem und stilvoll zu bleiben. Hergestellt aus einer weichen und strapazierfähigen Mischung aus Acrylstrick, ist diese Beanie darauf ausgelegt, eine gemütliche und bequeme Passform zu bieten. Das klassische Design macht sie sowohl für Männer als auch für Frauen geeignet und bietet ein vielseitiges Accessoire, das verschiedene Stile ergänzt. Egal, ob Sie einen lässigen Tag in der Stadt verbringen oder die Natur genießen, diese Beanie verleiht Ihrem Outfit eine Note von Komfort und Wärme. Das weiche und atmungsaktive Material sorgt dafür, dass Sie gemütlich bleiben, ohne dabei auf Stil zu verzichten. Die Arctic Cozy Knit Beanie ist nicht nur ein Accessoire, sondern auch ein Statement der Wintermode. Ihre Einfachheit macht es einfach, sie mit verschiedenen Outfits zu kombinieren und sie zu einem unverzichtbaren Bestandteil Ihrer Wintergarderobe zu machen. Ideal zum Verschenken oder als kleine Freude für sich selbst ist diese Beanie eine durchdachte Ergänzung zu jedem Winterensemble. Sie ist ein vielseitiges Accessoire, das über Funktionalität hinausgeht und Ihrem Look eine Note von Wärme und Stil verleiht. Tauchen Sie ein in die Essenz des Winters mit der Arctic Cozy Knit Beanie. Egal, ob Sie einen lässigen Tag genießen oder den Elementen trotzen, lassen Sie diese Beanie Ihr Begleiter für Komfort und Stil sein. Ergänzen Sie Ihre Wintergarderobe mit diesem klassischen Accessoire, das Wärme mit zeitlosem Modebewusstsein mühelos kombiniert.',
                    'meta-description' => 'Meta-Beschreibung',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Meta-Titel',
                    'name'             => 'Arctic Cozy Knit Unisex Beanie',
                    'sort-description' => 'Genießen Sie die kalten Tage mit Stil mit unserer Arctic Cozy Knit Beanie. Hergestellt aus einer weichen und strapazierfähigen Mischung aus Acryl, bietet diese klassische Beanie Wärme und Vielseitigkeit. Geeignet für Männer und Frauen, ist sie das ideale Accessoire für lässige oder Outdoor-Kleidung. Ergänzen Sie Ihre Wintergarderobe oder verschenken Sie jemandem Besonderem diese unverzichtbare Beanie-Kappe.',
                ],

                '2' => [
                    'description'      => 'Der Arctic Bliss Winter Schal ist mehr als nur ein Accessoire für kaltes Wetter; er ist eine Aussage von Wärme, Komfort und Stil für die Winterzeit. Sorgfältig aus einer luxuriösen Mischung aus Acryl und Wolle gefertigt, ist dieser Schal darauf ausgelegt, Sie auch bei den kältesten Temperaturen gemütlich und warm zu halten. Die weiche und flauschige Textur bietet nicht nur Isolierung gegen die Kälte, sondern verleiht auch Ihrem Winteroutfit einen Hauch von Luxus. Das Design des Arctic Bliss Winter Schals ist sowohl stilvoll als auch vielseitig und macht ihn zu einer perfekten Ergänzung für verschiedene Winteroutfits. Egal, ob Sie sich für einen besonderen Anlass schick machen oder Ihrem Alltagslook eine schicke Schicht hinzufügen, dieser Schal ergänzt Ihren Stil mühelos. Die extra lange Länge des Schals bietet individuelle Styling-Optionen. Wickeln Sie ihn für zusätzliche Wärme, drapieren Sie ihn locker für einen lässigen Look oder experimentieren Sie mit verschiedenen Knoten, um Ihren einzigartigen Stil auszudrücken. Diese Vielseitigkeit macht ihn zu einem unverzichtbaren Accessoire für die Winterzeit. Suchen Sie das perfekte Geschenk? Der Arctic Bliss Winter Schal ist eine ideale Wahl. Egal, ob Sie einen geliebten Menschen überraschen oder sich selbst verwöhnen, dieser Schal ist ein zeitloses und praktisches Geschenk, das während der Wintermonate geschätzt wird. Umarmen Sie den Winter mit dem Arctic Bliss Winter Schal, wo Wärme und Stil in perfekter Harmonie aufeinandertreffen. Ergänzen Sie Ihre Wintergarderobe mit diesem unverzichtbaren Accessoire, das Sie nicht nur warm hält, sondern auch einen Hauch von Raffinesse zu Ihrem Kälte-Wetter-Ensemble hinzufügt.',
                    'meta-description' => 'Meta-Beschreibung',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Meta-Titel',
                    'name'             => 'Arctic Bliss Stilvoller Winter Schal',
                    'sort-description' => 'Erleben Sie die Umarmung von Wärme und Stil mit unserem Arctic Bliss Winter Schal. Hergestellt aus einer luxuriösen Mischung aus Acryl und Wolle, ist dieser gemütliche Schal darauf ausgelegt, Sie an den kältesten Tagen warm zu halten. Sein stilvolles und vielseitiges Design in Kombination mit einer extra langen Länge bietet individuelle Styling-Optionen. Ergänzen Sie Ihre Wintergarderobe oder erfreuen Sie jemanden Besonderen mit diesem unverzichtbaren Winteraccessoire.',
                ],

                '3' => [
                    'description'      => 'Stellen Sie sich die Arctic Touchscreen Winterhandschuhe vor - wo Wärme, Stil und Konnektivität aufeinandertreffen, um Ihr Wintererlebnis zu verbessern. Hergestellt aus hochwertigem Acryl, sind diese Handschuhe darauf ausgelegt, außergewöhnliche Wärme und Haltbarkeit zu bieten. Die touchscreen-kompatiblen Fingerspitzen ermöglichen es Ihnen, verbunden zu bleiben, ohne Ihre Hände der Kälte auszusetzen. Beantworten Sie Anrufe, senden Sie Nachrichten und navigieren Sie mühelos auf Ihren Geräten, während Ihre Hände gemütlich bleiben. Das isolierte Futter sorgt für zusätzliche Gemütlichkeit und macht diese Handschuhe zu Ihrer Wahl für den Umgang mit der Winterkälte. Egal, ob Sie pendeln, Besorgungen erledigen oder Outdoor-Aktivitäten genießen, diese Handschuhe bieten die Wärme und den Schutz, die Sie benötigen. Elastische Bündchen sorgen für eine sichere Passform, verhindern kalte Zugluft und halten die Handschuhe während Ihrer täglichen Aktivitäten an Ort und Stelle. Das stilvolle Design verleiht Ihrem Winterensemble einen Hauch von Flair und macht diese Handschuhe so modisch wie funktional. Ideal zum Verschenken oder als Belohnung für sich selbst sind die Arctic Touchscreen Winterhandschuhe ein unverzichtbares Accessoire für den modernen Menschen. Verabschieden Sie sich von der Unannehmlichkeit, Ihre Handschuhe abnehmen zu müssen, um Ihre Geräte zu benutzen, und umarmen Sie die nahtlose Verbindung von Wärme, Stil und Konnektivität. Bleiben Sie verbunden, bleiben Sie warm und bleiben Sie mit den Arctic Touchscreen Winterhandschuhen stilvoll - Ihr zuverlässiger Begleiter, um die Wintersaison mit Zuversicht zu meistern.',
                    'meta-description' => 'Meta-Beschreibung',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Meta-Titel',
                    'name'             => 'Arctic Touchscreen Winterhandschuhe',
                    'sort-description' => 'Bleiben Sie verbunden und warm mit unseren Arctic Touchscreen Winterhandschuhen. Diese Handschuhe sind nicht nur aus hochwertigem Acryl für Wärme und Haltbarkeit gefertigt, sondern verfügen auch über ein touchscreen-kompatibles Design. Mit isoliertem Futter, elastischen Bündchen für eine sichere Passform und einem stilvollen Look sind diese Handschuhe perfekt für den täglichen Gebrauch bei kaltem Wetter.',
                ],

                '4' => [
                    'description'      => 'Stellen Sie sich die Arctic Warmth Wool Blend Socks vor - Ihr unverzichtbarer Begleiter für gemütliche und bequeme Füße während der kalten Jahreszeiten. Hergestellt aus einer hochwertigen Mischung aus Merinowolle, Acryl, Nylon und Elasthan, sind diese Socken darauf ausgelegt, unübertroffene Wärme und Komfort zu bieten. Die Wollmischung sorgt dafür, dass Ihre Füße auch bei den kältesten Temperaturen warm bleiben und macht diese Socken zur perfekten Wahl für Winterabenteuer oder einfach nur zum Wohlfühlen zu Hause. Die weiche und gemütliche Textur der Socken bietet ein luxuriöses Gefühl auf Ihrer Haut. Verabschieden Sie sich von kalten Füßen, während Sie die kuschelige Wärme genießen, die diese Wollmischungssocken bieten. Für Langlebigkeit sind die Socken mit einer verstärkten Ferse und Zehen ausgestattet, die zusätzliche Stärke in stark beanspruchten Bereichen bieten. Dies gewährleistet, dass Ihre Socken den Test der Zeit bestehen und lang anhaltenden Komfort und Gemütlichkeit bieten. Die atmungsaktive Natur des Materials verhindert Überhitzung und ermöglicht es Ihren Füßen, den ganzen Tag über bequem und trocken zu bleiben. Egal, ob Sie im Winter wandern gehen oder sich drinnen entspannen, diese Socken bieten die perfekte Balance zwischen Wärme und Atmungsaktivität. Vielseitig und stilvoll sind diese Wollmischungssocken für verschiedene Anlässe geeignet. Kombinieren Sie sie mit Ihren Lieblingsstiefeln für einen modischen Winterlook oder tragen Sie sie im Haus für ultimativen Komfort. Heben Sie Ihre Wintergarderobe auf und setzen Sie Komfort an erste Stelle mit den Arctic Warmth Wool Blend Socks. Gönnen Sie Ihren Füßen den Luxus, den sie verdienen, und tauchen Sie ein in eine Welt des Wohlbefindens, das die ganze Saison über anhält.',
                    'meta-description' => 'Meta-Beschreibung',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Meta-Titel',
                    'name'             => 'Arctic Warmth Wool Blend Socks',
                    'sort-description' => 'Erleben Sie die unübertroffene Wärme und den Komfort unserer Arctic Warmth Wool Blend Socks. Hergestellt aus einer Mischung aus Merinowolle, Acryl, Nylon und Elasthan bieten diese Socken ultimativen Komfort für kaltes Wetter. Mit verstärkter Ferse und Zehen für Langlebigkeit sind diese vielseitigen und stilvollen Socken perfekt für verschiedene Anlässe.',
                ],

                '5' => [
                    'description'      => 'Stellen Sie sich das Arctic Frost Winter Accessories Bundle vor, Ihre Lösung, um während der kalten Wintertage warm, stilvoll und verbunden zu bleiben. Dieses sorgfältig zusammengestellte Set vereint vier unverzichtbare Winteraccessoires zu einem harmonischen Ensemble. Der luxuriöse Schal, gewebt aus einer Mischung aus Acryl und Wolle, bietet nicht nur eine zusätzliche Wärmeschicht, sondern verleiht auch Ihrer Wintergarderobe eine elegante Note. Die weiche Strickmütze, sorgfältig gefertigt, verspricht Gemütlichkeit und verleiht Ihrem Look eine modische Note. Aber damit nicht genug - unser Bundle enthält auch touchscreen-kompatible Handschuhe. Bleiben Sie verbunden, ohne auf Wärme zu verzichten, während Sie mühelos Ihre Geräte bedienen. Egal, ob Sie Anrufe entgegennehmen, Nachrichten senden oder Wintermomente auf Ihrem Smartphone festhalten, diese Handschuhe bieten Bequemlichkeit, ohne dabei den Stil zu beeinträchtigen. Die weiche und gemütliche Textur der Socken bietet ein luxuriöses Gefühl auf Ihrer Haut. Verabschieden Sie sich von kalten Füßen und genießen Sie die kuschelige Wärme, die diese Wollmischungssocken bieten. Das Arctic Frost Winter Accessories Bundle steht nicht nur für Funktionalität, sondern ist auch ein Statement der Wintermode. Jedes Stück ist nicht nur darauf ausgelegt, Sie vor der Kälte zu schützen, sondern auch Ihren Stil in der frostigen Jahreszeit zu unterstreichen. Die für dieses Bundle gewählten Materialien legen sowohl Wert auf Langlebigkeit als auch auf Komfort und sorgen dafür, dass Sie die Winterlandschaft stilvoll genießen können. Egal, ob Sie sich selbst verwöhnen oder das perfekte Geschenk suchen, das Arctic Frost Winter Accessories Bundle ist eine vielseitige Wahl. Bereiten Sie jemandem während der Feiertage eine Freude oder heben Sie Ihre eigene Wintergarderobe mit diesem stilvollen und funktionalen Ensemble auf ein neues Level. Seien Sie selbstbewusst im Frost und wissen Sie, dass Sie die perfekten Accessoires haben, um warm und schick zu bleiben.',
                    'meta-description' => 'Meta-Beschreibung',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Meta-Titel',
                    'name'             => 'Arctic Frost Winter Accessories',
                    'sort-description' => 'Begrüßen Sie den Winter mit unserem Arctic Frost Winter Accessories Bundle. Dieses sorgfältig zusammengestellte Set umfasst einen luxuriösen Schal, eine gemütliche Mütze, touchscreen-kompatible Handschuhe und Wollsocken. Stilvoll und funktional, ist dieses Ensemble aus hochwertigen Materialien gefertigt, die sowohl Langlebigkeit als auch Komfort gewährleisten. Heben Sie Ihre Wintergarderobe an oder erfreuen Sie jemanden Besonderen mit dieser perfekten Geschenkoption.',
                ],

                '6' => [
                    'description'      => 'Stellen Sie sich das Arctic Frost Winter Accessories Bundle vor, Ihre Lösung, um an den kalten Wintertagen warm, stilvoll und verbunden zu bleiben. Dieses sorgfältig zusammengestellte Set vereint vier unverzichtbare Winteraccessoires zu einem harmonischen Ensemble. Der luxuriöse Schal, gewebt aus einer Mischung aus Acryl und Wolle, bietet nicht nur eine zusätzliche Wärmeschicht, sondern verleiht auch Ihrem Winteroutfit eine elegante Note. Die weiche Strickmütze, mit Sorgfalt gefertigt, verspricht Ihnen Gemütlichkeit und verleiht Ihrem Look eine modische Note. Aber damit nicht genug - unser Bundle enthält auch touchscreen-kompatible Handschuhe. Bleiben Sie verbunden, ohne auf Wärme zu verzichten, während Sie mühelos Ihre Geräte bedienen. Egal, ob Sie Anrufe entgegennehmen, Nachrichten senden oder winterliche Momente auf Ihrem Smartphone festhalten, diese Handschuhe bieten Bequemlichkeit, ohne dabei den Stil zu beeinträchtigen. Die weiche und gemütliche Textur der Socken bietet ein luxuriöses Gefühl auf Ihrer Haut. Verabschieden Sie sich von kalten Füßen und genießen Sie die angenehme Wärme, die diese Wollsocken bieten. Das Arctic Frost Winter Accessories Bundle steht nicht nur für Funktionalität, sondern ist auch ein Statement der Wintermode. Jedes Stück ist nicht nur darauf ausgelegt, Sie vor der Kälte zu schützen, sondern auch Ihren Stil in der frostigen Jahreszeit zu unterstreichen. Die für dieses Bundle gewählten Materialien legen sowohl Wert auf Langlebigkeit als auch auf Komfort und ermöglichen es Ihnen, die Winterlandschaft stilvoll zu genießen. Egal, ob Sie sich selbst verwöhnen oder nach dem perfekten Geschenk suchen, das Arctic Frost Winter Accessories Bundle ist eine vielseitige Wahl. Bereiten Sie jemandem in der Weihnachtszeit eine Freude oder heben Sie Ihre eigene Wintergarderobe mit diesem stilvollen und funktionalen Ensemble auf ein neues Level. Seien Sie selbstbewusst im Frost unterwegs und wissen Sie, dass Sie die perfekten Accessoires haben, um warm und schick zu bleiben.',
                    'meta-description' => 'Meta-Beschreibung',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Meta-Titel',
                    'name'             => 'Arctic Frost Winter Accessories Bundle',
                    'sort-description' => 'Begrüßen Sie den Winter mit unserem Arctic Frost Winter Accessories Bundle. Dieses sorgfältig zusammengestellte Set umfasst einen luxuriösen Schal, eine gemütliche Mütze, touchscreen-kompatible Handschuhe und Wollsocken. Stilvoll und funktional, ist dieses Ensemble aus hochwertigen Materialien gefertigt, die sowohl Langlebigkeit als auch Komfort gewährleisten. Heben Sie Ihre Wintergarderobe an oder erfreuen Sie jemanden Besonderen mit dieser perfekten Geschenkoption.',
                ],

                '7' => [
                    'description'      => 'Stellen Sie sich die OmniHeat Herren Solid Hooded Puffer Jacke vor, Ihre Lösung, um warm und modisch in den kälteren Jahreszeiten zu bleiben. Diese Jacke wurde mit Haltbarkeit und Wärme im Hinterkopf entworfen und wird zu Ihrem vertrauenswürdigen Begleiter. Das Kapuzen-Design verleiht nicht nur einen Hauch von Stil, sondern bietet auch zusätzliche Wärme und schützt Sie vor kaltem Wind und Wetter. Die langen Ärmel bieten vollständige Abdeckung und sorgen dafür, dass Sie von der Schulter bis zum Handgelenk gemütlich bleiben. Ausgestattet mit Einstecktaschen bietet diese Pufferjacke Bequemlichkeit zum Mitführen Ihrer Essentials oder zum Warmhalten Ihrer Hände. Die isolierte synthetische Füllung bietet verbesserte Wärme und macht sie ideal für kalte Tage und Nächte. Hergestellt aus einem strapazierfähigen Polyester-Shell und Futter, ist diese Jacke gebaut, um zu halten und den Elementen standzuhalten. Erhältlich in 5 attraktiven Farben, können Sie diejenige wählen, die Ihrem Stil und Ihren Vorlieben entspricht. Vielseitig und funktional, ist die OmniHeat Herren Solid Hooded Puffer Jacke für verschiedene Anlässe geeignet, ob Sie zur Arbeit gehen, einen zwanglosen Ausflug machen oder an einer Outdoor-Veranstaltung teilnehmen. Erleben Sie die perfekte Mischung aus Stil, Komfort und Funktionalität mit der OmniHeat Herren Solid Hooded Puffer Jacke. Heben Sie Ihre Wintergarderobe an und bleiben Sie gemütlich, während Sie die Natur genießen. Besiegen Sie die Kälte mit Stil und setzen Sie mit diesem unverzichtbaren Stück ein Statement.',
                    'meta-description' => 'Meta-Beschreibung',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Meta-Titel',
                    'name'             => 'OmniHeat Herren Solid Hooded Puffer Jacke',
                    'sort-description' => 'Bleiben Sie warm und stilvoll mit unserer OmniHeat Herren Solid Hooded Puffer Jacke. Diese Jacke wurde entwickelt, um ultimative Wärme zu bieten und verfügt über Einstecktaschen für zusätzliche Bequemlichkeit. Das isolierte Material sorgt dafür, dass Sie in kaltem Wetter gemütlich bleiben. Erhältlich in 5 attraktiven Farben, ist sie eine vielseitige Wahl für verschiedene Anlässe.',
                ],

                '8' => [
                    'description'      => 'Introducing the OmniHeat Men\'s Solid Hooded Puffer Jacket, your go-to solution for staying warm and fashionable during colder seasons. This jacket is crafted with durability and warmth in mind, ensuring it becomes your trusted companion. The hooded design not only adds a touch of style but also provides additional warmth, shielding you from cold winds and weather. The full sleeves offer complete coverage, ensuring you stay cozy from shoulder to wrist. Equipped with insert pockets, this puffer jacket provides convenience for carrying your essentials or keeping your hands warm. The insulated synthetic filling offers enhanced warmth, making it ideal for battling chilly days and nights. Made from a durable polyester shell and lining, this jacket is built to last and endure the elements. Available in 5 attractive colors, you can choose the one that suits your style and preference. Versatile and functional, the OmniHeat Men\'s Solid Hooded Puffer Jacket is suitable for various occasions, whether you\'re heading to work, going for a casual outing, or attending an outdoor event. Experience the perfect blend of style, comfort, and functionality with OmniHeat Men\'s Solid Hooded Puffer Jacket. Elevate your winter wardrobe and stay snug while embracing the outdoors. Beat the cold in style and make a statement with this essential piece.',
                    'meta-description' => 'meta description',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Meta-Titel',
                    'name'             => 'OmniHeat Herren Solide Kapuzenpufferjacke-Blau-Gelb-M',
                    'sort-description' => 'Bleiben Sie warm und stilvoll mit unserer OmniHeat Herren Solid Hooded Puffer Jacke. Diese Jacke wurde entwickelt, um ultimative Wärme zu bieten und verfügt über Einstecktaschen für zusätzliche Bequemlichkeit. Das isolierte Material sorgt dafür, dass Sie in kaltem Wetter gemütlich bleiben. Erhältlich in 5 attraktiven Farben, ist sie eine vielseitige Wahl für verschiedene Anlässe.',
                ],

                '9' => [
                    'description'      => 'Introducing the OmniHeat Men\'s Solid Hooded Puffer Jacket, your go-to solution for staying warm and fashionable during colder seasons. This jacket is crafted with durability and warmth in mind, ensuring it becomes your trusted companion. The hooded design not only adds a touch of style but also provides additional warmth, shielding you from cold winds and weather. The full sleeves offer complete coverage, ensuring you stay cozy from shoulder to wrist. Equipped with insert pockets, this puffer jacket provides convenience for carrying your essentials or keeping your hands warm. The insulated synthetic filling offers enhanced warmth, making it ideal for battling chilly days and nights. Made from a durable polyester shell and lining, this jacket is built to last and endure the elements. Available in 5 attractive colors, you can choose the one that suits your style and preference. Versatile and functional, the OmniHeat Men\'s Solid Hooded Puffer Jacket is suitable for various occasions, whether you\'re heading to work, going for a casual outing, or attending an outdoor event. Experience the perfect blend of style, comfort, and functionality with OmniHeat Men\'s Solid Hooded Puffer Jacket. Elevate your winter wardrobe and stay snug while embracing the outdoors. Beat the cold in style and make a statement with this essential piece.',
                    'meta-description' => 'meta description',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Meta-Titel',
                    'name'             => 'OmniHeat Herren Solide Kapuzenpufferjacke-Blau-Gelb-L',
                    'sort-description' => 'Bleiben Sie warm und stilvoll mit unserer OmniHeat Herren Solid Hooded Puffer Jacke. Diese Jacke wurde entwickelt, um ultimative Wärme zu bieten und verfügt über Einstecktaschen für zusätzliche Bequemlichkeit. Das isolierte Material sorgt dafür, dass Sie in kaltem Wetter gemütlich bleiben. Erhältlich in 5 attraktiven Farben, ist sie eine vielseitige Wahl für verschiedene Anlässe.',
                ],

                '10' => [
                    'description'      => 'Stellen Sie sich die OmniHeat Herren Solide Kapuzenpufferjacke vor, Ihre Lösung, um warm und modisch in den kälteren Jahreszeiten zu bleiben. Diese Jacke wurde mit Haltbarkeit und Wärme im Hinterkopf entwickelt und wird zu Ihrem vertrauenswürdigen Begleiter. Das Kapuzen-Design verleiht nicht nur einen Hauch von Stil, sondern bietet auch zusätzliche Wärme und schützt Sie vor kaltem Wind und Wetter. Die langen Ärmel bieten vollständige Abdeckung und sorgen dafür, dass Sie von der Schulter bis zum Handgelenk gemütlich bleiben. Ausgestattet mit Einstecktaschen bietet diese Pufferjacke Bequemlichkeit zum Mitführen Ihrer Essentials oder zum Warmhalten Ihrer Hände. Die isolierte synthetische Füllung bietet verbesserte Wärme und eignet sich ideal für kalte Tage und Nächte. Hergestellt aus einem strapazierfähigen Polyester-Shell und Futter, ist diese Jacke langlebig und trotzt den Elementen. Erhältlich in 5 attraktiven Farben, können Sie diejenige wählen, die Ihrem Stil und Ihren Vorlieben entspricht. Vielseitig und funktional, ist die OmniHeat Herren Solide Kapuzenpufferjacke für verschiedene Anlässe geeignet, ob Sie zur Arbeit gehen, einen zwanglosen Ausflug machen oder an einer Outdoor-Veranstaltung teilnehmen. Erleben Sie die perfekte Kombination aus Stil, Komfort und Funktionalität mit der OmniHeat Herren Solide Kapuzenpufferjacke. Heben Sie Ihre Wintergarderobe an und bleiben Sie gemütlich, während Sie die Natur genießen. Besiegen Sie die Kälte mit Stil und setzen Sie mit diesem unverzichtbaren Stück ein Statement.',
                    'meta-description' => 'Meta-Beschreibung',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Meta-Titel',
                    'name'             => 'OmniHeat Herren Solide Kapuzenpufferjacke-Blau-Grün-M',
                    'sort-description' => 'Bleiben Sie warm und stilvoll mit unserer OmniHeat Herren Solide Kapuzenpufferjacke. Diese Jacke wurde entwickelt, um ultimative Wärme zu bieten und verfügt über Einstecktaschen für zusätzliche Bequemlichkeit. Das isolierte Material sorgt dafür, dass Sie in kaltem Wetter gemütlich bleiben. Erhältlich in 5 attraktiven Farben, ist sie eine vielseitige Wahl für verschiedene Anlässe.',
                ],

                '11' => [
                    'description'      => 'Stellen Sie sich die OmniHeat Herren Solide Kapuzenpufferjacke vor, Ihre Lösung, um warm und modisch in den kälteren Jahreszeiten zu bleiben. Diese Jacke wurde mit Haltbarkeit und Wärme im Hinterkopf entwickelt und wird zu Ihrem vertrauenswürdigen Begleiter. Das Kapuzen-Design verleiht nicht nur einen Hauch von Stil, sondern bietet auch zusätzliche Wärme und schützt Sie vor kaltem Wind und Wetter. Die langen Ärmel bieten vollständige Abdeckung und sorgen dafür, dass Sie von der Schulter bis zum Handgelenk gemütlich bleiben. Ausgestattet mit Einstecktaschen bietet diese Pufferjacke Bequemlichkeit zum Mitführen Ihrer Essentials oder zum Warmhalten Ihrer Hände. Die isolierte synthetische Füllung bietet verbesserte Wärme und eignet sich ideal für kalte Tage und Nächte. Hergestellt aus einem strapazierfähigen Polyester-Shell und Futter, ist diese Jacke langlebig und trotzt den Elementen. Erhältlich in 5 attraktiven Farben, können Sie diejenige wählen, die Ihrem Stil und Ihren Vorlieben entspricht. Vielseitig und funktional, ist die OmniHeat Herren Solide Kapuzenpufferjacke für verschiedene Anlässe geeignet, ob Sie zur Arbeit gehen, einen zwanglosen Ausflug machen oder an einer Outdoor-Veranstaltung teilnehmen. Erleben Sie die perfekte Kombination aus Stil, Komfort und Funktionalität mit der OmniHeat Herren Solide Kapuzenpufferjacke. Heben Sie Ihre Wintergarderobe an und bleiben Sie gemütlich, während Sie die Natur genießen. Besiegen Sie die Kälte mit Stil und setzen Sie mit diesem unverzichtbaren Stück ein Statement.',
                    'meta-description' => 'Meta-Beschreibung',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Meta-Titel',
                    'name'             => 'OmniHeat Herren Solide Kapuzenpufferjacke-Blau-Grün-L',
                    'sort-description' => 'Bleiben Sie warm und stilvoll mit unserer OmniHeat Herren Solide Kapuzenpufferjacke. Diese Jacke wurde entwickelt, um ultimative Wärme zu bieten und verfügt über Einstecktaschen für zusätzliche Bequemlichkeit. Das isolierte Material sorgt dafür, dass Sie in kaltem Wetter gemütlich bleiben. Erhältlich in 5 attraktiven Farben, ist sie eine vielseitige Wahl für verschiedene Anlässe.',
                ],
            ],

            'product-bundle-option-translations' => [
                '1' => [
                    'label' => 'Bundle-Option 1',
                ],

                '2' => [
                    'label' => 'Bundle-Option 1',
                ],

                '3' => [
                    'label' => 'Bundle-Option 2',
                ],

                '4' => [
                    'label' => 'Bundle-Option 2',
                ],
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
                'warning-message'             => 'Achtung! Die Einstellungen für Ihre Standardsystemsprache und Standardwährung sind dauerhaft und können nach der Festlegung nicht mehr geändert werden.',
                'zambian-kwacha'              => 'Sambischer Kwacha (ZMW)',
            ],

            'sample-products' => [
                'download-sample' => 'Beispiel herunterladen',
                'no'              => 'Nein',
                'sample-products' => 'Beispielfprodukte',
                'title'           => 'Beispielfprodukte',
                'yes'             => 'Ja',
            ],

            'installation-processing' => [
                'title'        => 'Installation',
                'bagisto-info' => 'Erstellung der Datenbanktabellen, dies kann einige Momente dauern',
                'bagisto'      => 'Installation Bagisto',
            ],

            'installation-completed' => [
                'admin-panel'                => 'Admin-Panel',
                'bagisto-forums'             => 'Bagisto Forum',
                'customer-panel'             => 'Kundenpanel',
                'explore-bagisto-extensions' => 'Erkunden Sie Bagisto-Erweiterungen',
                'title'                      => 'Installation abgeschlossen',
                'title-info'                 => 'Bagisto wurde erfolgreich auf Ihrem System installiert.',
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
                'welcome-title' => 'Willkommen bei Bagisto',
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
            'installation-description' => 'Die Installation von Bagisto umfasst in der Regel mehrere Schritte. Hier ist eine allgemeine Übersicht über den Installationsprozess für Bagisto',
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
