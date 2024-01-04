<?php

return [
    'seeders' => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'Domyślny',
            ],

            'attribute-groups' => [
                'description'       => 'Opis',
                'general'           => 'Ogólne',
                'inventories'       => 'Zasoby',
                'meta-description'  => 'Opis meta',
                'price'             => 'Cena',
                'shipping'          => 'Wysyłka',
                'settings'          => 'Ustawienia',
            ],

            'attributes' => [
                'brand'                => 'Marka',
                'color'                => 'Kolor',
                'cost'                 => 'Koszt',
                'description'          => 'Opis',
                'featured'             => 'Wyróżniony',
                'guest-checkout'       => 'Zamówienie gości',
                'height'               => 'Wysokość',
                'length'               => 'Długość',
                'meta-title'           => 'Tytuł meta',
                'meta-keywords'        => 'Słowa kluczowe meta',
                'meta-description'     => 'Opis meta',
                'manage-stock'         => 'Zarządzaj zapasami',
                'new'                  => 'Nowy',
                'name'                 => 'Nazwa',
                'product-number'       => 'Numer produktu',
                'price'                => 'Cena',
                'sku'                  => 'SKU',
                'status'               => 'Status',
                'short-description'    => 'Krótki opis',
                'special-price'        => 'Specjalna cena',
                'special-price-from'   => 'Specjalna cena od',
                'special-price-to'     => 'Specjalna cena do',
                'size'                 => 'Rozmiar',
                'tax-category'         => 'Kategoria podatkowa',
                'url-key'              => 'Klucz URL',
                'visible-individually' => 'Widoczny indywidualnie',
                'width'                => 'Szerokość',
                'weight'               => 'Waga',
            ],

            'attribute-options' => [
                'black'  => 'Czarny',
                'green'  => 'Zielony',
                'l'      => 'L',
                'm'      => 'M',
                'red'    => 'Czerwony',
                's'      => 'S',
                'white'  => 'Biały',
                'xl'     => 'XL',
                'yellow' => 'Żółty',
            ],
        ],

        'category' => [
            'categories' => [
                'description' => 'Opis kategorii głównej',
                'name'        => 'Główna',
            ],
        ],

        'cms' => [
            'pages' => [
                'about-us' => [
                    'content' => 'Treść strony "O nas"',
                    'title'   => 'O nas',
                ],

                'refund-policy' => [
                    'content' => 'Treść strony z regulaminem zwrotów',
                    'title'   => 'Regulamin zwrotów',
                ],

                'return-policy' => [
                    'content' => 'Treść strony z regulaminem zwrotów',
                    'title'   => 'Regulamin zwrotów',
                ],

                'terms-conditions' => [
                    'content' => 'Treść strony z regulaminem ogólnym',
                    'title'   => 'Regulamin ogólny',
                ],

                'terms-of-use' => [
                    'content' => 'Treść strony z warunkami użytkowania',
                    'title'   => 'Warunki użytkowania',
                ],

                'contact-us' => [
                    'content' => 'Treść strony "Kontakt"',
                    'title'   => 'Kontakt',
                ],

                'customer-service' => [
                    'content' => 'Treść strony obsługi klienta',
                    'title'   => 'Obsługa klienta',
                ],

                'whats-new' => [
                    'content' => 'Treść strony "Co nowego"',
                    'title'   => 'Co nowego',
                ],

                'payment-policy' => [
                    'content' => 'Treść strony z regulaminem płatności',
                    'title'   => 'Regulamin płatności',
                ],

                'shipping-policy' => [
                    'content' => 'Treść strony z regulaminem wysyłki',
                    'title'   => 'Regulamin wysyłki',
                ],

                'privacy-policy' => [
                    'content' => 'Treść strony z polityką prywatności',
                    'title'   => 'Polityka prywatności',
                ],
            ],
        ],

        'core' => [
            'channels' => [
                'meta-title'       => 'Sklep demonstracyjny',
                'meta-keywords'    => 'Słowa kluczowe sklepu demonstracyjnego',
                'meta-description' => 'Opis meta sklepu demonstracyjnego',
                'name'             => 'Domyślny',
            ],

            'currencies' => [
                'CNY' => 'Juan chiński',
                'AED' => 'Dirham',
                'EUR' => 'Euro',
                'INR' => 'Rupia indyjska',
                'IRR' => 'Rial irański',
                'AFN' => 'Szekel izraelski',
                'JPY' => 'Jen japoński',
                'GBP' => 'Funt szterling',
                'RUB' => 'Rubel rosyjski',
                'SAR' => 'Rial saudyjski',
                'TRY' => 'Lira turecka',
                'USD' => 'Dolar amerykański',
                'UAH' => 'Hrywna ukraińska',
            ],

            'locales' => [
                'ar'    => 'Arabski',
                'bn'    => 'Bengalski',
                'de'    => 'Niemiecki',
                'es'    => 'Hiszpański',
                'en'    => 'Angielski',
                'fr'    => 'Francuski',
                'fa'    => 'Perski',
                'he'    => 'Hebrajski',
                'hi_IN' => 'Hindi',
                'it'    => 'Włoski',
                'ja'    => 'Japoński',
                'nl'    => 'Holenderski',
                'pl'    => 'Polski',
                'pt_BR' => 'Portugalski brazylijski',
                'ru'    => 'Rosyjski',
                'sin'   => 'Syngaleski',
                'tr'    => 'Turecki',
                'uk'    => 'Ukraiński',
                'zh_CN' => 'Chiński',
            ],
        ],

        'customer' => [
            'customer-groups' => [
                'guest'     => 'Gość',
                'general'   => 'Ogólny',
                'wholesale' => 'Hurtowy',
            ],
        ],

        'inventory' => [
            'inventory-sources' => [
                'name' => 'Domyślny',
            ],
        ],

        'shop' => [
            'theme-customizations' => [
                'image-carousel' => [
                    'name' => 'Karuzela obrazków',

                    'sliders' => [
                        'title' => 'Przygotuj się na nową kolekcję',
                    ],
                ],

                'offer-information' => [
                    'name' => 'Informacje o ofercie',

                    'content' => [
                        'title' => 'Rabat do 40% na pierwsze zamówienie - KUP TERAZ',
                    ],
                ],

                'categories-collections' => [
                    'name' => 'Kolekcje kategorii',
                ],

                'new-products' => [
                    'name' => 'Nowe produkty',

                    'options' => [
                        'title' => 'Nowe produkty',
                    ],
                ],

                'top-collections' => [
                    'name' => 'Najlepsze kolekcje',

                    'content' => [
                        'sub-title-1' => 'Nasze kolekcje',
                        'sub-title-2' => 'Nasze kolekcje',
                        'sub-title-3' => 'Nasze kolekcje',
                        'sub-title-4' => 'Nasze kolekcje',
                        'sub-title-5' => 'Nasze kolekcje',
                        'sub-title-6' => 'Nasze kolekcje',
                        'title'       => 'Gra z naszymi nowymi dodatkami!',
                    ],
                ],

                'bold-collections' => [
                    'name' => 'Odważne kolekcje',

                    'content' => [
                        'btn-title'   => 'Zobacz wszystko',
                        'description' => 'Przedstawiamy nasze nowe odważne kolekcje! Podnieś swój styl dzięki śmiałym wzorom i żywym deklaracjom. Odkryj uderzające wzory i odważne kolory, które redefiniują Twoją garderobę. Przygotuj się, by zaakceptować niezwykłość!',
                        'title'       => 'Przygotuj się na nasze nowe odważne kolekcje!',
                    ],
                ],

                'featured-collections' => [
                    'name' => 'Polecane kolekcje',

                    'options' => [
                        'title' => 'Polecane produkty',
                    ],
                ],

                'game-container' => [
                    'name' => 'Kontener gier',

                    'content' => [
                        'sub-title-1' => 'Nasze kolekcje',
                        'sub-title-2' => 'Nasze kolekcje',
                        'title'       => 'Gra z naszymi nowymi dodatkami!',
                    ],
                ],

                'all-products' => [
                    'name' => 'Wszystkie produkty',

                    'options' => [
                        'title' => 'Wszystkie produkty',
                    ],
                ],

                'footer-links' => [
                    'name' => 'Linki stopki',

                    'options' => [
                        'about-us'         => 'O nas',
                        'contact-us'       => 'Skontaktuj się z nami',
                        'customer-service' => 'Obsługa klienta',
                        'privacy-policy'   => 'Polityka prywatności',
                        'payment-policy'   => 'Regulamin płatności',
                        'return-policy'    => 'Regulamin zwrotów',
                        'refund-policy'    => 'Polityka zwrotów',
                        'shipping-policy'  => 'Polityka wysyłki',
                        'terms-of-use'     => 'Warunki korzystania',
                        'terms-conditions' => 'Regulamin ogólny',
                        'whats-new'        => 'Co nowego',
                    ],
                ],

                'services-content' => [
                    'name'  => 'Zawartość usług',

                    'title' => [
                        'free-shipping'   => 'Darmowa wysyłka',
                        'product-replace' => 'Zamiana produktu',
                        'emi-available'   => 'EMI dostępne',
                        'time-support'    => 'Wsparcie 24/7',
                    ],

                    'description' => [
                        'free-shipping-info'   => 'Ciesz się darmową wysyłką na wszystkie zamówienia',
                        'product-replace-info' => 'Dostępna łatwa zamiana produktu!',
                        'emi-available-info'   => 'EMI bez kosztów dostępne na wszystkich głównych kartach kredytowych',
                        'time-support-info'    => 'Dedykowane wsparcie 24/7 za pośrednictwem czatu i e-maila',
                    ],
                ],
            ],
        ],

        'user' => [
            'users' => [
                'name' => 'Przykład',
            ],

            'roles' => [
                'description' => 'Użytkownicy o tej roli będą miały pełny dostęp',
                'name'        => 'Administrator',
            ],
        ],
    ],

    'installer' => [
        'index' => [
            'start' => [
                'locale'        => 'Locatie',
                'main'          => 'Rozpocznij',
                'select-locale' => 'Selecteer Locatie',
                'title'         => 'Uw Bagisto-installatie',
                'welcome-title' => 'Welkom bij Bagisto 2.0.',
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
                'intl'        => 'Intl',
                'json'        => 'JSON',
                'mbstring'    => 'mbstring',
                'openssl'     => 'OpenSSL',
                'php'         => 'PHP',
                'php-version' => '8.1 of hoger',
                'pcre'        => 'PCRE',
                'pdo'         => 'PDO',
                'session'     => 'Sessie',
                'title'       => 'Serververeisten',
                'tokenizer'   => 'Tokenizer',
                'xml'         => 'XML',
            ],

            'environment-configuration' => [
                'allowed-locales'     => 'Dozwolone lokalizacje',
                'allowed-currencies'  => 'Dozwolone waluty',
                'application-name'    => 'Toepassingsnaam',
                'bagisto'             => 'Bagisto',
                'chinese-yuan'        => 'Chinese Yuan (CNY)',
                'dirham'              => 'Dirham (AED)',
                'default-url'         => 'Standaard URL',
                'default-url-link'    => 'https://localhost',
                'default-currency'    => 'Standaard Valuta',
                'default-timezone'    => 'Standaard Tijdzone',
                'default-locale'      => 'Standaard Locatie',
                'database-connection' => 'Databaseverbinding',
                'database-hostname'   => 'Database Hostnaam',
                'database-port'       => 'Database Poort',
                'database-name'       => 'Databasenaam',
                'database-username'   => 'Database Gebruikersnaam',
                'database-prefix'     => 'Database Voorvoegsel',
                'database-password'   => 'Database Wachtwoord',
                'euro'                => 'Euro (EUR)',
                'iranian'             => 'Iraanse Rial (IRR)',
                'israeli'             => 'Israëlische Sjekel (AFN)',
                'japanese-yen'        => 'Japanse Yen (JPY)',
                'mysql'               => 'Mysql',
                'pgsql'               => 'pgSQL',
                'pound'               => 'Brits Pond (GBP)',
                'rupee'               => 'Indiase Roepie (INR)',
                'russian-ruble'       => 'Russische Roebel (RUB)',
                'sqlsrv'              => 'SQLSRV',
                'saudi'               => 'Saoedi-Riyal (SAR)',
                'title'               => 'Omgevingsconfiguratie',
                'turkish-lira'        => 'Turkse Lira (TRY)',
                'usd'                 => 'Amerikaanse Dollar (USD)',
                'ukrainian-hryvnia'   => 'Oekraïense Hryvnia (UAH)',
                'warning-message'     => 'Uwaga! Ustawienia domyślnych języków systemowych oraz domyślnej waluty są stałe i nie mogą zostać zmienione.',
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

            'installation-processing' => [
                'bagisto'          => 'Instalacja Bagisto',
                'bagisto-info'     => 'Tworzenie tabel w bazie danych może zająć kilka chwil',
                'title'            => 'Instalacja',
            ],

            'create-administrator' => [
                'admin'            => 'Administrator',
                'bagisto'          => 'Bagisto',
                'confirm-password' => 'Potwierdź hasło',
                'email'            => 'E-mail',
                'email-address'    => 'admin@example.com',
                'password'         => 'Hasło',
                'title'            => 'Utwórz administratora',
            ],

            'installation-completed' => [
                'admin-panel'                => 'Panel administratora',
                'bagisto-forums'             => 'Forum Bagisto',
                'customer-panel'             => 'Panel klienta',
                'explore-bagisto-extensions' => 'Przeglądaj rozszerzenia Bagisto',
                'title'                      => 'Instalacja zakończona',
                'title-info'                 => 'Bagisto został pomyślnie zainstalowany na Twoim systemie.',
            ],

            'arabic'                   => 'Arabski',
            'bengali'                  => 'Bengalski',
            'bagisto-logo'             => 'Logo Bagisto',
            'back'                     => 'Wstecz',
            'bagisto-info'             => 'Projekt społecznościowy',
            'bagisto'                  => 'Bagisto',
            'chinese'                  => 'Chiński',
            'continue'                 => 'Kontynuuj',
            'dutch'                    => 'Holenderski',
            'english'                  => 'Angielski',
            'french'                   => 'Francuski',
            'german'                   => 'Niemiecki',
            'hebrew'                   => 'Hebrajski',
            'hindi'                    => 'Hinduski',
            'installation-title'       => 'Witaj w instalacji',
            'installation-info'        => 'Cieszymy się, że tu jesteś!',
            'installation-description' => 'Instalacja Bagisto zazwyczaj obejmuje kilka kroków. Oto ogólny zarys procesu instalacji Bagisto:',
            'italian'                  => 'Włoski',
            'japanese'                 => 'Japoński',
            'persian'                  => 'Perski',
            'polish'                   => 'Polski',
            'portuguese'               => 'Portugalski (Brazylijski)',
            'russian'                  => 'Rosyjski',
            'spanish'                  => 'Hiszpański',
            'sinhala'                  => 'Syngaleski',
            'skip'                     => 'Pomiń',
            'save-configuration'       => 'Zapisz konfigurację',
            'title'                    => 'Instalator Bagisto',
            'turkish'                  => 'Turecki',
            'ukrainian'                => 'Ukraiński',
            'webkul'                   => 'Webkul',
        ],
    ],
];
