<?php

return [
    'seeders' => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'Standaard',
            ],

            'attribute-groups' => [
                'description' => 'Beschrijving',
                'general' => 'Algemeen',
                'inventories' => 'Voorraden',
                'meta-description' => 'Meta Beschrijving',
                'price' => 'Prijs',
                'rma' => 'RMA',
                'settings' => 'Instellingen',
                'shipping' => 'Verzending',
            ],

            'attributes' => [
                'allow-rma' => 'RMA toestaan',
                'brand' => 'Merk',
                'color' => 'Kleur',
                'cost' => 'Kostprijs',
                'description' => 'Beschrijving',
                'featured' => 'Uitgelicht',
                'guest-checkout' => 'Gast Uitchecken',
                'height' => 'Hoogte',
                'length' => 'Lengte',
                'manage-stock' => 'Voorraad Beheren',
                'meta-description' => 'Meta Beschrijving',
                'meta-keywords' => 'Meta Sleutelwoorden',
                'meta-title' => 'Meta Titel',
                'name' => 'Naam',
                'new' => 'Nieuw',
                'price' => 'Prijs',
                'product-number' => 'Productnummer',
                'rma-rules' => 'RMA-regels',
                'short-description' => 'Korte Beschrijving',
                'size' => 'Maat',
                'sku' => 'SKU',
                'special-price' => 'Speciale Prijs',
                'special-price-from' => 'Speciale Prijs Vanaf',
                'special-price-to' => 'Speciale Prijs Tot',
                'status' => 'Status',
                'tax-category' => 'Belastingcategorie',
                'url-key' => 'URL Sleutel',
                'visible-individually' => 'Individueel Zichtbaar',
                'weight' => 'Gewicht',
                'width' => 'Breedte',
            ],

            'attribute-options' => [
                'black' => 'Zwart',
                'green' => 'Groen',
                'l' => 'L',
                'm' => 'M',
                'red' => 'Rood',
                's' => 'S',
                'white' => 'Wit',
                'xl' => 'XL',
                'yellow' => 'Geel',
            ],
        ],

        'category' => [
            'categories' => [
                'description' => 'Root Categorie Beschrijving',
                'name' => 'Root',
            ],
        ],

        'cms' => [
            'pages' => [
                'about-us' => [
                    'content' => 'Over Ons Pagina Inhoud',
                    'title' => 'Over Ons',
                ],

                'contact-us' => [
                    'content' => 'Contacteer Ons Pagina Inhoud',
                    'title' => 'Contacteer Ons',
                ],

                'customer-service' => [
                    'content' => 'Klantenservice Pagina Inhoud',
                    'title' => 'Klantenservice',
                ],

                'payment-policy' => [
                    'content' => 'Betalingsbeleid Pagina Inhoud',
                    'title' => 'Betalingsbeleid',
                ],

                'privacy-policy' => [
                    'content' => 'Privacybeleid Pagina Inhoud',
                    'title' => 'Privacybeleid',
                ],

                'refund-policy' => [
                    'content' => 'Retourbeleid Pagina Inhoud',
                    'title' => 'Retourbeleid',
                ],

                'return-policy' => [
                    'content' => 'Terugstuurbeleid Pagina Inhoud',
                    'title' => 'Terugstuurbeleid',
                ],

                'shipping-policy' => [
                    'content' => 'Verzendingsbeleid Pagina Inhoud',
                    'title' => 'Verzendingsbeleid',
                ],

                'terms-conditions' => [
                    'content' => 'Algemene Voorwaarden Pagina Inhoud',
                    'title' => 'Algemene Voorwaarden',
                ],

                'terms-of-use' => [
                    'content' => 'Gebruiksvoorwaarden Pagina Inhoud',
                    'title' => 'Gebruiksvoorwaarden',
                ],

                'whats-new' => [
                    'content' => 'Wat is nieuw pagina inhoud',
                    'title' => 'Wat is nieuw',
                ],
            ],
        ],

        'core' => [
            'channels' => [
                'meta-description' => 'Demo winkel meta beschrijving',
                'meta-keywords' => 'Demo winkel meta trefwoorden',
                'meta-title' => 'Demo winkel',
                'name' => 'Standaard',
            ],

            'currencies' => [
                'AED' => 'Verenigde Arabische Emiraten dirham',
                'ARS' => 'Argentijnse peso',
                'AUD' => 'Australische dollar',
                'BDT' => 'Bangladeshische taka',
                'BHD' => 'Bahreinse dinar',
                'BRL' => 'Braziliaanse real',
                'CAD' => 'Canadese dollar',
                'CHF' => 'Zwitserse frank',
                'CLP' => 'Chileense peso',
                'CNY' => 'Chinese yuan',
                'COP' => 'Colombiaanse peso',
                'CZK' => 'Tsjechische kroon',
                'DKK' => 'Deense kroon',
                'DZD' => 'Algerijnse dinar',
                'EGP' => 'Egyptisch pond',
                'EUR' => 'Euro',
                'FJD' => 'Fijische dollar',
                'GBP' => 'Brits pond sterling',
                'HKD' => 'Hongkongse dollar',
                'HUF' => 'Hongaarse forint',
                'IDR' => 'Indonesische roepia',
                'ILS' => 'Israëlische nieuwe shekel',
                'INR' => 'Indiase roepie',
                'JOD' => 'Jordaanse dinar',
                'JPY' => 'Japanse yen',
                'KRW' => 'Zuid-Koreaanse won',
                'KWD' => 'Koeweitse dinar',
                'KZT' => 'Kazachse tenge',
                'LBP' => 'Libanees pond',
                'LKR' => 'Sri Lankaanse roepie',
                'LYD' => 'Libische dinar',
                'MAD' => 'Marokkaanse dirham',
                'MUR' => 'Mauritiaanse roepie',
                'MXN' => 'Mexicaanse peso',
                'MYR' => 'Maleisische ringgit',
                'NGN' => 'Nigeriaanse naira',
                'NOK' => 'Noorse kroon',
                'NPR' => 'Nepalese roepie',
                'NZD' => 'Nieuw-Zeelandse dollar',
                'OMR' => 'Omaanse rial',
                'PAB' => 'Panamese balboa',
                'PEN' => 'Peruaanse nuevo sol',
                'PHP' => 'Filipijnse peso',
                'PKR' => 'Pakistaanse roepie',
                'PLN' => 'Poolse złoty',
                'PYG' => 'Paraguayaanse guarani',
                'QAR' => 'Qatarese rial',
                'RON' => 'Roemeense leu',
                'RUB' => 'Russische roebel',
                'SAR' => 'Saoedi-Arabische riyal',
                'SEK' => 'Zweedse kroon',
                'SGD' => 'Singaporese dollar',
                'THB' => 'Thaise baht',
                'TND' => 'Tunesische dinar',
                'TRY' => 'Turkse lira',
                'TWD' => 'Nieuwe Taiwanese dollar',
                'UAH' => 'Oekraïense hryvnia',
                'USD' => 'Amerikaanse dollar',
                'UZS' => 'Oezbeekse som',
                'VEF' => 'Venezolaanse bolívar',
                'VND' => 'Vietnamese dong',
                'XAF' => 'CFA-frank BEAC',
                'XOF' => 'CFA-frank BCEAO',
                'ZAR' => 'Zuid-Afrikaanse rand',
                'ZMW' => 'Zambiaanse kwacha',
            ],

            'locales' => [
                'ar' => 'Arabisch',
                'bn' => 'Bengali',
                'ca' => 'Catalaans',
                'de' => 'Duits',
                'en' => 'Engels',
                'es' => 'Spaans',
                'fa' => 'Perzisch',
                'fr' => 'Frans',
                'he' => 'Hebreeuws',
                'hi_IN' => 'Hindi',
                'id' => 'Indonesisch',
                'it' => 'Italiaans',
                'ja' => 'Japans',
                'nl' => 'Nederlands',
                'pl' => 'Pools',
                'pt_BR' => 'Braziliaans Portugees',
                'ru' => 'Russisch',
                'sin' => 'Singalees',
                'tr' => 'Turks',
                'uk' => 'Oekraïens',
                'zh_CN' => 'Chinees',
            ],
        ],

        'customer' => [
            'customer-groups' => [
                'general' => 'Algemeen',
                'guest' => 'Gast',
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
                'all-products' => [
                    'name' => 'Alle Producten',

                    'options' => [
                        'title' => 'Alle Producten',
                    ],
                ],

                'bold-collections' => [
                    'content' => [
                        'btn-title' => 'Bekijk Collecties',
                        'description' => 'Maak kennis met onze nieuwe gedurfde collecties! Verhoog je stijl met gedurfde ontwerpen en levendige statements. Verken opvallende patronen en gedurfde kleuren die je garderobe opnieuw definiëren. Maak je klaar om het buitengewone te omarmen!',
                        'title' => 'Maak je klaar voor onze nieuwe gedurfde collecties!',
                    ],

                    'name' => 'Gedurfde Collecties',
                ],

                'bold-collections-2' => [
                    'content' => [
                        'btn-title' => 'Collecties Bekijken',
                        'description' => 'Onze Gedurfde Collecties zijn hier om je garderobe opnieuw te definiëren met onverschrokken ontwerpen en opvallende, levendige kleuren. Van gedurfde patronen tot krachtige tinten, dit is je kans om los te breken van het gewone en het buitengewone binnen te stappen.',
                        'title' => 'Ontgrendel Je Durf met Onze Nieuwe Collectie!',
                    ],

                    'name' => 'Gedurfde Collecties',
                ],

                'booking-products' => [
                    'name' => 'Boekingsproducten',

                    'options' => [
                        'title' => 'Tickets Boeken',
                    ],
                ],

                'categories-collections' => [
                    'name' => 'Categorieën Collecties',
                ],

                'featured-collections' => [
                    'name' => 'Uitgelichte Collecties',

                    'options' => [
                        'title' => 'Uitgelichte Producten',
                    ],
                ],

                'footer-links' => [
                    'name' => 'Footer Links',

                    'options' => [
                        'about-us' => 'Over Ons',
                        'contact-us' => 'Contacteer Ons',
                        'customer-service' => 'Klantenservice',
                        'payment-policy' => 'Betalingsbeleid',
                        'privacy-policy' => 'Privacybeleid',
                        'refund-policy' => 'Retourbeleid',
                        'return-policy' => 'Terugstuurbeleid',
                        'shipping-policy' => 'Verzendingsbeleid',
                        'terms-conditions' => 'Algemene Voorwaarden',
                        'terms-of-use' => 'Gebruiksvoorwaarden',
                        'whats-new' => 'Wat is nieuw',
                    ],
                ],

                'game-container' => [
                    'content' => [
                        'sub-title-1' => 'Onze Collecties',
                        'sub-title-2' => 'Onze Collecties',
                        'title' => 'Het spel met onze nieuwe toevoegingen!',
                    ],

                    'name' => 'Spelcontainer',
                ],

                'image-carousel' => [
                    'name' => 'Afbeeldingencarrousel',

                    'sliders' => [
                        'title' => 'Maak je klaar voor de nieuwe collectie',
                    ],
                ],

                'new-products' => [
                    'name' => 'Nieuwe Producten',

                    'options' => [
                        'title' => 'Nieuwe Producten',
                    ],
                ],

                'offer-information' => [
                    'content' => [
                        'title' => 'KRIJG TOT 40% KORTING op je 1e bestelling NU WINKELEN',
                    ],

                    'name' => 'Aanbiedingsinformatie',
                ],

                'services-content' => [
                    'description' => [
                        'emi-available-info' => 'Geen kosten EMI beschikbaar op alle belangrijke creditcards',
                        'free-shipping-info' => 'Geniet van gratis verzending op alle bestellingen',
                        'product-replace-info' => 'Eenvoudige productvervanging beschikbaar!',
                        'time-support-info' => 'Toegewijde 24/7 ondersteuning via chat en e-mail',
                    ],

                    'name' => 'Diensteninhoud',

                    'title' => [
                        'emi-available' => 'EMI beschikbaar',
                        'free-shipping' => 'Gratis verzending',
                        'product-replace' => 'Product vervangen',
                        'time-support' => '24/7 ondersteuning',
                    ],
                ],

                'top-collections' => [
                    'content' => [
                        'sub-title-1' => 'Onze Collecties',
                        'sub-title-2' => 'Onze Collecties',
                        'sub-title-3' => 'Onze Collecties',
                        'sub-title-4' => 'Onze Collecties',
                        'sub-title-5' => 'Onze Collecties',
                        'sub-title-6' => 'Onze Collecties',
                        'title' => 'Het spel met onze nieuwe toevoegingen!',
                    ],

                    'name' => 'Top Collecties',
                ],
            ],
        ],

        'user' => [
            'roles' => [
                'description' => 'Deze rol geeft gebruikers alle toegang',
                'name' => 'Beheerder',
            ],

            'users' => [
                'name' => 'Voorbeeld',
            ],
        ],

        'sample-categories' => [
            'category-translation' => [
                '2' => [
                    'description' => '<p>Heren</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Heren',
                    'slug' => 'mens',
                    'url-path' => 'men',
                ],

                '3' => [
                    'description' => '<p>Kinderen</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Kinderen',
                    'slug' => 'kids',
                    'url-path' => 'kids',
                ],

                '4' => [
                    'description' => '<p>Dames</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Dames',
                    'slug' => 'womens',
                    'url-path' => 'woman',
                ],

                '5' => [
                    'description' => '<p>Formele Kleding</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Formele Kleding',
                    'slug' => 'formal-wear-men',
                    'url-path' => 'men/formal-wear-men',
                ],

                '6' => [
                    'description' => '<p>Casual Kleding</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Casual Kleding',
                    'slug' => 'casual-wear-men',
                    'url-path' => 'men/casual-wear-men',
                ],

                '7' => [
                    'description' => '<p>Sportkleding</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Sportkleding',
                    'slug' => 'active-wear',
                    'url-path' => 'men/active-wear',
                ],

                '8' => [
                    'description' => '<p>Schoenen</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Schoenen',
                    'slug' => 'footwear',
                    'url-path' => 'men/footwear',
                ],

                '9' => [
                    'description' => '<p><span>Formele Kleding</span></p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Formele Kleding',
                    'slug' => 'formal-wear-female',
                    'url-path' => 'woman/formal-wear-female',
                ],

                '10' => [
                    'description' => '<p>Casual Kleding</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Casual Kleding',
                    'slug' => 'casual-wear-female',
                    'url-path' => 'woman/casual-wear-female',
                ],

                '11' => [
                    'description' => '<p>Sportkleding</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Sportkleding',
                    'slug' => 'active-wear-female',
                    'url-path' => 'woman/active-wear-female',
                ],

                '12' => [
                    'description' => '<p>Schoenen</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Schoenen',
                    'slug' => 'footwear-female',
                    'url-path' => 'woman/footwear-female',
                ],

                '13' => [
                    'description' => '<p>Meisjeskleding</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => 'Meisjeskleding',
                    'name' => 'Meisjeskleding',
                    'slug' => 'girls-clothing',
                    'url-path' => 'kids/girls-clothing',
                ],

                '14' => [
                    'description' => '<p>Jongenskleding</p>',
                    'meta-description' => 'Jongensmode',
                    'meta-keywords' => '',
                    'meta-title' => 'Jongenskleding',
                    'name' => 'Jongenskleding',
                    'slug' => 'boys-clothing',
                    'url-path' => 'kids/boys-clothing',
                ],

                '15' => [
                    'description' => '<p>Meisjesschoenen</p>',
                    'meta-description' => 'Modieuze Schoenen Collectie voor Meisjes',
                    'meta-keywords' => '',
                    'meta-title' => 'Meisjesschoenen',
                    'name' => 'Meisjesschoenen',
                    'slug' => 'girls-footwear',
                    'url-path' => 'kids/girls-footwear',
                ],

                '16' => [
                    'description' => '<p>Jongensschoenen</p>',
                    'meta-description' => 'Stijlvolle Schoenen Collectie voor Jongens',
                    'meta-keywords' => '',
                    'meta-title' => 'Jongensschoenen',
                    'name' => 'Jongensschoenen',
                    'slug' => 'boys-footwear',
                    'url-path' => 'kids/boys-footwear',
                ],

                '17' => [
                    'description' => '<p>Welzijn</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Welzijn',
                    'slug' => 'wellness',
                    'url-path' => 'wellness',
                ],

                '18' => [
                    'description' => '<p>Downloadbare Yoga Tutorial</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Downloadbare Yoga Tutorial',
                    'slug' => 'downloadable-yoga-tutorial',
                    'url-path' => 'wellness/downloadable-yoga-tutorial',
                ],

                '19' => [
                    'description' => '<p>E-Boeken Collectie</p>',
                    'meta-description' => 'E-Boeken Collectie',
                    'meta-keywords' => '',
                    'meta-title' => 'E-Boeken Collectie',
                    'name' => 'E-Boeken',
                    'slug' => 'e-books',
                    'url-path' => 'wellness/e-books',
                ],

                '20' => [
                    'description' => '<p>Bioscoop Pas</p>',
                    'meta-description' => 'Dompel je onder in de magie van 10 films per maand zonder extra kosten.',
                    'meta-keywords' => '',
                    'meta-title' => 'CineXperience Maandelijkse Bioscoop Pas',
                    'name' => 'Bioscoop Pas',
                    'slug' => 'movie-pass',
                    'url-path' => 'wellness/movie-pass',
                ],

                '21' => [
                    'description' => '<p>Beheer en verkoop eenvoudig uw reserveringsproducten met ons naadloze boekingssysteem.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Reserveringen',
                    'slug' => 'bookings',
                    'url-path' => '',
                ],

                '22' => [
                    'description' => '<p>Afspraakboekingen stellen klanten in staat om tijdslots te plannen voor diensten of consultaties met bedrijven of professionals.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Afspraakboeking',
                    'slug' => 'appointment-booking',
                    'url-path' => '',
                ],

                '23' => [
                    'description' => '<p>Evenementboekingen stellen individuen of groepen in staat om zich te registreren of plaatsen te reserveren voor openbare of privé-evenementen.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Evenementboeking',
                    'slug' => 'event-booking',
                    'url-path' => '',
                ],

                '24' => [
                    'description' => '<p>Gemeenschapszaal reserveringen stellen individuen, organisaties of groepen in staat om gemeenschappelijke ruimtes te reserveren voor verschillende evenementen.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Gemeenschapszaal Reserveringen',
                    'slug' => 'community-hall-bookings',
                    'url-path' => '',
                ],

                '25' => [
                    'description' => '<p>Tafelreserveringen stellen klanten in staat om vooraf tafels te reserveren in restaurants, cafés of eetgelegenheden.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Tafelreservering',
                    'slug' => 'table-booking',
                    'url-path' => '',
                ],

                '26' => [
                    'description' => '<p>Huurboeking faciliteert de reservering van artikelen of eigendommen voor tijdelijk gebruik.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Huurboeking',
                    'slug' => 'rental-booking',
                    'url-path' => '',
                ],

                '27' => [
                    'description' => '<p>Ontdek de nieuwste consumentenelektronica, ontworpen om u verbonden, productief en vermaakt te houden.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Elektronica',
                    'slug' => 'electronics',
                    'url-path' => '',
                ],

                '28' => [
                    'description' => '<p>Ontdek smartphones, opladers, hoesjes en andere essentiële zaken om onderweg verbonden te blijven.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Mobiele Telefoons & Accessoires',
                    'slug' => 'mobile-phones-accessories',
                    'url-path' => '',
                ],

                '29' => [
                    'description' => '<p>Vind krachtige laptops en draagbare tablets voor werk, studie en ontspanning.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Laptops & Tablets',
                    'slug' => 'laptops-tablets',
                    'url-path' => '',
                ],

                '30' => [
                    'description' => '<p>Shop koptelefoons, oordopjes en speakers voor kristalhelder geluid.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Audio Apparaten',
                    'slug' => 'audio-devices',
                    'url-path' => '',
                ],

                '31' => [
                    'description' => '<p>Maak het leven gemakkelijker met slimme verlichting, thermostaten, beveiligingssystemen en meer.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Smart Home & Automatisering',
                    'slug' => 'smart-home-automation',
                    'url-path' => '',
                ],

                '32' => [
                    'description' => '<p>Upgrade uw leefruimte met functionele en stijlvolle huis- en keukenbenodigdheden.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Huishouden',
                    'slug' => 'household',
                    'url-path' => '',
                ],

                '33' => [
                    'description' => '<p>Blader door blenders, airfryers, koffiezetapparaten en meer om maaltijdbereiding te vereenvoudigen.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Keukenapparatuur',
                    'slug' => 'kitchen-appliances',
                    'url-path' => '',
                ],

                '34' => [
                    'description' => '<p>Ontdek kookgerei sets, keukengerei, serviesgoed en tafelgerei voor uw culinaire behoeften.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Kookgerei & Tafelen',
                    'slug' => 'cookware-dining',
                    'url-path' => '',
                ],

                '35' => [
                    'description' => '<p>Voeg comfort en charme toe met banken, tafels, wandkunst en woondecoratie.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Meubels & Decoratie',
                    'slug' => 'furniture-decor',
                    'url-path' => '',
                ],

                '36' => [
                    'description' => '<p>Houd uw ruimte schoon met stofzuigers, schoonmaaksprays, bezems en organizers.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Schoonmaakbenodigdheden',
                    'slug' => 'cleaning-supplies',
                    'url-path' => '',
                ],

                '37' => [
                    'description' => '<p>Ontsteek uw verbeelding of organiseer uw werkruimte met een brede selectie boeken en kantoorartikelen.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Boeken & Kantoorartikelen',
                    'slug' => 'books-stationery',
                    'url-path' => '',
                ],

                '38' => [
                    'description' => '<p>Duik in bestsellers, biografieën, zelfhulp en meer.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Fictie & Non-Fictie Boeken',
                    'slug' => 'fiction-non-fiction-books',
                    'url-path' => '',
                ],

                '39' => [
                    'description' => '<p>Vind studieboeken, naslagmateriaal en studiegidsen voor alle leeftijden.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Educatief & Academisch',
                    'slug' => 'educational-academic',
                    'url-path' => '',
                ],

                '40' => [
                    'description' => '<p>Shop pennen, notitieboekjes, planners en kantoorbenodigdheden voor productiviteit.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Kantoorbenodigdheden',
                    'slug' => 'office-supplies',
                    'url-path' => '',
                ],

                '41' => [
                    'description' => '<p>Ontdek verf, penselen, schetsboeken en DIY knutselpakketten voor creatievelingen.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Kunst & Knutselmateriaal',
                    'slug' => 'art-craft-materials',
                    'url-path' => '',
                ],
            ],
        ],
    ],

    'installer' => [
        'middleware' => [
            'already-installed' => 'Applicatie is al geïnstalleerd.',
        ],

        'index' => [
            'create-administrator' => [
                'admin' => 'Beheerder',
                'bagisto' => 'Bagisto',
                'confirm-password' => 'Bevestig wachtwoord',
                'email' => 'E-mail',
                'email-address' => 'admin@example.com',
                'password' => 'Wachtwoord',
                'title' => 'Beheerder aanmaken',
            ],

            'environment-configuration' => [
                'algerian-dinar' => 'Algerijnse Dinar (DZD)',
                'allowed-currencies' => 'Toegestane Valuta',
                'allowed-locales' => 'Toegestane Locaties',
                'application-name' => 'Toepassingsnaam',
                'argentine-peso' => 'Argentijnse Peso (ARS)',
                'australian-dollar' => 'Australische Dollar (AUD)',
                'bagisto' => 'Bagisto',
                'bangladeshi-taka' => 'Bangladesh Taka (BDT)',
                'bahraini-dinar' => 'Bahreinse Dinar (BHD)',
                'brazilian-real' => 'Braziliaanse Real (BRL)',
                'british-pound-sterling' => 'Britse Pond Sterling (GBP)',
                'canadian-dollar' => 'Canadese Dollar (CAD)',
                'cfa-franc-bceao' => 'CFA Franc BCEAO (XOF)',
                'cfa-franc-beac' => 'CFA Franc BEAC (XAF)',
                'chilean-peso' => 'Chileense Peso (CLP)',
                'chinese-yuan' => 'Chinese Yuan (CNY)',
                'colombian-peso' => 'Colombiaanse Peso (COP)',
                'czech-koruna' => 'Tsjechische Kroon (CZK)',
                'danish-krone' => 'Deense Kroon (DKK)',
                'database-connection' => 'Database Verbinding',
                'database-hostname' => 'Database Hostnaam',
                'database-name' => 'Database Naam',
                'database-password' => 'Database Wachtwoord',
                'database-port' => 'Database Poort',
                'database-prefix' => 'Database Voorvoegsel',
                'database-prefix-help' => 'De prefix moet 4 tekens lang zijn en mag alleen letters, cijfers en onderstrepingstekens bevatten.',
                'database-username' => 'Database Gebruikersnaam',
                'default-currency' => 'Standaard Valuta',
                'default-locale' => 'Standaard Locatie',
                'default-timezone' => 'Standaard Tijdzone',
                'default-url' => 'Standaard URL',
                'default-url-link' => 'https://localhost',
                'egyptian-pound' => 'Egyptische Pond (EGP)',
                'euro' => 'Euro (EUR)',
                'fijian-dollar' => 'Fijische Dollar (FJD)',
                'hong-kong-dollar' => 'Hongkong Dollar (HKD)',
                'hungarian-forint' => 'Hongaarse Forint (HUF)',
                'indian-rupee' => 'Indiase Roepie (INR)',
                'indonesian-rupiah' => 'Indonesische Roepia (IDR)',
                'israeli-new-shekel' => 'Israëlische Nieuwe Shekel (ILS)',
                'japanese-yen' => 'Japanse Yen (JPY)',
                'jordanian-dinar' => 'Jordaanse Dinar (JOD)',
                'kazakhstani-tenge' => 'Kazachse Tenge (KZT)',
                'kuwaiti-dinar' => 'Koeweitse Dinar (KWD)',
                'lebanese-pound' => 'Libanese Pond (LBP)',
                'libyan-dinar' => 'Libische Dinar (LYD)',
                'malaysian-ringgit' => 'Maleisische Ringgit (MYR)',
                'mauritian-rupee' => 'Mauritiaanse Roepie (MUR)',
                'mexican-peso' => 'Mexicaanse Peso (MXN)',
                'moroccan-dirham' => 'Marokkaanse Dirham (MAD)',
                'mysql' => 'Mysql',
                'nepalese-rupee' => 'Nepalese Roepie (NPR)',
                'new-taiwan-dollar' => 'Nieuwe Taiwanese Dollar (TWD)',
                'new-zealand-dollar' => 'Nieuw-Zeelandse Dollar (NZD)',
                'nigerian-naira' => 'Nigeriaanse Naira (NGN)',
                'norwegian-krone' => 'Noorse Kroon (NOK)',
                'omani-rial' => 'Omaanse Rial (OMR)',
                'pakistani-rupee' => 'Pakistaanse Roepie (PKR)',
                'panamanian-balboa' => 'Panamese Balboa (PAB)',
                'paraguayan-guarani' => 'Paraguayaanse Guarani (PYG)',
                'peruvian-nuevo-sol' => 'Peruviaanse Nuevo Sol (PEN)',
                'pgsql' => 'pgSQL',
                'philippine-peso' => 'Filipijnse Peso (PHP)',
                'polish-zloty' => 'Poolse Zloty (PLN)',
                'qatari-rial' => 'Qatarese Rial (QAR)',
                'romanian-leu' => 'Roemeense Leu (RON)',
                'russian-ruble' => 'Russische Roebel (RUB)',
                'saudi-riyal' => 'Saoedi-Riyal (SAR)',
                'select-timezone' => 'Selecteer Tijdzone',
                'singapore-dollar' => 'Singaporese Dollar (SGD)',
                'south-african-rand' => 'Zuid-Afrikaanse Rand (ZAR)',
                'south-korean-won' => 'Zuid-Koreaanse Won (KRW)',
                'sqlsrv' => 'SQLSRV',
                'sri-lankan-rupee' => 'Sri Lankaanse Roepie (LKR)',
                'swedish-krona' => 'Zweedse Kroon (SEK)',
                'swiss-franc' => 'Zwitserse Frank (CHF)',
                'thai-baht' => 'Thaise Baht (THB)',
                'title' => 'Winkelconfiguratie',
                'tunisian-dinar' => 'Tunesische Dinar (TND)',
                'turkish-lira' => 'Turkse Lira (TRY)',
                'ukrainian-hryvnia' => 'Oekraïense Hryvnia (UAH)',
                'united-arab-emirates-dirham' => 'Verenigde Arabische Emiraten Dirham (AED)',
                'united-states-dollar' => 'Amerikaanse Dollar (USD)',
                'uzbekistani-som' => 'Oezbeekse Som (UZS)',
                'venezuelan-bolívar' => 'Venezolaanse Bolívar (VEF)',
                'vietnamese-dong' => 'Vietnamese Dong (VND)',
                'warning-message' => 'Pas op! De instellingen voor uw standaardtaal en standaardvaluta zijn permanent en kunnen niet worden gewijzigd zodra ze zijn ingesteld.',
                'zambian-kwacha' => 'Zambiaanse Kwacha (ZMW)',
            ],

            'sample-products' => [
                'no' => 'Nee',
                'sample-products' => 'Voorbeeldproducten',
                'title' => 'Voorbeeldproducten',
                'yes' => 'Ja',
            ],

            'installation-processing' => [
                'bagisto' => 'Bagisto installatie',
                'bagisto-info' => 'Het maken van database tabellen kan even duren',
                'title' => 'Installatie',
            ],

            'installation-completed' => [
                'admin-panel' => 'Beheerderspaneel',
                'bagisto-forums' => 'Bagisto Forum',
                'customer-panel' => 'Klantenpaneel',
                'explore-bagisto-extensions' => 'Verken Bagisto-extensies',
                'title' => 'Installatie voltooid',
                'title-info' => 'Bagisto is succesvol geïnstalleerd op uw systeem.',
            ],

            'ready-for-installation' => [
                'create-database-table' => 'Maak de databasetabel aan',
                'install' => 'Installatie',
                'install-info' => 'Bagisto Voor Installatie',
                'install-info-button' => 'Klik op de knop hieronder om',
                'populate-database-table' => 'Vul de databasetabellen',
                'start-installation' => 'Start Installatie',
                'title' => 'Klaar voor Installatie',
            ],

            'start' => [
                'locale' => 'Locatie',
                'main' => 'Start',
                'select-locale' => 'Selecteer Locatie',
                'title' => 'Uw Bagisto-installatie',
                'welcome-title' => 'Welkom bij Bagisto',
            ],

            'server-requirements' => [
                'calendar' => 'Kalender',
                'ctype' => 'cType',
                'curl' => 'cURL',
                'dom' => 'DOM',
                'fileinfo' => 'Bestandsinformatie',
                'filter' => 'Filter',
                'gd' => 'GD',
                'hash' => 'Hash',
                'intl' => 'intl',
                'json' => 'JSON',
                'mbstring' => 'mbstring',
                'openssl' => 'OpenSSL',
                'pcre' => 'PCRE',
                'pdo' => 'PDO',
                'php' => 'PHP',
                'php-version' => '8.1 of hoger',
                'session' => 'Sessie',
                'title' => 'Serververeisten',
                'tokenizer' => 'Tokenizer',
                'xml' => 'XML',
            ],

            'arabic' => 'Arabisch',
            'back' => 'Terug',
            'bagisto' => 'Bagisto',
            'bagisto-info' => 'Een communityproject van',
            'bagisto-logo' => 'Bagisto Logo',
            'bengali' => 'Bengaals',
            'catalan' => 'Catalaans',
            'chinese' => 'Chinees',
            'continue' => 'Doorgaan',
            'dutch' => 'Nederlands',
            'english' => 'Engels',
            'french' => 'Frans',
            'german' => 'Duits',
            'hebrew' => 'Hebreeuws',
            'hindi' => 'Hindi',
            'indonesian' => 'Indonesisch',
            'installation-description' => 'De installatie van Bagisto omvat doorgaans verschillende stappen. Hier is een algemeen overzicht van het installatieproces voor Bagisto',
            'installation-info' => 'We zijn blij je hier te zien!',
            'installation-title' => 'Welkom bij de Bagisto-installatie',
            'italian' => 'Italiaans',
            'japanese' => 'Japans',
            'persian' => 'Perzisch',
            'polish' => 'Pools',
            'portuguese' => 'Braziliaans Portugees',
            'russian' => 'Russisch',
            'sinhala' => 'Singalees',
            'spanish' => 'Spaans',
            'title' => 'Bagisto Installer',
            'turkish' => 'Turks',
            'ukrainian' => 'Oekraïens',
            'webkul' => 'Webkul',
        ],
    ],
];
