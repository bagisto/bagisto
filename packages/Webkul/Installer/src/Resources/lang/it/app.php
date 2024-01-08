<?php

return [
    'seeders' => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'Predefinito',
            ],

            'attribute-groups' => [
                'description'       => 'Descrizione',
                'general'           => 'Generale',
                'inventories'       => 'Inventario',
                'meta-description'  => 'Meta Descrizione',
                'price'             => 'Prezzo',
                'shipping'          => 'Spedizione',
                'settings'          => 'Impostazioni',
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
                'meta-title'           => 'Meta Titolo',
                'meta-keywords'        => 'Meta Parole chiave',
                'meta-description'     => 'Meta Descrizione',
                'manage-stock'         => 'Gestisci Scorte',
                'new'                  => 'Nuovo',
                'name'                 => 'Nome',
                'product-number'       => 'Numero del Prodotto',
                'price'                => 'Prezzo',
                'sku'                  => 'SKU',
                'status'               => 'Stato',
                'short-description'    => 'Breve Descrizione',
                'special-price'        => 'Prezzo Speciale',
                'special-price-from'   => 'Prezzo Speciale Da',
                'special-price-to'     => 'Prezzo Speciale A',
                'size'                 => 'Taglia',
                'tax-category'         => 'Categoria Fiscale',
                'url-key'              => 'Chiave URL',
                'visible-individually' => 'Visibile Individualmente',
                'width'                => 'Larghezza',
                'weight'               => 'Peso',
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

                'refund-policy' => [
                    'content' => 'Contenuto della Pagina Politica di Rimborso',
                    'title'   => 'Politica di Rimborso',
                ],

                'return-policy' => [
                    'content' => 'Contenuto della Pagina Politica di Reso',
                    'title'   => 'Politica di Reso',
                ],

                'terms-conditions' => [
                    'content' => 'Contenuto della Pagina Termini e Condizioni',
                    'title'   => 'Termini e Condizioni',
                ],

                'terms-of-use' => [
                    'content' => 'Contenuto della Pagina Termini d\'Uso',
                    'title'   => 'Termini d\'Uso',
                ],

                'contact-us' => [
                    'content' => 'Contenuto della Pagina Contattaci',
                    'title'   => 'Contattaci',
                ],

                'customer-service' => [
                    'content' => 'Contenuto della Pagina Assistenza Clienti',
                    'title'   => 'Assistenza Clienti',
                ],

                'whats-new' => [
                    'content' => 'Contenuto della Pagina Novità',
                    'title'   => 'Novità',
                ],

                'payment-policy' => [
                    'content' => 'Contenuto della Pagina Politica di Pagamento',
                    'title'   => 'Politica di Pagamento',
                ],

                'shipping-policy' => [
                    'content' => 'Contenuto della Pagina Politica di Spedizione',
                    'title'   => 'Politica di Spedizione',
                ],

                'privacy-policy' => [
                    'content' => 'Contenuto della Pagina Politica sulla Privacy',
                    'title'   => 'Politica sulla Privacy',
                ],
            ],
        ],

        'core' => [
            'channels' => [
                'meta-title'       => 'Negozio Dimostrativo',
                'meta-keywords'    => 'Parole chiave meta del Negozio Dimostrativo',
                'meta-description' => 'Descrizione meta del Negozio Dimostrativo',
                'name'             => 'Predefinito',
            ],

            'currencies' => [
                'CNY' => 'Yuan Cinese',
                'AED' => 'Dirham',
                'EUR' => 'EURO',
                'INR' => 'Rupia Indiana',
                'IRR' => 'Rial Iraniano',
                'AFN' => 'Shekel Israeliano',
                'JPY' => 'Yen Giapponese',
                'GBP' => 'Sterlina Inglese',
                'RUB' => 'Rublo Russo',
                'SAR' => 'Riyal Saudita',
                'TRY' => 'Lira Turca',
                'USD' => 'Dollaro Americano',
                'UAH' => 'Grivnia Ucraina',
            ],

            'locales' => [
                'ar'    => 'Arabo',
                'bn'    => 'Bengalese',
                'de'    => 'Tedesco',
                'es'    => 'Spagnolo',
                'en'    => 'Inglese',
                'fr'    => 'Francese',
                'fa'    => 'Persiano',
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
                'guest'     => 'Ospite',
                'general'   => 'Generale',
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
                'image-carousel' => [
                    'name'  => 'Carosello delle Immagini',

                    'sliders' => [
                        'title' => 'Preparati per la Nuova Collezione',
                    ],
                ],

                'offer-information' => [
                    'name' => 'Informazioni sull\'Offerta',

                    'content' => [
                        'title' => 'Fino al 40% di SCONTO sul tuo primo ordine ACQUISTA ORA',
                    ],
                ],

                'categories-collections' => [
                    'name' => 'Collezioni per Categorie',
                ],

                'new-products' => [
                    'name' => 'Nuovi Prodotti',

                    'options' => [
                        'title' => 'Nuovi Prodotti',
                    ],
                ],

                'top-collections' => [
                    'name' => 'Collezioni Top',

                    'content' => [
                        'sub-title-1' => 'Le Nostre Collezioni',
                        'sub-title-2' => 'Le Nostre Collezioni',
                        'sub-title-3' => 'Le Nostre Collezioni',
                        'sub-title-4' => 'Le Nostre Collezioni',
                        'sub-title-5' => 'Le Nostre Collezioni',
                        'sub-title-6' => 'Le Nostre Collezioni',
                        'title'       => 'Il gioco con le nostre nuove aggiunte!',
                    ],
                ],

                'bold-collections' => [
                    'name' => 'Collezioni Audaci',

                    'content' => [
                        'btn-title'   => 'Visualizza Tutto',
                        'description' => 'Presentiamo le nostre nuove Collezioni Audaci! Elevalo il tuo stile con design audaci e dichiarazioni vibranti. Esplora modelli straordinari e colori audaci che ridefiniscono il tuo guardaroba. Preparati ad abbracciare l\'straordinario!',
                        'title'       => 'Preparati per le nostre nuove Collezioni Audaci!',
                    ],
                ],

                'featured-collections' => [
                    'name' => 'Prodotti in Primo Piano',

                    'options' => [
                        'title' => 'Prodotti in Primo Piano',
                    ],
                ],

                'game-container' => [
                    'name' => 'Contenitore del Gioco',

                    'content' => [
                        'sub-title-1' => 'Le Nostre Collezioni',
                        'sub-title-2' => 'Le Nostre Collezioni',
                        'title'       => 'Il gioco con le nostre nuove aggiunte!',
                    ],
                ],

                'all-products' => [
                    'name' => 'Tutti i Prodotti',

                    'options' => [
                        'title' => 'Tutti i Prodotti',
                    ],
                ],

                'footer-links' => [
                    'name' => 'Link del Piede',

                    'options' => [
                        'about-us'         => 'Chi Siamo',
                        'contact-us'       => 'Contattaci',
                        'customer-service' => 'Servizio Clienti',
                        'privacy-policy'   => 'Informativa sulla Privacy',
                        'payment-policy'   => 'Politica di Pagamento',
                        'return-policy'    => 'Politica di Reso',
                        'refund-policy'    => 'Politica di Rimborso',
                        'shipping-policy'  => 'Politica di Spedizione',
                        'terms-of-use'     => 'Termini di Utilizzo',
                        'terms-conditions' => 'Termini e Condizioni',
                        'whats-new'        => 'Novità',
                    ],
                ],

                'services-content' => [
                    'name'  => 'Contenuto dei servizi',

                    'title' => [
                        'free-shipping'   => 'Spedizione gratuita',
                        'product-replace' => 'Sostituzione del prodotto',
                        'emi-available'   => 'EMI disponibile',
                        'time-support'    => 'Supporto 24/7',
                    ],

                    'description' => [
                        'free-shipping-info'   => 'Goditi la spedizione gratuita su tutti gli ordini',
                        'product-replace-info' => 'Sostituzione facile del prodotto disponibile!',
                        'emi-available-info'   => 'EMI senza costi disponibile su tutte le principali carte di credito',
                        'time-support-info'    => 'Supporto dedicato 24/7 tramite chat ed e-mail',
                    ],
                ],
            ],
        ],

        'user' => [
            'users' => [
                'name' => 'Esempio',
            ],

            'roles' => [
                'description' => 'Questo ruolo consentirà agli utenti di avere tutti gli accessi',
                'name'        => 'Amministratore',
            ],
        ],
    ],

    'installer' => [
        'index' => [
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
                'php'         => 'PHP',
                'php-version' => '8.1 o superiore',
                'pcre'        => 'pcre',
                'pdo'         => 'pdo',
                'session'     => 'sessione',
                'title'       => 'Requisiti del server',
                'tokenizer'   => 'tokenizer',
                'xml'         => 'XML',
            ],

            'environment-configuration' => [
                'allowed-locales'     => 'Lingue consentite',
                'allowed-currencies'  => 'Valute consentite',
                'application-name'    => 'Nome dell\'applicazione',
                'bagisto'             => 'Bagisto',
                'chinese-yuan'        => 'Yuan cinese (CNY)',
                'dirham'              => 'Dirham (AED)',
                'default-url'         => 'URL predefinito',
                'default-url-link'    => 'https://localhost',
                'default-currency'    => 'Valuta predefinita',
                'default-timezone'    => 'Fuso orario predefinito',
                'default-locale'      => 'Locale predefinito',
                'database-connection' => 'Connessione al database',
                'database-hostname'   => 'Nome host del database',
                'database-port'       => 'Porta del database',
                'database-name'       => 'Nome del database',
                'database-username'   => 'Nome utente del database',
                'database-prefix'     => 'Prefisso del database',
                'database-password'   => 'Password del database',
                'euro'                => 'Euro (EUR)',
                'iranian'             => 'Rial iraniano (IRR)',
                'israeli'             => 'Shekel israeliano (AFN)',
                'japanese-yen'        => 'Yen giapponese (JPY)',
                'mysql'               => 'Mysql',
                'pgsql'               => 'pgSQL',
                'pound'               => 'Sterlina britannica (GBP)',
                'rupee'               => 'Rupia indiana (INR)',
                'russian-ruble'       => 'Rublo russo (RUB)',
                'sqlsrv'              => 'SQLSRV',
                'saudi'               => 'Riyal saudita (SAR)',
                'title'               => 'Configurazione dell\'ambiente',
                'turkish-lira'        => 'Lira turca (TRY)',
                'usd'                 => 'Dollaro statunitense (USD)',
                'ukrainian-hryvnia'   => 'Grivnia ucraina (UAH)',
                'warning-message'     => 'Attenzione! Le impostazioni per le lingue di sistema predefinite e la valuta predefinita sono permanenti e non possono essere cambiate mai più.',
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

            'installation-processing' => [
                'bagisto'          => 'Installazione Bagisto',
                'bagisto-info'     => 'Creazione delle tabelle del Database, questo potrebbe richiedere qualche momento',
                'title'            => 'Installazione',
            ],

            'create-administrator' => [
                'admin'            => 'Amministratore',
                'bagisto'          => 'Bagisto',
                'confirm-password' => 'Conferma password',
                'email'            => 'Email',
                'email-address'    => 'admin@example.com',
                'password'         => 'Password',
                'title'            => 'Creazione dell\'Amministratore',
            ],

            'installation-completed' => [
                'admin-panel'                => 'Pannello di Amministrazione',
                'bagisto-forums'             => 'Forum di Bagisto',
                'customer-panel'             => 'Pannello del Cliente',
                'explore-bagisto-extensions' => 'Esplora le Estensioni di Bagisto',
                'title'                      => 'Installazione Completata',
                'title-info'                 => 'Bagisto è stato installato con successo sul tuo sistema.',
            ],

            'arabic'                   => 'Arabo',
            'bengali'                  => 'Bengalese',
            'bagisto-logo'             => 'Logo Bagisto',
            'back'                     => 'Indietro',
            'bagisto-info'             => 'Un progetto della comunità di',
            'bagisto'                  => 'Bagisto',
            'chinese'                  => 'Cinese',
            'continue'                 => 'Continua',
            'dutch'                    => 'Olandese',
            'english'                  => 'Inglese',
            'french'                   => 'Francese',
            'german'                   => 'Tedesco',
            'hebrew'                   => 'Ebraico',
            'hindi'                    => 'Hindi',
            'installation-title'       => 'Benvenuti all\'installazione',
            'installation-info'        => 'Siamo felici di vederti qui!',
            'installation-description' => 'L\'installazione di Bagisto coinvolge tipicamente diversi passaggi. Ecco un outline generale del processo di installazione per Bagisto:',
            'italian'                  => 'Italiano',
            'japanese'                 => 'Giapponese',
            'persian'                  => 'Persiano',
            'polish'                   => 'Polacco',
            'portuguese'               => 'Portoghese brasiliano',
            'russian'                  => 'Russo',
            'spanish'                  => 'Spagnolo',
            'sinhala'                  => 'Singalese',
            'skip'                     => 'Salta',
            'save-configuration'       => 'Salva configurazione',
            'title'                    => 'Installazione di Bagisto',
            'turkish'                  => 'Turco',
            'ukrainian'                => 'Ucraino',
            'webkul'                   => 'Webkul',
        ],
    ],
];
