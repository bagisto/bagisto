<?php

return [
    'seeders' => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'Domyślny',
            ],

            'attribute-groups' => [
                'description'      => 'Opis',
                'general'          => 'Ogólne',
                'inventories'      => 'Zasoby',
                'meta-description' => 'Opis meta',
                'price'            => 'Cena',
                'settings'         => 'Ustawienia',
                'shipping'         => 'Wysyłka',
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
                'manage-stock'         => 'Zarządzaj zapasami',
                'meta-description'     => 'Opis meta',
                'meta-keywords'        => 'Słowa kluczowe meta',
                'meta-title'           => 'Tytuł meta',
                'name'                 => 'Nazwa',
                'new'                  => 'Nowy',
                'price'                => 'Cena',
                'product-number'       => 'Numer produktu',
                'short-description'    => 'Krótki opis',
                'size'                 => 'Rozmiar',
                'sku'                  => 'SKU',
                'special-price'        => 'Specjalna cena',
                'special-price-from'   => 'Specjalna cena od',
                'special-price-to'     => 'Specjalna cena do',
                'status'               => 'Status',
                'tax-category'         => 'Kategoria podatkowa',
                'url-key'              => 'Klucz URL',
                'visible-individually' => 'Widoczny indywidualnie',
                'weight'               => 'Waga',
                'width'                => 'Szerokość',
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

                'contact-us' => [
                    'content' => 'Treść strony "Kontakt"',
                    'title'   => 'Kontakt',
                ],

                'customer-service' => [
                    'content' => 'Treść strony obsługi klienta',
                    'title'   => 'Obsługa klienta',
                ],

                'payment-policy'   => [
                    'content' => 'Treść strony z regulaminem płatności',
                    'title'   => 'Regulamin płatności',
                ],

                'privacy-policy' => [
                    'content' => 'Treść strony z polityką prywatności',
                    'title'   => 'Polityka prywatności',
                ],

                'refund-policy' => [
                    'content' => 'Treść strony z regulaminem zwrotów',
                    'title'   => 'Regulamin zwrotów',
                ],

                'return-policy' => [
                    'content' => 'Treść strony z regulaminem zwrotów',
                    'title'   => 'Regulamin zwrotów',
                ],

                'shipping-policy' => [
                    'content' => 'Treść strony z regulaminem wysyłki',
                    'title'   => 'Regulamin wysyłki',
                ],

                'terms-conditions' => [
                    'content' => 'Treść strony z regulaminem ogólnym',
                    'title'   => 'Regulamin ogólny',
                ],

                'terms-of-use' => [
                    'content' => 'Treść strony z warunkami użytkowania',
                    'title'   => 'Warunki użytkowania',
                ],

                'whats-new' => [
                    'content' => 'Treść strony "Co nowego"',
                    'title'   => 'Co nowego',
                ],
            ],
        ],

        'core' => [
            'channels' => [
                'name'             => 'Domyślny',
                'meta-title'       => 'Sklep demonstracyjny',
                'meta-keywords'    => 'Słowa kluczowe sklepu demonstracyjnego',
                'meta-description' => 'Opis meta sklepu demonstracyjnego',
            ],

            'currencies' => [
                'AED' => 'Dirham Zjednoczonych Emiratów Arabskich',
                'ARS' => 'Peso argentyńskie',
                'AUD' => 'Dolar australijski',
                'BDT' => 'Taka bengalska',
                'BRL' => 'Real brazylijski',
                'CAD' => 'Dolar kanadyjski',
                'CHF' => 'Frank szwajcarski',
                'CLP' => 'Peso chilijskie',
                'CNY' => 'Juan chiński',
                'COP' => 'Peso kolumbijskie',
                'CZK' => 'Korona czeska',
                'DKK' => 'Korona duńska',
                'DZD' => 'Dinar algierski',
                'EGP' => 'Funt egipski',
                'EUR' => 'Euro',
                'FJD' => 'Dolar fidżyjski',
                'GBP' => 'Funt szterling',
                'HKD' => 'Dolar hongkoński',
                'HUF' => 'Forint węgierski',
                'IDR' => 'Rupia indonezyjska',
                'ILS' => 'Nowy szekel izraelski',
                'INR' => 'Rupia indyjska',
                'JOD' => 'Dinar jordański',
                'JPY' => 'Jen japoński',
                'KRW' => 'Won południowokoreański',
                'KWD' => 'Dinar kuwejcki',
                'KZT' => 'Tenge kazachskie',
                'LBP' => 'Funt libański',
                'LKR' => 'Rupia lankijska',
                'LYD' => 'Dinar libijski',
                'MAD' => 'Dirham marokański',
                'MUR' => 'Rupia maurytyjska',
                'MXN' => 'Peso meksykańskie',
                'MYR' => 'Ringgit malezyjski',
                'NGN' => 'Naira nigeryjska',
                'NOK' => 'Korona norweska',
                'NPR' => 'Rupia nepalska',
                'NZD' => 'Dolar nowozelandzki',
                'OMR' => 'Rial omański',
                'PAB' => 'Balboa panamska',
                'PEN' => 'Sol peruwiański',
                'PHP' => 'Peso filipińskie',
                'PKR' => 'Rupia pakistańska',
                'PLN' => 'Złoty polski',
                'PYG' => 'Guarani paragwajskie',
                'QAR' => 'Rial katarski',
                'RON' => 'Lej rumuński',
                'RUB' => 'Rubel rosyjski',
                'SAR' => 'Rial saudyjski',
                'SEK' => 'Korona szwedzka',
                'SGD' => 'Dolar singapurski',
                'THB' => 'Baht tajski',
                'TND' => 'Dinar tunezyjski',
                'TRY' => 'Lira turecka',
                'TWD' => 'Dolar tajwański',
                'UAH' => 'Hrywna ukraińska',
                'USD' => 'Dolar amerykański',
                'UZS' => 'Som uzbecki',
                'VEF' => 'Boliwar wenezuelski',
                'VND' => 'Dong wietnamski',
                'XAF' => 'Frank CFA BEAC',
                'XOF' => 'Frank CFA BCEAO',
                'ZAR' => 'Rand południowoafrykański',
                'ZMW' => 'Kwacha zambijska',
            ],

            'locales' => [
                'ar'    => 'Arabski',
                'bn'    => 'Bengalski',
                'de'    => 'Niemiecki',
                'en'    => 'Angielski',
                'es'    => 'Hiszpański',
                'fa'    => 'Perski',
                'fr'    => 'Francuski',
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
                'general'   => 'Ogólny',
                'guest'     => 'Gość',
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
                'all-products' => [
                    'name' => 'Wszystkie produkty',

                    'options' => [
                        'title' => 'Wszystkie produkty',
                    ],
                ],

                'bold-collections' => [
                    'content' => [
                        'btn-title'   => 'Zobacz kolekcje',
                        'description' => 'Przedstawiamy nasze nowe odważne kolekcje! Podnieś swój styl dzięki śmiałym wzorom i żywym deklaracjom. Odkryj uderzające wzory i odważne kolory, które redefiniują Twoją garderobę. Przygotuj się, by zaakceptować niezwykłość!',
                        'title'       => 'Przygotuj się na nasze nowe odważne kolekcje!',
                    ],

                    'name' => 'Odważne kolekcje',
                ],

                'categories-collections' => [
                    'name' => 'Kolekcje kategorii',
                ],

                'featured-collections' => [
                    'name' => 'Polecane kolekcje',

                    'options' => [
                        'title' => 'Polecane produkty',
                    ],
                ],

                'footer-links' => [
                    'name' => 'Linki stopki',

                    'options' => [
                        'about-us'         => 'O nas',
                        'contact-us'       => 'Skontaktuj się z nami',
                        'customer-service' => 'Obsługa klienta',
                        'payment-policy'   => 'Regulamin płatności',
                        'privacy-policy'   => 'Polityka prywatności',
                        'refund-policy'    => 'Polityka zwrotów',
                        'return-policy'    => 'Regulamin zwrotów',
                        'shipping-policy'  => 'Polityka wysyłki',
                        'terms-conditions' => 'Regulamin ogólny',
                        'terms-of-use'     => 'Warunki korzystania',
                        'whats-new'        => 'Co nowego',
                    ],
                ],

                'game-container' => [
                    'content' => [
                        'sub-title-1' => 'Nasze kolekcje',
                        'sub-title-2' => 'Nasze kolekcje',
                        'title'       => 'Gra z naszymi nowymi dodatkami!',
                    ],

                    'name' => 'Kontener gier',
                ],

                'image-carousel' => [
                    'name' => 'Karuzela obrazków',

                    'sliders' => [
                        'title' => 'Przygotuj się na nową kolekcję',
                    ],
                ],

                'new-products' => [
                    'name' => 'Nowe produkty',

                    'options' => [
                        'title' => 'Nowe produkty',
                    ],
                ],

                'offer-information' => [
                    'content' => [
                        'title' => 'Rabat do 40% na pierwsze zamówienie - KUP TERAZ',
                    ],

                    'name' => 'Informacje o ofercie',
                ],

                'services-content' => [
                    'description' => [
                        'emi-available-info'   => 'EMI bez kosztów dostępne na wszystkich głównych kartach kredytowych',
                        'free-shipping-info'   => 'Ciesz się darmową wysyłką na wszystkie zamówienia',
                        'product-replace-info' => 'Dostępna łatwa zamiana produktu!',
                        'time-support-info'    => 'Dedykowane wsparcie 24/7 za pośrednictwem czatu i e-maila',
                    ],

                    'name' => 'Zawartość usług',

                    'title' => [
                        'free-shipping'   => 'Darmowa wysyłka',
                        'product-replace' => 'Zamiana produktu',
                        'emi-available'   => 'EMI dostępne',
                        'time-support'    => 'Wsparcie 24/7',
                    ],
                ],

                'top-collections' => [
                    'content' => [
                        'sub-title-1' => 'Nasze kolekcje',
                        'sub-title-2' => 'Nasze kolekcje',
                        'sub-title-3' => 'Nasze kolekcje',
                        'sub-title-4' => 'Nasze kolekcje',
                        'sub-title-5' => 'Nasze kolekcje',
                        'sub-title-6' => 'Nasze kolekcje',
                        'title'       => 'Gra z naszymi nowymi dodatkami!',
                    ],

                    'name' => 'Najlepsze kolekcje',
                ],
            ],
        ],

        'user' => [
            'roles' => [
                'description' => 'Użytkownicy o tej roli będą miały pełny dostęp',
                'name'        => 'Administrator',
            ],

            'users' => [
                'name' => 'Przykład',
            ],
        ],
    ],

    'installer' => [
        'index' => [
            'create-administrator' => [
                'admin'            => 'Administrator',
                'bagisto'          => 'Bagisto',
                'confirm-password' => 'Potwierdź hasło',
                'email'            => 'E-mail',
                'email-address'    => 'admin@example.com',
                'password'         => 'Hasło',
                'title'            => 'Utwórz administratora',
            ],

            'environment-configuration' => [
                'algerian-dinar'              => 'Dinar Algierski (DZD)',
                'allowed-currencies'          => 'Dozwolone Waluty',
                'allowed-locales'             => 'Dozwolone Lokalizacje',
                'application-name'            => 'Nazwa Aplikacji',
                'argentine-peso'              => 'Peso Argentyńskie (ARS)',
                'australian-dollar'           => 'Dolar Australijski (AUD)',
                'bagisto'                     => 'Bagisto',
                'bangladeshi-taka'            => 'Taka Banglijska (BDT)',
                'brazilian-real'              => 'Real Brazylijski (BRL)',
                'british-pound-sterling'      => 'Funt Szterling (GBP)',
                'canadian-dollar'             => 'Dolar Kanadyjski (CAD)',
                'cfa-franc-bceao'             => 'Frank CFA BCEAO (XOF)',
                'cfa-franc-beac'              => 'Frank CFA BEAC (XAF)',
                'chilean-peso'                => 'Peso Chileńskie (CLP)',
                'chinese-yuan'                => 'Juan Chiński (CNY)',
                'colombian-peso'              => 'Peso Kolumbijskie (COP)',
                'czech-koruna'                => 'Korona Czeska (CZK)',
                'danish-krone'                => 'Korona Duńska (DKK)',
                'database-connection'         => 'Połączenie z Bazą Danych',
                'database-hostname'           => 'Nazwa Hosta Bazy Danych',
                'database-name'               => 'Nazwa Bazy Danych',
                'database-password'           => 'Hasło Bazy Danych',
                'database-port'               => 'Port Bazy Danych',
                'database-prefix'             => 'Prefiks Bazy Danych',
                'database-username'           => 'Nazwa Użytkownika Bazy Danych',
                'default-currency'            => 'Domyślna Waluta',
                'default-locale'              => 'Domyślna Lokalizacja',
                'default-timezone'            => 'Domyślna Strefa Czasowa',
                'default-url'                 => 'Domyślny URL',
                'default-url-link'            => 'https://localhost',
                'egyptian-pound'              => 'Funt Egipski (EGP)',
                'euro'                        => 'Euro (EUR)',
                'fijian-dollar'               => 'Dolar Fidżijski (FJD)',
                'hong-kong-dollar'            => 'Dolar Hongkoński (HKD)',
                'hungarian-forint'            => 'Forint Węgierski (HUF)',
                'indian-rupee'                => 'Rupia Indyjska (INR)',
                'indonesian-rupiah'           => 'Rupia Indonezyjska (IDR)',
                'israeli-new-shekel'          => 'Nowy Szekel Izraelski (ILS)',
                'japanese-yen'                => 'Jen Japoński (JPY)',
                'jordanian-dinar'             => 'Dinar Jordanijski (JOD)',
                'kazakhstani-tenge'           => 'Tengie Kazachskie (KZT)',
                'kuwaiti-dinar'               => 'Dinar Kuwejcki (KWD)',
                'lebanese-pound'              => 'Funt Libański (LBP)',
                'libyan-dinar'                => 'Dinar Libijski (LYD)',
                'malaysian-ringgit'           => 'Ringgit Malezyjski (MYR)',
                'mauritian-rupee'             => 'Rupia Mauritiuska (MUR)',
                'mexican-peso'                => 'Peso Meksykańskie (MXN)',
                'moroccan-dirham'             => 'Dirham Marokański (MAD)',
                'mysql'                       => 'Mysql',
                'nepalese-rupee'              => 'Rupia Nepalska (NPR)',
                'new-taiwan-dollar'           => 'Dolar Nowotajwański (TWD)',
                'new-zealand-dollar'          => 'Dolar Nowozelandzki (NZD)',
                'nigerian-naira'              => 'Naira Nigerijska (NGN)',
                'norwegian-krone'             => 'Korona Norweska (NOK)',
                'omani-rial'                  => 'Rial Omanu (OMR)',
                'pakistani-rupee'             => 'Rupia Pakistańska (PKR)',
                'panamanian-balboa'           => 'Balboa Panamska (PAB)',
                'paraguayan-guarani'          => 'Guarani Paragwajskie (PYG)',
                'peruvian-nuevo-sol'          => 'Nowy Sol Peruwiański (PEN)',
                'pgsql'                       => 'pgSQL',
                'philippine-peso'             => 'Peso Filipińskie (PHP)',
                'polish-zloty'                => 'Złoty Polski (PLN)',
                'qatari-rial'                 => 'Rial Katarski (QAR)',
                'romanian-leu'                => 'Lej Rumuński (RON)',
                'russian-ruble'               => 'Rubel Rosyjski (RUB)',
                'saudi-riyal'                 => 'Rial Saudyjski (SAR)',
                'select-timezone'             => 'Wybierz Strefę Czasową',
                'singapore-dollar'            => 'Dolar Singapurski (SGD)',
                'south-african-rand'          => 'Rand Południowoafrykański (ZAR)',
                'south-korean-won'            => 'Won Południowokoreański (KRW)',
                'sqlsrv'                      => 'SQLSRV',
                'sri-lankan-rupee'            => 'Rupia Sri Lanki (LKR)',
                'swedish-krona'               => 'Korona Szwedzka (SEK)',
                'swiss-franc'                 => 'Frank Szwajcarski (CHF)',
                'thai-baht'                   => 'Bat Tajlandzki (THB)',
                'title'                       => 'Konfiguracja Sklepu',
                'tunisian-dinar'              => 'Dinar Tunezyjski (TND)',
                'turkish-lira'                => 'Lira Turecka (TRY)',
                'ukrainian-hryvnia'           => 'Hrywna Ukraińska (UAH)',
                'united-arab-emirates-dirham' => 'Dirham Zjednoczonych Emiratów Arabskich (AED)',
                'united-states-dollar'        => 'Dolar Amerykański (USD)',
                'uzbekistani-som'             => 'Som Uzbekistański (UZS)',
                'venezuelan-bolívar'          => 'Boliwar Wenezuelski (VEF)',
                'vietnamese-dong'             => 'Dong Wietnamski (VND)',
                'warning-message'             => 'Uwaga! Ustawienia domyślnych języków systemowych oraz domyślnej waluty są trwałe i nie mogą być nigdy więcej zmienione.',
                'zambian-kwacha'              => 'Kwacha Zambijska (ZMW)',
            ],

            'installation-processing' => [
                'bagisto'          => 'Instalacja Bagisto',
                'bagisto-info'     => 'Tworzenie tabel w bazie danych może zająć kilka chwil',
                'title'            => 'Instalacja',
            ],

            'installation-completed' => [
                'admin-panel'                => 'Panel administratora',
                'bagisto-forums'             => 'Forum Bagisto',
                'customer-panel'             => 'Panel klienta',
                'explore-bagisto-extensions' => 'Przeglądaj rozszerzenia Bagisto',
                'title'                      => 'Instalacja zakończona',
                'title-info'                 => 'Bagisto został pomyślnie zainstalowany na Twoim systemie.',
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
                'pcre'        => 'PCRE',
                'pdo'         => 'PDO',
                'php'         => 'PHP',
                'php-version' => '8.1 of hoger',
                'session'     => 'Sessie',
                'title'       => 'Serververeisten',
                'tokenizer'   => 'Tokenizer',
                'xml'         => 'XML',
            ],

            'arabic'                   => 'Arabski',
            'back'                     => 'Wstecz',
            'bagisto'                  => 'Bagisto',
            'bagisto-info'             => 'Projekt społecznościowy',
            'bagisto-logo'             => 'Logo Bagisto',
            'bengali'                  => 'Bengalski',
            'chinese'                  => 'Chiński',
            'continue'                 => 'Kontynuuj',
            'dutch'                    => 'Holenderski',
            'english'                  => 'Angielski',
            'french'                   => 'Francuski',
            'german'                   => 'Niemiecki',
            'hebrew'                   => 'Hebrajski',
            'hindi'                    => 'Hinduski',
            'installation-description' => 'Instalacja Bagisto zazwyczaj obejmuje kilka kroków. Oto ogólny zarys procesu instalacji Bagisto:',
            'installation-info'        => 'Cieszymy się, że tu jesteś!',
            'installation-title'       => 'Witaj w instalacji',
            'italian'                  => 'Włoski',
            'japanese'                 => 'Japoński',
            'persian'                  => 'Perski',
            'polish'                   => 'Polski',
            'portuguese'               => 'Portugalski (Brazylijski)',
            'russian'                  => 'Rosyjski',
            'sinhala'                  => 'Syngaleski',
            'spanish'                  => 'Hiszpański',
            'title'                    => 'Instalator Bagisto',
            'turkish'                  => 'Turecki',
            'ukrainian'                => 'Ukraiński',
            'webkul'                   => 'Webkul',
        ],
    ],
];
