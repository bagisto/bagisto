<?php

return [
    'seeders' => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'Standaard',
            ],

            'attribute-groups' => [
                'description'      => 'Beschrijving',
                'general'          => 'Algemeen',
                'inventories'      => 'Voorraden',
                'meta-description' => 'Meta Beschrijving',
                'price'            => 'Prijs',
                'settings'         => 'Instellingen',
                'shipping'         => 'Verzending',
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
                'special-price'        => 'Speciale Prijs',
                'special-price-from'   => 'Speciale Prijs Vanaf',
                'special-price-to'     => 'Speciale Prijs Tot',
                'status'               => 'Status',
                'tax-category'         => 'Belastingcategorie',
                'url-key'              => 'URL Sleutel',
                'visible-individually' => 'Individueel Zichtbaar',
                'weight'               => 'Gewicht',
                'width'                => 'Breedte',
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

                'contact-us' => [
                    'content' => 'Contacteer Ons Pagina Inhoud',
                    'title'   => 'Contacteer Ons',
                ],

                'customer-service' => [
                    'content' => 'Klantenservice Pagina Inhoud',
                    'title'   => 'Klantenservice',
                ],

                'payment-policy' => [
                    'content' => 'Betalingsbeleid Pagina Inhoud',
                    'title'   => 'Betalingsbeleid',
                ],

                'privacy-policy' => [
                    'content' => 'Privacybeleid Pagina Inhoud',
                    'title'   => 'Privacybeleid',
                ],

                'refund-policy' => [
                    'content' => 'Retourbeleid Pagina Inhoud',
                    'title'   => 'Retourbeleid',
                ],

                'return-policy' => [
                    'content' => 'Terugstuurbeleid Pagina Inhoud',
                    'title'   => 'Terugstuurbeleid',
                ],

                'shipping-policy' => [
                    'content' => 'Verzendingsbeleid Pagina Inhoud',
                    'title'   => 'Verzendingsbeleid',
                ],

                'terms-conditions' => [
                    'content' => 'Algemene Voorwaarden Pagina Inhoud',
                    'title'   => 'Algemene Voorwaarden',
                ],

                'terms-of-use' => [
                    'content' => 'Gebruiksvoorwaarden Pagina Inhoud',
                    'title'   => 'Gebruiksvoorwaarden',
                ],

                'whats-new' => [
                    'content' => 'Wat is nieuw pagina inhoud',
                    'title'   => 'Wat is nieuw',
                ],
            ],
        ],

        'core' => [
            'channels' => [
                'name'             => 'Standaard',
                'meta-title'       => 'Demo winkel',
                'meta-keywords'    => 'Demo winkel meta trefwoorden',
                'meta-description' => 'Demo winkel meta beschrijving',
            ],

            'currencies' => [
                'AED' => 'Verenigde Arabische Emiraten dirham',
                'ARS' => 'Argentijnse peso',
                'AUD' => 'Australische dollar',
                'BDT' => 'Bangladeshische taka',
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

        'customer' => [
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
                        'btn-title'   => 'Bekijk Collecties',
                        'description' => 'Maak kennis met onze nieuwe gedurfde collecties! Verhoog je stijl met gedurfde ontwerpen en levendige statements. Verken opvallende patronen en gedurfde kleuren die je garderobe opnieuw definiëren. Maak je klaar om het buitengewone te omarmen!',
                        'title'       => 'Maak je klaar voor onze nieuwe gedurfde collecties!',
                    ],

                    'name' => 'Gedurfde Collecties',
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
                        'about-us'         => 'Over Ons',
                        'contact-us'       => 'Contacteer Ons',
                        'customer-service' => 'Klantenservice',
                        'payment-policy'   => 'Betalingsbeleid',
                        'privacy-policy'   => 'Privacybeleid',
                        'refund-policy'    => 'Retourbeleid',
                        'return-policy'    => 'Terugstuurbeleid',
                        'shipping-policy'  => 'Verzendingsbeleid',
                        'terms-conditions' => 'Algemene Voorwaarden',
                        'terms-of-use'     => 'Gebruiksvoorwaarden',
                        'whats-new'        => 'Wat is nieuw',
                    ],
                ],

                'game-container' => [
                    'name' => 'Spelcontainer',

                    'content' => [
                        'sub-title-1' => 'Onze Collecties',
                        'sub-title-2' => 'Onze Collecties',
                        'title'       => 'Het spel met onze nieuwe toevoegingen!',
                    ],
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
                    'name' => 'Aanbiedingsinformatie',

                    'content' => [
                        'title' => 'KRIJG TOT 40% KORTING op je 1e bestelling NU WINKELEN',
                    ],
                ],

                'services-content' => [
                    'description' => [
                        'emi-available-info'   => 'Geen kosten EMI beschikbaar op alle belangrijke creditcards',
                        'free-shipping-info'   => 'Geniet van gratis verzending op alle bestellingen',
                        'product-replace-info' => 'Eenvoudige productvervanging beschikbaar!',
                        'time-support-info'    => 'Toegewijde 24/7 ondersteuning via chat en e-mail',
                    ],

                    'name' => 'Diensteninhoud',

                    'title' => [
                        'emi-available'   => 'EMI beschikbaar',
                        'free-shipping'   => 'Gratis verzending',
                        'product-replace' => 'Product vervangen',
                        'time-support'    => '24/7 ondersteuning',
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
                        'title'       => 'Het spel met onze nieuwe toevoegingen!',
                    ],

                    'name' => 'Top Collecties',
                ],
            ],
        ],

        'user' => [
            'roles' => [
                'description' => 'Deze rol geeft gebruikers alle toegang',
                'name'        => 'Beheerder',
            ],

            'users' => [
                'name' => 'Voorbeeld',
            ],
        ],

        'sample-categories' => [
            'category-translation' => [
                '2' => [
                    'description'      => 'Mannen Categorie Beschrijving',
                    'meta-description' => 'Mannen Categorie Meta Beschrijving',
                    'meta-keywords'    => 'Mannen Categorie Meta Trefwoorden',
                    'meta-title'       => 'Mannen Categorie Meta Titel',
                    'name'             => 'Heren',
                    'slug'             => 'heren',
                ],

                '3' => [
                    'description'      => 'Winterkleding Categorie Beschrijving',
                    'meta-description' => 'Winterkleding Categorie Meta Beschrijving',
                    'meta-keywords'    => 'Winterkleding Categorie Meta Trefwoorden',
                    'meta-title'       => 'Winterkleding Categorie Meta Titel',
                    'name'             => 'Winterkleding',
                    'slug'             => 'winterkleding',
                ],
            ],
        ],

        'sample-products' => [
            'product-flat' => [
                '1' => [
                    'description'       => 'De Arctic Cozy Knit Beanie is dé oplossing om warm, comfortabel en stijlvol te blijven tijdens de koudere maanden. Gemaakt van een zachte en duurzame mix van acryl breisel, is deze muts ontworpen om een gezellige en nauwsluitende pasvorm te bieden. Het klassieke ontwerp maakt het geschikt voor zowel mannen als vrouwen, en biedt een veelzijdige accessoire die verschillende stijlen aanvult. Of je nu een casual dagje uit gaat of de natuur in trekt, deze muts voegt een vleugje comfort en warmte toe aan je outfit. Het zachte en ademende materiaal zorgt ervoor dat je gezellig blijft zonder stijl op te offeren. De Arctic Cozy Knit Beanie is niet zomaar een accessoire; het is een statement van wintermode. Door zijn eenvoud is hij gemakkelijk te combineren met verschillende outfits, waardoor hij een essentieel onderdeel is van je wintergarderobe. Ideaal om cadeau te geven of jezelf te verwennen, deze muts is een doordachte toevoeging aan elke winteroutfit. Het is een veelzijdige accessoire die verder gaat dan functionaliteit, en een vleugje warmte en stijl toevoegt aan je look. Omarm de essentie van de winter met de Arctic Cozy Knit Beanie. Of je nu geniet van een casual dagje uit of de elementen trotseert, laat deze muts je metgezel zijn voor comfort en stijl. Upgrade je wintergarderobe met deze klassieke accessoire die moeiteloos warmte combineert met een tijdloos gevoel voor mode.',
                    'meta-description'  => 'meta beschrijving',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Meta Titel',
                    'name'              => 'Arctic Cozy Knit Unisex Beanie',
                    'short-description' => 'Omarm de koude dagen in stijl met onze Arctic Cozy Knit Beanie. Gemaakt van een zachte en duurzame mix van acryl, biedt deze klassieke muts warmte en veelzijdigheid. Geschikt voor zowel mannen als vrouwen, is het de ideale accessoire voor casual of buitenkleding. Upgrade je wintergarderobe of geef iemand speciaal een essentiële muts cadeau.',
                ],

                '2' => [
                    'description'       => 'De Arctic Bliss Winter Sjaal is meer dan alleen een accessoire voor koud weer; het is een statement van warmte, comfort en stijl voor het winterseizoen. Met zorg gemaakt van een luxueuze mix van acryl en wol, is deze sjaal ontworpen om je gezellig en knus te houden, zelfs in de koudste temperaturen. De zachte en pluche textuur biedt niet alleen isolatie tegen de kou, maar voegt ook een vleugje luxe toe aan je wintergarderobe. Het ontwerp van de Arctic Bliss Winter Sjaal is zowel stijlvol als veelzijdig, waardoor het een perfecte aanvulling is op verschillende winteroutfits. Of je je nu aankleedt voor een speciale gelegenheid of een chique laag toevoegt aan je alledaagse look, deze sjaal vult je stijl moeiteloos aan. De extra lange lengte van de sjaal biedt aanpasbare stylingopties. Wikkel hem om voor extra warmte, drapeer hem losjes voor een casual look, of experimenteer met verschillende knopen om je unieke stijl uit te drukken. Deze veelzijdigheid maakt het een must-have accessoire voor het winterseizoen. Op zoek naar het perfecte cadeau? De Arctic Bliss Winter Sjaal is een ideale keuze. Of je nu een geliefde verrast of jezelf trakteert, deze sjaal is een tijdloos en praktisch cadeau dat gedurende de wintermaanden gekoesterd zal worden. Omarm de winter met de Arctic Bliss Winter Sjaal, waar warmte en stijl perfect samenkomen. Upgrade je wintergarderobe met deze essentiële accessoire die je niet alleen warm houdt, maar ook een vleugje verfijning toevoegt aan je koude-weer ensemble.',
                    'meta-description'  => 'meta beschrijving',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Meta Titel',
                    'name'              => 'Arctic Bliss Stijlvolle Winter Sjaal',
                    'short-description' => 'Ervaar de omhelzing van warmte en stijl met onze Arctic Bliss Winter Sjaal. Gemaakt van een luxueuze mix van acryl en wol, is deze gezellige sjaal ontworpen om je knus te houden tijdens de koudste dagen. Het stijlvolle en veelzijdige ontwerp, gecombineerd met een extra lange lengte, biedt aanpasbare stylingopties. Upgrade je wintergarderobe of verwen iemand speciaal met deze essentiële winteraccessoire.',
                ],

                '3' => [
                    'description'       => 'Maak kennis met de Arctic Touchscreen Winterhandschoenen - waar warmte, stijl en connectiviteit samenkomen om je winterervaring te verbeteren. Gemaakt van hoogwaardig acryl, zijn deze handschoenen ontworpen om uitzonderlijke warmte en duurzaamheid te bieden. De touchscreen-compatibele vingertoppen stellen je in staat om verbonden te blijven zonder je handen bloot te stellen aan de kou. Beantwoord oproepen, stuur berichten en navigeer moeiteloos op je apparaten, terwijl je je handen warm houdt. De geïsoleerde voering voegt een extra laag gezelligheid toe, waardoor deze handschoenen je favoriete keuze zijn om de winterkou te trotseren. Of je nu onderweg bent, boodschappen doet of buitenactiviteiten onderneemt, deze handschoenen bieden de warmte en bescherming die je nodig hebt. Elastische manchetten zorgen voor een goede pasvorm, voorkomen koude tocht en houden de handschoenen op hun plaats tijdens je dagelijkse activiteiten. Het stijlvolle ontwerp voegt een vleugje flair toe aan je winteroutfit, waardoor deze handschoenen net zo modieus zijn als functioneel. Ideaal om cadeau te geven of als traktatie voor jezelf, de Arctic Touchscreen Winterhandschoenen zijn een must-have accessoire voor de moderne individu. Zeg vaarwel tegen het ongemak van het verwijderen van je handschoenen om je apparaten te gebruiken en omarm de naadloze combinatie van warmte, stijl en connectiviteit. Blijf verbonden, blijf warm en blijf stijlvol met de Arctic Touchscreen Winterhandschoenen - je betrouwbare metgezel om het winterseizoen met vertrouwen te trotseren.',
                    'meta-description'  => 'meta beschrijving',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Meta Titel',
                    'name'              => 'Arctic Touchscreen Winterhandschoenen',
                    'short-description' => 'Blijf verbonden en warm met onze Arctic Touchscreen Winterhandschoenen. Deze handschoenen zijn niet alleen gemaakt van hoogwaardig acryl voor warmte en duurzaamheid, maar hebben ook een touchscreen-compatibel ontwerp. Met een geïsoleerde voering, elastische manchetten voor een goede pasvorm en een stijlvolle uitstraling zijn deze handschoenen perfect voor dagelijks gebruik in koude omstandigheden.',
                ],

                '4' => [
                    'description'       => 'Maak kennis met de Arctic Warmte Wolblend Sokken - je essentiële metgezel voor gezellige en comfortabele voeten tijdens de koudere seizoenen. Gemaakt van een premium mix van Merino wol, acryl, nylon en spandex, zijn deze sokken ontworpen om ongeëvenaarde warmte en comfort te bieden. De wolblend zorgt ervoor dat je voeten zelfs bij de koudste temperaturen warm blijven, waardoor deze sokken de perfecte keuze zijn voor winterse avonturen of gewoon lekker knus thuis. De zachte en gezellige textuur van de sokken biedt een luxueus gevoel tegen je huid. Zeg vaarwel tegen koude voeten terwijl je geniet van de weelderige warmte die deze wolblend sokken bieden. Ontworpen voor duurzaamheid, hebben de sokken een versterkte hiel en teen, wat extra stevigheid toevoegt aan slijtagegevoelige gebieden. Dit zorgt ervoor dat je sokken de tand des tijds doorstaan, langdurig comfort en gezelligheid biedend. De ademende eigenschappen van het materiaal voorkomen oververhitting, waardoor je voeten de hele dag comfortabel en droog blijven. Of je nu naar buiten gaat voor een winterse wandeling of binnen ontspant, deze sokken bieden de perfecte balans tussen warmte en ademend vermogen. Veelzijdig en stijlvol, deze wolblend sokken zijn geschikt voor verschillende gelegenheden. Combineer ze met je favoriete laarzen voor een modieuze winterlook of draag ze thuis voor ultiem comfort. Upgrade je wintergarderobe en geef prioriteit aan comfort met de Arctic Warmte Wolblend Sokken. Verwen je voeten met de luxe die ze verdienen en stap in een wereld van gezelligheid die het hele seizoen meegaat.',
                    'meta-description'  => 'meta beschrijving',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Meta Titel',
                    'name'              => 'Arctic Warmte Wolblend Sokken',
                    'short-description' => 'Ervaar de ongeëvenaarde warmte en comfort van onze Arctic Warmte Wolblend Sokken. Gemaakt van een mix van Merino wol, acryl, nylon en spandex, bieden deze sokken ultieme gezelligheid voor koud weer. Met een versterkte hiel en teen voor duurzaamheid zijn deze veelzijdige en stijlvolle sokken perfect voor verschillende gelegenheden.',
                ],

                '5' => [
                    'description'       => 'Maak kennis met de Arctic Frost Winter Accessoires Bundel, jouw oplossing om warm, stijlvol en verbonden te blijven tijdens de koude winterdagen. Deze zorgvuldig samengestelde set brengt vier essentiële winteraccessoires samen om een harmonieus geheel te creëren. De luxueuze sjaal, geweven van een mix van acryl en wol, voegt niet alleen warmte toe, maar geeft ook een vleugje elegantie aan je wintergarderobe. De zachte gebreide muts, met zorg gemaakt, belooft je gezellig te houden terwijl je een modieuze uitstraling toevoegt aan je look. Maar daar houdt het niet op - onze bundel bevat ook handschoenen die compatibel zijn met touchscreen. Blijf verbonden zonder warmte op te offeren terwijl je moeiteloos je apparaten bedient. Of je nu oproepen beantwoordt, berichten verstuurt of winterse momenten vastlegt op je smartphone, deze handschoenen zorgen voor gemak zonder stijl op te offeren. De zachte en gezellige textuur van de sokken biedt een luxueus gevoel tegen je huid. Zeg vaarwel tegen koude voeten terwijl je geniet van de warmte van deze wollen sokken. De Arctic Frost Winter Accessoires Bundel gaat niet alleen over functionaliteit; het is een statement van wintermode. Elk stuk is ontworpen om je niet alleen tegen de kou te beschermen, maar ook om je stijl te verhogen tijdens het winterseizoen. De gekozen materialen voor deze bundel hebben zowel duurzaamheid als comfort als prioriteit, zodat je in stijl kunt genieten van het winterlandschap. Of je jezelf wilt verwennen of op zoek bent naar het perfecte cadeau, de Arctic Frost Winter Accessoires Bundel is een veelzijdige keuze. Verras iemand speciaal tijdens de feestdagen of verhoog je eigen wintergarderobe met deze stijlvolle en functionele set. Omarm de vorst met vertrouwen, wetende dat je de perfecte accessoires hebt om warm en chic te blijven.',
                    'meta-description'  => 'meta beschrijving',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Meta Titel',
                    'name'              => 'Arctic Frost Winter Accessoires Bundel',
                    'short-description' => 'Omarm de winterse kou met onze Arctic Frost Winter Accessoires Bundel. Deze samengestelde set bevat een luxueuze sjaal, een gezellige muts, handschoenen die compatibel zijn met touchscreen en wollen sokken. Stijlvol en functioneel, deze set is gemaakt van hoogwaardige materialen, waardoor zowel duurzaamheid als comfort gegarandeerd zijn. Verhoog je wintergarderobe of verras iemand speciaal met deze perfecte cadeau-optie.',
                ],

                '6' => [
                    'description'       => 'Maak kennis met de Arctic Frost Winter Accessoires Bundel, jouw oplossing om warm, stijlvol en verbonden te blijven tijdens de koude winterdagen. Deze zorgvuldig samengestelde set brengt vier essentiële winteraccessoires samen om een harmonieus geheel te creëren. De luxueuze sjaal, geweven van een mix van acryl en wol, voegt niet alleen warmte toe, maar geeft ook een vleugje elegantie aan je wintergarderobe. De zachte gebreide muts, met zorg gemaakt, belooft je gezellig te houden terwijl je een modieuze uitstraling toevoegt aan je look. Maar daar houdt het niet op - onze bundel bevat ook handschoenen die compatibel zijn met touchscreen. Blijf verbonden zonder warmte op te offeren terwijl je moeiteloos je apparaten bedient. Of je nu oproepen beantwoordt, berichten verstuurt of winterse momenten vastlegt op je smartphone, deze handschoenen zorgen voor gemak zonder stijl op te offeren. De zachte en gezellige textuur van de sokken biedt een luxueus gevoel tegen je huid. Zeg vaarwel tegen koude voeten terwijl je geniet van de warmte van deze wollen sokken. De Arctic Frost Winter Accessoires Bundel gaat niet alleen over functionaliteit; het is een statement van wintermode. Elk stuk is ontworpen om je niet alleen tegen de kou te beschermen, maar ook om je stijl te verhogen tijdens het winterseizoen. De gekozen materialen voor deze bundel hebben zowel duurzaamheid als comfort als prioriteit, zodat je in stijl kunt genieten van het winterlandschap. Of je jezelf wilt verwennen of op zoek bent naar het perfecte cadeau, de Arctic Frost Winter Accessoires Bundel is een veelzijdige keuze. Verras iemand speciaal tijdens de feestdagen of verhoog je eigen wintergarderobe met deze stijlvolle en functionele set. Omarm de vorst met vertrouwen, wetende dat je de perfecte accessoires hebt om warm en chic te blijven.',
                    'meta-description'  => 'meta beschrijving',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Meta Titel',
                    'name'              => 'Arctic Frost Winter Accessoires Bundel',
                    'short-description' => 'Omarm de winterse kou met onze Arctic Frost Winter Accessoires Bundel. Deze samengestelde set bevat een luxueuze sjaal, een gezellige muts, handschoenen die compatibel zijn met touchscreen en wollen sokken. Stijlvol en functioneel, deze set is gemaakt van hoogwaardige materialen, waardoor zowel duurzaamheid als comfort gegarandeerd zijn. Verhoog je wintergarderobe of verras iemand speciaal met deze perfecte cadeau-optie.',
                ],

                '7' => [
                    'description'       => 'Maak kennis met de OmniHeat Heren Solide Gevoerde Gewatteerde Jas met Capuchon, jouw oplossing om warm en modieus te blijven tijdens koudere seizoenen. Deze jas is ontworpen met duurzaamheid en warmte in gedachten, zodat het jouw vertrouwde metgezel wordt. Het capuchonontwerp voegt niet alleen een vleugje stijl toe, maar biedt ook extra warmte, waardoor je beschermd bent tegen koude wind en weer. De lange mouwen bieden volledige dekking, zodat je van schouder tot pols warm blijft. Uitgerust met insteekzakken biedt deze gewatteerde jas gemak voor het meenemen van je benodigdheden of het warm houden van je handen. De geïsoleerde synthetische vulling biedt verbeterde warmte, waardoor het ideaal is voor koude dagen en nachten. Gemaakt van een duurzame polyester buitenkant en voering, is deze jas gebouwd om lang mee te gaan en de elementen te doorstaan. Verkrijgbaar in 5 aantrekkelijke kleuren, kun je degene kiezen die bij jouw stijl en voorkeur past. Veelzijdig en functioneel, de OmniHeat Heren Solide Gevoerde Gewatteerde Jas met Capuchon is geschikt voor verschillende gelegenheden, of je nu naar het werk gaat, een informeel uitje maakt of een buitenevenement bijwoont. Ervaar de perfecte combinatie van stijl, comfort en functionaliteit met de OmniHeat Heren Solide Gevoerde Gewatteerde Jas met Capuchon. Upgrade je wintergarderobe en blijf knus terwijl je van de buitenlucht geniet. Versla de kou in stijl en maak een statement met dit essentiële kledingstuk.',
                    'meta-description'  => 'meta beschrijving',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Meta Titel',
                    'name'              => 'OmniHeat Heren Solide Gevoerde Gewatteerde Jas met Capuchon',
                    'short-description' => 'Blijf warm en stijlvol met onze OmniHeat Heren Solide Gevoerde Gewatteerde Jas met Capuchon. Deze jas is ontworpen om ultieme warmte te bieden en heeft insteekzakken voor extra gemak. Het geïsoleerde materiaal zorgt ervoor dat je warm blijft in koud weer. Verkrijgbaar in 5 aantrekkelijke kleuren, waardoor het een veelzijdige keuze is voor verschillende gelegenheden.',
                ],

                '8' => [
                    'description'       => 'Maak kennis met de OmniHeat Heren Solide Gevoerde Gewatteerde Jas met Capuchon, jouw oplossing om warm en modieus te blijven tijdens koudere seizoenen. Deze jas is ontworpen met duurzaamheid en warmte in gedachten, zodat het jouw vertrouwde metgezel wordt. Het capuchonontwerp voegt niet alleen een vleugje stijl toe, maar biedt ook extra warmte, waardoor je beschermd bent tegen koude wind en weer. De lange mouwen bieden volledige dekking, zodat je van schouder tot pols warm blijft. Uitgerust met insteekzakken biedt deze gewatteerde jas gemak voor het meenemen van je benodigdheden of het warm houden van je handen. De geïsoleerde synthetische vulling biedt verbeterde warmte, waardoor het ideaal is voor koude dagen en nachten. Gemaakt van een duurzame polyester buitenkant en voering, is deze jas gebouwd om lang mee te gaan en de elementen te doorstaan. Verkrijgbaar in 5 aantrekkelijke kleuren, kun je degene kiezen die bij jouw stijl en voorkeur past. Veelzijdig en functioneel, de OmniHeat Heren Solide Gevoerde Gewatteerde Jas met Capuchon is geschikt voor verschillende gelegenheden, of je nu naar het werk gaat, een informeel uitje maakt of een buitenevenement bijwoont. Ervaar de perfecte combinatie van stijl, comfort en functionaliteit met de OmniHeat Heren Solide Gevoerde Gewatteerde Jas met Capuchon. Upgrade je wintergarderobe en blijf knus terwijl je van de buitenlucht geniet. Versla de kou in stijl en maak een statement met dit essentiële kledingstuk.',
                    'meta-description'  => 'meta beschrijving',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Meta Titel',
                    'name'              => 'OmniHeat Heren Solide Gevoerde Gewatteerde Jas met Capuchon-Blauw-Geel-M',
                    'short-description' => 'Blijf warm en stijlvol met onze OmniHeat Heren Solide Gevoerde Gewatteerde Jas met Capuchon. Deze jas is ontworpen om ultieme warmte te bieden en heeft insteekzakken voor extra gemak. Het geïsoleerde materiaal zorgt ervoor dat je warm blijft in koud weer. Verkrijgbaar in 5 aantrekkelijke kleuren, waardoor het een veelzijdige keuze is voor verschillende gelegenheden.',
                ],

                '9' => [
                    'description'       => 'Maak kennis met de OmniHeat Heren Solide Gevoerde Gewatteerde Jas, jouw oplossing om warm en modieus te blijven tijdens koudere seizoenen. Deze jas is ontworpen met duurzaamheid en warmte in gedachten, zodat het jouw betrouwbare metgezel wordt. Het capuchonontwerp voegt niet alleen een vleugje stijl toe, maar biedt ook extra warmte, waardoor je beschermd bent tegen koude wind en weer. De lange mouwen bieden volledige dekking, zodat je van schouder tot pols warm blijft. Uitgerust met insteekzakken biedt deze gewatteerde jas gemak voor het meenemen van je benodigdheden of om je handen warm te houden. De geïsoleerde synthetische vulling biedt verbeterde warmte, waardoor het ideaal is voor koude dagen en nachten. Gemaakt van een duurzame polyester buitenkant en voering, is deze jas gebouwd om lang mee te gaan en de elementen te doorstaan. Verkrijgbaar in 5 aantrekkelijke kleuren, kun je degene kiezen die bij jouw stijl en voorkeur past. Veelzijdig en functioneel, de OmniHeat Heren Solide Gevoerde Gewatteerde Jas is geschikt voor verschillende gelegenheden, of je nu naar het werk gaat, een casual uitje maakt of een buitenevenement bijwoont. Ervaar de perfecte combinatie van stijl, comfort en functionaliteit met de OmniHeat Heren Solide Gevoerde Gewatteerde Jas. Upgrade je wintergarderobe en blijf knus terwijl je van de buitenlucht geniet. Trotseer de kou in stijl en maak een statement met dit essentiële kledingstuk.',
                    'meta-description'  => 'meta beschrijving',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Meta Titel',
                    'name'              => 'OmniHeat Heren Solide Gevoerde Gewatteerde Jas-Blauw-Geel-L',
                    'short-description' => 'Blijf warm en stijlvol met onze OmniHeat Heren Solide Gevoerde Gewatteerde Jas. Deze jas is ontworpen om ultieme warmte te bieden en heeft insteekzakken voor extra gemak. Het geïsoleerde materiaal zorgt ervoor dat je warm blijft in koud weer. Verkrijgbaar in 5 aantrekkelijke kleuren, waardoor het een veelzijdige keuze is voor verschillende gelegenheden.',
                ],

                '10' => [
                    'description'       => 'Maak kennis met de OmniHeat Heren Solide Gevoerde Gewatteerde Jas, jouw oplossing om warm en modieus te blijven tijdens koudere seizoenen. Deze jas is ontworpen met duurzaamheid en warmte in gedachten, zodat het jouw betrouwbare metgezel wordt. Het capuchonontwerp voegt niet alleen een vleugje stijl toe, maar biedt ook extra warmte, waardoor je beschermd bent tegen koude wind en weer. De lange mouwen bieden volledige dekking, zodat je van schouder tot pols warm blijft. Uitgerust met insteekzakken biedt deze gewatteerde jas gemak voor het meenemen van je benodigdheden of om je handen warm te houden. De geïsoleerde synthetische vulling biedt verbeterde warmte, waardoor het ideaal is voor koude dagen en nachten. Gemaakt van een duurzame polyester buitenkant en voering, is deze jas gebouwd om lang mee te gaan en de elementen te doorstaan. Verkrijgbaar in 5 aantrekkelijke kleuren, kun je degene kiezen die bij jouw stijl en voorkeur past. Veelzijdig en functioneel, de OmniHeat Heren Solide Gevoerde Gewatteerde Jas is geschikt voor verschillende gelegenheden, of je nu naar het werk gaat, een casual uitje maakt of een buitenevenement bijwoont. Ervaar de perfecte combinatie van stijl, comfort en functionaliteit met de OmniHeat Heren Solide Gevoerde Gewatteerde Jas. Upgrade je wintergarderobe en blijf knus terwijl je van de buitenlucht geniet. Trotseer de kou in stijl en maak een statement met dit essentiële kledingstuk.',
                    'meta-description'  => 'meta beschrijving',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Meta Titel',
                    'name'              => 'OmniHeat Heren Solide Gevoerde Gewatteerde Jas-Blauw-Groen-M',
                    'short-description' => 'Blijf warm en stijlvol met onze OmniHeat Heren Solide Gevoerde Gewatteerde Jas. Deze jas is ontworpen om ultieme warmte te bieden en heeft insteekzakken voor extra gemak. Het geïsoleerde materiaal zorgt ervoor dat je warm blijft in koud weer. Verkrijgbaar in 5 aantrekkelijke kleuren, waardoor het een veelzijdige keuze is voor verschillende gelegenheden.',
                ],

                '11' => [
                    'description'       => 'Maak kennis met de OmniHeat Heren Solide Gevoerde Gewatteerde Jas, jouw oplossing om warm en modieus te blijven tijdens koudere seizoenen. Deze jas is ontworpen met duurzaamheid en warmte in gedachten, zodat het jouw betrouwbare metgezel wordt. Het capuchonontwerp voegt niet alleen een vleugje stijl toe, maar biedt ook extra warmte, waardoor je beschermd bent tegen koude wind en weer. De lange mouwen bieden volledige dekking, zodat je van schouder tot pols warm blijft. Uitgerust met insteekzakken biedt deze gewatteerde jas gemak voor het meenemen van je benodigdheden of om je handen warm te houden. De geïsoleerde synthetische vulling biedt verbeterde warmte, waardoor het ideaal is voor koude dagen en nachten. Gemaakt van een duurzame polyester buitenkant en voering, is deze jas gebouwd om lang mee te gaan en de elementen te doorstaan. Verkrijgbaar in 5 aantrekkelijke kleuren, kun je degene kiezen die bij jouw stijl en voorkeur past. Veelzijdig en functioneel, de OmniHeat Heren Solide Gevoerde Gewatteerde Jas is geschikt voor verschillende gelegenheden, of je nu naar het werk gaat, een casual uitje maakt of een buitenevenement bijwoont. Ervaar de perfecte combinatie van stijl, comfort en functionaliteit met de OmniHeat Heren Solide Gevoerde Gewatteerde Jas. Upgrade je wintergarderobe en blijf knus terwijl je van de buitenlucht geniet. Trotseer de kou in stijl en maak een statement met dit essentiële kledingstuk.',
                    'meta-description'  => 'meta beschrijving',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Meta Titel',
                    'name'              => 'OmniHeat Heren Solide Gevoerde Gewatteerde Jas-Blauw-Groen-L',
                    'short-description' => 'Blijf warm en stijlvol met onze OmniHeat Heren Solide Gevoerde Gewatteerde Jas. Deze jas is ontworpen om ultieme warmte te bieden en heeft insteekzakken voor extra gemak. Het geïsoleerde materiaal zorgt ervoor dat je warm blijft in koud weer. Verkrijgbaar in 5 aantrekkelijke kleuren, waardoor het een veelzijdige keuze is voor verschillende gelegenheden.',
                ],
            ],

            'product-attribute-values' => [
                '1' => [
                    'description'      => 'De Arctic Cozy Knit Beanie is jouw oplossing om warm, comfortabel en stijlvol te blijven tijdens de koudere maanden. Gemaakt van een zachte en duurzame mix van acryl breisel, is deze muts ontworpen om een gezellige en nauwsluitende pasvorm te bieden. Het klassieke ontwerp maakt het geschikt voor zowel mannen als vrouwen, en biedt een veelzijdige accessoire die verschillende stijlen aanvult. Of je nu een casual dag in de stad hebt of de natuur in trekt, deze muts voegt een vleugje comfort en warmte toe aan je outfit. Het zachte en ademende materiaal zorgt ervoor dat je gezellig blijft zonder stijl op te offeren. De Arctic Cozy Knit Beanie is niet alleen een accessoire; het is een statement van wintermode. De eenvoud maakt het gemakkelijk te combineren met verschillende outfits, waardoor het een essentieel onderdeel is van je wintergarderobe. Ideaal om cadeau te geven of jezelf te verwennen, deze muts is een doordachte toevoeging aan elke winteroutfit. Het is een veelzijdige accessoire die verder gaat dan functionaliteit en een vleugje warmte en stijl toevoegt aan je look. Omarm de essentie van de winter met de Arctic Cozy Knit Beanie. Of je nu geniet van een casual dagje uit of de elementen trotseert, laat deze muts je metgezel zijn voor comfort en stijl. Upgrade je wintergarderobe met dit klassieke accessoire dat warmte combineert met een tijdloos gevoel voor mode.',
                    'meta-description' => 'meta beschrijving',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Meta Titel',
                    'name'             => 'Arctic Cozy Knit Unisex Beanie',
                    'sort-description' => 'Omarm de koude dagen in stijl met onze Arctic Cozy Knit Beanie. Gemaakt van een zachte en duurzame mix van acryl, biedt deze klassieke muts warmte en veelzijdigheid. Geschikt voor zowel mannen als vrouwen, is het de ideale accessoire voor casual of buitenkleding. Upgrade je wintergarderobe of verras iemand speciaal met deze essentiële muts.',
                ],

                '2' => [
                    'description'      => 'De Arctic Bliss Winter Sjaal is meer dan alleen een accessoire voor koud weer; het is een statement van warmte, comfort en stijl voor het winterseizoen. Met zorg gemaakt van een luxueuze mix van acryl en wol, is deze sjaal ontworpen om je gezellig en knus te houden, zelfs in de koudste temperaturen. De zachte en pluche textuur biedt niet alleen isolatie tegen de kou, maar voegt ook een vleugje luxe toe aan je wintergarderobe. Het ontwerp van de Arctic Bliss Winter Sjaal is zowel stijlvol als veelzijdig, waardoor het een perfecte aanvulling is op verschillende winteroutfits. Of je je nu aankleedt voor een speciale gelegenheid of een chique laag toevoegt aan je alledaagse look, deze sjaal vult je stijl moeiteloos aan. De extra lange lengte van de sjaal biedt aanpasbare stylingopties. Wikkel hem om voor extra warmte, drapeer hem losjes voor een casual look, of experimenteer met verschillende knopen om je unieke stijl uit te drukken. Deze veelzijdigheid maakt het een must-have accessoire voor het winterseizoen. Op zoek naar het perfecte cadeau? De Arctic Bliss Winter Sjaal is een ideale keuze. Of je nu een geliefde verrast of jezelf trakteert, deze sjaal is een tijdloos en praktisch cadeau dat gekoesterd zal worden gedurende de wintermaanden. Omarm de winter met de Arctic Bliss Winter Sjaal, waar warmte en stijl perfect samenkomen. Upgrade je wintergarderobe met deze essentiële accessoire die je niet alleen warm houdt, maar ook een vleugje verfijning toevoegt aan je koude-weer ensemble.',
                    'meta-description' => 'meta beschrijving',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Meta Titel',
                    'name'             => 'Arctic Bliss Stijlvolle Winter Sjaal',
                    'sort-description' => 'Ervaar de omhelzing van warmte en stijl met onze Arctic Bliss Winter Sjaal. Gemaakt van een luxueuze mix van acryl en wol, biedt deze gezellige sjaal warmte tijdens de koudste dagen. Het stijlvolle en veelzijdige ontwerp, gecombineerd met een extra lange lengte, biedt aanpasbare stylingopties. Upgrade je wintergarderobe of verwen iemand speciaal met dit essentiële winteraccessoire.',
                ],

                '3' => [
                    'description'      => 'Maak kennis met de Arctic Touchscreen Winterhandschoenen - waar warmte, stijl en connectiviteit samenkomen om je winterervaring te verbeteren. Gemaakt van hoogwaardig acryl, zijn deze handschoenen ontworpen om uitzonderlijke warmte en duurzaamheid te bieden. De touchscreen-compatibele vingertoppen stellen je in staat om verbonden te blijven zonder je handen bloot te stellen aan de kou. Beantwoord oproepen, stuur berichten en navigeer moeiteloos op je apparaten, terwijl je je handen warm houdt. De geïsoleerde voering voegt een extra laag gezelligheid toe, waardoor deze handschoenen je keuze zijn voor het trotseren van de winterkou. Of je nu onderweg bent, boodschappen doet of buitenactiviteiten onderneemt, deze handschoenen bieden de warmte en bescherming die je nodig hebt. Elastische manchetten zorgen voor een goede pasvorm, voorkomen koude tocht en houden de handschoenen op hun plaats tijdens je dagelijkse activiteiten. Het stijlvolle ontwerp voegt een vleugje flair toe aan je winterensemble, waardoor deze handschoenen net zo modieus zijn als functioneel. Ideaal om cadeau te geven of jezelf te verwennen, de Arctic Touchscreen Winterhandschoenen zijn een must-have accessoire voor de moderne individu. Zeg vaarwel tegen het ongemak van het verwijderen van je handschoenen om je apparaten te gebruiken en omarm de naadloze combinatie van warmte, stijl en connectiviteit. Blijf verbonden, blijf warm en blijf stijlvol met de Arctic Touchscreen Winterhandschoenen - je betrouwbare metgezel om het winterseizoen met vertrouwen te trotseren.',
                    'meta-description' => 'meta beschrijving',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Meta Titel',
                    'name'             => 'Arctic Touchscreen Winterhandschoenen',
                    'sort-description' => 'Blijf verbonden en warm met onze Arctic Touchscreen Winterhandschoenen. Deze handschoenen zijn niet alleen gemaakt van hoogwaardig acryl voor warmte en duurzaamheid, maar hebben ook een touchscreen-compatibel ontwerp. Met een geïsoleerde voering, elastische manchetten voor een goede pasvorm en een stijlvolle uitstraling zijn deze handschoenen perfect voor dagelijks gebruik in koude omstandigheden.',
                ],

                '4' => [
                    'description'      => 'Maak kennis met de Arctic Warmth Wool Blend Sokken - je essentiële metgezel voor gezellige en comfortabele voeten tijdens de koudere seizoenen. Gemaakt van een premium mix van Merino wol, acryl, nylon en spandex, zijn deze sokken ontworpen om ongeëvenaarde warmte en comfort te bieden. De wolblend zorgt ervoor dat je voeten zelfs in de koudste temperaturen warm blijven, waardoor deze sokken de perfecte keuze zijn voor winterse avonturen of gewoon lekker knus thuis blijven. De zachte en gezellige textuur van de sokken biedt een luxueus gevoel tegen je huid. Zeg vaarwel tegen koude voeten terwijl je geniet van de weelderige warmte die deze wolblend sokken bieden. Ontworpen voor duurzaamheid, hebben de sokken een versterkte hiel en teen, waardoor extra stevigheid wordt toegevoegd aan slijtagegevoelige gebieden. Dit zorgt ervoor dat je sokken de tand des tijds doorstaan, langdurig comfort en gezelligheid biedend. De ademende eigenschap van het materiaal voorkomt oververhitting, waardoor je voeten de hele dag comfortabel en droog blijven. Of je nu naar buiten gaat voor een winterse wandeling of binnen ontspant, deze sokken bieden de perfecte balans tussen warmte en ademend vermogen. Veelzijdig en stijlvol, deze wolblend sokken zijn geschikt voor verschillende gelegenheden. Combineer ze met je favoriete laarzen voor een modieuze winterlook of draag ze thuis voor ultiem comfort. Upgrade je wintergarderobe en geef prioriteit aan comfort met de Arctic Warmth Wool Blend Sokken. Verwen je voeten met de luxe die ze verdienen en stap in een wereld van gezelligheid die het hele seizoen meegaat.',
                    'meta-description' => 'meta beschrijving',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Meta Titel',
                    'name'             => 'Arctic Warmth Wool Blend Sokken',
                    'sort-description' => 'Ervaar de ongeëvenaarde warmte en comfort van onze Arctic Warmth Wool Blend Sokken. Gemaakt van een mix van Merino wol, acryl, nylon en spandex, bieden deze sokken ultiem comfort voor koud weer. Met een versterkte hiel en teen voor duurzaamheid zijn deze veelzijdige en stijlvolle sokken perfect voor verschillende gelegenheden.',
                ],

                '5' => [
                    'description'      => 'Maak kennis met de Arctic Frost Winter Accessoires Bundel, jouw oplossing om warm, stijlvol en verbonden te blijven tijdens de koude winterdagen. Deze zorgvuldig samengestelde set brengt vier essentiële winteraccessoires samen om een harmonieus ensemble te creëren. De luxueuze sjaal, geweven van een mix van acryl en wol, voegt niet alleen een laag warmte toe, maar brengt ook een vleugje elegantie aan je wintergarderobe. De zachte gebreide muts, met zorg gemaakt, belooft je gezellig te houden terwijl het een modieuze flair aan je look toevoegt. Maar het houdt hier niet op - onze bundel bevat ook touchscreen-compatibele handschoenen. Blijf verbonden zonder warmte op te offeren terwijl je moeiteloos je apparaten bedient. Of je nu oproepen beantwoordt, berichten verstuurt of winterse momenten vastlegt op je smartphone, deze handschoenen zorgen voor gemak zonder stijl te compromitteren. De zachte en gezellige textuur van de sokken biedt een luxueus gevoel tegen je huid. Zeg vaarwel tegen koude voeten terwijl je geniet van de weelderige warmte die deze wolblend sokken bieden. De Arctic Frost Winter Accessoires Bundel gaat niet alleen over functionaliteit; het is een statement van wintermode. Elk stuk is ontworpen om je niet alleen te beschermen tegen de kou, maar ook om je stijl te verheffen tijdens het winterseizoen. De gekozen materialen voor deze bundel hebben zowel duurzaamheid als comfort als prioriteit, zodat je in stijl kunt genieten van het winterwonderland. Of je jezelf verwent of op zoek bent naar het perfecte cadeau, de Arctic Frost Winter Accessoires Bundel is een veelzijdige keuze. Verras iemand speciaal tijdens het vakantieseizoen of upgrade je eigen wintergarderobe met dit stijlvolle en functionele ensemble. Omarm de vorst met vertrouwen, wetende dat je de perfecte accessoires hebt om je warm en chic te houden.',
                    'meta-description' => 'meta beschrijving',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Meta Titel',
                    'name'             => 'Arctic Frost Winter Accessoires',
                    'sort-description' => 'Omarm de winterse kou met onze Arctic Frost Winter Accessoires Bundel. Deze samengestelde set bevat een luxueuze sjaal, een gezellige muts, touchscreen-compatibele handschoenen en wolblend sokken. Stijlvol en functioneel, dit ensemble is gemaakt van hoogwaardige materialen, waardoor zowel duurzaamheid als comfort worden gegarandeerd. Upgrade je wintergarderobe of verras iemand speciaal met deze perfecte cadeau-optie.',
                ],

                '6' => [
                    'description'      => 'Maak kennis met de Arctic Frost Winter Accessoires Bundel, jouw oplossing om warm, stijlvol en verbonden te blijven tijdens de koude winterdagen. Deze zorgvuldig samengestelde set brengt vier essentiële winteraccessoires samen om een harmonieus ensemble te creëren. De luxueuze sjaal, geweven van een mix van acryl en wol, voegt niet alleen een laag warmte toe, maar brengt ook een vleugje elegantie aan je wintergarderobe. De zachte gebreide muts, met zorg gemaakt, belooft je gezellig te houden terwijl het een modieuze flair aan je look toevoegt. Maar het houdt hier niet op - onze bundel bevat ook touchscreen-compatibele handschoenen. Blijf verbonden zonder warmte op te offeren terwijl je moeiteloos je apparaten bedient. Of je nu oproepen beantwoordt, berichten verstuurt of winterse momenten vastlegt op je smartphone, deze handschoenen zorgen voor gemak zonder stijl te compromitteren. De zachte en gezellige textuur van de sokken biedt een luxueus gevoel tegen je huid. Zeg vaarwel tegen koude voeten terwijl je geniet van de weelderige warmte die deze wolblend sokken bieden. De Arctic Frost Winter Accessoires Bundel gaat niet alleen over functionaliteit; het is een statement van wintermode. Elk stuk is ontworpen om je niet alleen te beschermen tegen de kou, maar ook om je stijl te verheffen tijdens het winterseizoen. De gekozen materialen voor deze bundel hebben zowel duurzaamheid als comfort als prioriteit, zodat je in stijl kunt genieten van het winterwonderland. Of je jezelf verwent of op zoek bent naar het perfecte cadeau, de Arctic Frost Winter Accessoires Bundel is een veelzijdige keuze. Verras iemand speciaal tijdens het vakantieseizoen of upgrade je eigen wintergarderobe met dit stijlvolle en functionele ensemble. Omarm de vorst met vertrouwen, wetende dat je de perfecte accessoires hebt om je warm en chic te houden.',
                    'meta-description' => 'meta beschrijving',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Meta Titel',
                    'name'             => 'Arctic Frost Winter Accessoires Bundel',
                    'sort-description' => 'Omarm de winterse kou met onze Arctic Frost Winter Accessoires Bundel. Deze samengestelde set bevat een luxueuze sjaal, een gezellige muts, touchscreen-compatibele handschoenen en wolblend sokken. Stijlvol en functioneel, dit ensemble is gemaakt van hoogwaardige materialen, waardoor zowel duurzaamheid als comfort worden gegarandeerd. Upgrade je wintergarderobe of verras iemand speciaal met deze perfecte cadeau-optie.',
                ],

                '7' => [
                    'description'      => 'Maak kennis met de OmniHeat Heren Solide Gevoerde Gewatteerde Jas met Capuchon, jouw oplossing om warm en modieus te blijven tijdens koudere seizoenen. Deze jas is ontworpen met duurzaamheid en warmte in gedachten, waardoor het jouw betrouwbare metgezel wordt. Het ontwerp met capuchon voegt niet alleen een vleugje stijl toe, maar biedt ook extra warmte, waardoor je beschermd bent tegen koude wind en weer. De lange mouwen bieden volledige dekking, zodat je van schouder tot pols warm blijft. Uitgerust met insteekzakken biedt deze gewatteerde jas gemak voor het meenemen van je benodigdheden of het warm houden van je handen. De geïsoleerde synthetische vulling biedt verbeterde warmte, waardoor het ideaal is voor koude dagen en nachten. Gemaakt van een duurzame polyester buitenkant en voering, is deze jas gebouwd om lang mee te gaan en de elementen te doorstaan. Verkrijgbaar in 5 aantrekkelijke kleuren, kun je degene kiezen die bij jouw stijl en voorkeur past. Veelzijdig en functioneel, de OmniHeat Heren Solide Gevoerde Gewatteerde Jas met Capuchon is geschikt voor verschillende gelegenheden, of je nu naar het werk gaat, een informeel uitje maakt of een buitenevenement bijwoont. Ervaar de perfecte combinatie van stijl, comfort en functionaliteit met de OmniHeat Heren Solide Gevoerde Gewatteerde Jas met Capuchon. Upgrade je wintergarderobe en blijf knus terwijl je van de buitenlucht geniet. Trotseer de kou in stijl en maak een statement met dit essentiële kledingstuk.',
                    'meta-description' => 'meta beschrijving',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Meta Titel',
                    'name'             => 'OmniHeat Heren Solide Gevoerde Gewatteerde Jas met Capuchon',
                    'sort-description' => 'Blijf warm en stijlvol met onze OmniHeat Heren Solide Gevoerde Gewatteerde Jas met Capuchon. Deze jas is ontworpen om ultieme warmte te bieden en heeft insteekzakken voor extra gemak. Het geïsoleerde materiaal zorgt ervoor dat je warm blijft in koud weer. Verkrijgbaar in 5 aantrekkelijke kleuren, waardoor het een veelzijdige keuze is voor verschillende gelegenheden.',
                ],

                '8' => [
                    'description'      => 'Maak kennis met de OmniHeat Heren Solide Gevoerde Gewatteerde Jas met Capuchon, jouw oplossing om warm en modieus te blijven tijdens koudere seizoenen. Deze jas is ontworpen met duurzaamheid en warmte in gedachten, waardoor het jouw betrouwbare metgezel wordt. Het ontwerp met capuchon voegt niet alleen een vleugje stijl toe, maar biedt ook extra warmte, waardoor je beschermd bent tegen koude wind en weer. De lange mouwen bieden volledige dekking, zodat je van schouder tot pols warm blijft. Uitgerust met insteekzakken biedt deze gewatteerde jas gemak voor het meenemen van je benodigdheden of het warm houden van je handen. De geïsoleerde synthetische vulling biedt verbeterde warmte, waardoor het ideaal is voor koude dagen en nachten. Gemaakt van een duurzame polyester buitenkant en voering, is deze jas gebouwd om lang mee te gaan en de elementen te doorstaan. Verkrijgbaar in 5 aantrekkelijke kleuren, kun je degene kiezen die bij jouw stijl en voorkeur past. Veelzijdig en functioneel, de OmniHeat Heren Solide Gevoerde Gewatteerde Jas met Capuchon is geschikt voor verschillende gelegenheden, of je nu naar het werk gaat, een informeel uitje maakt of een buitenevenement bijwoont. Ervaar de perfecte combinatie van stijl, comfort en functionaliteit met de OmniHeat Heren Solide Gevoerde Gewatteerde Jas met Capuchon. Upgrade je wintergarderobe en blijf knus terwijl je van de buitenlucht geniet. Trotseer de kou in stijl en maak een statement met dit essentiële kledingstuk.',
                    'meta-description' => 'meta beschrijving',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Meta Titel',
                    'name'             => 'OmniHeat Heren Solide Gevoerde Gewatteerde Jas met Capuchon-Blauw-Geel-M',
                    'sort-description' => 'Blijf warm en stijlvol met onze OmniHeat Heren Solide Gevoerde Gewatteerde Jas met Capuchon. Deze jas is ontworpen om ultieme warmte te bieden en heeft insteekzakken voor extra gemak. Het geïsoleerde materiaal zorgt ervoor dat je warm blijft in koud weer. Verkrijgbaar in 5 aantrekkelijke kleuren, waardoor het een veelzijdige keuze is voor verschillende gelegenheden.',
                ],

                '9' => [
                    'description'      => 'Maak kennis met de OmniHeat Heren Solide Gevoerde Gewatteerde Jas met Capuchon, jouw oplossing om warm en modieus te blijven tijdens koudere seizoenen. Deze jas is ontworpen met duurzaamheid en warmte in gedachten, waardoor het jouw vertrouwde metgezel wordt. Het ontwerp met capuchon voegt niet alleen een vleugje stijl toe, maar biedt ook extra warmte, waardoor je beschermd bent tegen koude wind en weer. De lange mouwen bieden volledige bedekking, zodat je van schouder tot pols warm blijft. Uitgerust met insteekzakken biedt deze gewatteerde jas gemak voor het meenemen van je benodigdheden of om je handen warm te houden. De geïsoleerde synthetische vulling biedt verbeterde warmte, waardoor het ideaal is voor koude dagen en nachten. Gemaakt van een duurzame polyester buitenkant en voering, is deze jas gebouwd om lang mee te gaan en de elementen te doorstaan. Verkrijgbaar in 5 aantrekkelijke kleuren, kun je degene kiezen die bij jouw stijl en voorkeur past. Veelzijdig en functioneel, de OmniHeat Heren Solide Gevoerde Gewatteerde Jas met Capuchon is geschikt voor verschillende gelegenheden, of je nu naar het werk gaat, een informeel uitje maakt of een buitenevenement bijwoont. Ervaar de perfecte combinatie van stijl, comfort en functionaliteit met de OmniHeat Heren Solide Gevoerde Gewatteerde Jas met Capuchon. Upgrade je wintergarderobe en blijf knus terwijl je van de buitenlucht geniet. Trotseer de kou in stijl en maak een statement met dit essentiële kledingstuk.',
                    'meta-description' => 'meta beschrijving',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Meta Titel',
                    'name'             => 'OmniHeat Heren Solide Gevoerde Gewatteerde Jas met Capuchon-Blauw-Geel-L',
                    'sort-description' => 'Blijf warm en stijlvol met onze OmniHeat Heren Solide Gevoerde Gewatteerde Jas met Capuchon. Deze jas is ontworpen om ultieme warmte te bieden en heeft insteekzakken voor extra gemak. Het geïsoleerde materiaal zorgt ervoor dat je warm blijft in koud weer. Verkrijgbaar in 5 aantrekkelijke kleuren, waardoor het een veelzijdige keuze is voor verschillende gelegenheden.',
                ],

                '10' => [
                    'description'      => 'Maak kennis met de OmniHeat Heren Solide Gevoerde Gewatteerde Jas met Capuchon, jouw oplossing om warm en modieus te blijven tijdens koudere seizoenen. Deze jas is ontworpen met duurzaamheid en warmte in gedachten, waardoor het jouw vertrouwde metgezel wordt. Het ontwerp met capuchon voegt niet alleen een vleugje stijl toe, maar biedt ook extra warmte, waardoor je beschermd bent tegen koude wind en weer. De lange mouwen bieden volledige bedekking, zodat je van schouder tot pols warm blijft. Uitgerust met insteekzakken biedt deze gewatteerde jas gemak voor het meenemen van je benodigdheden of om je handen warm te houden. De geïsoleerde synthetische vulling biedt verbeterde warmte, waardoor het ideaal is voor koude dagen en nachten. Gemaakt van een duurzame polyester buitenkant en voering, is deze jas gebouwd om lang mee te gaan en de elementen te doorstaan. Verkrijgbaar in 5 aantrekkelijke kleuren, kun je degene kiezen die bij jouw stijl en voorkeur past. Veelzijdig en functioneel, de OmniHeat Heren Solide Gevoerde Gewatteerde Jas met Capuchon is geschikt voor verschillende gelegenheden, of je nu naar het werk gaat, een informeel uitje maakt of een buitenevenement bijwoont. Ervaar de perfecte combinatie van stijl, comfort en functionaliteit met de OmniHeat Heren Solide Gevoerde Gewatteerde Jas met Capuchon. Upgrade je wintergarderobe en blijf knus terwijl je van de buitenlucht geniet. Trotseer de kou in stijl en maak een statement met dit essentiële kledingstuk.',
                    'meta-description' => 'meta beschrijving',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Meta Titel',
                    'name'             => 'OmniHeat Heren Solide Gevoerde Gewatteerde Jas met Capuchon-Blauw-Groen-M',
                    'sort-description' => 'Blijf warm en stijlvol met onze OmniHeat Heren Solide Gevoerde Gewatteerde Jas met Capuchon. Deze jas is ontworpen om ultieme warmte te bieden en heeft insteekzakken voor extra gemak. Het geïsoleerde materiaal zorgt ervoor dat je warm blijft in koud weer. Verkrijgbaar in 5 aantrekkelijke kleuren, waardoor het een veelzijdige keuze is voor verschillende gelegenheden.',
                ],

                '11' => [
                    'description'      => 'Maak kennis met de OmniHeat Heren Solide Gewatteerde Jas met Capuchon, jouw oplossing om warm en modieus te blijven tijdens koudere seizoenen. Deze jas is ontworpen met duurzaamheid en warmte in gedachten, waardoor het jouw vertrouwde metgezel wordt. Het ontwerp met capuchon voegt niet alleen een vleugje stijl toe, maar biedt ook extra warmte, waardoor je beschermd bent tegen koude wind en weer. De lange mouwen bieden volledige bedekking, zodat je van schouder tot pols warm blijft. Uitgerust met insteekzakken biedt deze gewatteerde jas gemak voor het meenemen van je benodigdheden of om je handen warm te houden. De geïsoleerde synthetische vulling biedt verbeterde warmte, waardoor het ideaal is voor koude dagen en nachten. Gemaakt van een duurzame polyester buitenkant en voering, is deze jas gebouwd om lang mee te gaan en de elementen te doorstaan. Verkrijgbaar in 5 aantrekkelijke kleuren, kun je degene kiezen die bij jouw stijl en voorkeur past. Veelzijdig en functioneel, de OmniHeat Heren Solide Gewatteerde Jas met Capuchon is geschikt voor verschillende gelegenheden, of je nu naar het werk gaat, een informeel uitje maakt of een buitenevenement bijwoont. Ervaar de perfecte combinatie van stijl, comfort en functionaliteit met de OmniHeat Heren Solide Gewatteerde Jas met Capuchon. Upgrade je wintergarderobe en blijf knus terwijl je van de buitenlucht geniet. Trotseer de kou in stijl en maak een statement met dit essentiële kledingstuk.',
                    'meta-description' => 'meta beschrijving',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Meta Titel',
                    'name'             => 'OmniHeat Heren Solide Gewatteerde Jas met Capuchon-Blauw-Groen-L',
                    'sort-description' => 'Blijf warm en stijlvol met onze OmniHeat Heren Solide Gewatteerde Jas met Capuchon. Deze jas is ontworpen om ultieme warmte te bieden en heeft insteekzakken voor extra gemak. Het geïsoleerde materiaal zorgt ervoor dat je warm blijft in koud weer. Verkrijgbaar in 5 aantrekkelijke kleuren, waardoor het een veelzijdige keuze is voor verschillende gelegenheden.',
                ],
            ],

            'product-bundle-option-translations' => [
                '1' => [
                    'label' => 'Bundeloptie 1',
                ],

                '2' => [
                    'label' => 'Bundeloptie 1',
                ],

                '3' => [
                    'label' => 'Bundeloptie 2',
                ],

                '4' => [
                    'label' => 'Bundeloptie 2',
                ],
            ],
        ],
    ],

    'installer' => [
        'index' => [
            'create-administrator' => [
                'admin'            => 'Beheerder',
                'bagisto'          => 'Bagisto',
                'confirm-password' => 'Bevestig wachtwoord',
                'email'            => 'E-mail',
                'email-address'    => 'admin@example.com',
                'password'         => 'Wachtwoord',
                'title'            => 'Beheerder aanmaken',
            ],

            'environment-configuration' => [
                'algerian-dinar'              => 'Algerijnse Dinar (DZD)',
                'allowed-currencies'          => 'Toegestane Valuta',
                'allowed-locales'             => 'Toegestane Locaties',
                'application-name'            => 'Toepassingsnaam',
                'argentine-peso'              => 'Argentijnse Peso (ARS)',
                'australian-dollar'           => 'Australische Dollar (AUD)',
                'bagisto'                     => 'Bagisto',
                'bangladeshi-taka'            => 'Bangladesh Taka (BDT)',
                'brazilian-real'              => 'Braziliaanse Real (BRL)',
                'british-pound-sterling'      => 'Britse Pond Sterling (GBP)',
                'canadian-dollar'             => 'Canadese Dollar (CAD)',
                'cfa-franc-bceao'             => 'CFA Franc BCEAO (XOF)',
                'cfa-franc-beac'              => 'CFA Franc BEAC (XAF)',
                'chilean-peso'                => 'Chileense Peso (CLP)',
                'chinese-yuan'                => 'Chinese Yuan (CNY)',
                'colombian-peso'              => 'Colombiaanse Peso (COP)',
                'czech-koruna'                => 'Tsjechische Kroon (CZK)',
                'danish-krone'                => 'Deense Kroon (DKK)',
                'database-connection'         => 'Database Verbinding',
                'database-hostname'           => 'Database Hostnaam',
                'database-name'               => 'Database Naam',
                'database-password'           => 'Database Wachtwoord',
                'database-port'               => 'Database Poort',
                'database-prefix'             => 'Database Voorvoegsel',
                'database-username'           => 'Database Gebruikersnaam',
                'default-currency'            => 'Standaard Valuta',
                'default-locale'              => 'Standaard Locatie',
                'default-timezone'            => 'Standaard Tijdzone',
                'default-url'                 => 'Standaard URL',
                'default-url-link'            => 'https://localhost',
                'egyptian-pound'              => 'Egyptische Pond (EGP)',
                'euro'                        => 'Euro (EUR)',
                'fijian-dollar'               => 'Fijische Dollar (FJD)',
                'hong-kong-dollar'            => 'Hongkong Dollar (HKD)',
                'hungarian-forint'            => 'Hongaarse Forint (HUF)',
                'indian-rupee'                => 'Indiase Roepie (INR)',
                'indonesian-rupiah'           => 'Indonesische Roepia (IDR)',
                'israeli-new-shekel'          => 'Israëlische Nieuwe Shekel (ILS)',
                'japanese-yen'                => 'Japanse Yen (JPY)',
                'jordanian-dinar'             => 'Jordaanse Dinar (JOD)',
                'kazakhstani-tenge'           => 'Kazachse Tenge (KZT)',
                'kuwaiti-dinar'               => 'Koeweitse Dinar (KWD)',
                'lebanese-pound'              => 'Libanese Pond (LBP)',
                'libyan-dinar'                => 'Libische Dinar (LYD)',
                'malaysian-ringgit'           => 'Maleisische Ringgit (MYR)',
                'mauritian-rupee'             => 'Mauritiaanse Roepie (MUR)',
                'mexican-peso'                => 'Mexicaanse Peso (MXN)',
                'moroccan-dirham'             => 'Marokkaanse Dirham (MAD)',
                'mysql'                       => 'Mysql',
                'nepalese-rupee'              => 'Nepalese Roepie (NPR)',
                'new-taiwan-dollar'           => 'Nieuwe Taiwanese Dollar (TWD)',
                'new-zealand-dollar'          => 'Nieuw-Zeelandse Dollar (NZD)',
                'nigerian-naira'              => 'Nigeriaanse Naira (NGN)',
                'norwegian-krone'             => 'Noorse Kroon (NOK)',
                'omani-rial'                  => 'Omaanse Rial (OMR)',
                'pakistani-rupee'             => 'Pakistaanse Roepie (PKR)',
                'panamanian-balboa'           => 'Panamese Balboa (PAB)',
                'paraguayan-guarani'          => 'Paraguayaanse Guarani (PYG)',
                'peruvian-nuevo-sol'          => 'Peruviaanse Nuevo Sol (PEN)',
                'pgsql'                       => 'pgSQL',
                'philippine-peso'             => 'Filipijnse Peso (PHP)',
                'polish-zloty'                => 'Poolse Zloty (PLN)',
                'qatari-rial'                 => 'Qatarese Rial (QAR)',
                'romanian-leu'                => 'Roemeense Leu (RON)',
                'russian-ruble'               => 'Russische Roebel (RUB)',
                'saudi-riyal'                 => 'Saoedi-Riyal (SAR)',
                'select-timezone'             => 'Selecteer Tijdzone',
                'singapore-dollar'            => 'Singaporese Dollar (SGD)',
                'south-african-rand'          => 'Zuid-Afrikaanse Rand (ZAR)',
                'south-korean-won'            => 'Zuid-Koreaanse Won (KRW)',
                'sqlsrv'                      => 'SQLSRV',
                'sri-lankan-rupee'            => 'Sri Lankaanse Roepie (LKR)',
                'swedish-krona'               => 'Zweedse Kroon (SEK)',
                'swiss-franc'                 => 'Zwitserse Frank (CHF)',
                'thai-baht'                   => 'Thaise Baht (THB)',
                'title'                       => 'Winkelconfiguratie',
                'tunisian-dinar'              => 'Tunesische Dinar (TND)',
                'turkish-lira'                => 'Turkse Lira (TRY)',
                'ukrainian-hryvnia'           => 'Oekraïense Hryvnia (UAH)',
                'united-arab-emirates-dirham' => 'Verenigde Arabische Emiraten Dirham (AED)',
                'united-states-dollar'        => 'Amerikaanse Dollar (USD)',
                'uzbekistani-som'             => 'Oezbeekse Som (UZS)',
                'venezuelan-bolívar'          => 'Venezolaanse Bolívar (VEF)',
                'vietnamese-dong'             => 'Vietnamese Dong (VND)',
                'warning-message'             => 'Pas op! De instellingen voor uw standaardtaal en standaardvaluta zijn permanent en kunnen niet worden gewijzigd zodra ze zijn ingesteld.',
                'zambian-kwacha'              => 'Zambiaanse Kwacha (ZMW)',
            ],

            'sample-products' => [
                'download-sample' => 'download voorbeeld',
                'no'              => 'Nee',
                'sample-products' => 'Voorbeeldproducten',
                'title'           => 'Voorbeeldproducten',
                'yes'             => 'Ja',
            ],

            'installation-processing' => [
                'bagisto'          => 'Bagisto installatie',
                'bagisto-info'     => 'Het maken van database tabellen kan even duren',
                'title'            => 'Installatie',
            ],

            'installation-completed' => [
                'admin-panel'                => 'Beheerderspaneel',
                'bagisto-forums'             => 'Bagisto Forum',
                'customer-panel'             => 'Klantenpaneel',
                'explore-bagisto-extensions' => 'Verken Bagisto-extensies',
                'title'                      => 'Installatie voltooid',
                'title-info'                 => 'Bagisto is succesvol geïnstalleerd op uw systeem.',
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

            'start' => [
                'locale'        => 'Locatie',
                'main'          => 'Start',
                'select-locale' => 'Selecteer Locatie',
                'title'         => 'Uw Bagisto-installatie',
                'welcome-title' => 'Welkom bij Bagisto',
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
                'pcre'        => 'PCRE',
                'pdo'         => 'PDO',
                'php'         => 'PHP',
                'php-version' => '8.1 of hoger',
                'session'     => 'Sessie',
                'title'       => 'Serververeisten',
                'tokenizer'   => 'Tokenizer',
                'xml'         => 'XML',
            ],

            'arabic'                   => 'Arabisch',
            'back'                     => 'Terug',
            'bagisto'                  => 'Bagisto',
            'bagisto-info'             => 'Een communityproject van',
            'bagisto-logo'             => 'Bagisto Logo',
            'bengali'                  => 'Bengaals',
            'chinese'                  => 'Chinees',
            'continue'                 => 'Doorgaan',
            'dutch'                    => 'Nederlands',
            'english'                  => 'Engels',
            'french'                   => 'Frans',
            'german'                   => 'Duits',
            'hebrew'                   => 'Hebreeuws',
            'hindi'                    => 'Hindi',
            'installation-description' => 'De installatie van Bagisto omvat doorgaans verschillende stappen. Hier is een algemeen overzicht van het installatieproces voor Bagisto',
            'installation-info'        => 'We zijn blij je hier te zien!',
            'installation-title'       => 'Welkom bij de Bagisto-installatie',
            'italian'                  => 'Italiaans',
            'japanese'                 => 'Japans',
            'persian'                  => 'Perzisch',
            'polish'                   => 'Pools',
            'portuguese'               => 'Braziliaans Portugees',
            'russian'                  => 'Russisch',
            'sinhala'                  => 'Singalees',
            'spanish'                  => 'Spaans',
            'title'                    => 'Bagisto Installer',
            'turkish'                  => 'Turks',
            'ukrainian'                => 'Oekraïens',
            'webkul'                   => 'Webkul',
        ],
    ],
];
