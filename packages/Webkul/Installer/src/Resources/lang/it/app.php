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
                'ILS' => 'Shekel Israeliano',
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
                'title'       => 'Requisiti del Server',
                'tokenizer'   => 'tokenizer',
                'xml'         => 'XML',
            ],

            'environment-configuration' => [
                'application-name'    => 'Nome dell\'applicazione',
                'arabic'              => 'Arabo',
                'bagisto'             => 'Bagisto',
                'bengali'             => 'Bengalese',
                'chinese-yuan'        => 'Yuan cinese (CNY)',
                'chinese'             => 'Cinese',
                'dirham'              => 'Dirham (AED)',
                'default-url'         => 'URL predefinito',
                'default-url-link'    => 'https://localhost',
                'default-currency'    => 'Valuta predefinita',
                'default-timezone'    => 'Fuso orario predefinito',
                'default-locale'      => 'Impostazione regionale predefinita',
                'dutch'               => 'Olandese',
                'database-connection' => 'Connessione al Database',
                'database-hostname'   => 'Nome host del Database',
                'database-port'       => 'Porta del Database',
                'database-name'       => 'Nome del Database',
                'database-username'   => 'Nome utente del Database',
                'database-prefix'     => 'Prefisso del Database',
                'database-password'   => 'Password del Database',
                'euro'                => 'Euro (EUR)',
                'english'             => 'Inglese',
                'french'              => 'Francese',
                'hebrew'              => 'Ebraico',
                'hindi'               => 'Hindi',
                'iranian'             => 'Rial iraniano (IRR)',
                'israeli'             => 'Shekel israeliano (ILS)',
                'italian'             => 'Italiano',
                'japanese-yen'        => 'Yen giapponese (JPY)',
                'japanese'            => 'Giapponese',
                'mysql'               => 'Mysql',
                'pgsql'               => 'pgSQL',
                'pound'               => 'Sterlina inglese (GBP)',
                'persian'             => 'Persiano',
                'polish'              => 'Polacco',
                'portuguese'          => 'Portoghese brasiliano',
                'rupee'               => 'Rupia indiana (INR)',
                'russian-ruble'       => 'Rublo russo (RUB)',
                'russian'             => 'Russo',
                'sqlsrv'              => 'SQLSRV',
                'saudi'               => 'Riyal saudita (SAR)',
                'spanish'             => 'Spagnolo',
                'sinhala'             => 'Sinhala',
                'title'               => 'Configurazione dell\'Ambiente',
                'turkish-lira'        => 'Lira turca (TRY)',
                'turkish'             => 'Turco',
                'usd'                 => 'Dollaro USA (USD)',
                'ukrainian-hryvnia'   => 'Grivnia ucraina (UAH)',
                'ukrainian'           => 'Ucraino',
            ],

            'ready-for-installation' => [
                'create-databsae-table'   => 'Crea la tabella del Database',
                'install'                 => 'Installazione',
                'install-info'            => 'Bagisto per l\'installazione',
                'install-info-button'     => 'Fare clic sul pulsante sottostante per',
                'populate-database-table' => 'Popola le tabelle del Database',
                'start-installation'      => 'Inizia l\'installazione',
                'title'                   => 'Pronto per l\'Installazione',
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

            'email-configuration' => [
                'encryption'           => 'Crittografia',
                'enter-username'       => 'Inserire nome utente',
                'enter-password'       => 'Inserire la password',
                'outgoing-mail-server' => 'Server di posta in uscita',
                'outgoing-email'       => 'smpt.mailtrap.io',
                'password'             => 'Password',
                'store-email'          => 'Indirizzo email del Negozio',
                'enter-store-email'    => 'Inserire l\'indirizzo email del Negozio',
                'server-port'          => 'Porta del Server',
                'server-port-code'     => '3306',
                'title'                => 'Configurazione Email',
                'username'             => 'Nome utente',
            ],

            'installation-completed' => [
                'admin-panel'                => 'Pannello di Amministrazione',
                'bagisto-forums'             => 'Forum di Bagisto',
                'customer-panel'             => 'Pannello del Cliente',
                'explore-bagisto-extensions' => 'Esplora le Estensioni di Bagisto',
                'title'                      => 'Installazione Completata',
                'title-info'                 => 'Bagisto è stato installato con successo sul tuo sistema.',
            ],

            'bagisto-logo'             => 'Logo di Bagisto',
            'back'                     => 'Indietro',
            'bagisto-info'             => 'Un progetto della comunità di',
            'bagisto'                  => 'Bagisto',
            'continue'                 => 'Continua',
            'installation-title'       => 'Benvenuti all\'Installazione',
            'installation-info'        => 'Siamo felici di vederti qui!',
            'installation-description' => 'L\'installazione di Bagisto comporta tipicamente diversi passaggi. Ecco una panoramica generale del processo di installazione per Bagisto:',
            'skip'                     => 'Salta',
            'save-configuration'       => 'Salva configurazione',
            'title'                    => 'Installatore di Bagisto',
            'webkul'                   => 'Webkul',
        ],
    ],
];
