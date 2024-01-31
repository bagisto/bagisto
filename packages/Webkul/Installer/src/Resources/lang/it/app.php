<?php

return [
    'seeders'   => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'Predefinito',
            ],

            'attribute-groups'   => [
                'description'       => 'Descrizione',
                'general'           => 'Generale',
                'inventories'       => 'Inventario',
                'meta-description'  => 'Meta Descrizione',
                'price'             => 'Prezzo',
                'settings'          => 'Impostazioni',
                'shipping'          => 'Spedizione',
            ],

            'attributes'         => [
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
                'special-price-from'   => 'Prezzo Speciale Da',
                'special-price-to'     => 'Prezzo Speciale A',
                'special-price'        => 'Prezzo Speciale',
                'status'               => 'Stato',
                'tax-category'         => 'Categoria Fiscale',
                'url-key'              => 'Chiave URL',
                'visible-individually' => 'Visibile Individualmente',
                'weight'               => 'Peso',
                'width'                => 'Larghezza',
            ],

            'attribute-options'  => [
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

        'category'  => [
            'categories' => [
                'description' => 'Descrizione della Categoria Radice',
                'name'        => 'Radice',
            ],
        ],

        'cms'       => [
            'pages' => [
                'about-us'         => [
                    'content' => 'Contenuto della Pagina Chi siamo',
                    'title'   => 'Chi siamo',
                ],

                'contact-us'       => [
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

                'privacy-policy'   => [
                    'content' => 'Contenuto della Pagina Politica sulla Privacy',
                    'title'   => 'Politica sulla Privacy',
                ],

                'refund-policy'    => [
                    'content' => 'Contenuto della Pagina Politica di Rimborso',
                    'title'   => 'Politica di Rimborso',
                ],

                'return-policy'    => [
                    'content' => 'Contenuto della Pagina Politica di Reso',
                    'title'   => 'Politica di Reso',
                ],

                'shipping-policy'  => [
                    'content' => 'Contenuto della Pagina Politica di Spedizione',
                    'title'   => 'Politica di Spedizione',
                ],

                'terms-conditions' => [
                    'content' => 'Contenuto della Pagina Termini e Condizioni',
                    'title'   => 'Termini e Condizioni',
                ],

                'terms-of-use'     => [
                    'content' => 'Contenuto della Pagina Termini d\'Uso',
                    'title'   => 'Termini d\'Uso',
                ],

                'whats-new'        => [
                    'content' => 'Contenuto della Pagina Novità',
                    'title'   => 'Novità',
                ],
            ],
        ],

        'core'      => [
            'channels'   => [
                'meta-description' => 'Descrizione meta del Negozio Dimostrativo',
                'meta-keywords'    => 'Parole chiave meta del Negozio Dimostrativo',
                'meta-title'       => 'Negozio Dimostrativo',
                'name'             => 'Predefinito',
            ],

            'currencies' => [
                'AED' => 'Dirham',
                'AFN' => 'Shekel Israeliano',
                'CNY' => 'Yuan Cinese',
                'EUR' => 'EURO',
                'GBP' => 'Sterlina Inglese',
                'INR' => 'Rupia Indiana',
                'IRR' => 'Rial Iraniano',
                'JPY' => 'Yen Giapponese',
                'RUB' => 'Rublo Russo',
                'SAR' => 'Riyal Saudita',
                'TRY' => 'Lira Turca',
                'UAH' => 'Grivnia Ucraina',
                'USD' => 'Dollaro Americano',
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

        'customer'  => [
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

        'shop'      => [
            'theme-customizations' => [
                'all-products'           => [
                    'name'    => 'Tutti i Prodotti',

                    'options' => [
                        'title' => 'Tutti i Prodotti',
                    ],
                ],

                'bold-collections'       => [
                    'content' => [
                        'btn-title'   => 'Visualizza Tutto',
                        'description' => 'Presentiamo le nostre nuove Collezioni Audaci! Elevalo il tuo stile con design audaci e dichiarazioni vibranti. Esplora modelli straordinari e colori audaci che ridefiniscono il tuo guardaroba. Preparati ad abbracciare l\'straordinario!',
                        'title'       => 'Preparati per le nostre nuove Collezioni Audaci!',
                    ],

                    'name'    => 'Collezioni Audaci',
                ],

                'categories-collections' => [
                    'name' => 'Collezioni per Categorie',
                ],

                'featured-collections'   => [
                    'name'    => 'Prodotti in Primo Piano',

                    'options' => [
                        'title' => 'Prodotti in Primo Piano',
                    ],
                ],

                'footer-links'           => [
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

                'game-container'         => [
                    'content' => [
                        'sub-title-1' => 'Le Nostre Collezioni',
                        'sub-title-2' => 'Le Nostre Collezioni',
                        'title'       => 'Il gioco con le nostre nuove aggiunte!',
                    ],

                    'name'    => 'Contenitore del Gioco',
                ],

                'image-carousel'         => [
                    'name'    => 'Carosello delle Immagini',

                    'sliders' => [
                        'title' => 'Preparati per la Nuova Collezione',
                    ],
                ],

                'new-products'           => [
                    'name'    => 'Nuovi Prodotti',

                    'options' => [
                        'title' => 'Nuovi Prodotti',
                    ],
                ],

                'offer-information'      => [
                    'content' => [
                        'title' => 'Fino al 40% di SCONTO sul tuo primo ordine ACQUISTA ORA',
                    ],

                    'name'    => 'Informazioni sull\'Offerta',
                ],

                'services-content'       => [
                    'description' => [
                        'emi-available-info'   => 'EMI senza costi disponibile su tutte le principali carte di credito',
                        'free-shipping-info'   => 'Goditi la spedizione gratuita su tutti gli ordini',
                        'product-replace-info' => 'Sostituzione facile del prodotto disponibile!',
                        'time-support-info'    => 'Supporto dedicato 24/7 tramite chat ed e-mail',
                    ],

                    'name'        => 'Contenuto dei servizi',

                    'title'       => [
                        'emi-available'   => 'EMI disponibile',
                        'free-shipping'   => 'Spedizione gratuita',
                        'product-replace' => 'Sostituzione del prodotto',
                        'time-support'    => 'Supporto 24/7',
                    ],
                ],

                'top-collections'        => [
                    'content' => [
                        'sub-title-1' => 'Le Nostre Collezioni',
                        'sub-title-2' => 'Le Nostre Collezioni',
                        'sub-title-3' => 'Le Nostre Collezioni',
                        'sub-title-4' => 'Le Nostre Collezioni',
                        'sub-title-5' => 'Le Nostre Collezioni',
                        'sub-title-6' => 'Le Nostre Collezioni',
                        'title'       => 'Il gioco con le nostre nuove aggiunte!',
                    ],

                    'name'    => 'Collezioni Top',
                ],
            ],
        ],

        'user'      => [
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
            'create-administrator'      => [
                'admin'            => 'Amministratore',
                'bagisto'          => 'Bagisto',
                'confirm-password' => 'Conferma password',
                'email-address'    => 'admin@example.com',
                'email'            => 'Email',
                'password'         => 'Password',
                'title'            => 'Creazione dell\'Amministratore',
            ],

            'environment-configuration' => [
                'allowed-currencies'  => 'Valute consentite',
                'allowed-locales'     => 'Lingue consentite',
                'application-name'    => 'Nome dell\'applicazione',
                'bagisto'             => 'Bagisto',
                'chinese-yuan'        => 'Yuan cinese (CNY)',
                'database-connection' => 'Connessione al database',
                'database-hostname'   => 'Nome host del database',
                'database-name'       => 'Nome del database',
                'database-password'   => 'Password del database',
                'database-port'       => 'Porta del database',
                'database-prefix'     => 'Prefisso del database',
                'database-username'   => 'Nome utente del database',
                'default-currency'    => 'Valuta predefinita',
                'default-locale'      => 'Locale predefinito',
                'default-timezone'    => 'Fuso orario predefinito',
                'default-url-link'    => 'https://localhost',
                'default-url'         => 'URL predefinito',
                'dirham'              => 'Dirham (AED)',
                'euro'                => 'Euro (EUR)',
                'iranian'             => 'Rial iraniano (IRR)',
                'israeli'             => 'Shekel israeliano (AFN)',
                'japanese-yen'        => 'Yen giapponese (JPY)',
                'mysql'               => 'Mysql',
                'pgsql'               => 'pgSQL',
                'pound'               => 'Sterlina britannica (GBP)',
                'rupee'               => 'Rupia indiana (INR)',
                'russian-ruble'       => 'Rublo russo (RUB)',
                'saudi'               => 'Riyal saudita (SAR)',
                'select-timezone'     => 'Seleziona Fuso orario',
                'sqlsrv'              => 'SQLSRV',
                'title'               => 'Configurazione dell\'ambiente',
                'turkish-lira'        => 'Lira turca (TRY)',
                'ukrainian-hryvnia'   => 'Grivnia ucraina (UAH)',
                'usd'                 => 'Dollaro statunitense (USD)',
                'warning-message'     => 'Attenzione! Le impostazioni per le lingue di sistema predefinite e la valuta predefinita sono permanenti e non possono essere cambiate mai più.',
            ],

            'installation-processing'   => [
                'bagisto-info'     => 'Creazione delle tabelle del Database, questo potrebbe richiedere qualche momento',
                'bagisto'          => 'Installazione Bagisto',
                'title'            => 'Installazione',
            ],

            'installation-completed'    => [
                'admin-panel'                => 'Pannello di Amministrazione',
                'bagisto-forums'             => 'Forum di Bagisto',
                'customer-panel'             => 'Pannello del Cliente',
                'explore-bagisto-extensions' => 'Esplora le Estensioni di Bagisto',
                'title-info'                 => 'Bagisto è stato installato con successo sul tuo sistema.',
                'title'                      => 'Installazione Completata',
            ],

            'ready-for-installation'    => [
                'create-databsae-table'   => 'Crea la tabella del database',
                'install-info-button'     => 'Clicca il pulsante sottostante per',
                'install-info'            => 'Bagisto per l\'installazione',
                'install'                 => 'Installazione',
                'populate-database-table' => 'Popola le tabelle del database',
                'start-installation'      => 'Avvia l\'installazione',
                'title'                   => 'Pronto per l\'installazione',
            ],

            'start'                     => [
                'locale'        => 'Località',
                'main'          => 'Inizio',
                'select-locale' => 'Seleziona la località',
                'title'         => 'La tua installazione di Bagisto',
                'welcome-title' => 'Benvenuto in Bagisto 2.0.',
            ],

            'server-requirements'       => [
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
                'php-version' => '8.1 o superiore',
                'php'         => 'PHP',
                'session'     => 'sessione',
                'title'       => 'Requisiti del server',
                'tokenizer'   => 'tokenizer',
                'xml'         => 'XML',
            ],

            'arabic'                    => 'Arabo',
            'back'                      => 'Indietro',
            'bagisto-info'              => 'Un progetto della comunità di',
            'bagisto-logo'              => 'Logo Bagisto',
            'bagisto'                   => 'Bagisto',
            'bengali'                   => 'Bengalese',
            'chinese'                   => 'Cinese',
            'continue'                  => 'Continua',
            'dutch'                     => 'Olandese',
            'english'                   => 'Inglese',
            'french'                    => 'Francese',
            'german'                    => 'Tedesco',
            'hebrew'                    => 'Ebraico',
            'hindi'                     => 'Hindi',
            'installation-description'  => 'L\'installazione di Bagisto coinvolge tipicamente diversi passaggi. Ecco un outline generale del processo di installazione p er Bagisto:',
            'installation-info'         => 'Siamo felici di vederti qui!',
            'installation-title'        => 'Benvenuti all\'installazione',
            'italian'                   => 'Italiano',
            'japanese'                  => 'Giapponese',
            'persian'                   => 'Persiano',
            'polish'                    => 'Polacco',
            'portuguese'                => 'Portoghese brasiliano',
            'russian'                   => 'Russo',
            'save-configuration'        => 'Salva configurazione',
            'sinhala'                   => 'Singalese',
            'skip'                      => 'Salta',
            'spanish'                   => 'Spagnolo',
            'title'                     => 'Installazione di Bagisto',
            'turkish'                   => 'Turco',
            'ukrainian'                 => 'Ucraino',
            'webkul'                    => 'Webkul',
        ],
    ],
];
