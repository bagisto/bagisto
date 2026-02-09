<?php

return [
    'seeders' => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'Predeterminat',
            ],

            'attribute-groups' => [
                'description' => 'Descripció',
                'general' => 'General',
                'inventories' => 'Inventaris',
                'meta-description' => 'Meta Descripció',
                'price' => 'Preu',
                'rma' => 'RMA',
                'settings' => 'Configuracions',
                'shipping' => 'Enviament',
            ],

            'attributes' => [
                'allow-rma' => 'Permetre RMA',
                'brand' => 'Marca',
                'color' => 'Color',
                'cost' => 'Cost',
                'description' => 'Descripció',
                'featured' => 'Destacat',
                'guest-checkout' => 'Compra de Convidat',
                'height' => 'Altura',
                'length' => 'Longitud',
                'manage-stock' => 'Gestionar Stock',
                'meta-description' => 'Meta Descripció',
                'meta-keywords' => 'Meta Paraules Clau',
                'meta-title' => 'Meta Títol',
                'name' => 'Nom',
                'new' => 'Nou',
                'price' => 'Preu',
                'product-number' => 'Número de Producte',
                'rma-rules' => 'Normes de RMA',
                'short-description' => 'Descripció Curta',
                'size' => 'Mida',
                'sku' => 'SKU',
                'special-price' => 'Preu Especial',
                'special-price-from' => 'Preu Especial Des de',
                'special-price-to' => 'Preu Especial Fins a',
                'status' => 'Estat',
                'tax-category' => 'Categoria d\'Impostos',
                'url-key' => 'Clau d\'URL',
                'visible-individually' => 'Visible Individualment',
                'weight' => 'Pes',
                'width' => 'Ample',
            ],

            'attribute-options' => [
                'black' => 'Negre',
                'green' => 'Verd',
                'l' => 'L',
                'm' => 'M',
                'red' => 'Roig',
                's' => 'S',
                'white' => 'Blanc',
                'xl' => 'XL',
                'yellow' => 'Groc',
            ],
        ],

        'category' => [
            'categories' => [
                'description' => 'Descripció de la Categoria Arrel',
                'name' => 'Arrel',
            ],
        ],

        'cms' => [
            'pages' => [
                'about-us' => [
                    'content' => 'Contingut de la Pàgina Sobre de Nosaltres',
                    'title' => 'Sobre de Nosaltres',
                ],

                'contact-us' => [
                    'content' => 'Contingut de la Pàgina Contàcti-n\'s',
                    'title' => 'Contàcti-n\'s',
                ],

                'customer-service' => [
                    'content' => 'Contingut de la pàgina Servei al Client',
                    'title' => 'Servei al Client',
                ],

                'payment-policy' => [
                    'content' => 'Contingut de la pàgina Política de Pagament',
                    'title' => 'Política de Pagament',
                ],

                'privacy-policy' => [
                    'content' => 'Contingut de la pàgina Política de Privacitat',
                    'title' => 'Política de Privacitat',
                ],

                'refund-policy' => [
                    'content' => 'Contingut de la pàgina Política de Reemborsament',
                    'title' => 'Política de Reemborsament',
                ],

                'return-policy' => [
                    'content' => 'Contingut de la pàgina Política de Retorn',
                    'title' => 'Política de Retorn',
                ],

                'shipping-policy' => [
                    'content' => 'Contingut de la pàgina Política d\'Enviament',
                    'title' => 'Política d\'Enviament',
                ],

                'terms-conditions' => [
                    'content' => 'Contingut de la pàgina Termes i Condicions',
                    'title' => 'Termes i Condicions',
                ],

                'terms-of-use' => [
                    'content' => 'Contingut de la pàgina Termes d\'Ús',
                    'title' => 'Termes d\'Ús',
                ],

                'whats-new' => [
                    'content' => 'Contingut de la pàgina Novetats',
                    'title' => 'Novetats',
                ],
            ],
        ],

        'core' => [
            'channels' => [
                'meta-description' => 'Descripció de Meta de la Botiga de Demostració',
                'meta-keywords' => 'Paraules Clau de Meta de la Botiga de Demostració',
                'meta-title' => 'Botiga de Demostració',
                'name' => 'Predeterminat',
            ],

            'currencies' => [
                'AED' => 'Dírham dels Emirats Àrabs Units',
                'ARS' => 'Peso Argentí',
                'AUD' => 'Dòlar Australià',
                'BDT' => 'Taka de Bangladesh',
                'BHD' => 'Dinar bahreïnien',
                'BRL' => 'Real Brasiler',
                'CAD' => 'Dòlar Canadenc',
                'CHF' => 'Franc Suís',
                'CLP' => 'Peso Xinilè',
                'CNY' => 'Iuan Xinès',
                'COP' => 'Peso Colombià',
                'CZK' => 'Corona Txeca',
                'DKK' => 'Corona Danesa',
                'DZD' => 'Dinar Algerí',
                'EGP' => 'Lliura Egípcia',
                'EUR' => 'Euro',
                'FJD' => 'Dòlar Fijian',
                'GBP' => 'Lliura Esterlina',
                'HKD' => 'Dòlar de Hong Kong',
                'HUF' => 'Florí Hongarès',
                'IDR' => 'Rupia Indonèsia',
                'ILS' => 'Nou Xéquel Israelí',
                'INR' => 'Rupia Índia',
                'JOD' => 'Dinar Jordà',
                'JPY' => 'Ien Japonès',
                'KRW' => 'Won Sud-coreà',
                'KWD' => 'Dinar Kuwaitià',
                'KZT' => 'Tengue Kazakh',
                'LBP' => 'Lliura Libanesa',
                'LKR' => 'Rupia de Sri Lanka',
                'LYD' => 'Dinar Libi',
                'MAD' => 'Dírham Marroquí',
                'MUR' => 'Rupia Mauriciana',
                'MXN' => 'Peso Mexicà',
                'MYR' => 'Ringgit Malai',
                'NGN' => 'Naira Nigerià',
                'NOK' => 'Corona Noruega',
                'NPR' => 'Rupia Nepalesa',
                'NZD' => 'Dòlar Neozelandès',
                'OMR' => 'Rial Omanita',
                'PAB' => 'Balboa Panameny',
                'PEN' => 'Nou Sol Peruà',
                'PHP' => 'Peso Filipí',
                'PKR' => 'Rupia Pakistanesa',
                'PLN' => 'Zloty Polonès',
                'PYG' => 'Guaraní Paraguaià',
                'QAR' => 'Rial de Qatar',
                'RON' => 'Leu Romanès',
                'RUB' => 'Rublo Rus',
                'SAR' => 'Riyal Saudita',
                'SEK' => 'Corona Sueca',
                'SGD' => 'Dòlar de Singapur',
                'THB' => 'Baht Tailandès',
                'TND' => 'Dinar Tunisià',
                'TRY' => 'Lira Turca',
                'TWD' => 'Nou Dòlar de Taiwan',
                'UAH' => 'Hryvnia Ucraïnesa',
                'USD' => 'Dòlar Estatunidenc',
                'UZS' => 'Som Uzbequès',
                'VEF' => 'Bolívar Veneçolà',
                'VND' => 'Dong Vietnamita',
                'XAF' => 'Franc CFA BEAC',
                'XOF' => 'Franc CFA BCEAO',
                'ZAR' => 'Rand Sud-africà',
                'ZMW' => 'Kwacha Zambià',
            ],

            'locales' => [
                'ar' => 'Àrab',
                'bn' => 'Bengalí',
                'ca' => 'Català',
                'de' => 'Alemany',
                'en' => 'Anglès',
                'es' => 'Espanyol',
                'fa' => 'Persa',
                'fr' => 'Francès',
                'he' => 'Hebreu',
                'hi_IN' => 'Hindi',
                'id' => 'Indonesi',
                'it' => 'Italià',
                'ja' => 'Japonès',
                'nl' => 'Holandès',
                'pl' => 'Polonès',
                'pt_BR' => 'Portuguès Brasiler',
                'ru' => 'Rus',
                'sin' => 'Singalès',
                'tr' => 'Turc',
                'uk' => 'Ucraïnès',
                'zh_CN' => 'Xinès',
            ],
        ],

        'customer' => [
            'customer-groups' => [
                'general' => 'General',
                'guest' => 'Convidat',
                'wholesale' => 'Majorista',
            ],
        ],

        'inventory' => [
            'inventory-sources' => [
                'name' => 'Predeterminat',
            ],
        ],

        'shop' => [
            'theme-customizations' => [
                'all-products' => [
                    'name' => 'Tots els Productes',

                    'options' => [
                        'title' => 'Tots els Productes',
                    ],
                ],

                'bold-collections' => [
                    'content' => [
                        'btn-title' => 'Veure Col·leccions',
                        'description' => 'Presentem les nostres noves Col·leccions Atrevides! Eleva el teu estil amb dissenys agosarats i declaracions vibrants. Explora patrons cridaners i colors vius que redefineixen el teu armari. Prepara\'t per abraçar l\'extraordinari!',
                        'title' => 'Prepara\'t per les nostres noves Col·leccions Atrevides!',
                    ],

                    'name' => 'Col·leccions Atrevides',
                ],

                'bold-collections-2' => [
                    'content' => [
                        'btn-title' => 'Veure Col·leccions',
                        'description' => 'Les nostres Col·leccions Agosarades són aquí per redefinir el teu armari amb dissenys atrevits i colors vibrants i cridaners. Des de patrons agosarats fins a tons poderosos, aquesta és la teva oportunitat de trencar amb l\'ordinari i entrar a l\'extraordinari.',
                        'title' => 'Allibera la Teva Gosadia amb la Nostra Nova Col·lecció!',
                    ],

                    'name' => 'Col·leccions Agosarades',
                ],

                'booking-products' => [
                    'name' => 'Productes de Reserva',

                    'options' => [
                        'title' => 'Reservar Entrades',
                    ],
                ],

                'categories-collections' => [
                    'name' => 'Col·leccions de Categories',
                ],

                'featured-collections' => [
                    'name' => 'Col·leccions Destacades',

                    'options' => [
                        'title' => 'Productes Destacats',
                    ],
                ],

                'footer-links' => [
                    'name' => 'Enllaços del Peu de Pàgina',

                    'options' => [
                        'about-us' => 'Sobre Nosaltres',
                        'contact-us' => 'Contacta\'ns',
                        'customer-service' => 'Servei al Client',
                        'payment-policy' => 'Política de Pagament',
                        'privacy-policy' => 'Política de Privacitat',
                        'refund-policy' => 'Política de Reemborsament',
                        'return-policy' => 'Política de Retorn',
                        'shipping-policy' => 'Política d\'Enviament',
                        'terms-conditions' => 'Termes i Condicions',
                        'terms-of-use' => 'Termes d\'Ús',
                        'whats-new' => 'Novetats',
                    ],
                ],

                'game-container' => [
                    'content' => [
                        'sub-title-1' => 'Les Nostres Col·leccions',
                        'sub-title-2' => 'Les Nostres Col·leccions',
                        'title' => 'El joc amb les nostres noves addicions!',
                    ],

                    'name' => 'Contenidor de Jocs',
                ],

                'image-carousel' => [
                    'name' => 'Carrusel d\'Imatges',

                    'sliders' => [
                        'title' => 'Prepara\'t per a la Nova Col·lecció',
                    ],
                ],

                'new-products' => [
                    'name' => 'Nous Productes',

                    'options' => [
                        'title' => 'Nous Productes',
                    ],
                ],

                'offer-information' => [
                    'content' => [
                        'title' => 'Fins a un 40% DE DESCOMPTE en la teva primera comanda COMPRA ARA!',
                    ],

                    'name' => 'Informació d\'Ofertes',
                ],

                'services-content' => [
                    'description' => [
                        'emi-available-info' => 'EMI sense cost disponible en totes les targetes de crèdit habituals',
                        'free-shipping-info' => 'Enviament gratuït en totes les comandes',
                        'product-replace-info' => 'Reemplaçament de producte fàcil disponible!',
                        'time-support-info' => 'Suport dedicat 24/7 per xat i correu electrònic',
                    ],

                    'name' => 'Contingut de Serveis',

                    'title' => [
                        'emi-available' => 'EMI disponible',
                        'free-shipping' => 'Enviament gratuït',
                        'product-replace' => 'Reemplaçament de producte',
                        'time-support' => 'Suport 24/7',
                    ],
                ],

                'top-collections' => [
                    'content' => [
                        'sub-title-1' => 'Les Nostres Col·leccions',
                        'sub-title-2' => 'Les Nostres Col·leccions',
                        'sub-title-3' => 'Les Nostres Col·leccions',
                        'sub-title-4' => 'Les Nostres Col·leccions',
                        'sub-title-5' => 'Les Nostres Col·leccions',
                        'sub-title-6' => 'Les Nostres Col·leccions',
                        'title' => 'El joc amb les nostres noves addicions!',
                    ],

                    'name' => 'Millors Col·leccions',
                ],
            ],
        ],

        'user' => [
            'roles' => [
                'description' => 'Els usuaris amb aquest rol tindran accés a tot',
                'name' => 'Administrador',
            ],

            'users' => [
                'name' => 'Exemple',
            ],
        ],

        'sample-categories' => [
            'category-translation' => [
                '2' => [
                    'description' => '<p>Homes</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Homes',
                    'slug' => 'mens',
                    'url-path' => 'men',
                ],

                '3' => [
                    'description' => '<p>Nens</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Nens',
                    'slug' => 'kids',
                    'url-path' => 'kids',
                ],

                '4' => [
                    'description' => '<p>Dones</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Dones',
                    'slug' => 'womens',
                    'url-path' => 'woman',
                ],

                '5' => [
                    'description' => '<p>Roba Formal</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Roba Formal',
                    'slug' => 'formal-wear-men',
                    'url-path' => 'men/formal-wear-men',
                ],

                '6' => [
                    'description' => '<p>Roba Casual</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Roba Casual',
                    'slug' => 'casual-wear-men',
                    'url-path' => 'men/casual-wear-men',
                ],

                '7' => [
                    'description' => '<p>Roba Esportiva</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Roba Esportiva',
                    'slug' => 'active-wear',
                    'url-path' => 'men/active-wear',
                ],

                '8' => [
                    'description' => '<p>Calçat</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Calçat',
                    'slug' => 'footwear',
                    'url-path' => 'men/footwear',
                ],

                '9' => [
                    'description' => '<p>Roba Formal Dona</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Roba Formal Dona',
                    'slug' => 'formal-wear-female',
                    'url-path' => 'woman/formal-wear-female',
                ],

                '10' => [
                    'description' => '<p>Roba Casual Dona</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Roba Casual Dona',
                    'slug' => 'casual-wear-female',
                    'url-path' => 'woman/casual-wear-female',
                ],

                '11' => [
                    'description' => '<p>Roba Esportiva Dona</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Roba Esportiva Dona',
                    'slug' => 'active-wear-female',
                    'url-path' => 'woman/active-wear-female',
                ],

                '12' => [
                    'description' => '<p>Calçat Dona</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Calçat Dona',
                    'slug' => 'footwear-female',
                    'url-path' => 'woman/footwear-female',
                ],

                '13' => [
                    'description' => '<p>Roba de Nenes</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => 'Girls Clothing',
                    'name' => 'Roba de Nenes',
                    'slug' => 'girls-clothing',
                    'url-path' => 'kids/girls-clothing',
                ],

                '14' => [
                    'description' => '<p>Roba de Nens</p>',
                    'meta-description' => 'Boys Fashion',
                    'meta-keywords' => '',
                    'meta-title' => 'Boys Clothing',
                    'name' => 'Roba de Nens',
                    'slug' => 'boys-clothing',
                    'url-path' => 'kids/boys-clothing',
                ],

                '15' => [
                    'description' => '<p>Calçat de Nenes</p>',
                    'meta-description' => 'Girls Fashionable Footwear Collection',
                    'meta-keywords' => '',
                    'meta-title' => 'Girls Footwear',
                    'name' => 'Calçat de Nenes',
                    'slug' => 'girls-footwear',
                    'url-path' => 'kids/girls-footwear',
                ],

                '16' => [
                    'description' => '<p>Calçat de Nens</p>',
                    'meta-description' => 'Boys Stylish Footwear Collection',
                    'meta-keywords' => '',
                    'meta-title' => 'Boys Footwear',
                    'name' => 'Calçat de Nens',
                    'slug' => 'boys-footwear',
                    'url-path' => 'kids/boys-footwear',
                ],

                '17' => [
                    'description' => '<p>Benestar</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Benestar',
                    'slug' => 'wellness',
                    'url-path' => 'wellness',
                ],

                '18' => [
                    'description' => '<p>Tutorials de Ioga Descarregables</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Tutorials de Ioga Descarregables',
                    'slug' => 'downloadable-yoga-tutorial',
                    'url-path' => 'wellness/downloadable-yoga-tutorial',
                ],

                '19' => [
                    'description' => '<p>Llibres Electrònics</p>',
                    'meta-description' => 'Books Collection',
                    'meta-keywords' => '',
                    'meta-title' => 'Books Collection',
                    'name' => 'Llibres Electrònics',
                    'slug' => 'e-books',
                    'url-path' => 'wellness/e-books',
                ],

                '20' => [
                    'description' => '<p>Passi de Cinema</p>',
                    'meta-description' => 'Immerse yourself in the magic of 10 movies each month without extra charges. Valid nationwide with no blackout dates, this pass offers exclusive perks and concession discounts, making it a must-have for movie enthusiasts.',
                    'meta-keywords' => '',
                    'meta-title' => 'CineXperience Monthly Movie Pass',
                    'name' => 'Passi de Cinema',
                    'slug' => 'movie-pass',
                    'url-path' => 'wellness/movie-pass',
                ],

                '21' => [
                    'description' => '<p>Reserves</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Reserves',
                    'slug' => 'bookings',
                    'url-path' => '',
                ],

                '22' => [
                    'description' => '<p>Reserva de Cites</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Reserva de Cites',
                    'slug' => 'appointment-booking',
                    'url-path' => '',
                ],

                '23' => [
                    'description' => '<p>Reserva d\'Esdeveniments</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Reserva d\'Esdeveniments',
                    'slug' => 'event-booking',
                    'url-path' => '',
                ],

                '24' => [
                    'description' => '<p>Reserves de Sales Comunitàries</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Reserves de Sales Comunitàries',
                    'slug' => 'community-hall-bookings',
                    'url-path' => '',
                ],

                '25' => [
                    'description' => '<p>Reserva de Taula</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Reserva de Taula',
                    'slug' => 'table-booking',
                    'url-path' => '',
                ],

                '26' => [
                    'description' => '<p>Reserva de Lloguer</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Reserva de Lloguer',
                    'slug' => 'rental-booking',
                    'url-path' => '',
                ],

                '27' => [
                    'description' => '<p>Electrònica</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Electrònica',
                    'slug' => 'electronics',
                    'url-path' => '',
                ],

                '28' => [
                    'description' => '<p>Telèfons Mòbils i Accessoris</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Telèfons Mòbils i Accessoris',
                    'slug' => 'mobile-phones-accessories',
                    'url-path' => '',
                ],

                '29' => [
                    'description' => '<p>Portàtils i Tauletes</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Portàtils i Tauletes',
                    'slug' => 'laptops-tablets',
                    'url-path' => '',
                ],

                '30' => [
                    'description' => '<p>Dispositius d\'Àudio</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Dispositius d\'Àudio',
                    'slug' => 'audio-devices',
                    'url-path' => '',
                ],

                '31' => [
                    'description' => '<p>Llar Intel·ligent i Automatització</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Llar Intel·ligent i Automatització',
                    'slug' => 'smart-home-automation',
                    'url-path' => '',
                ],

                '32' => [
                    'description' => '<p>Llar</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Llar',
                    'slug' => 'household',
                    'url-path' => '',
                ],

                '33' => [
                    'description' => '<p>Electrodomèstics de Cuina</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Electrodomèstics de Cuina',
                    'slug' => 'kitchen-appliances',
                    'url-path' => '',
                ],

                '34' => [
                    'description' => '<p>Utensilis de Cuina i Menjador</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Utensilis de Cuina i Menjador',
                    'slug' => 'cookware-dining',
                    'url-path' => '',
                ],

                '35' => [
                    'description' => '<p>Mobles i Decoració</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Mobles i Decoració',
                    'slug' => 'furniture-decor',
                    'url-path' => '',
                ],

                '36' => [
                    'description' => '<p>Productes de Neteja</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Productes de Neteja',
                    'slug' => 'cleaning-supplies',
                    'url-path' => '',
                ],

                '37' => [
                    'description' => '<p>Llibres i Papereria</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Llibres i Papereria',
                    'slug' => 'books-stationery',
                    'url-path' => '',
                ],

                '38' => [
                    'description' => '<p>Llibres de Ficció i No Ficció</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Llibres de Ficció i No Ficció',
                    'slug' => 'fiction-non-fiction-books',
                    'url-path' => '',
                ],

                '39' => [
                    'description' => '<p>Educatiu i Acadèmic</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Educatiu i Acadèmic',
                    'slug' => 'educational-academic',
                    'url-path' => '',
                ],

                '40' => [
                    'description' => '<p>Material d\'Oficina</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Material d\'Oficina',
                    'slug' => 'office-supplies',
                    'url-path' => '',
                ],

                '41' => [
                    'description' => '<p>Materials d\'Art i Manualitats</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Materials d\'Art i Manualitats',
                    'slug' => 'art-craft-materials',
                    'url-path' => '',
                ],
            ],
        ],
    ],

    'installer' => [
        'middleware' => [
            'already-installed' => 'L\'aplicació ja està instal·lada.',
        ],

        'index' => [
            'create-administrator' => [
                'admin' => 'Administrador',
                'bagisto' => 'Bagisto',
                'confirm-password' => 'Confirmar Contrasenya',
                'email' => 'Correu Electrònic',
                'email-address' => 'admin@example.com',
                'password' => 'Contrasenya',
                'title' => 'Crear Administrador',
            ],

            'environment-configuration' => [
                'algerian-dinar' => 'Dinar Algerià (DZD)',
                'allowed-currencies' => 'Monedes Autoritzades',
                'allowed-locales' => 'Idiomes Autoritzats',
                'application-name' => 'Nom de l\'Aplicació',
                'argentine-peso' => 'Pes Argentí (ARS)',
                'australian-dollar' => 'Dòlar Australià (AUD)',
                'bagisto' => 'Bagisto',
                'bangladeshi-taka' => 'Taka de Bangladesh (BDT)',
                'bahraini-dinar' => 'Dinar bahreïnien (BHD)',
                'brazilian-real' => 'Real Brasiler (BRL)',
                'british-pound-sterling' => 'Lliura Esterlina Britànica (GBP)',
                'canadian-dollar' => 'Dòlar Canadenc (CAD)',
                'cfa-franc-bceao' => 'Franc CFA BCEAO (XOF)',
                'cfa-franc-beac' => 'Franc CFA BEAC (XAF)',
                'chilean-peso' => 'Pes Xilè (CLP)',
                'chinese-yuan' => 'Iuan Xinès (CNY)',
                'colombian-peso' => 'Pes Colombià (COP)',
                'czech-koruna' => 'Corona Txeca (CZK)',
                'danish-krone' => 'Corona Danesa (DKK)',
                'database-connection' => 'Connexió de Base de Dades',
                'database-hostname' => 'Nom de l\'Host de la Base de Dades',
                'database-name' => 'Nom de la Base de Dades',
                'database-password' => 'Contrasenya de la Base de Dades',
                'database-port' => 'Port de la Base de Dades',
                'database-prefix' => 'Prefix de la Base de Dades',
                'database-prefix-help' => 'La prefixació ha de tenir 4 caràcters de llargada i només pot contenir lletres, números i guions baixos.',
                'database-username' => 'Nom d\'Usuari de la Base de Dades',
                'default-currency' => 'Moneda Predeterminada',
                'default-locale' => 'Idioma Predeterminat',
                'default-timezone' => 'Zona Horària Predeterminada',
                'default-url' => 'URL Predeterminada',
                'default-url-link' => 'https://localhost',
                'egyptian-pound' => 'Lliura Egípcia (EGP)',
                'euro' => 'Euro (EUR)',
                'fijian-dollar' => 'Dòlar Fiyà (FJD)',
                'hong-kong-dollar' => 'Dòlar de Hong Kong (HKD)',
                'hungarian-forint' => 'Florí Hongarès (HUF)',
                'indian-rupee' => 'Rupia Índia (INR)',
                'indonesian-rupiah' => 'Rupia Indonesa (IDR)',
                'israeli-new-shekel' => 'Nou Xéquel Israelí (ILS)',
                'japanese-yen' => 'Ien Japonès (JPY)',
                'jordanian-dinar' => 'Dinar Jordà (JOD)',
                'kazakhstani-tenge' => 'Tengue Kazakh (KZT)',
                'kuwaiti-dinar' => 'Dinar Kuwaití (KWD)',
                'lebanese-pound' => 'Lliura Libanesa (LBP)',
                'libyan-dinar' => 'Dinar Libi (LYD)',
                'malaysian-ringgit' => 'Ringgit Malaisi (MYR)',
                'mauritian-rupee' => 'Rupia Mauriciana (MUR)',
                'mexican-peso' => 'Pes Mexicà (MXN)',
                'moroccan-dirham' => 'Dirham Marroquí (MAD)',
                'mysql' => 'Mysql',
                'nepalese-rupee' => 'Rupia Nepalesa (NPR)',
                'new-taiwan-dollar' => 'Nou Dòlar de Taiwan (TWD)',
                'new-zealand-dollar' => 'Dòlar Neozelandès (NZD)',
                'nigerian-naira' => 'Naira Nigerià (NGN)',
                'norwegian-krone' => 'Corona Noruega (NOK)',
                'omani-rial' => 'Rial Omaní (OMR)',
                'pakistani-rupee' => 'Rupia Pakistanesa (PKR)',
                'panamanian-balboa' => 'Balboa Panameny (PAB)',
                'paraguayan-guarani' => 'Guaraní Paraguaià (PYG)',
                'peruvian-nuevo-sol' => 'Nou Sol Peruà (PEN)',
                'pgsql' => 'pgSQL',
                'philippine-peso' => 'Pes Filipí (PHP)',
                'polish-zloty' => 'Zloty Polonès (PLN)',
                'qatari-rial' => 'Rial de Qatar (QAR)',
                'romanian-leu' => 'Leu Romanès (RON)',
                'russian-ruble' => 'Ruble Rus (RUB)',
                'saudi-riyal' => 'Riyal Saudita (SAR)',
                'select-timezone' => 'Selecciona Zona Horària',
                'singapore-dollar' => 'Dòlar de Singapur (SGD)',
                'south-african-rand' => 'Rand Sud-africà (ZAR)',
                'south-korean-won' => 'Won Sud-coreà (KRW)',
                'sqlsrv' => 'SQLSRV',
                'sri-lankan-rupee' => 'Rupia de Sri Lanka (LKR)',
                'swedish-krona' => 'Corona Sueca (SEK)',
                'swiss-franc' => 'Franc Suís (CHF)',
                'thai-baht' => 'Baht Tailandès (THB)',
                'title' => 'Configuració de la Botiga',
                'tunisian-dinar' => 'Dinar Tunisià (TND)',
                'turkish-lira' => 'Lira Turca (TRY)',
                'ukrainian-hryvnia' => 'Hrívnia Ucraïnesa (UAH)',
                'united-arab-emirates-dirham' => 'Dirham dels Emirats Àrabs Units (AED)',
                'united-states-dollar' => 'Dòlar Estatunidenc (USD)',
                'uzbekistani-som' => 'Som Uzbeq (UZS)',
                'venezuelan-bolívar' => 'Bolívar Veneçolà (VEF)',
                'vietnamese-dong' => 'Dong Vietnamita (VND)',
                'warning-message' => 'Compte! Els ajustaments del teu idioma i moneda predeterminats del sistema són permanents i no es poden modificar després de ser establerts.',
                'zambian-kwacha' => 'Kwacha de Zàmbia (ZMW)',
            ],

            'sample-products' => [
                'no' => 'No',
                'sample-products' => 'Productes de mostra',
                'title' => 'Productes de mostra',
                'yes' => 'Sí',
            ],

            'installation-processing' => [
                'bagisto' => 'Instal·lació de Bagisto',
                'bagisto-info' => 'Creant les taules de la base de dades, això pot trigar uns moments',
                'title' => 'Instal·lació',
            ],

            'installation-completed' => [
                'admin-panel' => 'Panell d\'administració',
                'bagisto-forums' => 'Fòrum de Bagisto',
                'customer-panel' => 'Panell de clients',
                'explore-bagisto-extensions' => 'Explorar extensions de Bagisto',
                'title' => 'Instal·lació completada',
                'title-info' => 'Bagisto s\'ha instal·lat correctament al teu sistema.',
            ],

            'ready-for-installation' => [
                'create-database-table' => 'Crear la taula de la base de dades',
                'install' => 'Instal·lació',
                'install-info' => 'Bagisto per a la instal·lació',
                'install-info-button' => 'Fes clic al botó de sota per',
                'populate-database-table' => 'Omplir les taules de la base de dades',
                'start-installation' => 'Iniciar instal·lació',
                'title' => 'A punt per a la instal·lació',
            ],

            'start' => [
                'locale' => 'Idioma',
                'main' => 'Inici',
                'select-locale' => 'Selecciona l\'idioma',
                'title' => 'La teva instal·lació de Bagisto',
                'welcome-title' => 'Benvingut a Bagisto',
            ],

            'server-requirements' => [
                'calendar' => 'Calendari',
                'ctype' => 'cType',
                'curl' => 'cURL',
                'dom' => 'DOM',
                'fileinfo' => 'Informació del fitxer',
                'filter' => 'Filtre',
                'gd' => 'GD',
                'hash' => 'Hash',
                'intl' => 'Intl',
                'json' => 'JSON',
                'mbstring' => 'mbstring',
                'openssl' => 'OpenSSL',
                'pcre' => 'pcre',
                'pdo' => 'pdo',
                'php' => 'PHP',
                'php-version' => '8.1 o superior',
                'session' => 'Sessió',
                'title' => 'Requisits del servidor',
                'tokenizer' => 'Tokenizer',
                'xml' => 'XML',
            ],

            'arabic' => 'Àrab',
            'back' => 'Enrere',
            'bagisto' => 'Bagisto',
            'bagisto-info' => 'Un projecte comunitari per',
            'bagisto-logo' => 'Logotip de Bagisto',
            'bengali' => 'Bengalí',
            'catalan' => 'Català',
            'chinese' => 'Xinès',
            'continue' => 'Continuar',
            'dutch' => 'Holandès',
            'english' => 'Anglès',
            'french' => 'Francès',
            'german' => 'Alemany',
            'hebrew' => 'Hebreu',
            'hindi' => 'Hindi',
            'indonesian' => 'Indonesi',
            'installation-description' => "La instal·lació de Bagisto generalment implica diversos passos. Aquí tens un esquema general del procés d'instal·lació per a Bagisto.",
            'installation-info' => "Ens alegra veure't aquí!",
            'installation-title' => 'Benvingut a la Instal·lació',
            'italian' => 'Italià',
            'japanese' => 'Japonès',
            'persian' => 'Persa',
            'polish' => 'Polonès',
            'portuguese' => 'Portuguès brasiler',
            'russian' => 'Rus',
            'sinhala' => 'Singalès',
            'spanish' => 'Espanyol',
            'title' => 'Instal·lador de Bagisto',
            'turkish' => 'Turc',
            'ukrainian' => 'Ucraïnès',
            'webkul' => 'Webkul',
        ],
    ],
];
