<?php

return [
    'seeders' => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'Predeterminat',
            ],

            'attribute-groups' => [
                'description'      => 'Descripció',
                'general'          => 'General',
                'inventories'      => 'Inventaris',
                'meta-description' => 'Meta Descripció',
                'price'            => 'Preu',
                'settings'         => 'Configuracions',
                'shipping'         => 'Enviament',
            ],

            'attributes' => [
                'brand'                => 'Marca',
                'color'                => 'Color',
                'cost'                 => 'Cost',
                'description'          => 'Descripció',
                'featured'             => 'Destacat',
                'guest-checkout'       => 'Compra de Convidat',
                'height'               => 'Altura',
                'length'               => 'Longitud',
                'manage-stock'         => 'Gestionar Stock',
                'meta-description'     => 'Meta Descripció',
                'meta-keywords'        => 'Meta Paraules Clau',
                'meta-title'           => 'Meta Títol',
                'name'                 => 'Nom',
                'new'                  => 'Nou',
                'price'                => 'Preu',
                'product-number'       => 'Número de Producte',
                'short-description'    => 'Descripció Curta',
                'size'                 => 'Mida',
                'sku'                  => 'SKU',
                'special-price'        => 'Preu Especial',
                'special-price-from'   => 'Preu Especial Des de',
                'special-price-to'     => 'Preu Especial Fins a',
                'status'               => 'Estat',
                'tax-category'         => 'Categoria d\'Impostos',
                'url-key'              => 'Clau d\'URL',
                'visible-individually' => 'Visible Individualment',
                'weight'               => 'Pes',
                'width'                => 'Ample',
            ],

            'attribute-options' => [
                'black'  => 'Negre',
                'green'  => 'Verd',
                'l'      => 'L',
                'm'      => 'M',
                'red'    => 'Roig',
                's'      => 'S',
                'white'  => 'Blanc',
                'xl'     => 'XL',
                'yellow' => 'Groc',
            ],
        ],

        'category' => [
            'categories' => [
                'description' => 'Descripció de la Categoria Arrel',
                'name'        => 'Arrel',
            ],
        ],

        'cms' => [
            'pages' => [
                'about-us' => [
                    'content' => 'Contingut de la Pàgina Sobre de Nosaltres',
                    'title'   => 'Sobre de Nosaltres',
                ],

                'contact-us' => [
                    'content' => 'Contingut de la Pàgina Contàcti-n\'s',
                    'title'   => 'Contàcti-n\'s',
                ],

                'customer-service' => [
                    'content' => 'Contingut de la pàgina Servei al Client',
                    'title'   => 'Servei al Client',
                ],

                'payment-policy' => [
                    'content' => 'Contingut de la pàgina Política de Pagament',
                    'title'   => 'Política de Pagament',
                ],

                'privacy-policy' => [
                    'content' => 'Contingut de la pàgina Política de Privacitat',
                    'title'   => 'Política de Privacitat',
                ],

                'refund-policy' => [
                    'content' => 'Contingut de la pàgina Política de Reemborsament',
                    'title'   => 'Política de Reemborsament',
                ],

                'return-policy' => [
                    'content' => 'Contingut de la pàgina Política de Retorn',
                    'title'   => 'Política de Retorn',
                ],

                'shipping-policy' => [
                    'content' => 'Contingut de la pàgina Política d\'Enviament',
                    'title'   => 'Política d\'Enviament',
                ],

                'terms-conditions' => [
                    'content' => 'Contingut de la pàgina Termes i Condicions',
                    'title'   => 'Termes i Condicions',
                ],

                'terms-of-use' => [
                    'content' => 'Contingut de la pàgina Termes d\'Ús',
                    'title'   => 'Termes d\'Ús',
                ],

                'whats-new' => [
                    'content' => 'Contingut de la pàgina Novetats',
                    'title'   => 'Novetats',
                ],
            ],
        ],

        'core' => [
            'channels' => [
                'name'             => 'Predeterminat',
                'meta-title'       => 'Botiga de Demostració',
                'meta-keywords'    => 'Paraules Clau de Meta de la Botiga de Demostració',
                'meta-description' => 'Descripció de Meta de la Botiga de Demostració',
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
                'ar'    => 'Àrab',
                'bn'    => 'Bengalí',
                'ca'    => 'Català',
                'de'    => 'Alemany',
                'en'    => 'Anglès',
                'es'    => 'Espanyol',
                'fa'    => 'Persa',
                'fr'    => 'Francès',
                'he'    => 'Hebreu',
                'hi_IN' => 'Hindi',
                'id'    => 'Indonesi',
                'it'    => 'Italià',
                'ja'    => 'Japonès',
                'nl'    => 'Holandès',
                'pl'    => 'Polonès',
                'pt_BR' => 'Portuguès Brasiler',
                'ru'    => 'Rus',
                'sin'   => 'Singalès',
                'tr'    => 'Turc',
                'uk'    => 'Ucraïnès',
                'zh_CN' => 'Xinès',
            ],
        ],

        'customer' => [
            'customer-groups' => [
                'general'   => 'General',
                'guest'     => 'Convidat',
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
                        'btn-title'   => 'Veure Col·leccions',
                        'description' => 'Presentem les nostres noves Col·leccions Atrevides! Eleva el teu estil amb dissenys agosarats i declaracions vibrants. Explora patrons cridaners i colors vius que redefineixen el teu armari. Prepara\'t per abraçar l\'extraordinari!',
                        'title'       => 'Prepara\'t per les nostres noves Col·leccions Atrevides!',
                    ],

                    'name' => 'Col·leccions Atrevides',
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
                        'about-us'         => 'Sobre Nosaltres',
                        'contact-us'       => 'Contacta\'ns',
                        'customer-service' => 'Servei al Client',
                        'payment-policy'   => 'Política de Pagament',
                        'privacy-policy'   => 'Política de Privacitat',
                        'refund-policy'    => 'Política de Reemborsament',
                        'return-policy'    => 'Política de Retorn',
                        'shipping-policy'  => 'Política d\'Enviament',
                        'terms-conditions' => 'Termes i Condicions',
                        'terms-of-use'     => 'Termes d\'Ús',
                        'whats-new'        => 'Novetats',
                    ],
                ],

                'game-container' => [
                    'content' => [
                        'sub-title-1' => 'Les Nostres Col·leccions',
                        'sub-title-2' => 'Les Nostres Col·leccions',
                        'title'       => 'El joc amb les nostres noves addicions!',
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
                        'emi-available-info'   => 'EMI sense cost disponible en totes les targetes de crèdit habituals',
                        'free-shipping-info'   => 'Enviament gratuït en totes les comandes',
                        'product-replace-info' => 'Reemplaçament de producte fàcil disponible!',
                        'time-support-info'    => 'Suport dedicat 24/7 per xat i correu electrònic',
                    ],

                    'name' => 'Contingut de Serveis',

                    'title' => [
                        'emi-available'   => 'EMI disponible',
                        'free-shipping'   => 'Enviament gratuït',
                        'product-replace' => 'Reemplaçament de producte',
                        'time-support'    => 'Suport 24/7',
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
                        'title'       => 'El joc amb les nostres noves addicions!',
                    ],

                    'name' => 'Millors Col·leccions',
                ],
            ],
        ],

        'user' => [
            'roles' => [
                'description' => 'Els usuaris amb aquest rol tindran accés a tot',
                'name'        => 'Administrador',
            ],

            'users' => [
                'name' => 'Exemple',
            ],
        ],

        'sample-categories' => [
            'category-translation' => [
                '2' => [
                    'description'      => 'Descripció de la categoria d\'homes',
                    'meta-description' => 'Meta descripció de la categoria d\'homes',
                    'meta-keywords'    => 'Meta paraules clau de la categoria d\'homes',
                    'meta-title'       => 'Meta títol de la categoria d\'homes',
                    'name'             => 'Homes',
                    'slug'             => 'homes',
                ],

                '3' => [
                    'description'      => 'Descripció de la categoria de roba d\'hivern',
                    'meta-description' => 'Meta descripció de la categoria de roba d\'hivern',
                    'meta-keywords'    => 'Meta paraules clau de la categoria de roba d\'hivern',
                    'meta-title'       => 'Meta títol de la categoria de roba d\'hivern',
                    'name'             => 'Roba d\'hivern',
                    'slug'             => 'roba-d-hivern',
                ],
            ],
        ],

        'sample-products' => [
            'product-flat' => [
                '1' => [
                    'description'       => 'El Gorro de Punt còmode de l\'Artic es la teva sol·lució per a mantenir-te abirgat, còmode i amb estil durant els mesos més freds. Elaborat amb una barreja suau i duradora d\'acrílic, aquest gorro està disseñat pèr a proporcionar un ajust acollidor i ajustat. El seu disseny clàssic el fà adequat tant per a homes com per a dones, oferint un accesòri versàtil que complementa diversos estils. Ja sigui que surtis un dia casual en la ciutat o disfrutis a l\'aire lliure, aquest gorro afegeix un toc de comoditat i calidesa al teu conjunt. El material suau i transpirable assegura que et mantinguis abrigat sense sacrificar l\'estil. El Gorro de Punt còmode de l\'Àrtic no és només un accessori; es una declaració de moda hivernal. La seva simplicitat fà que sigui fàcil de combinar amb diferents outfits, convertint-lo en un bàsic del teu guardarrobes d\¡hivern. Ideal per a regalar o com un caprici per a tu mateix, aquest gorro es un afegit considerat a qualsevol conjunt hivernal. És un accessòri versàtil que va més enllà de la funcionalitat, afegint un toc de calidesa i estil. Eleva el teu guardarrobes d\'hivern amb aquest accessòri clàssoc que combina sense esforç la calidesa amb un sentit atemporal de la moda.',
                    'meta-description'  => 'descripció meta',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Títol Meta',
                    'name'              => 'Gorro de Punt Unisex Còmode de l\'Àrtico',
                    'short-description' => 'Abraça els dies freds amb estil amb el nostre Gorro de Punt còmode de l\'Àrtic. Elaborat amb una barreja suau i duradora d\'acrílic, aquest gorro clàssic ofereix calidesa i versatilitat. Adecuat tant per a homes com per a dones, és l\'accessòri ideal per a un ús casual o a l\'aire lliure. Eleva el teu guardarrobes d\'hivern o regala-li a algú especial aquest gorro essencial.',
                ],

                '2' => [
                    'description'       => 'La Bufanda d\'Hivern Arctic Bliss és més que un accessori per al clima fred; és una declaració de calidesa, comoditat i estil per a la temporada d\'hivern. Elaborada amb cura a partir d\'una luxosa barreja d\'acrílic i llana, aquesta bufanda està dissenyada per mantenir-te abrigat i còmode fins i tot en les temperatures més fredes. La textura suau i mullida no només proporciona aïllament contra el fred, sinó que també afegeix un toc de luxe al teu guardarroba d\'hivern. El disseny de la Bufanda de Invierno Arctic Bliss és elegant i versàtil, el que la converteix en una addició perfecta a una varietat d\'outfits hivernals. Sigui que et vesteixis per a una ocasió especial o afegueixis una capa elegant a el teu look diari, aquesta bufanda complementa el teu estil sense esforç. La longitud extra llarga de la bufanda ofereix opcions d\'estil personalitzables. Envuelve-la per a major calidesa, deixa-la suelta per a un look casual o experimenta amb diferents nusos per expressar el teu estil únic. Aquesta versatilitat la converteix en un accessori imprescindible per a la temporada d\'hivern. ¿Busques el regal perfecte? La Bufanda de Invierno Arctic Bliss és una elecció ideal. Sigui que sorprenguis a un ser estimat o et donis un caprici, aquesta bufanda és un regal atemporal i pràctic que serà apreciat durant els mesos d\'hivern. Abraça l\'hivern amb la Bufanda de Invierno Arctic Bliss, on la calidesa es troba amb l\'estil en perfecta harmonia. Eleva el teu guardarroba d\'hivern amb aquest accessori essencial que no només et manté abrigat, sinó que també afegeix un toc de sofisticació al teu conjunt per al clima fred.',
                    'meta-description'  => 'descripció meta',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Títol Meta',
                    'name'              => 'Bufanda d\'Hivern Arctic Bliss',
                    'short-description' => 'Experimenta l\'abraçada de la calidesa i l\'estil amb la nostra Bufanda d\'Hivern Arctic Bliss. Elaborada amb una luxosa barreja d\'acrílic i llana, aquesta bufanda acollidora està dissenyada per mantenir-te abrigat durant els dies més freds. El seu disseny elegant i versàtil, combinat amb una longitud extra llarga, ofereix opcions d\'estil personalitzables. Eleva el teu guardarroba d\'hivern o deleita algú especial amb aquest accessori essencial per a l\'hivern.',
                ],

                '3' => [
                    'description'       => 'Presentem els Guants d\'Hivern Arctic amb Pantalla Tàctil Arctic, on la calidesa, l\'estil i la connectivitat es combinen per millorar la teva experiència hivernal. Elaborats amb acrílic de qualitat alta, aquests guants estan dissenyats per proporcionar una calidesa i durabilitat excepcionals. Les puntes compatibles amb pantalles tàctils et permeten estar connectat sense exposar les teves mans al fred. Contestes trucades, envia missatges i navega pels teus dispositius sense esforç, tot mentre mantens les teves mans abrigades. El forro aïllant afegeix una capa addicional de comoditat, fent d\'aquests guants la teva elecció ideal per afrontar el fred de l\'hivern. Sigui que estiguis viatjant, fent compres o disfrutant d\'activitats a l\'aire lliure, aquests guants et brinden la calidesa i protecció que necessites. Els punys elàstics asseguren un ajust segur, evitant corrents d\'aire fred i mantenint els guants al seu lloc durant les teves activitats diàries. El disseny elegant afegeix un toc d\'estil al teu conjunt hivernal, fent que aquests guants siguin tan fashion com funcionals. Ideals per a regalar o com un caprici per a tu mateix, els Guants d\'Hivern Arctic amb Pantalla Tàctil són un accessori imprescindible per a l\'individu modern. Di adéu a la molèstia de treure\'t els guants per a usar els teus dispositius i abraça la combinació perfecta de calidesa, estil i connectivitat. Mantén-te connectat, mantén-te abrigat i mantén-te a la moda amb els Guants d\'Hivern Arctic amb Pantalla Tàctil, el teu company de confiança per a conquerir la temporada d\'hivern amb confiança.',
                    'meta-description'  => 'descripció meta',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Títol Meta',
                    'name'              => 'Guants d\'Hivern Arctic amb Pantalla Tàctil Arctic',
                    'short-description' => 'Mantén-te connectat i abrigat amb els nostres Guants d\'Hivern amb Pantalla Tàctil Arctic. Aquests guants no només estan elaborats amb acrílic de qualitat alta per brindar calidesa i durabilitat, sinó que també compten amb un disseny compatible amb pantalles tàctils. Amb un forro aïllant, punys elàstics per a un ajust segur i un aspecte elegant, aquests guants són perfectes per a l\'ús diari en condicions fredes.',
                ],

                '4' => [
                    'description'       => 'Presentem els Calcetins de Barreja de Llana Arctic Warmth, el teu company essencial per a peus acollidors i còmodes durant les estacions més fredes. Elaborats amb una mescla premium de llana Merino, acrílic, niló i spandex, aquests calcetins estan dissenyats per oferir un calor i comoditat incomparables. La mescla de llana assegura que els teus peus es mantinguin calents fins i tot en les temperatures més fredes, convertint aquests calcetins en la elecció perfecta per a aventures hivernals o simplement per a estar còmode a casa. La textura suau i acollidora dels calcetins ofereix una sensació de luxe en la teva pell. Di adéu als peus freds mentre disfrutes de l\'abraç còmode proporcionat per aquests calcetins de mescla de llana. Dissenyats per a ser duradors, els calcetins compten amb un reforç al taló i la punta, brindant major resistència en les àrees de major desgast. Això assegura que els teus calcetins resistiràn el pas del temps, brindant comoditat i calidesa duradores. La natura transpirable del material evita el sobrecalentament, permetent que els teus peus es mantinguin còmodes i secs durant tot el dia. Sigui que et dirigis a l\'aire lliure per a una caminada hivernal o et relaxis a casa, aquests calcetins ofereixen l\'equilibri perfecte entre calor i transpirabilitat. Versàtils i elegants, aquests calcetins de mescla de llana són adequats per a diverses ocasions. Combina\'ls amb les teves botes favorites per a un look hivernal de moda o úsalos a casa per a una comoditat màxima. Eleva el teu guardarroba d\'hivern i prioritza la comoditat amb els Calcetins de Mescla de Llana Arctic Warmth. Consiente als teus peus amb el luxe que es mereixen i sumergit en un món de comoditat que dura tota la temporada.',
                    'meta-description'  => 'descripció meta',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Títol Meta',
                    'name'              => 'Calcetins de Barreja de Llana Arctic Warmth',
                    'short-description' => 'Experimenta el calor i la comoditat incomparables dels nostres Calcetins de Barreja de Llana Arctic Warmth. Elaborats amb una barreja de llana Merino, acrílic, niló i spandex, aquests calcetins ofereixen una comoditat definitiva per al clima fred. Amb un reforç al taló i la punta per a major durabilitat, aquests calcetins versàtils i elegants són perfectes per a diverses ocasions.',
                ],

                '5' => [
                    'description'       => 'Presentem el Conjunt d\'Accessòris d\'Hivern Arctic Frost, la teva solució per a mantenir-te abrigat, elegant i connectat durant els dies freds de l\'hivern. Aquest conjunt seleccionat amb cura reuneix quatre accessòris essencials d\'hivern per a crear un conjunt harmònic. La bufanda luxosa, teixida amb una barreja d\'acrílic i llana, no només afegeix una capa de calor, sinó que també aporta un toc d\'elegància al teu guardarroba d\'hivern. El gorro de punt suau, elaborat amb cura, promet mantenir-te acollidor i afegir un toc de moda al teu look. Però això no és tot, el nostre conjunt també inclou guants compatibles amb pantalles tàctils. Mantén-te connectat sense sacrificar el calor mentre naveges pels teus dispositius sense esforç. Sigui que estiguis contestant trucades, enviant missatges o capturant moments hivernals al teu telèfon intel·ligent, aquests guants garanteixen comoditat sense comprometre l\'estil. La textura suau i acollidora dels calcetins ofereix una sensació de luxe en la teva pell. Di adéu als peus freds mentre disfrutes de l\'abraç còmode proporcionat per aquests calcetins de barreja de llana. El Conjunt d\'Accessòris d\'Hivern Arctic Frost no es tracta només de funcionalitat; és una declaració de moda hivernal. Cada peça està dissenyada no només per a protegir-te del fred, sinó també per a elevar el teu estil durant la temporada helada. Els materials seleccionats per a aquest conjunt prioritzan tant la durabilitat com la comoditat, assegurant que puguis gaudir del paisatge hivernal amb estil. Sigui que et estiguis donant un gust o busquessin el regal perfecte, el Conjunt d\'Accessòris d\'Hivern Arctic Frost és una elecció versàtil. Deleita a algú especial durant la temporada navideña o eleva el teu propi guardarroba d\'hivern amb aquest conjunt elegant i funcional. Accepta el fred amb confiança, sabent que tens els accessòris perfectes per a mantenir-te abrigat i elegant.',
                    'meta-description'  => 'descripció meta',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Títol Meta',
                    'name'              => 'Conjunt d\'Accessòris d\'Hivern Arctic Frost',
                    'short-description' => 'Accepta el fred de l\'hivern amb el nostre Conjunt d\'Accessòris d\'Hivern Arctic Frost. Aquest conjunt seleccionat inclou una bufanda luxosa, un gorro acollidor, guants compatibles amb pantalles tàctils i calcetins de barreja de llana. Elegant i funcional, aquest conjunt està elaborat amb materials de qualitat alta, assegurant tant durabilitat com comoditat. Eleva el teu guardarroba d\'hivern o deleita a algú especial amb aquesta opció de regal perfecta.',
                ],

                '6' => [
                    'description'       => 'Presentem el Conjunt d\'Accessòris d\'Hivern Arctic Frost, la teva solució per a mantenir-te abrigat, elegant i connectat durant els dies freds de l\'hivern. Aquest conjunt seleccionat amb cura reuneix quatre accessòris essencials d\'hivern per a crear un conjunt harmònic. La bufanda luxosa, teixida amb una barreja d\'acrílic i llana, no només afegeix una capa de calor, sinó que també aporta un toc d\'elegància al teu guardarroba d\'hivern. El gorro de punt suau, elaborat amb cura, promet mantenir-te acollidor i afegir un toc de moda al teu look. Però això no és tot, el nostre conjunt també inclou guants compatibles amb pantalles tàctils. Mantén-te connectat sense sacrificar el calor mentre naveges pels teus dispositius sense esforç. Sigui que estiguis contestant trucades, enviant missatges o capturant moments hivernals al teu telèfon intel·ligent, aquests guants garanteixen comoditat sense comprometre l\'estil. La textura suau i acollidora dels calcetins ofereix una sensació de luxe en la teva pell. Di adéu als peus freds mentre disfrutes de l\'abraç còmode proporcionat per aquests calcetins de barreja de llana. El Conjunt d\'Accessòris d\'Hivern Arctic Frost és la teva opció perfecta per a l\'hivern. Eleva el teu guardarroba d\'hivern i prioritza la comoditat amb aquest conjunt seleccionat amb cura. Consiente als teus peus amb el luxe que es mereixen i sumergit en un món de comoditat que dura tota la temporada.',
                    'meta-description'  => 'descripció meta',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Títol Meta',
                    'name'              => 'Conjunt d\'Accessòris d\'Hivern Arctic Frost',
                    'short-description' => 'Accepta el fred de l\'hivern amb el nostre Conjunt d\'Accessòris d\'Hivern Arctic Frost. Aquest conjunt seleccionat inclou una bufanda luxosa, un gorro acollidor, guants compatibles amb pantalles tàctils i calcetins de barreja de llana. Elegant i funcional, aquest conjunt està elaborat amb materials de qualitat alta, assegurant tant durabilitat com comoditat. Eleva el teu guardarroba d\'hivern o deleita a algú especial amb aquesta opció de regal perfecta.',
                ],

                '7' => [
                    'description'       => 'Presentem la Jaqueta Encoixinada amb Caputxa OmniHeat per a Home, la teva solució ideal per a mantenir-te abrigat i a la moda durant les estacions més fredes. Aquesta jaqueta està dissenyada pensant en la durabilitat i el calor, assegurant que es converteixi en la teva companya de confiança. El disseny amb caputxa no només afegeix un toc d\'estil, sinó que també proporciona calor addicional, protegint-te dels vents freds i el clima. Les mànigues completes ofereixen cobertura completa, assegurant que et mantinguis còmode des del muscle fins a la canell. Equipada amb butxaques insertades, aquesta jaqueta acolchada brinda comoditat per a portar els teus objectes essencials o mantenir les teves mans calentes. El reompliment sintètic aïllant ofereix major calor, el que la fa ideal per a combatre els dies i nits fredes. Feta d\'una carcassa i forro de polièster resistents, aquesta jaqueta està construïda per a durar i resistir els elements. Disponible en 5 colors atractius, pots triar el que s\'adapta al teu estil i preferència. Versàtil i funcional, la Jaqueta Acolchada amb Caputxa OmniHeat per a Home és adequada per a diverses ocasions, sigui que vagis al treball, surtis de manera informal o assisteixis a un esdeveniment a l\'aire lliure. Experimenta la combinació perfecta d\'estil, comoditat i funcionalitat amb la Jaqueta Acolchada amb Caputxa OmniHeat per a Home. Eleva el teu guardarroba d\'hivern i mantén-te abrigat mentre disfrutes de l\'aire lliure. Venç el fred amb estil i fa una declaració amb aquesta peça essencial.',
                    'meta-description'  => 'descripció meta',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Títol Meta',
                    'name'              => 'Jaqueta Encoixinada amb Caputxa OmniHeat per a Home',
                    'short-description' => 'Mantén-te abrigat i a la moda amb la nostra Jaqueta Encoixinada amb Caputxa OmniHeat per a Home. Aquesta jaqueta està dissenyada per proporcionar calor màxima i compta amb butxaques insertades per a major comoditat. El material aïllant assegura que et mantinguis còmode en climes freds. Disponible en 5 colors atractius, el que la converteix en una opció versàtil per a diverses ocasions.',
                ],

                '8' => [
                    'description'       => 'Presentem la Jaqueta Encoixinada amb Caputxa OmniHeat per a Home, la teva solució ideal per a mantenir-te abrigat i a la moda durant les estacions més fredes. Aquesta jaqueta està dissenyada pensant en la durabilitat i el calor, assegurant que es converteixi en la teva companya de confiança. El disseny amb caputxa no només afegeix un toc d\'estil, sinó que també proporciona calor addicional, protegint-te dels vents freds i el clima. Les mànigues completes ofereixen cobertura completa, assegurant que et mantinguis còmode des del muscle fins a la canell. Equipada amb butxaques insertades, aquesta jaqueta acolchada brinda comoditat per a portar els teus objectes essencials o mantenir les teves mans calentes. El reompliment sintètic aïllant ofereix major calor, el que la fa ideal per a combatre els dies i nits fredes. Feta d\'una carcassa i forro de polièster resistents, aquesta jaqueta està construïda per a durar i resistir els elements. Disponible en 5 colors atractius, pots triar el que s\'adapta al teu estil i preferència. Versàtil i funcional, la Jaqueta Acolchada amb Caputxa OmniHeat per a Home és adequada per a diverses ocasions, sigui que vagis al treball, surtis de manera informal o assisteixis a un esdeveniment a l\'aire lliure. Experimenta la combinació perfecta de estil, comoditat i funcionalitat amb la Jaqueta Acolchada amb Caputxa OmniHeat per a Home. Eleva el teu guardarroba d\'hivern i mantén-te abrigat mentre disfrutes de l\'aire lliure. Venç el fred amb estil i fa una declaració amb aquesta peça essencial.',
                    'meta-description'  => 'descripció meta',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Títol Meta',
                    'name'              => 'Jaqueta Encoixinada amb Caputxa OmniHeat per a Home-Blau-Groc-M',
                    'short-description' => 'Mantente abrigat i a la moda amb la nostra Jaqueta Acolchada amb Caputxa OmniHeat per a Home. Aquesta jaqueta està dissenyada per proporcionar calor màxima i compta amb butxaques insertades per a major comoditat. El material aïllant assegura que et mantinguis còmode en climes freds. Disponible en 5 colors atractius, el que la converteix en una opció versàtil per a diverses ocasions.',
                ],

                '9' => [
                    'description'       => 'Presentem la Jaqueta Encoixinada amb Caputxa OmniHeat per a Home, la teva solució ideal per a mantenir-te abrigat i a la moda durant les estacions més fredes. Aquesta jaqueta està dissenyada pensant en la durabilitat i el calor, assegurant que es converteixi en la teva companya de confiança. El disseny amb caputxa no només afegeix un toc d\'estil, sinó que també proporciona calor addicional, protegint-te dels vents freds i el clima. Les mànigues completes ofereixen cobertura completa, assegurant que et mantinguis còmode des del muscle fins a la canell. Equipada amb butxaques insertades, aquesta jaqueta encoixinada brinda comoditat per a portar els teus objectes essencials o mantenir les teves mans calentes. El reompliment sintètic aïllant ofereix major calor, el que la fa ideal per a combatre els dies i nits fredes. Feta d\'una carcassa i forro de polièster resistents, aquesta jaqueta està construïda per a durar i resistir els elements. Disponible en 5 colors atractius, pots triar el que s\'adapta al teu estil i preferència. Versàtil i funcional, la Jaqueta Encoixinada amb Caputxa OmniHeat per a Home és adequada per a diverses ocasions, sigui que vagis al treball, surtis de manera informal o assisteixis a un esdeveniment al aire lliure. Experimenta la combinació perfecta d\'estil, comoditat i funcionalitat amb la Jaqueta Encoixinada amb Caputxa OmniHeat per a Home. Eleva el teu guardarroba d\'hivern i manting-te abrigat mentre disfrutes de l\'aire lliure. Vence el fred amb estil i fa una declaració amb aquesta peça essencial.',
                    'meta-description'  => 'descripció meta',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Títol Meta',
                    'name'              => 'Jaqueta Encoixinada amb Caputxa OmniHeat per a Home-Blau-Groc-L',
                    'short-description' => 'Mantén-te abrigat i a la moda amb la nostra Jaqueta Encoixinada amb Caputxa OmniHeat per a Home. Aquesta jaqueta està dissenyada per proporcionar calor màxima i compta amb butxaques insertades per a major comoditat. El material aïllant assegura que et mantinguis còmode en climes freds. Disponible en 5 colors atractius, el que la converteix en una opció versàtil per a diverses ocasions.',
                ],

                '10' => [
                    'description'       => 'Presentem la Jaqueta Encoixinada amb Caputxa OmniHeat per a Home, la teva solució ideal per a mantenir-te abrigat i a la moda durant les estacions més fredes. Aquesta jaqueta està dissenyada pensant en la durabilitat i el calor, assegurant que es converteixi en la teva companya de confiança. El disseny amb caputxa no només afegeix un toc d\'estil, sinó que també proporciona calor addicional, protegint-te dels vents freds i el clima. Les mànigues completes ofereixen cobertura completa, assegurant que et mantinguis còmode des del muscle fins a la canell. Equipada amb butxaques insertades, aquesta jaqueta acolchada brinda comoditat per a portar els teus objectes essencials o mantenir les teves mans calentes. El reompliment sintètic aïllant ofereix major calor, el que la fa ideal per a combatre els dies i nits fredes. Feta d\'una carcassa i forro de polièster resistents, aquesta jaqueta està construïda per a durar i resistir els elements. Disponible en 5 colors atractius, pots triar el que s\'adapta al teu estil i preferència. Versàtil i funcional, la Jaqueta Encoixinada amb Caputxa OmniHeat per a Home és adequada per a diverses ocasions, sigui que vagis al treball, surtis de manera informal o assisteixis a un esdeveniment a l\'aire lliure. Experimenta la combinació perfecta d\'estil, comoditat i funcionalitat amb la Jaqueta Encoixinada amb Caputxa OmniHeat per a Home. Eleva el teu guardarroba d\'hivern i mantén-te abrigat mentre disfrutes de l\'aire lliure. Venç el fred amb estil i fa una declaració amb aquesta peça essencial.',
                    'meta-description'  => 'descripció meta',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Títol Meta',
                    'name'              => 'Jaqueta Encoixinada amb Caputxa OmniHeat per a Home-Blau-Verd-M',
                    'short-description' => 'Mantén-te abrigat i a la moda amb la nostra Jaqueta Encoixinada amb Caputxa OmniHeat per a Home. Aquesta jaqueta està dissenyada per a proporcionar calor màxima i compta amb butxaques insertades per a major comoditat. El material aïllant assegura que et mantinguis còmode en clima fred. Disponible en 5 colors atractius, el que la converteix en una opció versàtil per a diverses ocasions.',
                ],

                '11' => [
                    'description'       => 'Presentem la Jaqueta Encoixinada amb Caputxa OmniHeat per a Home, la teva solució ideal per a mantenir-te abrigat i a la moda durant les estacions més fredes. Aquesta jaqueta està dissenyada pensant en la durabilitat i el calor, assegurant que es converteixi en la teva companya de confiança. El disseny amb caputxa no només afegeix un toc d\'estil, sinó que també proporciona calor addicional, protegint-te dels vents freds i el clima. Les mànigues completes ofereixen cobertura completa, assegurant que et mantinguis còmode des del muscle fins a la canell. Equipada amb butxaques insertades, aquesta jaqueta acolchada brinda comoditat per a portar els teus objectes essencials o mantenir les teves mans calentes. El reompliment sintètic aïllant ofereix major calor, el que la fa ideal per a combatre els dies i nits fredes. Feta d\'una carcassa i forro de polièster resistents, aquesta jaqueta està construïda per a durar i resistir els elements. Disponible en 5 colors atractius, pots triar el que s\'adapta al teu estil i preferència. Versàtil i funcional, la Jaqueta Acolchada amb Caputxa OmniHeat per a Home és adequada per a diverses ocasions, sigui que vagis al treball, surtis de manera informal o assisteixis a un esdeveniment a l\'aire lliure. Experimenta la combinació perfecta de estil, comoditat i funcionalitat amb la Jaqueta Acolchada amb Caputxa OmniHeat per a Home. Eleva el teu guardarroba d\'hivern i mantén-te abrigat mentre disfrutes de l\'aire lliure. Venç el fred amb estil i fa una declaració amb aquesta peça essencial.',
                    'meta-description'  => 'descripció meta',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Títol Meta',
                    'name'              => 'Jaqueta Encoixinada amb Caputxa OmniHeat per a Home-Blau-Verd-L',
                    'short-description' => 'Mantén-te abrigat i a la moda amb la nostra Jaqueta Encoixinada amb Caputxa OmniHeat per a Home. Aquesta jaqueta està dissenyada per proporcionar calor màxima i compta amb butxaques insertades per a major comoditat. El material aïllant assegura que et mantinguis còmode en climes freds. Disponible en 5 colors atractius, el que la converteix en una opció versàtil per a diverses ocasions.',
                ],
            ],

            'product-attribute-values' => [
                '1' => [
                    'description'      => 'El Gorro de Punt Còmode de l\'Àrtic és la teva solució per mantenir-te abrigat, còmode i amb estil durant els mesos més freds. Elaborat amb una barreja suau i duradora d\'acrílic, aquest gorro està dissenyat per proporcionar un ajust acollidor i ajustat. El seu disseny clàssic el fa adequat tant per a homes com per a dones, oferint un accessoris versàtil que complementa diversos estils. Ja siguis que surtis per a un dia casual a la ciutat o gaudeixis de l\'aire lliure, aquest gorro afegeix un toc de comoditat i calidesa al teu conjunt. El material suau i transpirable garanteix que et mantinguis abrigat sense sacrificar l\'estil. El Gorro de Punt Còmode de l\'Àrtic no és només un accessoris; és una declaració de moda hivernal. La seva simplicitat fa que sigui fàcil de combinar amb diferents outfits, convertint-lo en un bàsic al teu armari d\'hivern. Ideal per regalar o com un caprici per a tu mateix, aquest gorro és una addició considerada a qualsevol conjunt hivernal. És un accessoris versàtil que va més enllà de la funcionalitat, afegint un toc de calidesa i estil al teu look. Accepta l\'essència de l\'hivern amb el Gorro de Punt Còmode de l\'Àrtic. Ja siguis que gaudeixis d\'un dia casual o t\'enfrontis als elements, deixa que aquest gorro sigui el teu company de comoditat i estil. Eleva el teu armari d\'hivern amb aquest accessoris clàssic que combina sense esforç la calidesa amb un sentit atemporal de la moda.',
                    'meta-description' => 'descripció meta',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Títol Meta',
                    'name'             => 'Gorro de Punt Còmode de l\'Àrtic Unisex',
                    'sort-description' => 'Accepta els dies freds amb estil amb el nostre Gorro de Punt Còmode de l\'Àrtic. Elaborat amb una barreja suau i duradora d\'acrílic, aquest gorro clàssic ofereix calidesa i versatilitat. Adequat tant per a homes com per a dones, és l\'accessoris ideal per a ús casual o a l\'aire lliure. Eleva el teu armari d\'hivern o regala a algú especial aquesta gorra essencial.',
                ],

                '2' => [
                    'description'      => 'La Bufanda de Invierno Arctic Bliss és més que un accessori per al clima fred; és una declaració de calidesa, comoditat i estil per a la temporada d\'hivern. Elaborada amb cura a partir d\'una luxosa barreja d\'acrílic i llana, aquesta bufanda està dissenyada per mantenir-te abrigat i còmode fins i tot en les temperatures més fredes. La textura suau i mullida no només proporciona aïllament contra el fred, sinó que també afegeix un toc de luxe al teu guardarroba d\'hivern. El disseny de la Bufanda de Invierno Arctic Bliss és elegant i versàtil, el que la fa adequada per a diverses ocasions. Experimenta el abraç de la calidesa i l\'estil amb la nostra Bufanda de Invierno Arctic Bliss. Elaborada a partir d\'una luxosa barreja d\'acrílic i llana, aquesta bufanda acollidora està dissenyada per mantenir-te abrigat durant els dies més freds. El seu disseny elegant i versàtil, combinat amb una longitud extra llarga, ofereix opcions d\'estil personalitzables. Eleva el teu guardarroba d\'hivern o deixa a algú especial amb aquest accessori essencial per a l\'hivern.',
                    'meta-description' => 'descripció meta',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Títol Meta',
                    'name'             => 'Bufanda de Invierno Elegante Arctic Bliss',
                    'sort-description' => 'Experimenta l\'abraç de la calidesa i l\'estil amb la nostra Bufanda de Invierno Arctic Bliss. Elaborada a partir d\'una luxosa barreja d\'acrílic i llana, aquesta bufanda acollidora està dissenyada per mantenir-te abrigat durant els dies més freds. El seu disseny elegant i versàtil, combinat amb una longitud extra llarga, ofereix opcions d\'estil personalitzables. Eleva el teu guardarroba d\'hivern o deixa a algú especial amb aquest accessori essencial per a l\'hivern.',
                ],

                '3' => [
                    'description'      => 'Presentem els Guants d\'Hivern Arctic amb Pantalla Tàctil, on la calidesa, l\'estil i la connectivitat es combinen per millorar la teva experiència hivernal. Elaborats amb acrílic de alta qualitat, aquests guants estan dissenyats per proporcionar una calidesa i durabilitat excepcionals. Les puntes compatibles amb pantalles tàctils et permeten estar connectat sense exposar les teves mans al fred. Respon trucades, envia missatges i navega pels teus dispositius sense esforç, tot mentre mantens les teves mans abrigades. El forro aïllant afegeix una capa addicional de comoditat, fent d\'aquests guants la teva elecció ideal per enfrontar el fred de l\'hivern. Ja siguis que estiguis viatjant, fent recados o gaudint d\'activitats a l\'aire lliure, aquests guants et brinden la calidesa i protecció que necessites. Els punys elàstics garanteixen un ajust segur, evitant corrents d\'aire fred i mantenint els guants en el seu lloc durant les teves activitats diàries. El disseny elegant afegeix un toc d\'estil al teu conjunt hivernal, fent que aquests guants siguin tan fashion com funcionals. Ideals per regalar o com un caprici per a tu mateix, els Guants d\'Hivern Arctic amb Pantalla Tàctil són un accessori imprescindible per a l\'individu modern. Diga adeu a la molèstia de treure\'t els guants per usar els teus dispositius i accepta la perfecta combinació de calidesa, estil i connectivitat. Mantén-te connectat, mantén-te abrigat i mantén-te a la moda amb els Guants d\'Hivern Arctic amb Pantalla Tàctil, el teu company fiable per conquerir la temporada d\'hivern amb confiança.',
                    'meta-description' => 'descripció meta',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Títol Meta',
                    'name'             => 'Guants d\'Hivern amb Pantalla Tàctil Arctic',
                    'sort-description' => 'Mantén-te connectat i abrigat amb els nostres Guants d\'Hivern amb Pantalla Tàctil Arctic. Aquests guants no només estan elaborats amb acrílic de alta qualitat per proporcionar calidesa i durabilitat, sinó que també compten amb un disseny compatible amb pantalles tàctils. Amb un forro aïllant, punys elàstics per un ajust segur i un aspecte elegant, aquests guants són perfectes per a ús diari en condicions fredes.',
                ],

                '4' => [
                    'description'      => 'Presentem els mitjons de barreja de llana Arctic Warmth: el teu company essencial per a uns peus acollidors i còmodes durant les estacions més fredes. Elaborats amb una barreja premium de llana merino, acrílic, niló i spandex, aquests mitjons estan dissenyats per proporcionar una calidesa i comoditat incomparables. La barreja de llana garanteix que els teus peus es mantinguin calents fins i tot en les temperatures més fredes, el que els converteix en l\'elecció perfecta per a aventures hivernals o simplement per estar còmode a casa. La textura suau i acollidora dels mitjons ofereix una sensació de luxe a la teva pell. Digues adéu als peus freds mentre gaudeixes de l\'abraçada càlida proporcionada per aquests mitjons de barreja de llana. Dissenyats per ser duradors, els mitjons compten amb un reforç al taló i la punta, el que els brinda major resistència a les àrees de major desgast. Això garanteix que els teus mitjons resistiran el pas del temps, brindant comoditat i calidesa duradores. La naturalesa transpirable del material evita el sobreescalfament, permetent que els teus peus es mantinguin còmodes i secs durant tot el dia. Ja sigui que et dirigeixis a l\'aire lliure per a una caminada hivernal o et relaxis a casa, aquests mitjons ofereixen l\'equilibri perfecte entre calidesa i transpirabilitat. Versàtils i elegants, aquests mitjons de barreja de llana són adequats per a diverses ocasions. Combina\'ls amb les teves botes favorites per a un look hivernal de moda o utilitza\'ls a casa per a una comoditat absoluta. Eleva el teu armari d\'hivern i prioritza la comoditat amb els mitjons de barreja de llana Arctic Warmth. Mima els teus peus amb el luxe que es mereixen i submergeix-te en un món de comoditat que dura tota la temporada.',
                    'meta-description' => 'meta descripció',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Títol Meta',
                    'name'             => 'Mitjons de barreja de llana Arctic Warmth',
                    'sort-description' => 'Experimenta la calidesa i la comoditat incomparables dels nostres mitjons de barreja de llana Arctic Warmth. Elaborats amb una barreja de llana merino, acrílic, niló i spandex, aquests mitjons ofereixen una comoditat suprema per al clima fred. Amb un reforç al taló i la punta per a major durabilitat, aquests mitjons versàtils i elegants són perfectes per a diverses ocasions.',
                ],

                '5' => [
                    'description'      => 'Presentem el conjunt d\'accessoris d\'hivern Arctic Frost, la teva solució ideal per mantenir-te abrigat, elegant i connectat durant els dies freds d\'hivern. Aquest conjunt acuradament seleccionat reuneix quatre accessoris essencials d\'hivern per crear un conjunt harmoniós. La luxosa bufanda, teixida amb una barreja d\'acrílic i llana, no només afegeix una capa de calor, sinó que també aporta un toc d\'elegància al teu armari d\'hivern. El gorro de punt suau, elaborat amb cura, promet mantenir-te abrigat mentre afegeix un toc de moda al teu look. Però això no és tot: el nostre conjunt també inclou guants compatibles amb pantalles tàctils. Mantén-te connectat sense sacrificar la calor mentre navegues pels teus dispositius sense esforç. Ja sigui que estiguis contestant trucades, enviant missatges o capturant moments d\'hivern al teu telèfon intel·ligent, aquests guants garanteixen comoditat sense comprometre l\'estil. La textura suau i acollidora dels mitjons ofereix una sensació de luxe a la teva pell. Digues adéu als peus freds mentre gaudeixes de l\'abraçada càlida proporcionada per aquests mitjons de barreja de llana. El conjunt d\'accessoris d\'hivern Arctic Frost no es tracta només de funcionalitat; és una declaració de moda hivernal. Cada peça està dissenyada no només per protegir-te del fred, sinó també per elevar el teu estil durant la temporada gelada. Els materials escollits per a aquest conjunt prioritzen tant la durabilitat com la comoditat, assegurant que puguis gaudir del paisatge hivernal amb estil. Ja sigui que et facis un caprici o busquis el regal perfecte, el conjunt d\'accessoris d\'hivern Arctic Frost és una elecció versàtil. Delecta algú especial durant la temporada nadalenca o eleva el teu propi armari d\'hivern amb aquest conjunt elegant i funcional. Atreveix-te amb la gelada amb confiança, sabent que tens els accessoris perfectes per mantenir-te abrigat i elegant.',
                    'meta-description' => 'meta descripció',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Títol Meta',
                    'name'             => 'Conjunt d\'accessoris d\'hivern Arctic Frost',
                    'sort-description' => 'Atreveix-te amb el fred hivern amb el nostre conjunt d\'accessoris d\'hivern Arctic Frost. Aquest conjunt inclou una luxosa bufanda, un gorro acollidor, guants compatibles amb pantalles tàctils i mitjons de barreja de llana. Elegant i funcional, aquest conjunt està elaborat amb materials d\'alta qualitat, garantint tant durabilitat com comoditat. Eleva el teu armari d\'hivern o delecta algú especial amb aquesta opció de regal perfecta.',
                ],

                '6' => [
                    'description'      => 'Presentem el conjunt d\'accessoris d\'hivern Arctic Frost, la teva solució ideal per mantenir-te abrigat, elegant i connectat durant els dies freds d\'hivern. Aquest conjunt acuradament seleccionat reuneix quatre accessoris essencials d\'hivern per crear un conjunt harmoniós. La luxosa bufanda, teixida amb una barreja d\'acrílic i llana, no només afegeix una capa de calor, sinó que també aporta un toc d\'elegància al teu armari d\'hivern. El gorro de punt suau, elaborat amb cura, promet mantenir-te abrigat mentre afegeix un toc de moda al teu look. Però això no és tot: el nostre conjunt també inclou guants compatibles amb pantalles tàctils. Mantén-te connectat sense sacrificar la calor mentre navegues pels teus dispositius sense esforç. Ja sigui que estiguis contestant trucades, enviant missatges o capturant moments d\'hivern al teu telèfon intel·ligent, aquests guants garanteixen comoditat sense comprometre l\'estil. La textura suau i acollidora dels mitjons ofereix una sensació de luxe a la teva pell. Digues adéu als peus freds mentre gaudeixes de l\'abraçada càlida proporcionada per aquests mitjons de barreja de llana. El conjunt d\'accessoris d\'hivern Arctic Frost no es tracta només de funcionalitat; és una declaració de moda hivernal. Cada peça està dissenyada no només per protegir-te del fred, sinó també per elevar el teu estil durant la temporada gelada. Els materials escollits per a aquest conjunt prioritzen tant la durabilitat com la comoditat, assegurant que puguis gaudir del paisatge hivernal amb estil. Ja sigui que et facis un caprici o busquis el regal perfecte, el conjunt d\'accessoris d\'hivern Arctic Frost és una elecció versàtil. Delecta algú especial durant la temporada nadalenca o eleva el teu propi armari d\'hivern amb aquest conjunt elegant i funcional. Atreveix-te amb la gelada amb confiança, sabent que tens els accessoris perfectes per mantenir-te abrigat i elegant.',
                    'meta-description' => 'meta descripció',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Títol Meta',
                    'name'             => 'Conjunt d\'accessoris d\'hivern Arctic Frost',
                    'sort-description' => 'Atreveix-te amb el fred hivern amb el nostre conjunt d\'accessoris d\'hivern Arctic Frost. Aquest conjunt inclou una luxosa bufanda, un gorro acollidor, guants compatibles amb pantalles tàctils i mitjons de barreja de llana. Elegant i funcional, aquest conjunt està elaborat amb materials d\'alta qualitat, garantint tant durabilitat com comoditat. Eleva el teu armari d\'hivern o delecta algú especial amb aquesta opció de regal perfecta.',
                ],

                '7' => [
                    'description'      => 'Presentem la jaqueta encoixinada amb caputxa OmniHeat per a home, la teva solució ideal per mantenir-te abrigat i a la moda durant les estacions més fredes. Aquesta jaqueta està dissenyada amb durabilitat i calidesa en ment, assegurant que es converteixi en la teva companya de confiança. El disseny amb caputxa no només afegeix un toc d\'estil, sinó que també proporciona calor addicional, protegint-te dels vents freds i el clima. Les mànigues completes ofereixen cobertura completa, assegurant que et mantinguis abrigat des de l\'espatlla fins al canell. Equipada amb butxaques interiors, aquesta jaqueta encoixinada proporciona comoditat per portar els teus objectes essencials o mantenir les teves mans calentes. El farciment sintètic aïllant ofereix major calidesa, el que la fa ideal per combatre els dies i les nits fredes. Fabricada amb una resistent carcassa i folre de polièster, aquesta jaqueta està construïda per resistir els elements i perdurar en el temps. Disponible en 5 colors atractius, pots triar el que s\'adapti al teu estil i preferència. Versàtil i funcional, la jaqueta encoixinada amb caputxa OmniHeat per a home és adequada per a diverses ocasions, ja sigui que vagis a la feina, surtis de forma casual o assisteixis a un esdeveniment a l\'aire lliure. Experimenta la combinació perfecta d\'estil, comoditat i funcionalitat amb la jaqueta encoixinada amb caputxa OmniHeat per a home. Eleva el teu armari d\'hivern i mantén-te abrigat mentre gaudeixes de l\'aire lliure. Venç el fred amb estil i fes una declaració amb aquesta peça essencial.',
                    'meta-description' => 'meta descripció',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Títol Meta',
                    'name'             => 'Jaqueta encoixinada amb caputxa OmniHeat per a home',
                    'sort-description' => 'Mantén-te abrigat i a la moda amb la nostra jaqueta encoixinada amb caputxa OmniHeat per a home. Aquesta jaqueta està dissenyada per proporcionar calor màxima i compta amb butxaques interiors per a major comoditat. El material aïllant assegura que et mantinguis abrigat en clima fred. Disponible en 5 colors atractius, el que la converteix en una opció versàtil per a diverses ocasions.',
                ],

                '8' => [
                    'description'      => 'Presentem la jaqueta encoixinada amb caputxa OmniHeat per a home, la teva solució ideal per mantenir-te abrigat i a la moda durant les estacions més fredes. Aquesta jaqueta està dissenyada amb durabilitat i calidesa en ment, assegurant que es converteixi en la teva companya de confiança. El disseny amb caputxa no només afegeix un toc d\'estil, sinó que també proporciona calor addicional, protegint-te dels vents freds i el clima. Les mànigues completes ofereixen cobertura completa, assegurant que et mantinguis abrigat des de l\'espatlla fins al canell. Equipada amb butxaques interiors, aquesta jaqueta encoixinada proporciona comoditat per portar els teus objectes essencials o mantenir les teves mans calentes. El farciment sintètic aïllant ofereix major calidesa, el que la fa ideal per combatre els dies i les nits fredes. Fabricada amb una resistent carcassa i folre de polièster, aquesta jaqueta està construïda per resistir els elements i perdurar en el temps. Disponible en 5 colors atractius, pots triar el que s\'adapti al teu estil i preferència. Versàtil i funcional, la jaqueta encoixinada amb caputxa OmniHeat per a home és adequada per a diverses ocasions, ja sigui que vagis a la feina, surtis de forma casual o assisteixis a un esdeveniment a l\'aire lliure. Experimenta la combinació perfecta d\'estil, comoditat i funcionalitat amb la jaqueta encoixinada amb caputxa OmniHeat per a home. Eleva el teu armari d\'hivern i mantén-te abrigat mentre gaudeixes de l\'aire lliure. Venç el fred amb estil i fes una declaració amb aquesta peça essencial.',
                    'meta-description' => 'meta descripció',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Títol Meta',
                    'name'             => 'Jaqueta encoixinada amb caputxa OmniHeat per a home - Blau-Groc-M',
                    'sort-description' => 'Mantén-te abrigat i a la moda amb la nostra jaqueta encoixinada amb caputxa OmniHeat per a home. Aquesta jaqueta està dissenyada per proporcionar calor màxima i compta amb butxaques interiors per a major comoditat. El material aïllant assegura que et mantinguis abrigat en clima fred. Disponible en 5 colors atractius, el que la converteix en una opció versàtil per a diverses ocasions.',
                ],

                '9' => [
                    'description'      => 'Presentem la Jaqueta Encoixinada amb Caputxa OmniHeat per a home, la teva solució ideal per mantenir-te abrigat i a la moda durant les estacions més fredes. Aquesta jaqueta està dissenyada amb durabilitat i calidesa en ment, assegurant que es converteixi en la teva companya de confiança. El disseny amb caputxa no només afegeix un toc d\'estil, sinó que també proporciona calor addicional, protegint-te dels vents freds i el clima. Les mànigues llargues ofereixen cobertura completa, assegurant que et mantinguis abrigat des de l\'espatlla fins al canell. Equipada amb butxaques interiors, aquesta jaqueta encoixinada proporciona comoditat per portar els teus objectes essencials o mantenir les teves mans calentes. El farciment sintètic aïllant ofereix major calidesa, el que la fa ideal per combatre els dies i les nits fredes. Fabricada amb una resistent carcassa i folre de polièster, aquesta jaqueta està construïda per resistir els elements i perdurar en el temps. Disponible en 5 colors atractius, pots triar el que s\'adapti al teu estil i preferència. Versàtil i funcional, la Jaqueta Encoixinada amb Caputxa OmniHeat per a home és adequada per a diverses ocasions, ja sigui que vagis a la feina, surtis de forma casual o assisteixis a un esdeveniment a l\'aire lliure. Experimenta la combinació perfecta d\'estil, comoditat i funcionalitat amb la Jaqueta Encoixinada amb Caputxa OmniHeat per a home. Eleva el teu armari d\'hivern i mantén-te abrigat mentre gaudeixes de l\'aire lliure. Venç el fred amb estil i fes una declaració amb aquesta peça essencial.',
                    'meta-description' => 'descripció meta',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Títol Meta',
                    'name'             => 'Jaqueta Encoixinada amb Caputxa OmniHeat per a home - Blau-Groc-L',
                    'sort-description' => 'Mantén-te abrigat i a la moda amb la nostra Jaqueta Encoixinada amb Caputxa OmniHeat per a home. Aquesta jaqueta està dissenyada per proporcionar calor màxima i compta amb butxaques interiors per a major comoditat. El material aïllant assegura que et mantinguis abrigat en clima fred. Disponible en 5 colors atractius, el que la converteix en una opció versàtil per a diverses ocasions.',
                ],

                '10' => [
                    'description'      => 'Presentem la Jaqueta Encoixinada amb Caputxa OmniHeat per a home, la teva solució ideal per mantenir-te abrigat i a la moda durant les estacions més fredes. Aquesta jaqueta està dissenyada amb durabilitat i calidesa en ment, assegurant que es converteixi en la teva companya de confiança. El disseny amb caputxa no només afegeix un toc d\'estil, sinó que també proporciona calor addicional, protegint-te dels vents freds i el clima. Les mànigues llargues ofereixen cobertura completa, assegurant que et mantinguis abrigat des de l\'espatlla fins al canell. Equipada amb butxaques interiors, aquesta jaqueta encoixinada proporciona comoditat per portar els teus objectes essencials o mantenir les teves mans calentes. El farciment sintètic aïllant ofereix major calidesa, el que la fa ideal per combatre els dies i les nits fredes. Fabricada amb una resistent carcassa i folre de polièster, aquesta jaqueta està construïda per resistir els elements i perdurar en el temps. Disponible en 5 colors atractius, pots triar el que s\'adapti al teu estil i preferència. Versàtil i funcional, la Jaqueta Encoixinada amb Caputxa OmniHeat per a home és adequada per a diverses ocasions, ja sigui que vagis a la feina, surtis de forma casual o assisteixis a un esdeveniment a l\'aire lliure. Experimenta la combinació perfecta d\'estil, comoditat i funcionalitat amb la Jaqueta Encoixinada amb Caputxa OmniHeat per a home. Eleva el teu armari d\'hivern i mantén-te abrigat mentre gaudeixes de l\'aire lliure. Venç el fred amb estil i fes una declaració amb aquesta peça essencial.',
                    'meta-description' => 'descripció meta',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Títol Meta',
                    'name'             => 'Jaqueta Encoixinada amb Caputxa OmniHeat per a home - Blau-Verd-M',
                    'sort-description' => 'Mantén-te abrigat i a la moda amb la nostra Jaqueta Encoixinada amb Caputxa OmniHeat per a home. Aquesta jaqueta està dissenyada per proporcionar calor màxima i compta amb butxaques interiors per a major comoditat. El material aïllant assegura que et mantinguis abrigat en clima fred. Disponible en 5 colors atractius, el que la converteix en una opció versàtil per a diverses ocasions.',
                ],

                '11' => [
                    'description'      => 'Presentem la Jaqueta Encoixinada amb Caputxa OmniHeat per a home, la teva solució ideal per mantenir-te abrigat i a la moda durant les estacions més fredes. Aquesta jaqueta està dissenyada amb durabilitat i calidesa en ment, assegurant que es converteixi en la teva companya de confiança. El disseny amb caputxa no només afegeix un toc d\'estil, sinó que també proporciona calor addicional, protegint-te dels vents freds i el clima. Les mànigues llargues ofereixen cobertura completa, assegurant que et mantinguis abrigat des de l\'espatlla fins al canell. Equipada amb butxaques interiors, aquesta jaqueta encoixinada proporciona comoditat per portar els teus objectes essencials o mantenir les teves mans calentes. El farciment sintètic aïllant ofereix major calidesa, el que la fa ideal per combatre els dies i les nits fredes. Fabricada amb una resistent carcassa i folre de polièster, aquesta jaqueta està construïda per resistir els elements i perdurar en el temps. Disponible en 5 colors atractius, pots triar el que s\'adapti al teu estil i preferència. Versàtil i funcional, la Jaqueta Encoixinada amb Caputxa OmniHeat per a home és adequada per a diverses ocasions, ja sigui que vagis a la feina, surtis de forma casual o assisteixis a un esdeveniment a l\'aire lliure. Experimenta la combinació perfecta d\'estil, comoditat i funcionalitat amb la Jaqueta Encoixinada amb Caputxa OmniHeat per a home. Eleva el teu armari d\'hivern i mantén-te abrigat mentre gaudeixes de l\'aire lliure. Venç el fred amb estil i fes una declaració amb aquesta peça essencial.',
                    'meta-description' => 'descripció meta',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Títol Meta',
                    'name'             => 'Jaqueta Encoixinada amb Caputxa OmniHeat per a home - Blau-Verd-L',
                    'sort-description' => 'Mantén-te abrigat i a la moda amb la nostra Jaqueta Encoixinada amb Caputxa OmniHeat per a home. Aquesta jaqueta està dissenyada per proporcionar calor màxima i compta amb butxaques interiors per a major comoditat. El material aïllant assegura que et mantinguis abrigat en clima fred. Disponible en 5 colors atractius, el que la converteix en una opció versàtil per a diverses ocasions.',
                ],
            ],

            'product-bundle-option-translations' => [
                '1' => [
                    'label' => 'Opció de Paquet 1',
                ],

                '2' => [
                    'label' => 'Opció de Paquet 1',
                ],

                '3' => [
                    'label' => 'Opció de Paquet 2',
                ],

                '4' => [
                    'label' => 'Opció de Paquet 2',
                ],
            ],
        ],
    ],

    'installer' => [
        'index' => [
            'create-administrator' => [
                'admin'            => 'Administrador',
                'bagisto'          => 'Bagisto',
                'confirm-password' => 'Confirmar Contrasenya',
                'email'            => 'Correu Electrònic',
                'email-address'    => 'admin@example.com',
                'password'         => 'Contrasenya',
                'title'            => 'Crear Administrador',
            ],

            'environment-configuration' => [
                'algerian-dinar'              => 'Dinar Algerià (DZD)',
                'allowed-currencies'          => 'Monedes Autoritzades',
                'allowed-locales'             => 'Idiomes Autoritzats',
                'application-name'            => 'Nom de l\'Aplicació',
                'argentine-peso'              => 'Pes Argentí (ARS)',
                'australian-dollar'           => 'Dòlar Australià (AUD)',
                'bagisto'                     => 'Bagisto',
                'bangladeshi-taka'            => 'Taka de Bangladesh (BDT)',
                'bahraini-dinar'              => 'Dinar bahreïnien (BHD)',
                'brazilian-real'              => 'Real Brasiler (BRL)',
                'british-pound-sterling'      => 'Lliura Esterlina Britànica (GBP)',
                'canadian-dollar'             => 'Dòlar Canadenc (CAD)',
                'cfa-franc-bceao'             => 'Franc CFA BCEAO (XOF)',
                'cfa-franc-beac'              => 'Franc CFA BEAC (XAF)',
                'chilean-peso'                => 'Pes Xilè (CLP)',
                'chinese-yuan'                => 'Iuan Xinès (CNY)',
                'colombian-peso'              => 'Pes Colombià (COP)',
                'czech-koruna'                => 'Corona Txeca (CZK)',
                'danish-krone'                => 'Corona Danesa (DKK)',
                'database-connection'         => 'Connexió de Base de Dades',
                'database-hostname'           => 'Nom de l\'Host de la Base de Dades',
                'database-name'               => 'Nom de la Base de Dades',
                'database-password'           => 'Contrasenya de la Base de Dades',
                'database-port'               => 'Port de la Base de Dades',
                'database-prefix'             => 'Prefix de la Base de Dades',
                'database-prefix-help'        => 'La prefixació ha de tenir 4 caràcters de llargada i només pot contenir lletres, números i guions baixos.',
                'database-username'           => 'Nom d\'Usuari de la Base de Dades',
                'default-currency'            => 'Moneda Predeterminada',
                'default-locale'              => 'Idioma Predeterminat',
                'default-timezone'            => 'Zona Horària Predeterminada',
                'default-url'                 => 'URL Predeterminada',
                'default-url-link'            => 'https://localhost',
                'egyptian-pound'              => 'Lliura Egípcia (EGP)',
                'euro'                        => 'Euro (EUR)',
                'fijian-dollar'               => 'Dòlar Fiyà (FJD)',
                'hong-kong-dollar'            => 'Dòlar de Hong Kong (HKD)',
                'hungarian-forint'            => 'Florí Hongarès (HUF)',
                'indian-rupee'                => 'Rupia Índia (INR)',
                'indonesian-rupiah'           => 'Rupia Indonesa (IDR)',
                'israeli-new-shekel'          => 'Nou Xéquel Israelí (ILS)',
                'japanese-yen'                => 'Ien Japonès (JPY)',
                'jordanian-dinar'             => 'Dinar Jordà (JOD)',
                'kazakhstani-tenge'           => 'Tengue Kazakh (KZT)',
                'kuwaiti-dinar'               => 'Dinar Kuwaití (KWD)',
                'lebanese-pound'              => 'Lliura Libanesa (LBP)',
                'libyan-dinar'                => 'Dinar Libi (LYD)',
                'malaysian-ringgit'           => 'Ringgit Malaisi (MYR)',
                'mauritian-rupee'             => 'Rupia Mauriciana (MUR)',
                'mexican-peso'                => 'Pes Mexicà (MXN)',
                'moroccan-dirham'             => 'Dirham Marroquí (MAD)',
                'mysql'                       => 'Mysql',
                'nepalese-rupee'              => 'Rupia Nepalesa (NPR)',
                'new-taiwan-dollar'           => 'Nou Dòlar de Taiwan (TWD)',
                'new-zealand-dollar'          => 'Dòlar Neozelandès (NZD)',
                'nigerian-naira'              => 'Naira Nigerià (NGN)',
                'norwegian-krone'             => 'Corona Noruega (NOK)',
                'omani-rial'                  => 'Rial Omaní (OMR)',
                'pakistani-rupee'             => 'Rupia Pakistanesa (PKR)',
                'panamanian-balboa'           => 'Balboa Panameny (PAB)',
                'paraguayan-guarani'          => 'Guaraní Paraguaià (PYG)',
                'peruvian-nuevo-sol'          => 'Nou Sol Peruà (PEN)',
                'pgsql'                       => 'pgSQL',
                'philippine-peso'             => 'Pes Filipí (PHP)',
                'polish-zloty'                => 'Zloty Polonès (PLN)',
                'qatari-rial'                 => 'Rial de Qatar (QAR)',
                'romanian-leu'                => 'Leu Romanès (RON)',
                'russian-ruble'               => 'Ruble Rus (RUB)',
                'saudi-riyal'                 => 'Riyal Saudita (SAR)',
                'select-timezone'             => 'Selecciona Zona Horària',
                'singapore-dollar'            => 'Dòlar de Singapur (SGD)',
                'south-african-rand'          => 'Rand Sud-africà (ZAR)',
                'south-korean-won'            => 'Won Sud-coreà (KRW)',
                'sqlsrv'                      => 'SQLSRV',
                'sri-lankan-rupee'            => 'Rupia de Sri Lanka (LKR)',
                'swedish-krona'               => 'Corona Sueca (SEK)',
                'swiss-franc'                 => 'Franc Suís (CHF)',
                'thai-baht'                   => 'Baht Tailandès (THB)',
                'title'                       => 'Configuració de la Botiga',
                'tunisian-dinar'              => 'Dinar Tunisià (TND)',
                'turkish-lira'                => 'Lira Turca (TRY)',
                'ukrainian-hryvnia'           => 'Hrívnia Ucraïnesa (UAH)',
                'united-arab-emirates-dirham' => 'Dirham dels Emirats Àrabs Units (AED)',
                'united-states-dollar'        => 'Dòlar Estatunidenc (USD)',
                'uzbekistani-som'             => 'Som Uzbeq (UZS)',
                'venezuelan-bolívar'          => 'Bolívar Veneçolà (VEF)',
                'vietnamese-dong'             => 'Dong Vietnamita (VND)',
                'warning-message'             => 'Compte! Els ajustaments del teu idioma i moneda predeterminats del sistema són permanents i no es poden modificar després de ser establerts.',
                'zambian-kwacha'              => 'Kwacha de Zàmbia (ZMW)',
            ],

            'sample-products' => [
                'download-sample' => 'descarregar mostra',
                'no'              => 'No',
                'sample-products' => 'Productes de mostra',
                'title'           => 'Productes de mostra',
                'yes'             => 'Sí',
            ],

            'installation-processing' => [
                'bagisto'      => 'Instal·lació de Bagisto',
                'bagisto-info' => 'Creant les taules de la base de dades, això pot trigar uns moments',
                'title'        => 'Instal·lació',
            ],

            'installation-completed' => [
                'admin-panel'                => 'Panell d\'administració',
                'bagisto-forums'             => 'Fòrum de Bagisto',
                'customer-panel'             => 'Panell de clients',
                'explore-bagisto-extensions' => 'Explorar extensions de Bagisto',
                'title'                      => 'Instal·lació completada',
                'title-info'                 => 'Bagisto s\'ha instal·lat correctament al teu sistema.',
            ],

            'ready-for-installation' => [
                'create-databsae-table'   => 'Crear la taula de la base de dades',
                'install'                 => 'Instal·lació',
                'install-info'            => 'Bagisto per a la instal·lació',
                'install-info-button'     => 'Fes clic al botó de sota per',
                'populate-database-table' => 'Omplir les taules de la base de dades',
                'start-installation'      => 'Iniciar instal·lació',
                'title'                   => 'A punt per a la instal·lació',
            ],

            'start' => [
                'locale'        => 'Idioma',
                'main'          => 'Inici',
                'select-locale' => 'Selecciona l\'idioma',
                'title'         => 'La teva instal·lació de Bagisto',
                'welcome-title' => 'Benvingut a Bagisto',
            ],

            'server-requirements' => [
                'calendar'    => 'Calendari',
                'ctype'       => 'cType',
                'curl'        => 'cURL',
                'dom'         => 'DOM',
                'fileinfo'    => 'Informació del fitxer',
                'filter'      => 'Filtre',
                'gd'          => 'GD',
                'hash'        => 'Hash',
                'intl'        => 'Intl',
                'json'        => 'JSON',
                'mbstring'    => 'mbstring',
                'openssl'     => 'OpenSSL',
                'pcre'        => 'pcre',
                'pdo'         => 'pdo',
                'php'         => 'PHP',
                'php-version' => '8.1 o superior',
                'session'     => 'Sessió',
                'title'       => 'Requisits del servidor',
                'tokenizer'   => 'Tokenizer',
                'xml'         => 'XML',
            ],

            'arabic'                   => 'Àrab',
            'back'                     => 'Enrere',
            'bagisto'                  => 'Bagisto',
            'bagisto-info'             => 'Un projecte comunitari per',
            'bagisto-logo'             => 'Logotip de Bagisto',
            'bengali'                  => 'Bengalí',
            'catalan'                  => 'Català',
            'chinese'                  => 'Xinès',
            'continue'                 => 'Continuar',
            'dutch'                    => 'Holandès',
            'english'                  => 'Anglès',
            'french'                   => 'Francès',
            'german'                   => 'Alemany',
            'hebrew'                   => 'Hebreu',
            'hindi'                    => 'Hindi',
            'indonesian'               => 'Indonesi',
            'installation-description' => "La instal·lació de Bagisto generalment implica diversos passos. Aquí tens un esquema general del procés d'instal·lació per a Bagisto.",
            'installation-info'        => "Ens alegra veure't aquí!",
            'installation-title'       => 'Benvingut a la Instal·lació',
            'italian'                  => 'Italià',
            'japanese'                 => 'Japonès',
            'persian'                  => 'Persa',
            'polish'                   => 'Polonès',
            'portuguese'               => 'Portuguès brasiler',
            'russian'                  => 'Rus',
            'sinhala'                  => 'Singalès',
            'spanish'                  => 'Espanyol',
            'title'                    => 'Instal·lador de Bagisto',
            'turkish'                  => 'Turc',
            'ukrainian'                => 'Ucraïnès',
            'webkul'                   => 'Webkul',
        ],
    ],
];
