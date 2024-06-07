<?php

return [
    'seeders' => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'Predefinito',
            ],

            'attribute-groups' => [
                'description'      => 'Descrizione',
                'general'          => 'Generale',
                'inventories'      => 'Inventario',
                'meta-description' => 'Meta Descrizione',
                'price'            => 'Prezzo',
                'settings'         => 'Impostazioni',
                'shipping'         => 'Spedizione',
            ],

            'attributes' => [
                'brand'                => 'Marca',
                'color'                => 'Colore',
                'cost'                 => 'Costo',
                'description'          => 'Descrizione',
                'featured'             => 'In primo piano',
                'guest-checkout'       => 'Check-out Ospite',
                'height'               => 'Altezza',
                'length'               => 'Lunghezza',
                'manage-stock'         => 'Gestisci Scorte',
                'meta-description'     => 'Meta Descrizione',
                'meta-keywords'        => 'Meta Parole chiave',
                'meta-title'           => 'Meta Titolo',
                'name'                 => 'Nome',
                'new'                  => 'Nuovo',
                'price'                => 'Prezzo',
                'product-number'       => 'Numero del Prodotto',
                'short-description'    => 'Breve Descrizione',
                'size'                 => 'Taglia',
                'sku'                  => 'SKU',
                'special-price'        => 'Prezzo Speciale',
                'special-price-from'   => 'Prezzo Speciale Da',
                'special-price-to'     => 'Prezzo Speciale A',
                'status'               => 'Stato',
                'tax-category'         => 'Categoria Fiscale',
                'url-key'              => 'Chiave URL',
                'visible-individually' => 'Visibile Individualmente',
                'weight'               => 'Peso',
                'width'                => 'Larghezza',
            ],

            'attribute-options' => [
                'black'  => 'Nero',
                'green'  => 'Verde',
                'l'      => 'L',
                'm'      => 'M',
                'red'    => 'Rosso',
                's'      => 'S',
                'white'  => 'Bianco',
                'xl'     => 'XL',
                'yellow' => 'Giallo',
            ],
        ],

        'category' => [
            'categories' => [
                'description' => 'Descrizione della Categoria Radice',
                'name'        => 'Radice',
            ],
        ],

        'cms' => [
            'pages' => [
                'about-us' => [
                    'content' => 'Contenuto della Pagina Chi siamo',
                    'title'   => 'Chi siamo',
                ],

                'contact-us' => [
                    'content' => 'Contenuto della Pagina Contattaci',
                    'title'   => 'Contattaci',
                ],

                'customer-service' => [
                    'content' => 'Contenuto della Pagina Assistenza Clienti',
                    'title'   => 'Assistenza Clienti',
                ],

                'payment-policy'   => [
                    'content' => 'Contenuto della Pagina Politica di Pagamento',
                    'title'   => 'Politica di Pagamento',
                ],

                'privacy-policy' => [
                    'content' => 'Contenuto della Pagina Politica sulla Privacy',
                    'title'   => 'Politica sulla Privacy',
                ],

                'refund-policy' => [
                    'content' => 'Contenuto della Pagina Politica di Rimborso',
                    'title'   => 'Politica di Rimborso',
                ],

                'return-policy' => [
                    'content' => 'Contenuto della Pagina Politica di Reso',
                    'title'   => 'Politica di Reso',
                ],

                'shipping-policy' => [
                    'content' => 'Contenuto della Pagina Politica di Spedizione',
                    'title'   => 'Politica di Spedizione',
                ],

                'terms-conditions' => [
                    'content' => 'Contenuto della Pagina Termini e Condizioni',
                    'title'   => 'Termini e Condizioni',
                ],

                'terms-of-use' => [
                    'content' => 'Contenuto della Pagina Termini d\'Uso',
                    'title'   => 'Termini d\'Uso',
                ],

                'whats-new' => [
                    'content' => 'Contenuto della Pagina Novità',
                    'title'   => 'Novità',
                ],
            ],
        ],

        'core' => [
            'channels' => [
                'name'             => 'Predefinito',
                'meta-title'       => 'Negozio Dimostrativo',
                'meta-keywords'    => 'Parole chiave meta del Negozio Dimostrativo',
                'meta-description' => 'Descrizione meta del Negozio Dimostrativo',
            ],

            'currencies' => [
                'AED' => 'Dirham degli Emirati Arabi Uniti',
                'ARS' => 'Peso Argentino',
                'AUD' => 'Dollaro Australiano',
                'BDT' => 'Taka Bangladese',
                'BRL' => 'Real Brasiliano',
                'CAD' => 'Dollaro Canadese',
                'CHF' => 'Franco Svizzero',
                'CLP' => 'Peso Cileno',
                'CNY' => 'Yuan Cinese',
                'COP' => 'Peso Colombiano',
                'CZK' => 'Corona Ceca',
                'DKK' => 'Corona Danese',
                'DZD' => 'Dinaro Algerino',
                'EGP' => 'Sterlina Egiziana',
                'EUR' => 'Euro',
                'FJD' => 'Dollaro delle Figi',
                'GBP' => 'Sterlina Britannica',
                'HKD' => 'Dollaro di Hong Kong',
                'HUF' => 'Fiorino Ungherese',
                'IDR' => 'Rupia Indonesiana',
                'ILS' => 'Nuovo Shekel Israeliano',
                'INR' => 'Rupia Indiana',
                'JOD' => 'Dinaro Giordano',
                'JPY' => 'Yen Giapponese',
                'KRW' => 'Won Sudcoreano',
                'KWD' => 'Dinaro Kuwaitiano',
                'KZT' => 'Tenge Kazako',
                'LBP' => 'Sterlina Libanese',
                'LKR' => 'Rupia dello Sri Lanka',
                'LYD' => 'Dinaro Libico',
                'MAD' => 'Dirham Marocchino',
                'MUR' => 'Rupia Mauriziana',
                'MXN' => 'Peso Messicano',
                'MYR' => 'Ringgit Malese',
                'NGN' => 'Naira Nigeriana',
                'NOK' => 'Corona Norvegese',
                'NPR' => 'Rupia Nepalese',
                'NZD' => 'Dollaro Neozelandese',
                'OMR' => 'Rial Omanita',
                'PAB' => 'Balboa Panamense',
                'PEN' => 'Nuevo Sol Peruviano',
                'PHP' => 'Peso Filippino',
                'PKR' => 'Rupia Pakistana',
                'PLN' => 'Złoty Polacco',
                'PYG' => 'Guaraní Paraguaiano',
                'QAR' => 'Rial del Qatar',
                'RON' => 'Leu Rumeno',
                'RUB' => 'Rublo Russo',
                'SAR' => 'Riyal Saudita',
                'SEK' => 'Corona Svedese',
                'SGD' => 'Dollaro di Singapore',
                'THB' => 'Baht Tailandese',
                'TND' => 'Dinaro Tunisino',
                'TRY' => 'Lira Turca',
                'TWD' => 'Nuovo Dollaro di Taiwan',
                'UAH' => 'Grivnia Ucraina',
                'USD' => 'Dollaro Statunitense',
                'UZS' => 'Som Uzbeko',
                'VEF' => 'Bolívar Venezuelano',
                'VND' => 'Dong Vietnamita',
                'XAF' => 'Franco CFA BEAC',
                'XOF' => 'Franco CFA BCEAO',
                'ZAR' => 'Rand Sudafricano',
                'ZMW' => 'Kwacha Zambiano',
            ],

            'locales'    => [
                'ar'    => 'Arabo',
                'bn'    => 'Bengalese',
                'de'    => 'Tedesco',
                'en'    => 'Inglese',
                'es'    => 'Spagnolo',
                'fa'    => 'Persiano',
                'fr'    => 'Francese',
                'he'    => 'Ebraico',
                'hi_IN' => 'Hindi',
                'it'    => 'Italiano',
                'ja'    => 'Giapponese',
                'nl'    => 'Olandese',
                'pl'    => 'Polacco',
                'pt_BR' => 'Portoghese Brasiliano',
                'ru'    => 'Russo',
                'sin'   => 'Sinhala',
                'tr'    => 'Turco',
                'uk'    => 'Ucraino',
                'zh_CN' => 'Cinese',
            ],
        ],

        'customer' => [
            'customer-groups' => [
                'general'   => 'Generale',
                'guest'     => 'Ospite',
                'wholesale' => 'Ingrosso',
            ],
        ],

        'inventory' => [
            'inventory-sources' => [
                'name' => 'Predefinito',
            ],
        ],

        'shop' => [
            'theme-customizations' => [
                'all-products' => [
                    'name' => 'Tutti i Prodotti',

                    'options' => [
                        'title' => 'Tutti i Prodotti',
                    ],
                ],

                'bold-collections' => [
                    'content' => [
                        'btn-title'   => 'Visualizza Collezioni',
                        'description' => 'Presentiamo le nostre nuove Collezioni Audaci! Elevalo il tuo stile con design audaci e dichiarazioni vibranti. Esplora modelli straordinari e colori audaci che ridefiniscono il tuo guardaroba. Preparati ad abbracciare l\'straordinario!',
                        'title'       => 'Preparati per le nostre nuove Collezioni Audaci!',
                    ],

                    'name' => 'Collezioni Audaci',
                ],

                'categories-collections' => [
                    'name' => 'Collezioni per Categorie',
                ],

                'featured-collections' => [
                    'name' => 'Prodotti in Primo Piano',

                    'options' => [
                        'title' => 'Prodotti in Primo Piano',
                    ],
                ],

                'footer-links' => [
                    'name' => 'Link del Piede',

                    'options' => [
                        'about-us'         => 'Chi Siamo',
                        'contact-us'       => 'Contattaci',
                        'customer-service' => 'Servizio Clienti',
                        'payment-policy'   => 'Politica di Pagamento',
                        'privacy-policy'   => 'Informativa sulla Privacy',
                        'refund-policy'    => 'Politica di Rimborso',
                        'return-policy'    => 'Politica di Reso',
                        'shipping-policy'  => 'Politica di Spedizione',
                        'terms-conditions' => 'Termini e Condizioni',
                        'terms-of-use'     => 'Termini di Utilizzo',
                        'whats-new'        => 'Novità',
                    ],
                ],

                'game-container' => [
                    'content' => [
                        'sub-title-1' => 'Le Nostre Collezioni',
                        'sub-title-2' => 'Le Nostre Collezioni',
                        'title'       => 'Il gioco con le nostre nuove aggiunte!',
                    ],

                    'name' => 'Contenitore del Gioco',
                ],

                'image-carousel' => [
                    'name' => 'Carosello delle Immagini',

                    'sliders' => [
                        'title' => 'Preparati per la Nuova Collezione',
                    ],
                ],

                'new-products' => [
                    'name' => 'Nuovi Prodotti',

                    'options' => [
                        'title' => 'Nuovi Prodotti',
                    ],
                ],

                'offer-information' => [
                    'content' => [
                        'title' => 'Fino al 40% di SCONTO sul tuo primo ordine ACQUISTA ORA',
                    ],

                    'name' => 'Informazioni sull\'Offerta',
                ],

                'services-content' => [
                    'description' => [
                        'emi-available-info'   => 'EMI senza costi disponibile su tutte le principali carte di credito',
                        'free-shipping-info'   => 'Goditi la spedizione gratuita su tutti gli ordini',
                        'product-replace-info' => 'Sostituzione facile del prodotto disponibile!',
                        'time-support-info'    => 'Supporto dedicato 24/7 tramite chat ed e-mail',
                    ],

                    'name' => 'Contenuto dei servizi',

                    'title' => [
                        'emi-available'   => 'EMI disponibile',
                        'free-shipping'   => 'Spedizione gratuita',
                        'product-replace' => 'Sostituzione del prodotto',
                        'time-support'    => 'Supporto 24/7',
                    ],
                ],

                'top-collections' => [
                    'content' => [
                        'sub-title-1' => 'Le Nostre Collezioni',
                        'sub-title-2' => 'Le Nostre Collezioni',
                        'sub-title-3' => 'Le Nostre Collezioni',
                        'sub-title-4' => 'Le Nostre Collezioni',
                        'sub-title-5' => 'Le Nostre Collezioni',
                        'sub-title-6' => 'Le Nostre Collezioni',
                        'title'       => 'Il gioco con le nostre nuove aggiunte!',
                    ],

                    'name' => 'Collezioni Top',
                ],
            ],
        ],

        'user' => [
            'roles' => [
                'description' => 'Questo ruolo consentirà agli utenti di avere tutti gli accessi',
                'name'        => 'Amministratore',
            ],

            'users' => [
                'name' => 'Esempio',
            ],
        ],
    ],

    'installer' => [
        'index' => [
            'create-administrator' => [
                'admin'            => 'Amministratore',
                'bagisto'          => 'Bagisto',
                'confirm-password' => 'Conferma Password',
                'download-sample'  => 'Scarica Esempio',
                'email'            => 'Email',
                'email-address'    => 'admin@example.com',
                'password'         => 'Password',
                'sample-products'  => 'Prodotti di Esempio',
                'title'            => 'Crea Amministratore',
            ],

            'environment-configuration' => [
                'algerian-dinar'              => 'Dinaro algerino (DZD)',
                'allowed-currencies'          => 'Valute consentite',
                'allowed-locales'             => 'Lingue consentite',
                'application-name'            => 'Nome dell\'applicazione',
                'argentine-peso'              => 'Peso argentino (ARS)',
                'australian-dollar'           => 'Dollaro australiano (AUD)',
                'bagisto'                     => 'Bagisto',
                'bangladeshi-taka'            => 'Taka bangladese (BDT)',
                'brazilian-real'              => 'Real brasiliano (BRL)',
                'british-pound-sterling'      => 'Sterlina britannica (GBP)',
                'canadian-dollar'             => 'Dollaro canadese (CAD)',
                'cfa-franc-bceao'             => 'Franco CFA BCEAO (XOF)',
                'cfa-franc-beac'              => 'Franco CFA BEAC (XAF)',
                'chilean-peso'                => 'Peso cileno (CLP)',
                'chinese-yuan'                => 'Yuan cinese (CNY)',
                'colombian-peso'              => 'Peso colombiano (COP)',
                'czech-koruna'                => 'Corona ceca (CZK)',
                'danish-krone'                => 'Corona danese (DKK)',
                'database-connection'         => 'Connessione al database',
                'database-hostname'           => 'Nome host del database',
                'database-name'               => 'Nome del database',
                'database-password'           => 'Password del database',
                'database-port'               => 'Porta del database',
                'database-prefix'             => 'Prefisso del database',
                'database-username'           => 'Nome utente del database',
                'default-currency'            => 'Valuta predefinita',
                'default-locale'              => 'Lingua predefinita',
                'default-timezone'            => 'Fuso orario predefinito',
                'default-url'                 => 'URL predefinito',
                'default-url-link'            => 'https://localhost',
                'egyptian-pound'              => 'Sterlina egiziana (EGP)',
                'euro'                        => 'Euro (EUR)',
                'fijian-dollar'               => 'Dollaro delle Figi (FJD)',
                'hong-kong-dollar'            => 'Dollaro di Hong Kong (HKD)',
                'hungarian-forint'            => 'Fiorino ungherese (HUF)',
                'indian-rupee'                => 'Rupia indiana (INR)',
                'indonesian-rupiah'           => 'Rupia indonesiana (IDR)',
                'israeli-new-shekel'          => 'Nuovo siclo israeliano (ILS)',
                'japanese-yen'                => 'Yen giapponese (JPY)',
                'jordanian-dinar'             => 'Dinaro giordano (JOD)',
                'kazakhstani-tenge'           => 'Tenge kazako (KZT)',
                'kuwaiti-dinar'               => 'Dinaro kuwaitiano (KWD)',
                'lebanese-pound'              => 'Sterlina libanese (LBP)',
                'libyan-dinar'                => 'Dinaro libico (LYD)',
                'malaysian-ringgit'           => 'Ringgit malese (MYR)',
                'mauritian-rupee'             => 'Rupia mauriziana (MUR)',
                'mexican-peso'                => 'Peso messicano (MXN)',
                'moroccan-dirham'             => 'Dirham marocchino (MAD)',
                'mysql'                       => 'Mysql',
                'nepalese-rupee'              => 'Rupia nepalese (NPR)',
                'new-taiwan-dollar'           => 'Nuovo dollaro taiwanese (TWD)',
                'new-zealand-dollar'          => 'Dollaro neozelandese (NZD)',
                'nigerian-naira'              => 'Naira nigeriana (NGN)',
                'norwegian-krone'             => 'Corona norvegese (NOK)',
                'omani-rial'                  => 'Rial omanita (OMR)',
                'pakistani-rupee'             => 'Rupia pakistana (PKR)',
                'panamanian-balboa'           => 'Balboa panamense (PAB)',
                'paraguayan-guarani'          => 'Guaraní paraguayano (PYG)',
                'peruvian-nuevo-sol'          => 'Nuevo sol peruviano (PEN)',
                'pgsql'                       => 'pgSQL',
                'philippine-peso'             => 'Peso filippino (PHP)',
                'polish-zloty'                => 'Złoty polacco (PLN)',
                'qatari-rial'                 => 'Rial qatariota (QAR)',
                'romanian-leu'                => 'Leu rumeno (RON)',
                'russian-ruble'               => 'Rublo russo (RUB)',
                'saudi-riyal'                 => 'Riyal saudita (SAR)',
                'select-timezone'             => 'Seleziona fuso orario',
                'singapore-dollar'            => 'Dollaro di Singapore (SGD)',
                'south-african-rand'          => 'Rand sudafricano (ZAR)',
                'south-korean-won'            => 'Won sudcoreano (KRW)',
                'sqlsrv'                      => 'SQLSRV',
                'sri-lankan-rupee'            => 'Rupia dello Sri Lanka (LKR)',
                'swedish-krona'               => 'Corona svedese (SEK)',
                'swiss-franc'                 => 'Franco svizzero (CHF)',
                'thai-baht'                   => 'Baht thailandese (THB)',
                'title'                       => 'Configurazione del negozio',
                'tunisian-dinar'              => 'Dinaro tunisino (TND)',
                'turkish-lira'                => 'Lira turca (TRY)',
                'ukrainian-hryvnia'           => 'Grivnia ucraina (UAH)',
                'united-arab-emirates-dirham' => 'Dirham degli Emirati Arabi Uniti (AED)',
                'united-states-dollar'        => 'Dollaro statunitense (USD)',
                'uzbekistani-som'             => 'Som uzbeko (UZS)',
                'venezuelan-bolívar'          => 'Bolívar venezuelano (VEF)',
                'vietnamese-dong'             => 'Dong vietnamita (VND)',
                'warning-message'             => 'Attenzione! Le impostazioni per le lingue di sistema predefinite e la valuta predefinita sono permanenti e non possono essere più modificate.',
                'zambian-kwacha'              => 'Kwacha zambiano (ZMW)',
            ],

            'installation-processing' => [
                'bagisto'          => 'Installazione Bagisto',
                'bagisto-info'     => 'Creazione delle tabelle del Database, questo potrebbe richiedere qualche momento',
                'title'            => 'Installazione',
            ],

            'installation-completed' => [
                'admin-panel'                => 'Pannello di Amministrazione',
                'bagisto-forums'             => 'Forum di Bagisto',
                'customer-panel'             => 'Pannello del Cliente',
                'explore-bagisto-extensions' => 'Esplora le Estensioni di Bagisto',
                'title'                      => 'Installazione Completata',
                'title-info'                 => 'Bagisto è stato installato con successo sul tuo sistema.',
            ],

            'ready-for-installation' => [
                'create-databsae-table'   => 'Crea la tabella del database',
                'install'                 => 'Installazione',
                'install-info'            => 'Bagisto per l\'installazione',
                'install-info-button'     => 'Clicca il pulsante sottostante per',
                'populate-database-table' => 'Popola le tabelle del database',
                'start-installation'      => 'Avvia l\'installazione',
                'title'                   => 'Pronto per l\'installazione',
            ],

            'start' => [
                'locale'        => 'Località',
                'main'          => 'Inizio',
                'select-locale' => 'Seleziona la località',
                'title'         => 'La tua installazione di Bagisto',
                'welcome-title' => 'Benvenuto in Bagisto 2.0.',
            ],

            'server-requirements' => [
                'calendar'    => 'Calendario',
                'ctype'       => 'cType',
                'curl'        => 'cURL',
                'dom'         => 'dom',
                'fileinfo'    => 'fileInfo',
                'filter'      => 'Filtro',
                'gd'          => 'GD',
                'hash'        => 'Hash',
                'intl'        => 'intl',
                'json'        => 'JSON',
                'mbstring'    => 'mbstring',
                'openssl'     => 'openssl',
                'pcre'        => 'pcre',
                'pdo'         => 'pdo',
                'php'         => 'PHP',
                'php-version' => '8.1 o superiore',
                'session'     => 'sessione',
                'title'       => 'Requisiti del server',
                'tokenizer'   => 'tokenizer',
                'xml'         => 'XML',
            ],

            'arabic'                   => 'Arabo',
            'back'                     => 'Indietro',
            'bagisto'                  => 'Bagisto',
            'bagisto-info'             => 'Un progetto della comunità di',
            'bagisto-logo'             => 'Logo Bagisto',
            'bengali'                  => 'Bengalese',
            'chinese'                  => 'Cinese',
            'continue'                 => 'Continua',
            'dutch'                    => 'Olandese',
            'english'                  => 'Inglese',
            'french'                   => 'Francese',
            'german'                   => 'Tedesco',
            'hebrew'                   => 'Ebraico',
            'hindi'                    => 'Hindi',
            'installation-description' => 'L\'installazione di Bagisto coinvolge tipicamente diversi passaggi. Ecco un outline generale del processo di installazione p er Bagisto:',
            'installation-info'        => 'Siamo felici di vederti qui!',
            'installation-title'       => 'Benvenuti all\'installazione',
            'italian'                  => 'Italiano',
            'japanese'                 => 'Giapponese',
            'persian'                  => 'Persiano',
            'polish'                   => 'Polacco',
            'portuguese'               => 'Portoghese brasiliano',
            'russian'                  => 'Russo',
            'sinhala'                  => 'Singalese',
            'spanish'                  => 'Spagnolo',
            'title'                    => 'Installazione di Bagisto',
            'turkish'                  => 'Turco',
            'ukrainian'                => 'Ucraino',
            'webkul'                   => 'Webkul',
        ],
    ],
];
