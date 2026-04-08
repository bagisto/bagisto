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
                'rma' => 'RMA',
                'settings' => 'Setări',
                'shipping' => 'Livrare',
            ],

            'attributes' => [
                'allow-rma' => 'Allow RMA',
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
                'rma-rules' => 'RMA Rules',
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
                'bold-collections' => [
                    'content' => [
                        'btn-title' => 'Vezi colecțiile',
                        'description' => 'Vă prezentăm noile noastre colecții îndrăznețe! Ridicați-vă stilul cu designuri curajoase și declarații vibrante. Explorați modele izbitoare și culori îndrăznețe care vă redefinesc garderoba. Pregătiți-vă să îmbrățișați extraordinarul!',
                        'title' => 'Pregătiți-vă pentru noile noastre colecții îndrăznețe!',
                    ],

                    'name' => 'Colecții îndrăznețe',
                ],

                'bold-collections-2' => [
                    'content' => [
                        'btn-title' => 'Vezi colecțiile',
                        'description' => 'Our Bold Collections are here to redefine your wardrobe with fearless designs and striking, vibrant colors. From daring patterns to powerful hues, this is your chance to break away from the ordinary and step into the extraordinary.',
                        'title' => 'Unleash Your Boldness with Our New Collection!',
                    ],

                    'name' => 'Colecții îndrăznețe',
                ],

                'book-tickets' => [
                    'name' => 'Rezervare Bilete',

                    'options' => [
                        'title' => 'Rezervare Bilete',
                    ],
                ],

                'categories-collections' => [
                    'name' => 'Colecții pe categorii',
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

                'kids-collection' => [
                    'name' => 'Colecție Copii',

                    'options' => [
                        'title' => 'Colecție Copii',
                    ],
                ],

                'mens-collection' => [
                    'name' => 'Colecție Bărbați',

                    'options' => [
                        'title' => 'Colecție Bărbați',
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

                'womens-collection' => [
                    'name' => 'Colecție Femei',

                    'options' => [
                        'title' => 'Colecție Femei',
                    ],
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
                    'url-path' => 'men',
                ],

                '3' => [
                    'description' => 'Descrierea categoriei Îmbrăcăminte de iarnă',
                    'meta-description' => 'Meta descrierea categoriei Bărbați',
                    'meta-keywords' => 'Cuvinte cheie meta ale categoriei Bărbați',
                    'meta-title' => 'Titlu meta al categoriei Bărbați',
                    'name' => 'Îmbrăcăminte de iarnă',
                    'slug' => 'imbracaminte-de-iarna',
                    'url-path' => 'kids',
                ],

                '4' => [
                    'description' => '<p>Woman</p>',
                    'meta-description' => 'Meta descrierea categoriei Bărbați',
                    'meta-keywords' => 'Cuvinte cheie meta ale categoriei Bărbați',
                    'meta-title' => 'Titlu meta al categoriei Bărbați',
                    'name' => 'Womens',
                    'slug' => 'womens',
                    'url-path' => 'woman',
                ],

                '5' => [
                    'description' => '<p>Formal Wear</p>',
                    'meta-description' => 'Meta descrierea categoriei Bărbați',
                    'meta-keywords' => 'Cuvinte cheie meta ale categoriei Bărbați',
                    'meta-title' => 'Titlu meta al categoriei Bărbați',
                    'name' => 'Formal Wear',
                    'slug' => 'formal-wear-men',
                    'url-path' => 'men/formal-wear-men',
                ],

                '6' => [
                    'description' => '<p>Casual Wear</p>',
                    'meta-description' => 'Meta descrierea categoriei Bărbați',
                    'meta-keywords' => 'Cuvinte cheie meta ale categoriei Bărbați',
                    'meta-title' => 'Titlu meta al categoriei Bărbați',
                    'name' => 'Casual Wear',
                    'slug' => 'casual-wear-men',
                    'url-path' => 'men/casual-wear-men',
                ],

                '7' => [
                    'description' => '<p>Active Wear</p>',
                    'meta-description' => 'Meta descrierea categoriei Bărbați',
                    'meta-keywords' => 'Cuvinte cheie meta ale categoriei Bărbați',
                    'meta-title' => 'Titlu meta al categoriei Bărbați',
                    'name' => 'Active Wear',
                    'slug' => 'active-wear',
                    'url-path' => 'men/active-wear',
                ],

                '8' => [
                    'description' => '<p>Footwear</p>',
                    'meta-description' => 'Meta descrierea categoriei Bărbați',
                    'meta-keywords' => 'Cuvinte cheie meta ale categoriei Bărbați',
                    'meta-title' => 'Titlu meta al categoriei Bărbați',
                    'name' => 'Footwear',
                    'slug' => 'footwear',
                    'url-path' => 'men/footwear',
                ],

                '9' => [
                    'description' => '<p><span>Formal Wear</span></p>',
                    'meta-description' => 'Meta descrierea categoriei Bărbați',
                    'meta-keywords' => 'Cuvinte cheie meta ale categoriei Bărbați',
                    'meta-title' => 'Titlu meta al categoriei Bărbați',
                    'name' => 'Formal Wear',
                    'slug' => 'formal-wear-female',
                    'url-path' => 'woman/formal-wear-female',
                ],

                '10' => [
                    'description' => '<p>Casual Wear</p>',
                    'meta-description' => 'Meta descrierea categoriei Bărbați',
                    'meta-keywords' => 'Cuvinte cheie meta ale categoriei Bărbați',
                    'meta-title' => 'Titlu meta al categoriei Bărbați',
                    'name' => 'Casual Wear',
                    'slug' => 'casual-wear-female',
                    'url-path' => 'woman/casual-wear-female',
                ],

                '11' => [
                    'description' => '<p>Active</p>',
                    'meta-description' => 'Meta descrierea categoriei Bărbați',
                    'meta-keywords' => 'Cuvinte cheie meta ale categoriei Bărbați',
                    'meta-title' => 'Titlu meta al categoriei Bărbați',
                    'name' => 'Active Wear',
                    'slug' => 'active-wear-female',
                    'url-path' => 'woman/active-wear-female',
                ],

                '12' => [
                    'description' => '<p>Footwear</p>',
                    'meta-description' => 'Meta descrierea categoriei Bărbați',
                    'meta-keywords' => 'Cuvinte cheie meta ale categoriei Bărbați',
                    'meta-title' => 'Titlu meta al categoriei Bărbați',
                    'name' => 'Footwear',
                    'slug' => 'footwear-female',
                    'url-path' => 'woman/footwear-female',
                ],

                '13' => [
                    'description' => '<p>Girls Clothing</p>',
                    'meta-description' => 'Meta descrierea categoriei Bărbați',
                    'meta-keywords' => 'Cuvinte cheie meta ale categoriei Bărbați',
                    'meta-title' => 'Girls Clothing',
                    'name' => 'Girls Clothing',
                    'slug' => 'girls-clothing',
                    'url-path' => 'kids/girls-clothing',
                ],

                '14' => [
                    'description' => '<p>Boys Clothing</p>',
                    'meta-description' => 'Boys Fashion',
                    'meta-keywords' => 'Cuvinte cheie meta ale categoriei Bărbați',
                    'meta-title' => 'Boys Clothing',
                    'name' => 'Boys Clothing',
                    'slug' => 'boys-clothing',
                    'url-path' => 'kids/boys-clothing',
                ],

                '15' => [
                    'description' => '<p>Girls Footwear</p>',
                    'meta-description' => 'Girls Fashionable Footwear Collection',
                    'meta-keywords' => 'Cuvinte cheie meta ale categoriei Bărbați',
                    'meta-title' => 'Girls Footwear',
                    'name' => 'Girls Footwear',
                    'slug' => 'girls-footwear',
                    'url-path' => 'kids/girls-footwear',
                ],

                '16' => [
                    'description' => '<p>Boys Footwear</p>',
                    'meta-description' => 'Boys Stylish Footwear Collection',
                    'meta-keywords' => 'Cuvinte cheie meta ale categoriei Bărbați',
                    'meta-title' => 'Boys Footwear',
                    'name' => 'Boys Footwear',
                    'slug' => 'boys-footwear',
                    'url-path' => 'kids/boys-footwear',
                ],

                '17' => [
                    'description' => '<p>Fitness</p>',
                    'meta-description' => 'Meta descrierea categoriei Bărbați',
                    'meta-keywords' => 'Cuvinte cheie meta ale categoriei Bărbați',
                    'meta-title' => 'Titlu meta al categoriei Bărbați',
                    'name' => 'Wellness',
                    'slug' => 'wellness',
                    'url-path' => 'wellness',
                ],

                '18' => [
                    'description' => '<p>Downloadable Yoga Tutorial</p>',
                    'meta-description' => 'Meta descrierea categoriei Bărbați',
                    'meta-keywords' => 'Cuvinte cheie meta ale categoriei Bărbați',
                    'meta-title' => 'Titlu meta al categoriei Bărbați',
                    'name' => 'Downloadable Yoga Tutorial',
                    'slug' => 'downloadable-yoga-tutorial',
                    'url-path' => 'wellness/downloadable-yoga-tutorial',
                ],

                '19' => [
                    'description' => '<p>Books Collection</p>',
                    'meta-description' => 'Books Collection',
                    'meta-keywords' => 'Cuvinte cheie meta ale categoriei Bărbați',
                    'meta-title' => 'Books Collection',
                    'name' => 'E-Books',
                    'slug' => 'e-books',
                    'url-path' => 'wellness/e-books',
                ],

                '20' => [
                    'description' => '<p>Movie Pass</p>',
                    'meta-description' => 'Immerse yourself in the magic of 10 movies each month without extra charges. Valid nationwide with no blackout dates, this pass offers exclusive perks and concession discounts, making it a must-have for movie enthusiasts.',
                    'meta-keywords' => 'Cuvinte cheie meta ale categoriei Bărbați',
                    'meta-title' => 'CineXperience Monthly Movie Pass',
                    'name' => 'Movie Pass',
                    'slug' => 'movie-pass',
                    'url-path' => 'wellness/movie-pass',
                ],

                '21' => [
                    'description' => '<p>Easily manage and sell your booking-based products with our seamless booking system. Whether you offer appointments, rentals, events, or reservations, our solution ensures a smooth experience for both businesses and customers. With real-time availability, flexible scheduling, and automated notifications, you can streamline your booking process effortlessly. Enhance customer convenience and maximize your sales with our powerful booking product solution!</p>',
                    'meta-description' => 'Meta descrierea categoriei Bărbați',
                    'meta-keywords' => 'Cuvinte cheie meta ale categoriei Bărbați',
                    'meta-title' => 'Titlu meta al categoriei Bărbați',
                    'name' => 'Bookings',
                    'slug' => 'bookings',
                    'url-path' => '',
                ],

                '22' => [
                    'description' => '<p>Appointment booking allows customers to schedule time slots for services or consultations with businesses or professionals. This system is commonly used in industries such as healthcare, beauty, education, and personal services. It helps streamline scheduling, reduce wait times, and improve customer satisfaction by offering convenient, time-based reservations.</p>',
                    'meta-description' => 'Meta descrierea categoriei Bărbați',
                    'meta-keywords' => 'Cuvinte cheie meta ale categoriei Bărbați',
                    'meta-title' => 'Titlu meta al categoriei Bărbați',
                    'name' => 'Appointment Booking',
                    'slug' => 'appointment-booking',
                    'url-path' => '',
                ],

                '23' => [
                    'description' => '<p>Event booking allows individuals or groups to register or reserve spots for public or private events such as concerts, workshops, conferences, or parties. It typically includes options for selecting dates, seating types, and ticket categories, providing organizers with efficient attendee management and ensuring a smooth entry process.</p>',
                    'meta-description' => 'Meta descrierea categoriei Bărbați',
                    'meta-keywords' => 'Cuvinte cheie meta ale categoriei Bărbați',
                    'meta-title' => 'Titlu meta al categoriei Bărbați',
                    'name' => 'Event Booking',
                    'slug' => 'event-booking',
                    'url-path' => '',
                ],

                '24' => [
                    'description' => '<p>Community hall booking enables individuals, organizations, or groups to reserve community spaces for various events such as weddings, meetings, cultural programs, or social gatherings. This system helps manage availability, schedule bookings, and handle logistics like capacity, amenities, and rental duration. It ensures efficient use of public or private halls while offering a convenient way for users to organize their events.</p>',
                    'meta-description' => 'Meta descrierea categoriei Bărbați',
                    'meta-keywords' => 'Cuvinte cheie meta ale categoriei Bărbați',
                    'meta-title' => 'Titlu meta al categoriei Bărbați',
                    'name' => 'Community Hall Bookings',
                    'slug' => 'community-hall-bookings',
                    'url-path' => '',
                ],

                '25' => [
                    'description' => '<p>Table booking enables customers to reserve tables at restaurants, cafes, or dining venues in advance. It helps manage seating capacity, reduce wait times, and provide a better dining experience. This system is especially useful during peak hours, special events, or for accommodating large groups with specific preferences.</p>',
                    'meta-description' => 'Meta descrierea categoriei Bărbați',
                    'meta-keywords' => 'Cuvinte cheie meta ale categoriei Bărbați',
                    'meta-title' => 'Titlu meta al categoriei Bărbați',
                    'name' => 'Table Booking',
                    'slug' => 'table-booking',
                    'url-path' => '',
                ],

                '26' => [
                    'description' => '<p>Rental booking facilitates the reservation of items or properties for temporary use, such as vehicles, equipment, vacation homes, or meeting spaces. It includes features for selecting rental periods, checking availability, and managing payments. This system supports both short-term and long-term rentals, enhancing convenience for both providers and renters.</p>',
                    'meta-description' => 'Meta descrierea categoriei Bărbați',
                    'meta-keywords' => 'Cuvinte cheie meta ale categoriei Bărbați',
                    'meta-title' => 'Titlu meta al categoriei Bărbați',
                    'name' => 'Rental Booking',
                    'slug' => 'rental-booking',
                    'url-path' => '',
                ],

                '27' => [
                    'description' => '<p>Explore the latest in consumer electronics, designed to keep you connected, productive, and entertained. Whether you\'re upgrading your devices or looking for smart solutions, we have everything you need.</p>',
                    'meta-description' => 'Meta descrierea categoriei Bărbați',
                    'meta-keywords' => 'Cuvinte cheie meta ale categoriei Bărbați',
                    'meta-title' => 'Titlu meta al categoriei Bărbați',
                    'name' => 'Electronics',
                    'slug' => 'electronics',
                    'url-path' => '',
                ],

                '28' => [
                    'description' => '<p>Discover smartphones, chargers, cases, and other essentials for staying connected on the go.</p>',
                    'meta-description' => 'Meta descrierea categoriei Bărbați',
                    'meta-keywords' => 'Cuvinte cheie meta ale categoriei Bărbați',
                    'meta-title' => 'Titlu meta al categoriei Bărbați',
                    'name' => 'Mobile Phones & Accessories',
                    'slug' => 'mobile-phones-accessories',
                    'url-path' => '',
                ],

                '29' => [
                    'description' => '<p>Find powerful laptops and portable tablets for work, study, and play.</p>',
                    'meta-description' => 'Meta descrierea categoriei Bărbați',
                    'meta-keywords' => 'Cuvinte cheie meta ale categoriei Bărbați',
                    'meta-title' => 'Titlu meta al categoriei Bărbați',
                    'name' => 'Laptops & Tablets',
                    'slug' => 'laptops-tablets',
                    'url-path' => '',
                ],

                '30' => [
                    'description' => '<p>Shop headphones, earbuds, and speakers to enjoy crystal-clear sound and immersive audio experiences.</p>',
                    'meta-description' => 'Meta descrierea categoriei Bărbați',
                    'meta-keywords' => 'Cuvinte cheie meta ale categoriei Bărbați',
                    'meta-title' => 'Titlu meta al categoriei Bărbați',
                    'name' => 'Audio Devices',
                    'slug' => 'audio-devices',
                    'url-path' => '',
                ],

                '31' => [
                    'description' => '<p>Make life easier with smart lighting, thermostats, security systems, and more.</p>',
                    'meta-description' => 'Meta descrierea categoriei Bărbați',
                    'meta-keywords' => 'Cuvinte cheie meta ale categoriei Bărbați',
                    'meta-title' => 'Titlu meta al categoriei Bărbați',
                    'name' => 'Smart Home & Automation',
                    'slug' => 'smart-home-automation',
                    'url-path' => '',
                ],

                '32' => [
                    'description' => '<p>Upgrade your living space with functional and stylish home and kitchen essentials. From cooking to cleaning, find products that enhance comfort and efficiency.</p>',
                    'meta-description' => 'Meta descrierea categoriei Bărbați',
                    'meta-keywords' => 'Cuvinte cheie meta ale categoriei Bărbați',
                    'meta-title' => 'Titlu meta al categoriei Bărbați',
                    'name' => 'Household',
                    'slug' => 'household',
                    'url-path' => '',
                ],

                '33' => [
                    'description' => '<p>Browse blenders, air fryers, coffee makers, and more to simplify meal prep.</p>',
                    'meta-description' => 'Meta descrierea categoriei Bărbați',
                    'meta-keywords' => 'Cuvinte cheie meta ale categoriei Bărbați',
                    'meta-title' => 'Titlu meta al categoriei Bărbați',
                    'name' => 'Kitchen Appliances',
                    'slug' => 'kitchen-appliances',
                    'url-path' => '',
                ],

                '34' => [
                    'description' => '<p>Explore cookware sets, utensils, dinnerware, and serveware for your culinary needs.</p>',
                    'meta-description' => 'Meta descrierea categoriei Bărbați',
                    'meta-keywords' => 'Cuvinte cheie meta ale categoriei Bărbați',
                    'meta-title' => 'Titlu meta al categoriei Bărbați',
                    'name' => 'Cookware & Dining',
                    'slug' => 'cookware-dining',
                    'url-path' => '',
                ],

                '35' => [
                    'description' => '<p>Add comfort and charm with sofas, tables, wall art, and home accents.</p>',
                    'meta-description' => 'Meta descrierea categoriei Bărbați',
                    'meta-keywords' => 'Cuvinte cheie meta ale categoriei Bărbați',
                    'meta-title' => 'Titlu meta al categoriei Bărbați',
                    'name' => 'Furniture & Decor',
                    'slug' => 'furniture-decor',
                    'url-path' => '',
                ],

                '36' => [
                    'description' => '<p>Keep your space spotless with vacuums, cleaning sprays, brooms, and organizers.</p>',
                    'meta-description' => 'Meta descrierea categoriei Bărbați',
                    'meta-keywords' => 'Cuvinte cheie meta ale categoriei Bărbați',
                    'meta-title' => 'Titlu meta al categoriei Bărbați',
                    'name' => 'Cleaning Supplies',
                    'slug' => 'cleaning-supplies',
                    'url-path' => '',
                ],

                '37' => [
                    'description' => '<p>Ignite your imagination or organize your workspace with a wide selection of books and stationery. Perfect for readers, students, professionals, and artists.</p>',
                    'meta-description' => 'Meta descrierea categoriei Bărbați',
                    'meta-keywords' => 'Cuvinte cheie meta ale categoriei Bărbați',
                    'meta-title' => 'Titlu meta al categoriei Bărbați',
                    'name' => 'Books & Stationery',
                    'slug' => 'books-stationery',
                    'url-path' => '',
                ],

                '38' => [
                    'description' => '<p>Dive into bestselling novels, biographies, self-help, and more.</p>',
                    'meta-description' => 'Meta descrierea categoriei Bărbați',
                    'meta-keywords' => 'Cuvinte cheie meta ale categoriei Bărbați',
                    'meta-title' => 'Titlu meta al categoriei Bărbați',
                    'name' => 'Fiction & Non-Fiction Books',
                    'slug' => 'fiction-non-fiction-books',
                    'url-path' => '',
                ],

                '39' => [
                    'description' => '<p>Find textbooks, reference materials, and study guides for all ages.</p>',
                    'meta-description' => 'Meta descrierea categoriei Bărbați',
                    'meta-keywords' => 'Cuvinte cheie meta ale categoriei Bărbați',
                    'meta-title' => 'Titlu meta al categoriei Bărbați',
                    'name' => 'Educational & Academic',
                    'slug' => 'educational-academic',
                    'url-path' => '',
                ],

                '40' => [
                    'description' => '<p>Shop pens, notebooks, planners, and office essentials for productivity.</p>',
                    'meta-description' => 'Meta descrierea categoriei Bărbați',
                    'meta-keywords' => 'Cuvinte cheie meta ale categoriei Bărbați',
                    'meta-title' => 'Titlu meta al categoriei Bărbați',
                    'name' => 'Office Supplies',
                    'slug' => 'office-supplies',
                    'url-path' => '',
                ],

                '41' => [
                    'description' => '<p>Explore paints, brushes, sketchbooks, and DIY craft kits for creatives.</p>',
                    'meta-description' => 'Meta descrierea categoriei Bărbați',
                    'meta-keywords' => 'Cuvinte cheie meta ale categoriei Bărbați',
                    'meta-title' => 'Titlu meta al categoriei Bărbați',
                    'name' => 'Art & Craft Materials',
                    'slug' => 'art-craft-materials',
                    'url-path' => '',
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
                'no' => 'Nu',
                'note' => 'Note: Indexing time depends on the number of locales selected. This process may take up to 2 minutes to complete. If you add more locales, try to increase the max execution time in your server and PHP settings, or you can use our CLI installer to avoid request timeout.',
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
                'create-database-tables' => 'Create the database tables',
                'drop-existing-tables' => 'Drop any existing tables present',
                'install' => 'Instalare',
                'install-info' => 'Bagisto pentru instalare',
                'install-info-button' => 'Faceți clic pe butonul de mai jos pentru a',
                'populate-database-tables' => 'Populate the database tables',
                'start-installation' => 'Începeți instalarea',
                'title' => 'Pregătit pentru instalare',
            ],

            'start' => [
                'language' => 'Installation Wizard language',
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
