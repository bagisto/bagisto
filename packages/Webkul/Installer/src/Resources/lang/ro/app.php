<?php

return [
    'seeders' => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'Implicit',
            ],

            'attribute-groups' => [
                'description' => 'Descriere',
                'general' => 'General',
                'inventories' => 'Inventare',
                'meta-description' => 'Meta descriere',
                'price' => 'Preț',
                'settings' => 'Setări',
                'shipping' => 'Livrare',
            ],

            'attributes' => [
                'brand' => 'Marcă',
                'color' => 'Culoare',
                'cost' => 'Cost',
                'description' => 'Descriere',
                'featured' => 'Recomandat',
                'guest-checkout' => 'Comandă ca vizitator',
                'height' => 'Înălțime',
                'length' => 'Lungime',
                'manage-stock' => 'Gestionare stoc',
                'meta-description' => 'Meta descriere',
                'meta-keywords' => 'Cuvinte cheie meta',
                'meta-title' => 'Titlu meta',
                'name' => 'Nume',
                'new' => 'Nou',
                'price' => 'Preț',
                'product-number' => 'Număr produs',
                'short-description' => 'Descriere scurtă',
                'size' => 'Mărime',
                'sku' => 'SKU',
                'special-price' => 'Preț special',
                'special-price-from' => 'Preț special de la',
                'special-price-to' => 'Preț special până la',
                'status' => 'Status',
                'tax-category' => 'Categorie taxe',
                'url-key' => 'Cheie URL',
                'visible-individually' => 'Vizibil individual',
                'weight' => 'Greutate',
                'width' => 'Lățime',
            ],

            'attribute-options' => [
                'black' => 'Negru',
                'green' => 'Verde',
                'l' => 'L',
                'm' => 'M',
                'red' => 'Roșu',
                's' => 'S',
                'white' => 'Alb',
                'xl' => 'XL',
                'yellow' => 'Galben',
            ],
        ],

        'category' => [
            'categories' => [
                'description' => 'Descrierea categoriei rădăcină',
                'name' => 'Rădăcină',
            ],
        ],

        'cms' => [
            'pages' => [
                'about-us' => [
                    'content' => 'Conținutul paginii Despre noi',
                    'title' => 'Despre noi',
                ],

                'contact-us' => [
                    'content' => 'Conținutul paginii Contactați-ne',
                    'title' => 'Contactați-ne',
                ],

                'customer-service' => [
                    'content' => 'Conținutul paginii Serviciul clienți',
                    'title' => 'Serviciul clienți',
                ],

                'payment-policy' => [
                    'content' => 'Conținutul paginii Politica de plată',
                    'title' => 'Politica de plată',
                ],

                'privacy-policy' => [
                    'content' => 'Conținutul paginii Politica de confidențialitate',
                    'title' => 'Politica de confidențialitate',
                ],

                'refund-policy' => [
                    'content' => 'Conținutul paginii Politica de rambursare',
                    'title' => 'Politica de rambursare',
                ],

                'return-policy' => [
                    'content' => 'Conținutul paginii Politica de returnare',
                    'title' => 'Politica de returnare',
                ],

                'shipping-policy' => [
                    'content' => 'Conținutul paginii Politica de livrare',
                    'title' => 'Politica de livrare',
                ],

                'terms-conditions' => [
                    'content' => 'Conținutul paginii Termeni și condiții',
                    'title' => 'Termeni și condiții',
                ],

                'terms-of-use' => [
                    'content' => 'Conținutul paginii Termeni de utilizare',
                    'title' => 'Termeni de utilizare',
                ],

                'whats-new' => [
                    'content' => 'Conținutul paginii Noutăți',
                    'title' => 'Noutăți',
                ],
            ],
        ],

        'core' => [
            'channels' => [
                'meta-description' => 'Meta descriere magazin demonstrativ',
                'meta-keywords' => 'Cuvinte cheie meta magazin demonstrativ',
                'meta-title' => 'Magazin demonstrativ',
                'name' => 'Implicit',
            ],

            'currencies' => [
                'AED' => 'Dirham din Emiratele Arabe Unite',
                'ARS' => 'Peso argentinian',
                'AUD' => 'Dolar australian',
                'BDT' => 'Taka din Bangladesh',
                'BHD' => 'Dinar din Bahrain',
                'BRL' => 'Real brazilian',
                'CAD' => 'Dolar canadian',
                'CHF' => 'Franc elvețian',
                'CLP' => 'Peso chilian',
                'CNY' => 'Yuan chinezesc',
                'COP' => 'Peso columbian',
                'CZK' => 'Coroană cehă',
                'DKK' => 'Coroană daneză',
                'DZD' => 'Dinar algerian',
                'EGP' => 'Liră egipteană',
                'EUR' => 'Euro',
                'FJD' => 'Dolar fijian',
                'GBP' => 'Liră sterlină britanică',
                'HKD' => 'Dolar din Hong Kong',
                'HUF' => 'Forint maghiar',
                'IDR' => 'Rupie indoneziană',
                'ILS' => 'Shekel nou israelian',
                'INR' => 'Rupie indiană',
                'JOD' => 'Dinar iordanian',
                'JPY' => 'Yen japonez',
                'KRW' => 'Won sud-coreean',
                'KWD' => 'Dinar kuweitian',
                'KZT' => 'Tenge kazahă',
                'LBP' => 'Liră libaneză',
                'LKR' => 'Rupie din Sri Lanka',
                'LYD' => 'Dinar libian',
                'MAD' => 'Dirham marocan',
                'MUR' => 'Rupie mauritiană',
                'MXN' => 'Peso mexican',
                'MYR' => 'Ringgit malaiezian',
                'NGN' => 'Naira nigeriană',
                'NOK' => 'Coroană norvegiană',
                'NPR' => 'Rupie nepaleză',
                'NZD' => 'Dolar neozeelandez',
                'OMR' => 'Rial omanez',
                'PAB' => 'Balboa panamez',
                'PEN' => 'Sol nou peruan',
                'PHP' => 'Peso filipinez',
                'PKR' => 'Rupie pakistaneză',
                'PLN' => 'Zlot polonez',
                'PYG' => 'Guarani paraguayan',
                'QAR' => 'Rial qatarian',
                'RON' => 'Leu românesc',
                'RUB' => 'Rublă rusească',
                'SAR' => 'Rial saudit',
                'SEK' => 'Coroană suedeză',
                'SGD' => 'Dolar singaporez',
                'THB' => 'Baht thailandez',
                'TND' => 'Dinar tunisian',
                'TRY' => 'Liră turcească',
                'TWD' => 'Dolar nou taiwanez',
                'UAH' => 'Hryvnia ucraineană',
                'USD' => 'Dolar american',
                'UZS' => 'Som uzbek',
                'VEF' => 'Bolívar venezuelean',
                'VND' => 'Dong vietnamez',
                'XAF' => 'Franc CFA BEAC',
                'XOF' => 'Franc CFA BCEAO',
                'ZAR' => 'Rand sud-african',
                'ZMW' => 'Kwacha zambiană',
            ],

            'locales' => [
                'ar' => 'Arabă',
                'bn' => 'Bengali',
                'ca' => 'Catalană',
                'de' => 'Germană',
                'en' => 'Engleză',
                'es' => 'Spaniolă',
                'fa' => 'Persană',
                'fr' => 'Franceză',
                'he' => 'Ebraică',
                'hi_IN' => 'Hindi',
                'id' => 'Indoneziană',
                'it' => 'Italiană',
                'ja' => 'Japoneză',
                'nl' => 'Olandeză',
                'pl' => 'Poloneză',
                'pt_BR' => 'Portugheză braziliană',
                'ro' => 'Română',
                'ru' => 'Rusă',
                'sin' => 'Singhaleză',
                'tr' => 'Turcă',
                'uk' => 'Ucraineană',
                'zh_CN' => 'Chineză',
            ],
        ],

        'customer' => [
            'customer-groups' => [
                'general' => 'General',
                'guest' => 'Vizitator',
                'wholesale' => 'Angro',
            ],
        ],

        'inventory' => [
            'inventory-sources' => [
                'name' => 'Implicit',
            ],
        ],

        'shop' => [
            'theme-customizations' => [
                'all-products' => [
                    'name' => 'Toate produsele',

                    'options' => [
                        'title' => 'Toate produsele',
                    ],
                ],

                'bold-collections' => [
                    'content' => [
                        'btn-title' => 'Vezi colecțiile',
                        'description' => 'Vă prezentăm noile noastre colecții îndrăznețe! Ridicați-vă stilul cu designuri curajoase și declarații vibrante. Explorați modele izbitoare și culori îndrăznețe care vă redefinesc garderoba. Pregătiți-vă să îmbrățișați extraordinarul!',
                        'title' => 'Pregătiți-vă pentru noile noastre colecții îndrăznețe!',
                    ],

                    'name' => 'Colecții îndrăznețe',
                ],

                'categories-collections' => [
                    'name' => 'Colecții pe categorii',
                ],

                'featured-collections' => [
                    'name' => 'Colecții recomandate',

                    'options' => [
                        'title' => 'Produse recomandate',
                    ],
                ],

                'footer-links' => [
                    'name' => 'Linkuri subsol',

                    'options' => [
                        'about-us' => 'Despre noi',
                        'contact-us' => 'Contactați-ne',
                        'customer-service' => 'Serviciul clienți',
                        'payment-policy' => 'Politica de plată',
                        'privacy-policy' => 'Politica de confidențialitate',
                        'refund-policy' => 'Politica de rambursare',
                        'return-policy' => 'Politica de returnare',
                        'shipping-policy' => 'Politica de livrare',
                        'terms-conditions' => 'Termeni și condiții',
                        'terms-of-use' => 'Termeni de utilizare',
                        'whats-new' => 'Noutăți',
                    ],
                ],

                'game-container' => [
                    'content' => [
                        'sub-title-1' => 'Colecțiile noastre',
                        'sub-title-2' => 'Colecțiile noastre',
                        'title' => 'Jocul cu noile noastre adăugiri!',
                    ],

                    'name' => 'Container joc',
                ],

                'image-carousel' => [
                    'name' => 'Carusel de imagini',

                    'sliders' => [
                        'title' => 'Pregătiți-vă pentru noua colecție',
                    ],
                ],

                'new-products' => [
                    'name' => 'Produse noi',

                    'options' => [
                        'title' => 'Produse noi',
                    ],
                ],

                'offer-information' => [
                    'content' => [
                        'title' => 'Obțineți PÂNĂ LA 40% REDUCERE la prima comandă CUMPĂRAȚI ACUM',
                    ],

                    'name' => 'Informații ofertă',
                ],

                'services-content' => [
                    'description' => [
                        'emi-available-info' => 'Rate fără costuri disponibile pe toate cardurile de credit majore',
                        'free-shipping-info' => 'Bucurați-vă de livrare gratuită la toate comenzile',
                        'product-replace-info' => 'Înlocuire ușoară a produsului disponibilă!',
                        'time-support-info' => 'Suport dedicat 24/7 prin chat și e-mail',
                    ],

                    'name' => 'Conținut servicii',

                    'title' => [
                        'emi-available' => 'Rate disponibile',
                        'free-shipping' => 'Livrare gratuită',
                        'product-replace' => 'Înlocuire produs',
                        'time-support' => 'Suport 24/7',
                    ],
                ],

                'top-collections' => [
                    'content' => [
                        'sub-title-1' => 'Colecțiile noastre',
                        'sub-title-2' => 'Colecțiile noastre',
                        'sub-title-3' => 'Colecțiile noastre',
                        'sub-title-4' => 'Colecțiile noastre',
                        'sub-title-5' => 'Colecțiile noastre',
                        'sub-title-6' => 'Colecțiile noastre',
                        'title' => 'Jocul cu noile noastre adăugiri!',
                    ],

                    'name' => 'Colecții de top',
                ],
            ],
        ],

        'user' => [
            'roles' => [
                'description' => 'Utilizatorii cu acest rol vor avea acces complet',
                'name' => 'Administrator',
            ],

            'users' => [
                'name' => 'Exemplu',
            ],
        ],

        'sample-categories' => [
            'category-translation' => [
                '2' => [
                    'description' => 'Descrierea categoriei Bărbați',
                    'meta-description' => 'Meta descrierea categoriei Bărbați',
                    'meta-keywords' => 'Cuvinte cheie meta ale categoriei Bărbați',
                    'meta-title' => 'Titlu meta al categoriei Bărbați',
                    'name' => 'Bărbați',
                    'slug' => 'barbati',
                ],

                '3' => [
                    'description' => 'Descrierea categoriei Îmbrăcăminte de iarnă',
                    'meta-description' => 'Meta descrierea categoriei Îmbrăcăminte de iarnă',
                    'meta-keywords' => 'Cuvinte cheie meta ale categoriei Îmbrăcăminte de iarnă',
                    'meta-title' => 'Titlu meta al categoriei Îmbrăcăminte de iarnă',
                    'name' => 'Îmbrăcăminte de iarnă',
                    'slug' => 'imbracaminte-de-iarna',
                ],
            ],
        ],

        'sample-products' => [
            'product-flat' => [
                '1' => [
                    'description' => 'Căciula Arctic Cozy Knit este soluția ideală pentru a rămâne cald, confortabil și elegant în lunile mai reci. Realizată dintr-un amestec moale și durabil de tricot acrilic, această căciulă este concepută să ofere o potrivire confortabilă și plăcută. Designul clasic o face potrivită atât pentru bărbați, cât și pentru femei, oferind un accesoriu versatil care completează diverse stiluri. Fie că ieșiți într-o zi obișnuită în oraș sau vă bucurați de natură, această căciulă adaugă o notă de confort și căldură ținutei dumneavoastră. Materialul moale și respirabil vă asigură confortul fără a sacrifica stilul. Căciula Arctic Cozy Knit nu este doar un accesoriu; este o declarație de modă de iarnă. Simplitatea sa o face ușor de asortat cu diferite ținute, făcând-o un element de bază în garderoba de iarnă. Ideală pentru cadouri sau ca răsfăț personal, această căciulă este o adăugire plină de grijă la orice ținută de iarnă. Este un accesoriu versatil care depășește funcționalitatea, adăugând o notă de căldură și stil look-ului dumneavoastră. Îmbrățișați esența iernii cu căciula Arctic Cozy Knit. Fie că vă bucurați de o zi relaxată sau înfruntați elementele, lăsați această căciulă să fie compania dumneavoastră pentru confort și stil. Ridicați-vă garderoba de iarnă cu acest accesoriu clasic care combină fără efort căldura cu un simț etern al modei.',
                    'meta-description' => 'meta descriere',
                    'meta-keywords' => 'meta1, meta2, meta3',
                    'meta-title' => 'Titlu meta',
                    'name' => 'Căciulă unisex Arctic Cozy Knit',
                    'short-description' => 'Îmbrățișați zilele reci cu stil cu căciula noastră Arctic Cozy Knit. Realizată dintr-un amestec moale și durabil de acrilic, această căciulă clasică oferă căldură și versatilitate. Potrivită atât pentru bărbați, cât și pentru femei, este accesoriul ideal pentru purtare casual sau în aer liber. Ridicați-vă garderoba de iarnă sau oferiți un cadou special cu această căciulă esențială.',
                ],

                '2' => [
                    'description' => 'Fular de iarnă Arctic Bliss este mai mult decât un simplu accesoriu pentru vreme rece; este o declarație de căldură, confort și stil pentru sezonul de iarnă. Realizat cu grijă dintr-un amestec luxos de acrilic și lână, acest fular este conceput să vă mențină cald și confortabil chiar și la cele mai scăzute temperaturi. Textura moale și plușată nu doar oferă izolație împotriva frigului, ci adaugă și o notă de lux garderobei dumneavoastră de iarnă. Designul fularului Arctic Bliss este atât elegant, cât și versatil, făcându-l un complement perfect pentru o varietate de ținute de iarnă. Fie că vă îmbrăcați pentru o ocazie specială sau adăugați un strat șic la look-ul de zi cu zi, acest fular vă completează stilul fără efort. Lungimea extra-lungă a fularului oferă opțiuni de stilizare personalizabile. Înfășurați-l pentru căldură suplimentară, drapați-l lejer pentru un look casual sau experimentați cu diferite noduri pentru a vă exprima stilul unic. Această versatilitate îl face un accesoriu indispensabil pentru sezonul de iarnă. Căutați cadoul perfect? Fularul de iarnă Arctic Bliss este o alegere ideală. Fie că surprindeți o persoană dragă sau vă răsfățați pe dumneavoastră, acest fular este un cadou atemporal și practic ce va fi prețuit pe parcursul lunilor de iarnă. Îmbrățișați iarna cu fularul Arctic Bliss, unde căldura întâlnește stilul în armonie perfectă. Ridicați-vă garderoba de iarnă cu acest accesoriu esențial care nu doar vă menține cald, ci adaugă și o notă de sofisticare ținutei de vreme rece.',
                    'meta-description' => 'meta descriere',
                    'meta-keywords' => 'meta1, meta2, meta3',
                    'meta-title' => 'Titlu meta',
                    'name' => 'Fular elegant de iarnă Arctic Bliss',
                    'short-description' => 'Experimentați îmbrățișarea căldurii și stilului cu fularul nostru de iarnă Arctic Bliss. Realizat dintr-un amestec luxos de acrilic și lână, acest fular confortabil este conceput să vă mențină cald în cele mai reci zile. Designul său elegant și versatil, combinat cu o lungime extra-lungă, oferă opțiuni de stilizare personalizabile. Ridicați-vă garderoba de iarnă sau oferiți bucurie cuiva special cu acest accesoriu esențial de iarnă.',
                ],

                '3' => [
                    'description' => 'Vă prezentăm mănușile de iarnă Arctic Touchscreen – unde căldura, stilul și conectivitatea se întâlnesc pentru a vă îmbunătăți experiența de iarnă. Realizate din acrilic de înaltă calitate, aceste mănuși sunt concepute să ofere căldură și durabilitate excepționale. Vârfurile degetelor compatibile cu ecranul tactil vă permit să rămâneți conectat fără a vă expune mâinile la frig. Răspundeți la apeluri, trimiteți mesaje și navigați pe dispozitivele dumneavoastră fără efort, menținându-vă în același timp mâinile calde. Căptușeala izolantă adaugă un strat suplimentar de confort, făcând aceste mănuși alegerea ideală pentru a înfrunta frigul iernii. Fie că faceți naveta, rezolvați comisioane sau vă bucurați de activități în aer liber, aceste mănuși oferă căldura și protecția de care aveți nevoie. Manșetele elastice asigură o potrivire sigură, prevenind curentele reci și menținând mănușile la loc în timpul activităților zilnice. Designul elegant adaugă o notă de fler ținutei dumneavoastră de iarnă, făcând aceste mănuși la fel de la modă pe cât sunt de funcționale. Ideale pentru cadouri sau ca răsfăț personal, mănușile de iarnă Arctic Touchscreen sunt un accesoriu indispensabil pentru individul modern. Spuneți la revedere inconvenienței de a vă scoate mănușile pentru a folosi dispozitivele și îmbrățișați combinația perfectă de căldură, stil și conectivitate. Rămâneți conectat, rămâneți cald și rămâneți elegant cu mănușile de iarnă Arctic Touchscreen – companionul dumneavoastră de încredere pentru a cuceri sezonul de iarnă cu încredere.',
                    'meta-description' => 'meta descriere',
                    'meta-keywords' => 'meta1, meta2, meta3',
                    'meta-title' => 'Titlu meta',
                    'name' => 'Mănuși de iarnă Arctic Touchscreen',
                    'short-description' => 'Rămâneți conectat și cald cu mănușile noastre de iarnă Arctic Touchscreen. Aceste mănuși nu sunt doar realizate din acrilic de înaltă calitate pentru căldură și durabilitate, ci au și un design compatibil cu ecranul tactil. Cu căptușeală izolantă, manșete elastice pentru o potrivire sigură și un aspect elegant, aceste mănuși sunt perfecte pentru purtarea zilnică în condiții de frig.',
                ],

                '4' => [
                    'description' => 'Vă prezentăm șosetele Arctic Warmth din amestec de lână – companionul esențial pentru picioare confortabile și călduroase în sezoanele mai reci. Realizate dintr-un amestec premium de lână Merino, acrilic, nailon și spandex, aceste șosete sunt concepute să ofere căldură și confort incomparabile. Amestecul de lână asigură că picioarele dumneavoastră rămân calde chiar și la cele mai scăzute temperaturi, făcând aceste șosete alegerea perfectă pentru aventurile de iarnă sau pur și simplu pentru a rămâne confortabil acasă. Textura moale și confortabilă a șosetelor oferă o senzație luxoasă pe piele. Spuneți la revedere picioarelor reci pe măsură ce îmbrățișați căldura plușată oferită de aceste șosete din amestec de lână. Concepute pentru durabilitate, șosetele au călcâi și vârf ranforsat, adăugând rezistență suplimentară zonelor cu uzură mare. Acest lucru asigură că șosetele dumneavoastră rezistă testului timpului, oferind confort și căldură de lungă durată. Natura respirabilă a materialului previne supraîncălzirea, permițând picioarelor dumneavoastră să rămână confortabile și uscate pe parcursul zilei. Fie că ieșiți afară pentru o drumeție de iarnă sau vă relaxați în interior, aceste șosete oferă echilibrul perfect între căldură și respirabilitate. Versatile și elegante, aceste șosete din amestec de lână sunt potrivite pentru diverse ocazii. Asortați-le cu cizmele preferate pentru un look de iarnă la modă sau purtați-le în casă pentru confort maxim. Ridicați-vă garderoba de iarnă și prioritizați confortul cu șosetele Arctic Warmth din amestec de lână. Oferiți picioarelor dumneavoastră luxul pe care îl merită și pășiți într-o lume de confort care durează tot sezonul.',
                    'meta-description' => 'meta descriere',
                    'meta-keywords' => 'meta1, meta2, meta3',
                    'meta-title' => 'Titlu meta',
                    'name' => 'Șosete Arctic Warmth din amestec de lână',
                    'short-description' => 'Experimentați căldura și confortul incomparabile ale șosetelor noastre Arctic Warmth din amestec de lână. Realizate dintr-un amestec de lână Merino, acrilic, nailon și spandex, aceste șosete oferă confort maxim pentru vreme rece. Cu călcâi și vârf ranforsat pentru durabilitate, aceste șosete versatile și elegante sunt perfecte pentru diverse ocazii.',
                ],

                '5' => [
                    'description' => 'Vă prezentăm setul de accesorii de iarnă Arctic Frost, soluția ideală pentru a rămâne cald, elegant și conectat în zilele reci de iarnă. Acest set atent curatoriat reunește patru accesorii esențiale de iarnă pentru a crea un ansamblu armonios. Fularul luxos, țesut dintr-un amestec de acrilic și lână, nu doar adaugă un strat de căldură, ci aduce și o notă de eleganță garderobei dumneavoastră de iarnă. Căciula moale tricotată, realizată cu grijă, promite să vă mențină confortabil adăugând în același timp un fler la modă look-ului dumneavoastră. Dar nu se oprește aici – setul nostru include și mănuși compatibile cu ecranul tactil. Rămâneți conectat fără a sacrifica căldura pe măsură ce navigați pe dispozitivele dumneavoastră fără efort. Fie că răspundeți la apeluri, trimiteți mesaje sau capturați momente de iarnă pe smartphone, aceste mănuși asigură confort fără a compromite stilul. Textura moale și confortabilă a șosetelor oferă o senzație luxoasă pe piele. Spuneți la revedere picioarelor reci pe măsură ce îmbrățișați căldura plușată oferită de aceste șosete din amestec de lână. Setul de accesorii de iarnă Arctic Frost nu este doar despre funcționalitate; este o declarație de modă de iarnă. Fiecare piesă este concepută nu doar pentru a vă proteja de frig, ci și pentru a vă ridica stilul în sezonul rece. Materialele alese pentru acest set prioritizează atât durabilitatea, cât și confortul, asigurând că vă puteți bucura de minunățiile iernii cu stil. Fie că vă răsfățați pe dumneavoastră sau căutați cadoul perfect, setul de accesorii de iarnă Arctic Frost este o alegere versatilă. Oferiți bucurie cuiva special în sezonul sărbătorilor sau ridicați-vă propria garderobă de iarnă cu acest ansamblu elegant și funcțional. Îmbrățișați gerul cu încredere, știind că aveți accesoriile perfecte pentru a vă menține cald și șic.',
                    'meta-description' => 'meta descriere',
                    'meta-keywords' => 'meta1, meta2, meta3',
                    'meta-title' => 'Titlu meta',
                    'name' => 'Accesorii de iarnă Arctic Frost',
                    'short-description' => 'Îmbrățișați frigul iernii cu setul nostru de accesorii de iarnă Arctic Frost. Acest set curatoriat include un fular luxos, o căciulă confortabilă, mănuși compatibile cu ecranul tactil și șosete din amestec de lână. Elegant și funcțional, acest ansamblu este realizat din materiale de înaltă calitate, asigurând atât durabilitate, cât și confort. Ridicați-vă garderoba de iarnă sau oferiți bucurie cuiva special cu această opțiune perfectă de cadou.',
                ],

                '6' => [
                    'description' => 'Vă prezentăm setul de accesorii de iarnă Arctic Frost, soluția ideală pentru a rămâne cald, elegant și conectat în zilele reci de iarnă. Acest set atent curatoriat reunește patru accesorii esențiale de iarnă pentru a crea un ansamblu armonios. Fularul luxos, țesut dintr-un amestec de acrilic și lână, nu doar adaugă un strat de căldură, ci aduce și o notă de eleganță garderobei dumneavoastră de iarnă. Căciula moale tricotată, realizată cu grijă, promite să vă mențină confortabil adăugând în același timp un fler la modă look-ului dumneavoastră. Dar nu se oprește aici – setul nostru include și mănuși compatibile cu ecranul tactil. Rămâneți conectat fără a sacrifica căldura pe măsură ce navigați pe dispozitivele dumneavoastră fără efort. Fie că răspundeți la apeluri, trimiteți mesaje sau capturați momente de iarnă pe smartphone, aceste mănuși asigură confort fără a compromite stilul. Textura moale și confortabilă a șosetelor oferă o senzație luxoasă pe piele. Spuneți la revedere picioarelor reci pe măsură ce îmbrățișați căldura plușată oferită de aceste șosete din amestec de lână. Setul de accesorii de iarnă Arctic Frost nu este doar despre funcționalitate; este o declarație de modă de iarnă. Fiecare piesă este concepută nu doar pentru a vă proteja de frig, ci și pentru a vă ridica stilul în sezonul rece. Materialele alese pentru acest set prioritizează atât durabilitatea, cât și confortul, asigurând că vă puteți bucura de minunățiile iernii cu stil. Fie că vă răsfățați pe dumneavoastră sau căutați cadoul perfect, setul de accesorii de iarnă Arctic Frost este o alegere versatilă. Oferiți bucurie cuiva special în sezonul sărbătorilor sau ridicați-vă propria garderobă de iarnă cu acest ansamblu elegant și funcțional. Îmbrățișați gerul cu încredere, știind că aveți accesoriile perfecte pentru a vă menține cald și șic.',
                    'meta-description' => 'meta descriere',
                    'meta-keywords' => 'meta1, meta2, meta3',
                    'meta-title' => 'Titlu meta',
                    'name' => 'Set de accesorii de iarnă Arctic Frost',
                    'short-description' => 'Îmbrățișați frigul iernii cu setul nostru de accesorii de iarnă Arctic Frost. Acest set curatoriat include un fular luxos, o căciulă confortabilă, mănuși compatibile cu ecranul tactil și șosete din amestec de lână. Elegant și funcțional, acest ansamblu este realizat din materiale de înaltă calitate, asigurând atât durabilitate, cât și confort. Ridicați-vă garderoba de iarnă sau oferiți bucurie cuiva special cu această opțiune perfectă de cadou.',
                ],

                '7' => [
                    'description' => 'Vă prezentăm geaca OmniHeat pentru bărbați cu glugă și umplutură, soluția ideală pentru a rămâne cald și la modă în sezoanele mai reci. Această geacă este realizată ținând cont de durabilitate și căldură, asigurând că devine companionul dumneavoastră de încredere. Designul cu glugă nu doar adaugă o notă de stil, ci oferă și căldură suplimentară, protejându-vă de vânturile reci și de intemperii. Mânecile lungi oferă acoperire completă, asigurând că rămâneți confortabil de la umăr la încheietură. Echipată cu buzunare interioare, această geacă oferă confort pentru transportul obiectelor esențiale sau pentru menținerea mâinilor calde. Umplutura sintetică izolantă oferă căldură sporită, făcând-o ideală pentru a înfrunta zilele și nopțile reci. Realizată dintr-un exterior și căptușeală durabile din poliester, această geacă este construită să reziste în timp și să înfrunte elementele. Disponibilă în 5 culori atractive, puteți alege cea care se potrivește stilului și preferinței dumneavoastră. Versatilă și funcțională, geaca OmniHeat pentru bărbați cu glugă și umplutură este potrivită pentru diverse ocazii, fie că mergeți la muncă, la o ieșire casual sau la un eveniment în aer liber. Experimentați combinația perfectă de stil, confort și funcționalitate cu geaca OmniHeat pentru bărbați. Ridicați-vă garderoba de iarnă și rămâneți confortabil în timp ce vă bucurați de aer liber. Învingeți frigul cu stil și faceți o declarație cu această piesă esențială.',
                    'meta-description' => 'meta descriere',
                    'meta-keywords' => 'meta1, meta2, meta3',
                    'meta-title' => 'Titlu meta',
                    'name' => 'Geacă OmniHeat pentru bărbați cu glugă și umplutură',
                    'short-description' => 'Rămâneți cald și elegant cu geaca noastră OmniHeat pentru bărbați cu glugă și umplutură. Această geacă este concepută să ofere căldură maximă și are buzunare interioare pentru confort suplimentar. Materialul izolant vă asigură confortul pe vreme rece. Disponibilă în 5 culori atractive, făcând-o o alegere versatilă pentru diverse ocazii.',
                ],

                '8' => [
                    'description' => 'Vă prezentăm geaca OmniHeat pentru bărbați cu glugă și umplutură, soluția ideală pentru a rămâne cald și la modă în sezoanele mai reci. Această geacă este realizată ținând cont de durabilitate și căldură, asigurând că devine companionul dumneavoastră de încredere. Designul cu glugă nu doar adaugă o notă de stil, ci oferă și căldură suplimentară, protejându-vă de vânturile reci și de intemperii. Mânecile lungi oferă acoperire completă, asigurând că rămâneți confortabil de la umăr la încheietură. Echipată cu buzunare interioare, această geacă oferă confort pentru transportul obiectelor esențiale sau pentru menținerea mâinilor calde. Umplutura sintetică izolantă oferă căldură sporită, făcând-o ideală pentru a înfrunta zilele și nopțile reci. Realizată dintr-un exterior și căptușeală durabile din poliester, această geacă este construită să reziste în timp și să înfrunte elementele. Disponibilă în 5 culori atractive, puteți alege cea care se potrivește stilului și preferinței dumneavoastră. Versatilă și funcțională, geaca OmniHeat pentru bărbați cu glugă și umplutură este potrivită pentru diverse ocazii, fie că mergeți la muncă, la o ieșire casual sau la un eveniment în aer liber. Experimentați combinația perfectă de stil, confort și funcționalitate cu geaca OmniHeat pentru bărbați. Ridicați-vă garderoba de iarnă și rămâneți confortabil în timp ce vă bucurați de aer liber. Învingeți frigul cu stil și faceți o declarație cu această piesă esențială.',
                    'meta-description' => 'meta descriere',
                    'meta-keywords' => 'meta1, meta2, meta3',
                    'meta-title' => 'Titlu meta',
                    'name' => 'Geacă OmniHeat pentru bărbați cu glugă și umplutură-Albastru-Galben-M',
                    'short-description' => 'Rămâneți cald și elegant cu geaca noastră OmniHeat pentru bărbați cu glugă și umplutură. Această geacă este concepută să ofere căldură maximă și are buzunare interioare pentru confort suplimentar. Materialul izolant vă asigură confortul pe vreme rece. Disponibilă în 5 culori atractive, făcând-o o alegere versatilă pentru diverse ocazii.',
                ],

                '9' => [
                    'description' => 'Vă prezentăm geaca OmniHeat pentru bărbați cu glugă și umplutură, soluția ideală pentru a rămâne cald și la modă în sezoanele mai reci. Această geacă este realizată ținând cont de durabilitate și căldură, asigurând că devine companionul dumneavoastră de încredere. Designul cu glugă nu doar adaugă o notă de stil, ci oferă și căldură suplimentară, protejându-vă de vânturile reci și de intemperii. Mânecile lungi oferă acoperire completă, asigurând că rămâneți confortabil de la umăr la încheietură. Echipată cu buzunare interioare, această geacă oferă confort pentru transportul obiectelor esențiale sau pentru menținerea mâinilor calde. Umplutura sintetică izolantă oferă căldură sporită, făcând-o ideală pentru a înfrunta zilele și nopțile reci. Realizată dintr-un exterior și căptușeală durabile din poliester, această geacă este construită să reziste în timp și să înfrunte elementele. Disponibilă în 5 culori atractive, puteți alege cea care se potrivește stilului și preferinței dumneavoastră. Versatilă și funcțională, geaca OmniHeat pentru bărbați cu glugă și umplutură este potrivită pentru diverse ocazii, fie că mergeți la muncă, la o ieșire casual sau la un eveniment în aer liber. Experimentați combinația perfectă de stil, confort și funcționalitate cu geaca OmniHeat pentru bărbați. Ridicați-vă garderoba de iarnă și rămâneți confortabil în timp ce vă bucurați de aer liber. Învingeți frigul cu stil și faceți o declarație cu această piesă esențială.',
                    'meta-description' => 'meta descriere',
                    'meta-keywords' => 'meta1, meta2, meta3',
                    'meta-title' => 'Titlu meta',
                    'name' => 'Geacă OmniHeat pentru bărbați cu glugă și umplutură-Albastru-Galben-L',
                    'short-description' => 'Rămâneți cald și elegant cu geaca noastră OmniHeat pentru bărbați cu glugă și umplutură. Această geacă este concepută să ofere căldură maximă și are buzunare interioare pentru confort suplimentar. Materialul izolant vă asigură confortul pe vreme rece. Disponibilă în 5 culori atractive, făcând-o o alegere versatilă pentru diverse ocazii.',
                ],

                '10' => [
                    'description' => 'Vă prezentăm geaca OmniHeat pentru bărbați cu glugă și umplutură, soluția ideală pentru a rămâne cald și la modă în sezoanele mai reci. Această geacă este realizată ținând cont de durabilitate și căldură, asigurând că devine companionul dumneavoastră de încredere. Designul cu glugă nu doar adaugă o notă de stil, ci oferă și căldură suplimentară, protejându-vă de vânturile reci și de intemperii. Mânecile lungi oferă acoperire completă, asigurând că rămâneți confortabil de la umăr la încheietură. Echipată cu buzunare interioare, această geacă oferă confort pentru transportul obiectelor esențiale sau pentru menținerea mâinilor calde. Umplutura sintetică izolantă oferă căldură sporită, făcând-o ideală pentru a înfrunta zilele și nopțile reci. Realizată dintr-un exterior și căptușeală durabile din poliester, această geacă este construită să reziste în timp și să înfrunte elementele. Disponibilă în 5 culori atractive, puteți alege cea care se potrivește stilului și preferinței dumneavoastră. Versatilă și funcțională, geaca OmniHeat pentru bărbați cu glugă și umplutură este potrivită pentru diverse ocazii, fie că mergeți la muncă, la o ieșire casual sau la un eveniment în aer liber. Experimentați combinația perfectă de stil, confort și funcționalitate cu geaca OmniHeat pentru bărbați. Ridicați-vă garderoba de iarnă și rămâneți confortabil în timp ce vă bucurați de aer liber. Învingeți frigul cu stil și faceți o declarație cu această piesă esențială.',
                    'meta-description' => 'meta descriere',
                    'meta-keywords' => 'meta1, meta2, meta3',
                    'meta-title' => 'Titlu meta',
                    'name' => 'Geacă OmniHeat pentru bărbați cu glugă și umplutură-Albastru-Verde-M',
                    'short-description' => 'Rămâneți cald și elegant cu geaca noastră OmniHeat pentru bărbați cu glugă și umplutură. Această geacă este concepută să ofere căldură maximă și are buzunare interioare pentru confort suplimentar. Materialul izolant vă asigură confortul pe vreme rece. Disponibilă în 5 culori atractive, făcând-o o alegere versatilă pentru diverse ocazii.',
                ],

                '11' => [
                    'description' => 'Vă prezentăm geaca OmniHeat pentru bărbați cu glugă și umplutură, soluția ideală pentru a rămâne cald și la modă în sezoanele mai reci. Această geacă este realizată ținând cont de durabilitate și căldură, asigurând că devine companionul dumneavoastră de încredere. Designul cu glugă nu doar adaugă o notă de stil, ci oferă și căldură suplimentară, protejându-vă de vânturile reci și de intemperii. Mânecile lungi oferă acoperire completă, asigurând că rămâneți confortabil de la umăr la încheietură. Echipată cu buzunare interioare, această geacă oferă confort pentru transportul obiectelor esențiale sau pentru menținerea mâinilor calde. Umplutura sintetică izolantă oferă căldură sporită, făcând-o ideală pentru a înfrunta zilele și nopțile reci. Realizată dintr-un exterior și căptușeală durabile din poliester, această geacă este construită să reziste în timp și să înfrunte elementele. Disponibilă în 5 culori atractive, puteți alege cea care se potrivește stilului și preferinței dumneavoastră. Versatilă și funcțională, geaca OmniHeat pentru bărbați cu glugă și umplutură este potrivită pentru diverse ocazii, fie că mergeți la muncă, la o ieșire casual sau la un eveniment în aer liber. Experimentați combinația perfectă de stil, confort și funcționalitate cu geaca OmniHeat pentru bărbați. Ridicați-vă garderoba de iarnă și rămâneți confortabil în timp ce vă bucurați de aer liber. Învingeți frigul cu stil și faceți o declarație cu această piesă esențială.',
                    'meta-description' => 'meta descriere',
                    'meta-keywords' => 'meta1, meta2, meta3',
                    'meta-title' => 'Titlu meta',
                    'name' => 'Geacă OmniHeat pentru bărbați cu glugă și umplutură-Albastru-Verde-L',
                    'short-description' => 'Rămâneți cald și elegant cu geaca noastră OmniHeat pentru bărbați cu glugă și umplutură. Această geacă este concepută să ofere căldură maximă și are buzunare interioare pentru confort suplimentar. Materialul izolant vă asigură confortul pe vreme rece. Disponibilă în 5 culori atractive, făcând-o o alegere versatilă pentru diverse ocazii.',
                ],
            ],

            'product-attribute-values' => [
                '1' => [
                    'description' => 'Căciula Arctic Cozy Knit este soluția ideală pentru a rămâne cald, confortabil și elegant în lunile mai reci. Realizată dintr-un amestec moale și durabil de tricot acrilic, această căciulă este concepută să ofere o potrivire confortabilă și plăcută. Designul clasic o face potrivită atât pentru bărbați, cât și pentru femei, oferind un accesoriu versatil care completează diverse stiluri. Fie că ieșiți într-o zi obișnuită în oraș sau vă bucurați de natură, această căciulă adaugă o notă de confort și căldură ținutei dumneavoastră. Materialul moale și respirabil vă asigură confortul fără a sacrifica stilul. Căciula Arctic Cozy Knit nu este doar un accesoriu; este o declarație de modă de iarnă. Simplitatea sa o face ușor de asortat cu diferite ținute, făcând-o un element de bază în garderoba de iarnă. Ideală pentru cadouri sau ca răsfăț personal, această căciulă este o adăugire plină de grijă la orice ținută de iarnă. Este un accesoriu versatil care depășește funcționalitatea, adăugând o notă de căldură și stil look-ului dumneavoastră. Îmbrățișați esența iernii cu căciula Arctic Cozy Knit. Fie că vă bucurați de o zi relaxată sau înfruntați elementele, lăsați această căciulă să fie compania dumneavoastră pentru confort și stil. Ridicați-vă garderoba de iarnă cu acest accesoriu clasic care combină fără efort căldura cu un simț etern al modei.',
                    'meta-description' => 'meta descriere',
                    'meta-keywords' => 'meta1, meta2, meta3',
                    'meta-title' => 'Titlu meta',
                    'name' => 'Căciulă unisex Arctic Cozy Knit',
                    'sort-description' => 'Îmbrățișați zilele reci cu stil cu căciula noastră Arctic Cozy Knit. Realizată dintr-un amestec moale și durabil de acrilic, această căciulă clasică oferă căldură și versatilitate. Potrivită atât pentru bărbați, cât și pentru femei, este accesoriul ideal pentru purtare casual sau în aer liber. Ridicați-vă garderoba de iarnă sau oferiți un cadou special cu această căciulă esențială.',
                ],

                '2' => [
                    'description' => 'Fular de iarnă Arctic Bliss este mai mult decât un simplu accesoriu pentru vreme rece; este o declarație de căldură, confort și stil pentru sezonul de iarnă. Realizat cu grijă dintr-un amestec luxos de acrilic și lână, acest fular este conceput să vă mențină cald și confortabil chiar și la cele mai scăzute temperaturi. Textura moale și plușată nu doar oferă izolație împotriva frigului, ci adaugă și o notă de lux garderobei dumneavoastră de iarnă. Designul fularului Arctic Bliss este atât elegant, cât și versatil, făcându-l un complement perfect pentru o varietate de ținute de iarnă. Fie că vă îmbrăcați pentru o ocazie specială sau adăugați un strat șic la look-ul de zi cu zi, acest fular vă completează stilul fără efort. Lungimea extra-lungă a fularului oferă opțiuni de stilizare personalizabile. Înfășurați-l pentru căldură suplimentară, drapați-l lejer pentru un look casual sau experimentați cu diferite noduri pentru a vă exprima stilul unic. Această versatilitate îl face un accesoriu indispensabil pentru sezonul de iarnă. Căutați cadoul perfect? Fularul de iarnă Arctic Bliss este o alegere ideală. Fie că surprindeți o persoană dragă sau vă răsfățați pe dumneavoastră, acest fular este un cadou atemporal și practic ce va fi prețuit pe parcursul lunilor de iarnă. Îmbrățișați iarna cu fularul Arctic Bliss, unde căldura întâlnește stilul în armonie perfectă. Ridicați-vă garderoba de iarnă cu acest accesoriu esențial care nu doar vă menține cald, ci adaugă și o notă de sofisticare ținutei de vreme rece.',
                    'meta-description' => 'meta descriere',
                    'meta-keywords' => 'meta1, meta2, meta3',
                    'meta-title' => 'Titlu meta',
                    'name' => 'Fular elegant de iarnă Arctic Bliss',
                    'sort-description' => 'Experimentați îmbrățișarea căldurii și stilului cu fularul nostru de iarnă Arctic Bliss. Realizat dintr-un amestec luxos de acrilic și lână, acest fular confortabil este conceput să vă mențină cald în cele mai reci zile. Designul său elegant și versatil, combinat cu o lungime extra-lungă, oferă opțiuni de stilizare personalizabile. Ridicați-vă garderoba de iarnă sau oferiți bucurie cuiva special cu acest accesoriu esențial de iarnă.',
                ],

                '3' => [
                    'description' => 'Vă prezentăm mănușile de iarnă Arctic Touchscreen – unde căldura, stilul și conectivitatea se întâlnesc pentru a vă îmbunătăți experiența de iarnă. Realizate din acrilic de înaltă calitate, aceste mănuși sunt concepute să ofere căldură și durabilitate excepționale. Vârfurile degetelor compatibile cu ecranul tactil vă permit să rămâneți conectat fără a vă expune mâinile la frig. Răspundeți la apeluri, trimiteți mesaje și navigați pe dispozitivele dumneavoastră fără efort, menținându-vă în același timp mâinile calde. Căptușeala izolantă adaugă un strat suplimentar de confort, făcând aceste mănuși alegerea ideală pentru a înfrunta frigul iernii. Fie că faceți naveta, rezolvați comisioane sau vă bucurați de activități în aer liber, aceste mănuși oferă căldura și protecția de care aveți nevoie. Manșetele elastice asigură o potrivire sigură, prevenind curentele reci și menținând mănușile la loc în timpul activităților zilnice. Designul elegant adaugă o notă de fler ținutei dumneavoastră de iarnă, făcând aceste mănuși la fel de la modă pe cât sunt de funcționale. Ideale pentru cadouri sau ca răsfăț personal, mănușile de iarnă Arctic Touchscreen sunt un accesoriu indispensabil pentru individul modern. Spuneți la revedere inconvenienței de a vă scoate mănușile pentru a folosi dispozitivele și îmbrățișați combinația perfectă de căldură, stil și conectivitate. Rămâneți conectat, rămâneți cald și rămâneți elegant cu mănușile de iarnă Arctic Touchscreen – companionul dumneavoastră de încredere pentru a cuceri sezonul de iarnă cu încredere.',
                    'meta-description' => 'meta descriere',
                    'meta-keywords' => 'meta1, meta2, meta3',
                    'meta-title' => 'Titlu meta',
                    'name' => 'Mănuși de iarnă Arctic Touchscreen',
                    'sort-description' => 'Rămâneți conectat și cald cu mănușile noastre de iarnă Arctic Touchscreen. Aceste mănuși nu sunt doar realizate din acrilic de înaltă calitate pentru căldură și durabilitate, ci au și un design compatibil cu ecranul tactil. Cu căptușeală izolantă, manșete elastice pentru o potrivire sigură și un aspect elegant, aceste mănuși sunt perfecte pentru purtarea zilnică în condiții de frig.',
                ],

                '4' => [
                    'description' => 'Vă prezentăm șosetele Arctic Warmth din amestec de lână – companionul esențial pentru picioare confortabile și călduroase în sezoanele mai reci. Realizate dintr-un amestec premium de lână Merino, acrilic, nailon și spandex, aceste șosete sunt concepute să ofere căldură și confort incomparabile. Amestecul de lână asigură că picioarele dumneavoastră rămân calde chiar și la cele mai scăzute temperaturi, făcând aceste șosete alegerea perfectă pentru aventurile de iarnă sau pur și simplu pentru a rămâne confortabil acasă. Textura moale și confortabilă a șosetelor oferă o senzație luxoasă pe piele. Spuneți la revedere picioarelor reci pe măsură ce îmbrățișați căldura plușată oferită de aceste șosete din amestec de lână. Concepute pentru durabilitate, șosetele au călcâi și vârf ranforsat, adăugând rezistență suplimentară zonelor cu uzură mare. Acest lucru asigură că șosetele dumneavoastră rezistă testului timpului, oferind confort și căldură de lungă durată. Natura respirabilă a materialului previne supraîncălzirea, permițând picioarelor dumneavoastră să rămână confortabile și uscate pe parcursul zilei. Fie că ieșiți afară pentru o drumeție de iarnă sau vă relaxați în interior, aceste șosete oferă echilibrul perfect între căldură și respirabilitate. Versatile și elegante, aceste șosete din amestec de lână sunt potrivite pentru diverse ocazii. Asortați-le cu cizmele preferate pentru un look de iarnă la modă sau purtați-le în casă pentru confort maxim. Ridicați-vă garderoba de iarnă și prioritizați confortul cu șosetele Arctic Warmth din amestec de lână. Oferiți picioarelor dumneavoastră luxul pe care îl merită și pășiți într-o lume de confort care durează tot sezonul.',
                    'meta-description' => 'meta descriere',
                    'meta-keywords' => 'meta1, meta2, meta3',
                    'meta-title' => 'Titlu meta',
                    'name' => 'Șosete Arctic Warmth din amestec de lână',
                    'sort-description' => 'Experimentați căldura și confortul incomparabile ale șosetelor noastre Arctic Warmth din amestec de lână. Realizate dintr-un amestec de lână Merino, acrilic, nailon și spandex, aceste șosete oferă confort maxim pentru vreme rece. Cu călcâi și vârf ranforsat pentru durabilitate, aceste șosete versatile și elegante sunt perfecte pentru diverse ocazii.',
                ],

                '5' => [
                    'description' => 'Vă prezentăm setul de accesorii de iarnă Arctic Frost, soluția ideală pentru a rămâne cald, elegant și conectat în zilele reci de iarnă. Acest set atent curatoriat reunește patru accesorii esențiale de iarnă pentru a crea un ansamblu armonios. Fularul luxos, țesut dintr-un amestec de acrilic și lână, nu doar adaugă un strat de căldură, ci aduce și o notă de eleganță garderobei dumneavoastră de iarnă. Căciula moale tricotată, realizată cu grijă, promite să vă mențină confortabil adăugând în același timp un fler la modă look-ului dumneavoastră. Dar nu se oprește aici – setul nostru include și mănuși compatibile cu ecranul tactil. Rămâneți conectat fără a sacrifica căldura pe măsură ce navigați pe dispozitivele dumneavoastră fără efort. Fie că răspundeți la apeluri, trimiteți mesaje sau capturați momente de iarnă pe smartphone, aceste mănuși asigură confort fără a compromite stilul. Textura moale și confortabilă a șosetelor oferă o senzație luxoasă pe piele. Spuneți la revedere picioarelor reci pe măsură ce îmbrățișați căldura plușată oferită de aceste șosete din amestec de lână. Setul de accesorii de iarnă Arctic Frost nu este doar despre funcționalitate; este o declarație de modă de iarnă. Fiecare piesă este concepută nu doar pentru a vă proteja de frig, ci și pentru a vă ridica stilul în sezonul rece. Materialele alese pentru acest set prioritizează atât durabilitatea, cât și confortul, asigurând că vă puteți bucura de minunățiile iernii cu stil. Fie că vă răsfățați pe dumneavoastră sau căutați cadoul perfect, setul de accesorii de iarnă Arctic Frost este o alegere versatilă. Oferiți bucurie cuiva special în sezonul sărbătorilor sau ridicați-vă propria garderobă de iarnă cu acest ansamblu elegant și funcțional. Îmbrățișați gerul cu încredere, știind că aveți accesoriile perfecte pentru a vă menține cald și șic.',
                    'meta-description' => 'meta descriere',
                    'meta-keywords' => 'meta1, meta2, meta3',
                    'meta-title' => 'Titlu meta',
                    'name' => 'Accesorii de iarnă Arctic Frost',
                    'sort-description' => 'Îmbrățișați frigul iernii cu setul nostru de accesorii de iarnă Arctic Frost. Acest set curatoriat include un fular luxos, o căciulă confortabilă, mănuși compatibile cu ecranul tactil și șosete din amestec de lână. Elegant și funcțional, acest ansamblu este realizat din materiale de înaltă calitate, asigurând atât durabilitate, cât și confort. Ridicați-vă garderoba de iarnă sau oferiți bucurie cuiva special cu această opțiune perfectă de cadou.',
                ],

                '6' => [
                    'description' => 'Vă prezentăm setul de accesorii de iarnă Arctic Frost, soluția ideală pentru a rămâne cald, elegant și conectat în zilele reci de iarnă. Acest set atent curatoriat reunește patru accesorii esențiale de iarnă pentru a crea un ansamblu armonios. Fularul luxos, țesut dintr-un amestec de acrilic și lână, nu doar adaugă un strat de căldură, ci aduce și o notă de eleganță garderobei dumneavoastră de iarnă. Căciula moale tricotată, realizată cu grijă, promite să vă mențină confortabil adăugând în același timp un fler la modă look-ului dumneavoastră. Dar nu se oprește aici – setul nostru include și mănuși compatibile cu ecranul tactil. Rămâneți conectat fără a sacrifica căldura pe măsură ce navigați pe dispozitivele dumneavoastră fără efort. Fie că răspundeți la apeluri, trimiteți mesaje sau capturați momente de iarnă pe smartphone, aceste mănuși asigură confort fără a compromite stilul. Textura moale și confortabilă a șosetelor oferă o senzație luxoasă pe piele. Spuneți la revedere picioarelor reci pe măsură ce îmbrățișați căldura plușată oferită de aceste șosete din amestec de lână. Setul de accesorii de iarnă Arctic Frost nu este doar despre funcționalitate; este o declarație de modă de iarnă. Fiecare piesă este concepută nu doar pentru a vă proteja de frig, ci și pentru a vă ridica stilul în sezonul rece. Materialele alese pentru acest set prioritizează atât durabilitatea, cât și confortul, asigurând că vă puteți bucura de minunățiile iernii cu stil. Fie că vă răsfățați pe dumneavoastră sau căutați cadoul perfect, setul de accesorii de iarnă Arctic Frost este o alegere versatilă. Oferiți bucurie cuiva special în sezonul sărbătorilor sau ridicați-vă propria garderobă de iarnă cu acest ansamblu elegant și funcțional. Îmbrățișați gerul cu încredere, știind că aveți accesoriile perfecte pentru a vă menține cald și șic.',
                    'meta-description' => 'meta descriere',
                    'meta-keywords' => 'meta1, meta2, meta3',
                    'meta-title' => 'Titlu meta',
                    'name' => 'Set de accesorii de iarnă Arctic Frost',
                    'sort-description' => 'Îmbrățișați frigul iernii cu setul nostru de accesorii de iarnă Arctic Frost. Acest set curatoriat include un fular luxos, o căciulă confortabilă, mănuși compatibile cu ecranul tactil și șosete din amestec de lână. Elegant și funcțional, acest ansamblu este realizat din materiale de înaltă calitate, asigurând atât durabilitate, cât și confort. Ridicați-vă garderoba de iarnă sau oferiți bucurie cuiva special cu această opțiune perfectă de cadou.',
                ],

                '7' => [
                    'description' => 'Vă prezentăm geaca OmniHeat pentru bărbați cu glugă și umplutură, soluția ideală pentru a rămâne cald și la modă în sezoanele mai reci. Această geacă este realizată ținând cont de durabilitate și căldură, asigurând că devine companionul dumneavoastră de încredere. Designul cu glugă nu doar adaugă o notă de stil, ci oferă și căldură suplimentară, protejându-vă de vânturile reci și de intemperii. Mânecile lungi oferă acoperire completă, asigurând că rămâneți confortabil de la umăr la încheietură. Echipată cu buzunare interioare, această geacă oferă confort pentru transportul obiectelor esențiale sau pentru menținerea mâinilor calde. Umplutura sintetică izolantă oferă căldură sporită, făcând-o ideală pentru a înfrunta zilele și nopțile reci. Realizată dintr-un exterior și căptușeală durabile din poliester, această geacă este construită să reziste în timp și să înfrunte elementele. Disponibilă în 5 culori atractive, puteți alege cea care se potrivește stilului și preferinței dumneavoastră. Versatilă și funcțională, geaca OmniHeat pentru bărbați cu glugă și umplutură este potrivită pentru diverse ocazii, fie că mergeți la muncă, la o ieșire casual sau la un eveniment în aer liber. Experimentați combinația perfectă de stil, confort și funcționalitate cu geaca OmniHeat pentru bărbați. Ridicați-vă garderoba de iarnă și rămâneți confortabil în timp ce vă bucurați de aer liber. Învingeți frigul cu stil și faceți o declarație cu această piesă esențială.',
                    'meta-description' => 'meta descriere',
                    'meta-keywords' => 'meta1, meta2, meta3',
                    'meta-title' => 'Titlu meta',
                    'name' => 'Geacă OmniHeat pentru bărbați cu glugă și umplutură',
                    'sort-description' => 'Rămâneți cald și elegant cu geaca noastră OmniHeat pentru bărbați cu glugă și umplutură. Această geacă este concepută să ofere căldură maximă și are buzunare interioare pentru confort suplimentar. Materialul izolant vă asigură confortul pe vreme rece. Disponibilă în 5 culori atractive, făcând-o o alegere versatilă pentru diverse ocazii.',
                ],

                '8' => [
                    'description' => 'Vă prezentăm geaca OmniHeat pentru bărbați cu glugă și umplutură, soluția ideală pentru a rămâne cald și la modă în sezoanele mai reci. Această geacă este realizată ținând cont de durabilitate și căldură, asigurând că devine companionul dumneavoastră de încredere. Designul cu glugă nu doar adaugă o notă de stil, ci oferă și căldură suplimentară, protejându-vă de vânturile reci și de intemperii. Mânecile lungi oferă acoperire completă, asigurând că rămâneți confortabil de la umăr la încheietură. Echipată cu buzunare interioare, această geacă oferă confort pentru transportul obiectelor esențiale sau pentru menținerea mâinilor calde. Umplutura sintetică izolantă oferă căldură sporită, făcând-o ideală pentru a înfrunta zilele și nopțile reci. Realizată dintr-un exterior și căptușeală durabile din poliester, această geacă este construită să reziste în timp și să înfrunte elementele. Disponibilă în 5 culori atractive, puteți alege cea care se potrivește stilului și preferinței dumneavoastră. Versatilă și funcțională, geaca OmniHeat pentru bărbați cu glugă și umplutură este potrivită pentru diverse ocazii, fie că mergeți la muncă, la o ieșire casual sau la un eveniment în aer liber. Experimentați combinația perfectă de stil, confort și funcționalitate cu geaca OmniHeat pentru bărbați. Ridicați-vă garderoba de iarnă și rămâneți confortabil în timp ce vă bucurați de aer liber. Învingeți frigul cu stil și faceți o declarație cu această piesă esențială.',
                    'meta-description' => 'meta descriere',
                    'meta-keywords' => 'meta1, meta2, meta3',
                    'meta-title' => 'Titlu meta',
                    'name' => 'Geacă OmniHeat pentru bărbați cu glugă și umplutură-Albastru-Galben-M',
                    'sort-description' => 'Rămâneți cald și elegant cu geaca noastră OmniHeat pentru bărbați cu glugă și umplutură. Această geacă este concepută să ofere căldură maximă și are buzunare interioare pentru confort suplimentar. Materialul izolant vă asigură confortul pe vreme rece. Disponibilă în 5 culori atractive, făcând-o o alegere versatilă pentru diverse ocazii.',
                ],

                '9' => [
                    'description' => 'Vă prezentăm geaca OmniHeat pentru bărbați cu glugă și umplutură, soluția ideală pentru a rămâne cald și la modă în sezoanele mai reci. Această geacă este realizată ținând cont de durabilitate și căldură, asigurând că devine companionul dumneavoastră de încredere. Designul cu glugă nu doar adaugă o notă de stil, ci oferă și căldură suplimentară, protejându-vă de vânturile reci și de intemperii. Mânecile lungi oferă acoperire completă, asigurând că rămâneți confortabil de la umăr la încheietură. Echipată cu buzunare interioare, această geacă oferă confort pentru transportul obiectelor esențiale sau pentru menținerea mâinilor calde. Umplutura sintetică izolantă oferă căldură sporită, făcând-o ideală pentru a înfrunta zilele și nopțile reci. Realizată dintr-un exterior și căptușeală durabile din poliester, această geacă este construită să reziste în timp și să înfrunte elementele. Disponibilă în 5 culori atractive, puteți alege cea care se potrivește stilului și preferinței dumneavoastră. Versatilă și funcțională, geaca OmniHeat pentru bărbați cu glugă și umplutură este potrivită pentru diverse ocazii, fie că mergeți la muncă, la o ieșire casual sau la un eveniment în aer liber. Experimentați combinația perfectă de stil, confort și funcționalitate cu geaca OmniHeat pentru bărbați. Ridicați-vă garderoba de iarnă și rămâneți confortabil în timp ce vă bucurați de aer liber. Învingeți frigul cu stil și faceți o declarație cu această piesă esențială.',
                    'meta-description' => 'meta descriere',
                    'meta-keywords' => 'meta1, meta2, meta3',
                    'meta-title' => 'Titlu meta',
                    'name' => 'Geacă OmniHeat pentru bărbați cu glugă și umplutură-Albastru-Galben-L',
                    'sort-description' => 'Rămâneți cald și elegant cu geaca noastră OmniHeat pentru bărbați cu glugă și umplutură. Această geacă este concepută să ofere căldură maximă și are buzunare interioare pentru confort suplimentar. Materialul izolant vă asigură confortul pe vreme rece. Disponibilă în 5 culori atractive, făcând-o o alegere versatilă pentru diverse ocazii.',
                ],

                '10' => [
                    'description' => 'Vă prezentăm geaca OmniHeat pentru bărbați cu glugă și umplutură, soluția ideală pentru a rămâne cald și la modă în sezoanele mai reci. Această geacă este realizată ținând cont de durabilitate și căldură, asigurând că devine companionul dumneavoastră de încredere. Designul cu glugă nu doar adaugă o notă de stil, ci oferă și căldură suplimentară, protejându-vă de vânturile reci și de intemperii. Mânecile lungi oferă acoperire completă, asigurând că rămâneți confortabil de la umăr la încheietură. Echipată cu buzunare interioare, această geacă oferă confort pentru transportul obiectelor esențiale sau pentru menținerea mâinilor calde. Umplutura sintetică izolantă oferă căldură sporită, făcând-o ideală pentru a înfrunta zilele și nopțile reci. Realizată dintr-un exterior și căptușeală durabile din poliester, această geacă este construită să reziste în timp și să înfrunte elementele. Disponibilă în 5 culori atractive, puteți alege cea care se potrivește stilului și preferinței dumneavoastră. Versatilă și funcțională, geaca OmniHeat pentru bărbați cu glugă și umplutură este potrivită pentru diverse ocazii, fie că mergeți la muncă, la o ieșire casual sau la un eveniment în aer liber. Experimentați combinația perfectă de stil, confort și funcționalitate cu geaca OmniHeat pentru bărbați. Ridicați-vă garderoba de iarnă și rămâneți confortabil în timp ce vă bucurați de aer liber. Învingeți frigul cu stil și faceți o declarație cu această piesă esențială.',
                    'meta-description' => 'meta descriere',
                    'meta-keywords' => 'meta1, meta2, meta3',
                    'meta-title' => 'Titlu meta',
                    'name' => 'Geacă OmniHeat pentru bărbați cu glugă și umplutură-Albastru-Verde-M',
                    'sort-description' => 'Rămâneți cald și elegant cu geaca noastră OmniHeat pentru bărbați cu glugă și umplutură. Această geacă este concepută să ofere căldură maximă și are buzunare interioare pentru confort suplimentar. Materialul izolant vă asigură confortul pe vreme rece. Disponibilă în 5 culori atractive, făcând-o o alegere versatilă pentru diverse ocazii.',
                ],

                '11' => [
                    'description' => 'Vă prezentăm geaca OmniHeat pentru bărbați cu glugă și umplutură, soluția ideală pentru a rămâne cald și la modă în sezoanele mai reci. Această geacă este realizată ținând cont de durabilitate și căldură, asigurând că devine companionul dumneavoastră de încredere. Designul cu glugă nu doar adaugă o notă de stil, ci oferă și căldură suplimentară, protejându-vă de vânturile reci și de intemperii. Mânecile lungi oferă acoperire completă, asigurând că rămâneți confortabil de la umăr la încheietură. Echipată cu buzunare interioare, această geacă oferă confort pentru transportul obiectelor esențiale sau pentru menținerea mâinilor calde. Umplutura sintetică izolantă oferă căldură sporită, făcând-o ideală pentru a înfrunta zilele și nopțile reci. Realizată dintr-un exterior și căptușeală durabile din poliester, această geacă este construită să reziste în timp și să înfrunte elementele. Disponibilă în 5 culori atractive, puteți alege cea care se potrivește stilului și preferinței dumneavoastră. Versatilă și funcțională, geaca OmniHeat pentru bărbați cu glugă și umplutură este potrivită pentru diverse ocazii, fie că mergeți la muncă, la o ieșire casual sau la un eveniment în aer liber. Experimentați combinația perfectă de stil, confort și funcționalitate cu geaca OmniHeat pentru bărbați. Ridicați-vă garderoba de iarnă și rămâneți confortabil în timp ce vă bucurați de aer liber. Învingeți frigul cu stil și faceți o declarație cu această piesă esențială.',
                    'meta-description' => 'meta descriere',
                    'meta-keywords' => 'meta1, meta2, meta3',
                    'meta-title' => 'Titlu meta',
                    'name' => 'Geacă OmniHeat pentru bărbați cu glugă și umplutură-Albastru-Verde-L',
                    'sort-description' => 'Rămâneți cald și elegant cu geaca noastră OmniHeat pentru bărbați cu glugă și umplutură. Această geacă este concepută să ofere căldură maximă și are buzunare interioare pentru confort suplimentar. Materialul izolant vă asigură confortul pe vreme rece. Disponibilă în 5 culori atractive, făcând-o o alegere versatilă pentru diverse ocazii.',
                ],
            ],

            'product-bundle-option-translations' => [
                '1' => [
                    'label' => 'Opțiune pachet 1',
                ],

                '2' => [
                    'label' => 'Opțiune pachet 1',
                ],

                '3' => [
                    'label' => 'Opțiune pachet 2',
                ],

                '4' => [
                    'label' => 'Opțiune pachet 2',
                ],
            ],
        ],
    ],

    'installer' => [
        'middleware' => [
            'already-installed' => 'Aplicația este deja instalată.',
        ],

        'index' => [
            'create-administrator' => [
                'admin' => 'Administrator',
                'bagisto' => 'Bagisto',
                'confirm-password' => 'Confirmați parola',
                'email' => 'E-mail',
                'email-address' => 'admin@example.com',
                'password' => 'Parolă',
                'title' => 'Creare administrator',
            ],

            'environment-configuration' => [
                'algerian-dinar' => 'Dinar algerian (DZD)',
                'allowed-currencies' => 'Monede permise',
                'allowed-locales' => 'Limbi permise',
                'application-name' => 'Numele aplicației',
                'argentine-peso' => 'Peso argentinian (ARS)',
                'australian-dollar' => 'Dolar australian (AUD)',
                'bagisto' => 'Bagisto',
                'bangladeshi-taka' => 'Taka din Bangladesh (BDT)',
                'bahraini-dinar' => 'Dinar din Bahrain (BHD)',
                'brazilian-real' => 'Real brazilian (BRL)',
                'british-pound-sterling' => 'Liră sterlină britanică (GBP)',
                'canadian-dollar' => 'Dolar canadian (CAD)',
                'cfa-franc-bceao' => 'Franc CFA BCEAO (XOF)',
                'cfa-franc-beac' => 'Franc CFA BEAC (XAF)',
                'chilean-peso' => 'Peso chilian (CLP)',
                'chinese-yuan' => 'Yuan chinezesc (CNY)',
                'colombian-peso' => 'Peso columbian (COP)',
                'czech-koruna' => 'Coroană cehă (CZK)',
                'danish-krone' => 'Coroană daneză (DKK)',
                'database-connection' => 'Conexiune bază de date',
                'database-hostname' => 'Numele gazdei bazei de date',
                'database-name' => 'Numele bazei de date',
                'database-password' => 'Parola bazei de date',
                'database-port' => 'Portul bazei de date',
                'database-prefix' => 'Prefixul bazei de date',
                'database-prefix-help' => 'Prefixul trebuie să aibă 4 caractere și poate conține doar litere, cifre și underscore.',
                'database-username' => 'Numele de utilizator al bazei de date',
                'default-currency' => 'Moneda implicită',
                'default-locale' => 'Limba implicită',
                'default-timezone' => 'Fusul orar implicit',
                'default-url' => 'URL implicit',
                'default-url-link' => 'https://localhost',
                'egyptian-pound' => 'Liră egipteană (EGP)',
                'euro' => 'Euro (EUR)',
                'fijian-dollar' => 'Dolar fijian (FJD)',
                'hong-kong-dollar' => 'Dolar din Hong Kong (HKD)',
                'hungarian-forint' => 'Forint maghiar (HUF)',
                'indian-rupee' => 'Rupie indiană (INR)',
                'indonesian-rupiah' => 'Rupie indoneziană (IDR)',
                'israeli-new-shekel' => 'Shekel nou israelian (ILS)',
                'japanese-yen' => 'Yen japonez (JPY)',
                'jordanian-dinar' => 'Dinar iordanian (JOD)',
                'kazakhstani-tenge' => 'Tenge kazahă (KZT)',
                'kuwaiti-dinar' => 'Dinar kuweitian (KWD)',
                'lebanese-pound' => 'Liră libaneză (LBP)',
                'libyan-dinar' => 'Dinar libian (LYD)',
                'malaysian-ringgit' => 'Ringgit malaiezian (MYR)',
                'mauritian-rupee' => 'Rupie mauritiană (MUR)',
                'mexican-peso' => 'Peso mexican (MXN)',
                'moroccan-dirham' => 'Dirham marocan (MAD)',
                'mysql' => 'MySQL',
                'nepalese-rupee' => 'Rupie nepaleză (NPR)',
                'new-taiwan-dollar' => 'Dolar nou taiwanez (TWD)',
                'new-zealand-dollar' => 'Dolar neozeelandez (NZD)',
                'nigerian-naira' => 'Naira nigeriană (NGN)',
                'norwegian-krone' => 'Coroană norvegiană (NOK)',
                'omani-rial' => 'Rial omanez (OMR)',
                'pakistani-rupee' => 'Rupie pakistaneză (PKR)',
                'panamanian-balboa' => 'Balboa panamez (PAB)',
                'paraguayan-guarani' => 'Guarani paraguayan (PYG)',
                'peruvian-nuevo-sol' => 'Sol nou peruan (PEN)',
                'pgsql' => 'pgSQL',
                'philippine-peso' => 'Peso filipinez (PHP)',
                'polish-zloty' => 'Zlot polonez (PLN)',
                'qatari-rial' => 'Rial qatarian (QAR)',
                'romanian-leu' => 'Leu românesc (RON)',
                'russian-ruble' => 'Rublă rusească (RUB)',
                'saudi-riyal' => 'Rial saudit (SAR)',
                'select-timezone' => 'Selectați fusul orar',
                'singapore-dollar' => 'Dolar singaporez (SGD)',
                'south-african-rand' => 'Rand sud-african (ZAR)',
                'south-korean-won' => 'Won sud-coreean (KRW)',
                'sqlsrv' => 'SQLSRV',
                'sri-lankan-rupee' => 'Rupie din Sri Lanka (LKR)',
                'swedish-krona' => 'Coroană suedeză (SEK)',
                'swiss-franc' => 'Franc elvețian (CHF)',
                'thai-baht' => 'Baht thailandez (THB)',
                'title' => 'Configurare magazin',
                'tunisian-dinar' => 'Dinar tunisian (TND)',
                'turkish-lira' => 'Liră turcească (TRY)',
                'ukrainian-hryvnia' => 'Hryvnia ucraineană (UAH)',
                'united-arab-emirates-dirham' => 'Dirham din Emiratele Arabe Unite (AED)',
                'united-states-dollar' => 'Dolar american (USD)',
                'uzbekistani-som' => 'Som uzbek (UZS)',
                'venezuelan-bolívar' => 'Bolívar venezuelean (VEF)',
                'vietnamese-dong' => 'Dong vietnamez (VND)',
                'warning-message' => 'Atenție! Setările pentru limba implicită a sistemului și moneda implicită sunt permanente și nu pot fi modificate odată ce au fost stabilite.',
                'zambian-kwacha' => 'Kwacha zambiană (ZMW)',
            ],

            'sample-products' => [
                'download-sample' => 'descarcă-eșantion',
                'no' => 'Nu',
                'sample-products' => 'Produse demonstrative',
                'title' => 'Produse demonstrative',
                'yes' => 'Da',
            ],

            'installation-processing' => [
                'bagisto' => 'Instalare Bagisto',
                'bagisto-info' => 'Se creează tabelele bazei de date, acest lucru poate dura câteva momente',
                'title' => 'Instalare',
            ],

            'installation-completed' => [
                'admin-panel' => 'Panou de administrare',
                'bagisto-forums' => 'Forum Bagisto',
                'customer-panel' => 'Panou client',
                'explore-bagisto-extensions' => 'Explorați extensiile Bagisto',
                'title' => 'Instalare finalizată',
                'title-info' => 'Bagisto a fost instalat cu succes pe sistemul dumneavoastră.',
            ],

            'ready-for-installation' => [
                'create-database-table' => 'Crearea tabelelor bazei de date',
                'install' => 'Instalare',
                'install-info' => 'Bagisto pentru instalare',
                'install-info-button' => 'Faceți clic pe butonul de mai jos pentru a',
                'populate-database-table' => 'Popularea tabelelor bazei de date',
                'start-installation' => 'Începeți instalarea',
                'title' => 'Pregătit pentru instalare',
            ],

            'start' => [
                'locale' => 'Limbă',
                'main' => 'Început',
                'select-locale' => 'Selectați limba',
                'title' => 'Instalarea Bagisto',
                'welcome-title' => 'Bun venit la Bagisto',
            ],

            'server-requirements' => [
                'calendar' => 'Calendar',
                'ctype' => 'cType',
                'curl' => 'cURL',
                'dom' => 'dom',
                'fileinfo' => 'fileInfo',
                'filter' => 'Filtru',
                'gd' => 'GD',
                'hash' => 'Hash',
                'intl' => 'intl',
                'json' => 'JSON',
                'mbstring' => 'mbstring',
                'openssl' => 'openssl',
                'pcre' => 'pcre',
                'pdo' => 'pdo',
                'php' => 'PHP',
                'php-version' => '8.1 sau mai recent',
                'session' => 'sesiune',
                'title' => 'Cerințe de sistem',
                'tokenizer' => 'tokenizer',
                'xml' => 'XML',
            ],

            'arabic' => 'Arabă',
            'back' => 'Înapoi',
            'bagisto' => 'Bagisto',
            'bagisto-info' => 'un proiect comunitar realizat de',
            'bagisto-logo' => 'Logo Bagisto',
            'bengali' => 'Bengali',
            'catalan' => 'Catalană',
            'chinese' => 'Chineză',
            'continue' => 'Continuare',
            'dutch' => 'Olandeză',
            'english' => 'Engleză',
            'french' => 'Franceză',
            'german' => 'Germană',
            'hebrew' => 'Ebraică',
            'hindi' => 'Hindi',
            'indonesian' => 'Indoneziană',
            'installation-description' => 'Instalarea Bagisto implică de obicei mai mulți pași. Iată o prezentare generală a procesului de instalare pentru Bagisto',
            'installation-info' => 'Suntem bucuroși să vă vedem aici!',
            'installation-title' => 'Bun venit la instalare',
            'italian' => 'Italiană',
            'japanese' => 'Japoneză',
            'persian' => 'Persană',
            'polish' => 'Poloneză',
            'portuguese' => 'Portugheză braziliană',
            'romanian' => 'Română',
            'russian' => 'Rusă',
            'sinhala' => 'Singhaleză',
            'spanish' => 'Spaniolă',
            'title' => 'Instalator Bagisto',
            'turkish' => 'Turcă',
            'ukrainian' => 'Ucraineană',
            'webkul' => 'Webkul',
        ],
    ],
];
