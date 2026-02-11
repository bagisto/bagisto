<?php

return [
    'seeders' => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'Domyślny',
            ],

            'attribute-groups' => [
                'description' => 'Opis',
                'general' => 'Ogólne',
                'inventories' => 'Zasoby',
                'meta-description' => 'Opis meta',
                'price' => 'Cena',
                'rma' => 'RMA',
                'settings' => 'Ustawienia',
                'shipping' => 'Wysyłka',
            ],

            'attributes' => [
                'allow-rma' => 'Zezwalaj na RMA',
                'brand' => 'Marka',
                'color' => 'Kolor',
                'cost' => 'Koszt',
                'description' => 'Opis',
                'featured' => 'Wyróżniony',
                'guest-checkout' => 'Zamówienie gości',
                'height' => 'Wysokość',
                'length' => 'Długość',
                'manage-stock' => 'Zarządzaj zapasami',
                'meta-description' => 'Opis meta',
                'meta-keywords' => 'Słowa kluczowe meta',
                'meta-title' => 'Tytuł meta',
                'name' => 'Nazwa',
                'new' => 'Nowy',
                'price' => 'Cena',
                'product-number' => 'Numer produktu',
                'rma-rules' => 'Zasady RMA',
                'short-description' => 'Krótki opis',
                'size' => 'Rozmiar',
                'sku' => 'SKU',
                'special-price' => 'Specjalna cena',
                'special-price-from' => 'Specjalna cena od',
                'special-price-to' => 'Specjalna cena do',
                'status' => 'Status',
                'tax-category' => 'Kategoria podatkowa',
                'url-key' => 'Klucz URL',
                'visible-individually' => 'Widoczny indywidualnie',
                'weight' => 'Waga',
                'width' => 'Szerokość',
            ],

            'attribute-options' => [
                'black' => 'Czarny',
                'green' => 'Zielony',
                'l' => 'L',
                'm' => 'M',
                'red' => 'Czerwony',
                's' => 'S',
                'white' => 'Biały',
                'xl' => 'XL',
                'yellow' => 'Żółty',
            ],
        ],

        'category' => [
            'categories' => [
                'description' => 'Opis kategorii głównej',
                'name' => 'Główna',
            ],
        ],

        'cms' => [
            'pages' => [
                'about-us' => [
                    'content' => 'Treść strony "O nas"',
                    'title' => 'O nas',
                ],

                'contact-us' => [
                    'content' => 'Treść strony "Kontakt"',
                    'title' => 'Kontakt',
                ],

                'customer-service' => [
                    'content' => 'Treść strony obsługi klienta',
                    'title' => 'Obsługa klienta',
                ],

                'payment-policy' => [
                    'content' => 'Treść strony z regulaminem płatności',
                    'title' => 'Regulamin płatności',
                ],

                'privacy-policy' => [
                    'content' => 'Treść strony z polityką prywatności',
                    'title' => 'Polityka prywatności',
                ],

                'refund-policy' => [
                    'content' => 'Treść strony z regulaminem zwrotów',
                    'title' => 'Regulamin zwrotów',
                ],

                'return-policy' => [
                    'content' => 'Treść strony z regulaminem zwrotów',
                    'title' => 'Regulamin zwrotów',
                ],

                'shipping-policy' => [
                    'content' => 'Treść strony z regulaminem wysyłki',
                    'title' => 'Regulamin wysyłki',
                ],

                'terms-conditions' => [
                    'content' => 'Treść strony z regulaminem ogólnym',
                    'title' => 'Regulamin ogólny',
                ],

                'terms-of-use' => [
                    'content' => 'Treść strony z warunkami użytkowania',
                    'title' => 'Warunki użytkowania',
                ],

                'whats-new' => [
                    'content' => 'Treść strony "Co nowego"',
                    'title' => 'Co nowego',
                ],
            ],
        ],

        'core' => [
            'channels' => [
                'meta-description' => 'Opis meta sklepu demonstracyjnego',
                'meta-keywords' => 'Słowa kluczowe sklepu demonstracyjnego',
                'meta-title' => 'Sklep demonstracyjny',
                'name' => 'Domyślny',
            ],

            'currencies' => [
                'AED' => 'Dirham Zjednoczonych Emiratów Arabskich',
                'ARS' => 'Peso argentyńskie',
                'AUD' => 'Dolar australijski',
                'BDT' => 'Taka bengalska',
                'BHD' => 'Dinar bahrajnu',
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
                'ar' => 'Arabski',
                'bn' => 'Bengalski',
                'ca' => 'Kataloński',
                'de' => 'Niemiecki',
                'en' => 'Angielski',
                'es' => 'Hiszpański',
                'fa' => 'Perski',
                'fr' => 'Francuski',
                'he' => 'Hebrajski',
                'hi_IN' => 'Hindi',
                'id' => 'Indonezyjski',
                'it' => 'Włoski',
                'ja' => 'Japoński',
                'nl' => 'Holenderski',
                'pl' => 'Polski',
                'pt_BR' => 'Portugalski brazylijski',
                'ru' => 'Rosyjski',
                'sin' => 'Syngaleski',
                'tr' => 'Turecki',
                'uk' => 'Ukraiński',
                'zh_CN' => 'Chiński',
            ],
        ],

        'customer' => [
            'customer-groups' => [
                'general' => 'Ogólny',
                'guest' => 'Gość',
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
                        'btn-title' => 'Zobacz kolekcje',
                        'description' => 'Przedstawiamy nasze nowe odważne kolekcje! Podnieś swój styl dzięki śmiałym wzorom i żywym deklaracjom. Odkryj uderzające wzory i odważne kolory, które redefiniują Twoją garderobę. Przygotuj się, by zaakceptować niezwykłość!',
                        'title' => 'Przygotuj się na nasze nowe odważne kolekcje!',
                    ],

                    'name' => 'Odważne kolekcje',
                ],

                'bold-collections-2' => [
                    'content' => [
                        'btn-title' => 'Zobacz Kolekcje',
                        'description' => 'Nasze Odważne Kolekcje są tutaj, aby na nowo zdefiniować Twoją garderobę nieustraszonymi projektami i uderzającymi, żywymi kolorami. Od odważnych wzorów po mocne odcienie, to Twoja szansa, aby wyrwać się z codzienności i wkroczyć w niezwykłość.',
                        'title' => 'Uwolnij Swoją Odwagę z Naszą Nową Kolekcją!',
                    ],

                    'name' => 'Odważne Kolekcje',
                ],

                'booking-products' => [
                    'name' => 'Produkty Rezerwacyjne',

                    'options' => [
                        'title' => 'Zarezerwuj Bilety',
                    ],
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
                        'about-us' => 'O nas',
                        'contact-us' => 'Skontaktuj się z nami',
                        'customer-service' => 'Obsługa klienta',
                        'payment-policy' => 'Regulamin płatności',
                        'privacy-policy' => 'Polityka prywatności',
                        'refund-policy' => 'Polityka zwrotów',
                        'return-policy' => 'Regulamin zwrotów',
                        'shipping-policy' => 'Polityka wysyłki',
                        'terms-conditions' => 'Regulamin ogólny',
                        'terms-of-use' => 'Warunki korzystania',
                        'whats-new' => 'Co nowego',
                    ],
                ],

                'game-container' => [
                    'content' => [
                        'sub-title-1' => 'Nasze kolekcje',
                        'sub-title-2' => 'Nasze kolekcje',
                        'title' => 'Gra z naszymi nowymi dodatkami!',
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
                        'emi-available-info' => 'EMI bez kosztów dostępne na wszystkich głównych kartach kredytowych',
                        'free-shipping-info' => 'Ciesz się darmową wysyłką na wszystkie zamówienia',
                        'product-replace-info' => 'Dostępna łatwa zamiana produktu!',
                        'time-support-info' => 'Dedykowane wsparcie 24/7 za pośrednictwem czatu i e-maila',
                    ],

                    'name' => 'Zawartość usług',

                    'title' => [
                        'emi-available' => 'EMI dostępne',
                        'free-shipping' => 'Darmowa wysyłka',
                        'product-replace' => 'Zamiana produktu',
                        'time-support' => 'Wsparcie 24/7',
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
                        'title' => 'Gra z naszymi nowymi dodatkami!',
                    ],

                    'name' => 'Najlepsze kolekcje',
                ],
            ],
        ],

        'user' => [
            'roles' => [
                'description' => 'Użytkownicy o tej roli będą miały pełny dostęp',
                'name' => 'Administrator',
            ],

            'users' => [
                'name' => 'Przykład',
            ],
        ],

        'sample-categories' => [
            'category-translation' => [
                '2' => [
                    'description' => '<p>Mężczyźni</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Mężczyźni',
                    'slug' => 'mens',
                    'url-path' => 'men',
                ],

                '3' => [
                    'description' => '<p>Dzieci</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Dzieci',
                    'slug' => 'kids',
                    'url-path' => 'kids',
                ],

                '4' => [
                    'description' => '<p>Kobiety</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Kobiety',
                    'slug' => 'womens',
                    'url-path' => 'woman',
                ],

                '5' => [
                    'description' => '<p>Odzież Formalna</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Odzież Formalna',
                    'slug' => 'formal-wear-men',
                    'url-path' => 'men/formal-wear-men',
                ],

                '6' => [
                    'description' => '<p>Odzież Codzienna</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Odzież Codzienna',
                    'slug' => 'casual-wear-men',
                    'url-path' => 'men/casual-wear-men',
                ],

                '7' => [
                    'description' => '<p>Odzież Sportowa</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Odzież Sportowa',
                    'slug' => 'active-wear',
                    'url-path' => 'men/active-wear',
                ],

                '8' => [
                    'description' => '<p>Obuwie</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Obuwie',
                    'slug' => 'footwear',
                    'url-path' => 'men/footwear',
                ],

                '9' => [
                    'description' => '<p><span>Odzież Formalna</span></p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Odzież Formalna',
                    'slug' => 'formal-wear-female',
                    'url-path' => 'woman/formal-wear-female',
                ],

                '10' => [
                    'description' => '<p>Odzież Codzienna</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Odzież Codzienna',
                    'slug' => 'casual-wear-female',
                    'url-path' => 'woman/casual-wear-female',
                ],

                '11' => [
                    'description' => '<p>Odzież Sportowa</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Odzież Sportowa',
                    'slug' => 'active-wear-female',
                    'url-path' => 'woman/active-wear-female',
                ],

                '12' => [
                    'description' => '<p>Obuwie</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Obuwie',
                    'slug' => 'footwear-female',
                    'url-path' => 'woman/footwear-female',
                ],

                '13' => [
                    'description' => '<p>Odzież dla Dziewczynek</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => 'Odzież dla Dziewczynek',
                    'name' => 'Odzież dla Dziewczynek',
                    'slug' => 'girls-clothing',
                    'url-path' => 'kids/girls-clothing',
                ],

                '14' => [
                    'description' => '<p>Odzież dla Chłopców</p>',
                    'meta-description' => 'Moda dla Chłopców',
                    'meta-keywords' => '',
                    'meta-title' => 'Odzież dla Chłopców',
                    'name' => 'Odzież dla Chłopców',
                    'slug' => 'boys-clothing',
                    'url-path' => 'kids/boys-clothing',
                ],

                '15' => [
                    'description' => '<p>Obuwie dla Dziewczynek</p>',
                    'meta-description' => 'Kolekcja Modnego Obuwia dla Dziewczynek',
                    'meta-keywords' => '',
                    'meta-title' => 'Obuwie dla Dziewczynek',
                    'name' => 'Obuwie dla Dziewczynek',
                    'slug' => 'girls-footwear',
                    'url-path' => 'kids/girls-footwear',
                ],

                '16' => [
                    'description' => '<p>Obuwie dla Chłopców</p>',
                    'meta-description' => 'Kolekcja Stylowego Obuwia dla Chłopców',
                    'meta-keywords' => '',
                    'meta-title' => 'Obuwie dla Chłopców',
                    'name' => 'Obuwie dla Chłopców',
                    'slug' => 'boys-footwear',
                    'url-path' => 'kids/boys-footwear',
                ],

                '17' => [
                    'description' => '<p>Wellness</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Wellness',
                    'slug' => 'wellness',
                    'url-path' => 'wellness',
                ],

                '18' => [
                    'description' => '<p>Samouczek Jogi do Pobrania</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Samouczek Jogi do Pobrania',
                    'slug' => 'downloadable-yoga-tutorial',
                    'url-path' => 'wellness/downloadable-yoga-tutorial',
                ],

                '19' => [
                    'description' => '<p>Kolekcja E-Booków</p>',
                    'meta-description' => 'Kolekcja E-Booków',
                    'meta-keywords' => '',
                    'meta-title' => 'Kolekcja E-Booków',
                    'name' => 'E-Booki',
                    'slug' => 'e-books',
                    'url-path' => 'wellness/e-books',
                ],

                '20' => [
                    'description' => '<p>Karnet Kinowy</p>',
                    'meta-description' => 'Zanurz się w magii 10 filmów miesięcznie bez dodatkowych opłat.',
                    'meta-keywords' => '',
                    'meta-title' => 'Miesięczny Karnet Kinowy CineXperience',
                    'name' => 'Karnet Kinowy',
                    'slug' => 'movie-pass',
                    'url-path' => 'wellness/movie-pass',
                ],

                '21' => [
                    'description' => '<p>Łatwo zarządzaj i sprzedawaj swoje produkty oparte na rezerwacjach dzięki naszemu płynnemu systemowi rezerwacji.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Rezerwacje',
                    'slug' => 'bookings',
                    'url-path' => '',
                ],

                '22' => [
                    'description' => '<p>Rezerwacja wizyt umożliwia klientom planowanie terminów usług lub konsultacji z firmami lub profesjonalistami.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Rezerwacja Wizyt',
                    'slug' => 'appointment-booking',
                    'url-path' => '',
                ],

                '23' => [
                    'description' => '<p>Rezerwacja wydarzeń umożliwia osobom lub grupom rejestrację lub rezerwację miejsc na wydarzenia publiczne lub prywatne.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Rezerwacja Wydarzeń',
                    'slug' => 'event-booking',
                    'url-path' => '',
                ],

                '24' => [
                    'description' => '<p>Rezerwacja sal umożliwia osobom, organizacjom lub grupom rezerwowanie przestrzeni na różne wydarzenia.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Rezerwacje Sal',
                    'slug' => 'community-hall-bookings',
                    'url-path' => '',
                ],

                '25' => [
                    'description' => '<p>Rezerwacja stolików umożliwia klientom rezerwowanie stolików w restauracjach, kawiarniach lub lokalach gastronomicznych z wyprzedzeniem.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Rezerwacja Stolików',
                    'slug' => 'table-booking',
                    'url-path' => '',
                ],

                '26' => [
                    'description' => '<p>Rezerwacja wynajmu ułatwia rezerwację przedmiotów lub nieruchomości do tymczasowego użytku.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Rezerwacja Wynajmu',
                    'slug' => 'rental-booking',
                    'url-path' => '',
                ],

                '27' => [
                    'description' => '<p>Odkryj najnowszą elektronikę użytkową, zaprojektowaną, aby utrzymać Cię w kontakcie, produktywnym i rozrywkowym.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Elektronika',
                    'slug' => 'electronics',
                    'url-path' => '',
                ],

                '28' => [
                    'description' => '<p>Odkryj smartfony, ładowarki, etui i inne niezbędne akcesoria do pozostania w kontakcie w podróży.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Telefony i Akcesoria',
                    'slug' => 'mobile-phones-accessories',
                    'url-path' => '',
                ],

                '29' => [
                    'description' => '<p>Znajdź wydajne laptopy i przenośne tablety do pracy, nauki i rozrywki.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Laptopy i Tablety',
                    'slug' => 'laptops-tablets',
                    'url-path' => '',
                ],

                '30' => [
                    'description' => '<p>Kup słuchawki, earbudy i głośniki, aby cieszyć się krystalicznie czystym dźwiękiem.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Urządzenia Audio',
                    'slug' => 'audio-devices',
                    'url-path' => '',
                ],

                '31' => [
                    'description' => '<p>Ułatw sobie życie dzięki inteligentnemu oświetleniu, termostatom, systemom bezpieczeństwa i nie tylko.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Inteligentny Dom i Automatyka',
                    'slug' => 'smart-home-automation',
                    'url-path' => '',
                ],

                '32' => [
                    'description' => '<p>Ulepsz swoją przestrzeń życiową funkcjonalnymi i stylowymi artykułami domowymi i kuchennymi.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Artykuły Domowe',
                    'slug' => 'household',
                    'url-path' => '',
                ],

                '33' => [
                    'description' => '<p>Przeglądaj blendery, frytkownice, ekspresy do kawy i wiele więcej, aby uprościć przygotowanie posiłków.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Sprzęt Kuchenny',
                    'slug' => 'kitchen-appliances',
                    'url-path' => '',
                ],

                '34' => [
                    'description' => '<p>Odkryj zestawy do gotowania, przybory, zastawę stołową i serwisy do Twoich kulinarnych potrzeb.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Naczynia i Jadalnia',
                    'slug' => 'cookware-dining',
                    'url-path' => '',
                ],

                '35' => [
                    'description' => '<p>Dodaj komfort i urok z sofami, stołami, dekoracjami ściennymi i akcentami domowymi.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Meble i Dekoracje',
                    'slug' => 'furniture-decor',
                    'url-path' => '',
                ],

                '36' => [
                    'description' => '<p>Utrzymuj swoją przestrzeń w czystości dzięki odkurzaczom, środkom czyszczącym, miotłom i organizerom.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Artykuły Czystości',
                    'slug' => 'cleaning-supplies',
                    'url-path' => '',
                ],

                '37' => [
                    'description' => '<p>Rozpal swoją wyobraźnię lub zorganizuj swoją przestrzeń roboczą dzięki szerokiej ofercie książek i artykułów piśmienniczych.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Książki i Artykuły Piśmiennicze',
                    'slug' => 'books-stationery',
                    'url-path' => '',
                ],

                '38' => [
                    'description' => '<p>Zanurz się w bestsellerowych powieściach, biografiach, poradnikach i nie tylko.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Książki Beletrystyczne i Niebeletrystyczne',
                    'slug' => 'fiction-non-fiction-books',
                    'url-path' => '',
                ],

                '39' => [
                    'description' => '<p>Znajdź podręczniki, materiały referencyjne i przewodniki do nauki dla wszystkich grup wiekowych.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Edukacyjne i Akademickie',
                    'slug' => 'educational-academic',
                    'url-path' => '',
                ],

                '40' => [
                    'description' => '<p>Kup długopisy, notatniki, kalendarze i artykuły biurowe dla produktywności.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Artykuły Biurowe',
                    'slug' => 'office-supplies',
                    'url-path' => '',
                ],

                '41' => [
                    'description' => '<p>Odkryj farby, pędzle, szkicowniki i zestawy do rękodzieła DIY dla kreatywnych.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Materiały Artystyczne i Rękodzielnicze',
                    'slug' => 'art-craft-materials',
                    'url-path' => '',
                ],
            ],
        ],
    ],

    'installer' => [
        'middleware' => [
            'already-installed' => 'Aplikacja jest już zainstalowana.',
        ],

        'index' => [
            'create-administrator' => [
                'admin' => 'Administrator',
                'bagisto' => 'Bagisto',
                'confirm-password' => 'Potwierdź hasło',
                'email' => 'Email',
                'email-address' => 'admin@example.com',
                'password' => 'Hasło',
                'title' => 'Utwórz administratora',
            ],

            'environment-configuration' => [
                'algerian-dinar' => 'Dinar Algierski (DZD)',
                'allowed-currencies' => 'Dozwolone Waluty',
                'allowed-locales' => 'Dozwolone Lokalizacje',
                'application-name' => 'Nazwa Aplikacji',
                'argentine-peso' => 'Peso Argentyńskie (ARS)',
                'australian-dollar' => 'Dolar Australijski (AUD)',
                'bagisto' => 'Bagisto',
                'bangladeshi-taka' => 'Taka Banglijska (BDT)',
                'bahraini-dinar' => 'Dinar Bahrajnu (BHD)',
                'brazilian-real' => 'Real Brazylijski (BRL)',
                'british-pound-sterling' => 'Funt Szterling (GBP)',
                'canadian-dollar' => 'Dolar Kanadyjski (CAD)',
                'cfa-franc-bceao' => 'Frank CFA BCEAO (XOF)',
                'cfa-franc-beac' => 'Frank CFA BEAC (XAF)',
                'chilean-peso' => 'Peso Chileńskie (CLP)',
                'chinese-yuan' => 'Juan Chiński (CNY)',
                'colombian-peso' => 'Peso Kolumbijskie (COP)',
                'czech-koruna' => 'Korona Czeska (CZK)',
                'danish-krone' => 'Korona Duńska (DKK)',
                'database-connection' => 'Połączenie z Bazą Danych',
                'database-hostname' => 'Nazwa Hosta Bazy Danych',
                'database-name' => 'Nazwa Bazy Danych',
                'database-password' => 'Hasło Bazy Danych',
                'database-port' => 'Port Bazy Danych',
                'database-prefix' => 'Prefiks Bazy Danych',
                'database-prefix-help' => 'Prefiks powinien mieć długość 4 znaków i może zawierać tylko litery, cyfry i znaki podkreślenia.',
                'database-username' => 'Nazwa Użytkownika Bazy Danych',
                'default-currency' => 'Domyślna Waluta',
                'default-locale' => 'Domyślna Lokalizacja',
                'default-timezone' => 'Domyślna Strefa Czasowa',
                'default-url' => 'Domyślny URL',
                'default-url-link' => 'https://localhost',
                'egyptian-pound' => 'Funt Egipski (EGP)',
                'euro' => 'Euro (EUR)',
                'fijian-dollar' => 'Dolar Fidżijski (FJD)',
                'hong-kong-dollar' => 'Dolar Hongkoński (HKD)',
                'hungarian-forint' => 'Forint Węgierski (HUF)',
                'indian-rupee' => 'Rupia Indyjska (INR)',
                'indonesian-rupiah' => 'Rupia Indonezyjska (IDR)',
                'israeli-new-shekel' => 'Nowy Szekel Izraelski (ILS)',
                'japanese-yen' => 'Jen Japoński (JPY)',
                'jordanian-dinar' => 'Dinar Jordanijski (JOD)',
                'kazakhstani-tenge' => 'Tengie Kazachskie (KZT)',
                'kuwaiti-dinar' => 'Dinar Kuwejcki (KWD)',
                'lebanese-pound' => 'Funt Libański (LBP)',
                'libyan-dinar' => 'Dinar Libijski (LYD)',
                'malaysian-ringgit' => 'Ringgit Malezyjski (MYR)',
                'mauritian-rupee' => 'Rupia Mauritiuska (MUR)',
                'mexican-peso' => 'Peso Meksykańskie (MXN)',
                'moroccan-dirham' => 'Dirham Marokański (MAD)',
                'mysql' => 'Mysql',
                'nepalese-rupee' => 'Rupia Nepalska (NPR)',
                'new-taiwan-dollar' => 'Dolar Nowotajwański (TWD)',
                'new-zealand-dollar' => 'Dolar Nowozelandzki (NZD)',
                'nigerian-naira' => 'Naira Nigerijska (NGN)',
                'norwegian-krone' => 'Korona Norweska (NOK)',
                'omani-rial' => 'Rial Omanu (OMR)',
                'pakistani-rupee' => 'Rupia Pakistańska (PKR)',
                'panamanian-balboa' => 'Balboa Panamska (PAB)',
                'paraguayan-guarani' => 'Guarani Paragwajskie (PYG)',
                'peruvian-nuevo-sol' => 'Nowy Sol Peruwiański (PEN)',
                'pgsql' => 'pgSQL',
                'philippine-peso' => 'Peso Filipińskie (PHP)',
                'polish-zloty' => 'Złoty Polski (PLN)',
                'qatari-rial' => 'Rial Katarski (QAR)',
                'romanian-leu' => 'Lej Rumuński (RON)',
                'russian-ruble' => 'Rubel Rosyjski (RUB)',
                'saudi-riyal' => 'Rial Saudyjski (SAR)',
                'select-timezone' => 'Wybierz Strefę Czasową',
                'singapore-dollar' => 'Dolar Singapurski (SGD)',
                'south-african-rand' => 'Rand Południowoafrykański (ZAR)',
                'south-korean-won' => 'Won Południowokoreański (KRW)',
                'sqlsrv' => 'SQLSRV',
                'sri-lankan-rupee' => 'Rupia Sri Lanki (LKR)',
                'swedish-krona' => 'Korona Szwedzka (SEK)',
                'swiss-franc' => 'Frank Szwajcarski (CHF)',
                'thai-baht' => 'Bat Tajlandzki (THB)',
                'title' => 'Konfiguracja Sklepu',
                'tunisian-dinar' => 'Dinar Tunezyjski (TND)',
                'turkish-lira' => 'Lira Turecka (TRY)',
                'ukrainian-hryvnia' => 'Hrywna Ukraińska (UAH)',
                'united-arab-emirates-dirham' => 'Dirham Zjednoczonych Emiratów Arabskich (AED)',
                'united-states-dollar' => 'Dolar Amerykański (USD)',
                'uzbekistani-som' => 'Som Uzbekistański (UZS)',
                'venezuelan-bolívar' => 'Boliwar Wenezuelski (VEF)',
                'vietnamese-dong' => 'Dong Wietnamski (VND)',
                'warning-message' => 'Uwaga! Ustawienia domyślnego języka systemowego i domyślnej waluty są trwałe i nie można ich zmienić po ustawieniu.',
                'zambian-kwacha' => 'Kwacha Zambijska (ZMW)',
            ],

            'sample-products' => [
                'no' => 'Nie',
                'note' => 'Uwaga: Czas indeksowania zależy od liczby wybranych lokalizacji. Ten proces może potrwać do 2 minut.',
                'sample-products' => 'Produkty próbne',
                'title' => 'Produkty próbne',
                'yes' => 'Tak',
            ],

            'installation-processing' => [
                'bagisto' => 'Instalacja Bagisto',
                'bagisto-info' => 'Tworzenie tabel w bazie danych może zająć kilka chwil',
                'title' => 'Instalacja',
            ],

            'installation-completed' => [
                'admin-panel' => 'Panel administratora',
                'bagisto-forums' => 'Forum Bagisto',
                'customer-panel' => 'Panel klienta',
                'explore-bagisto-extensions' => 'Przeglądaj rozszerzenia Bagisto',
                'title' => 'Instalacja zakończona',
                'title-info' => 'Bagisto został pomyślnie zainstalowany na Twoim systemie.',
            ],

            'ready-for-installation' => [
                'create-database-tables' => 'Utwórz tabele bazy danych',
                'drop-existing-tables' => 'Usuń istniejące tabele',
                'install' => 'Installatie',
                'install-info' => 'Bagisto Voor Installatie',
                'install-info-button' => 'Klik op de knop hieronder om',
                'populate-database-tables' => 'Vul de databasetabellen',
                'start-installation' => 'Start Installatie',
                'title' => 'Klaar voor Installatie',
            ],

            'start' => [
                'locale' => 'Locatie',
                'main' => 'Rozpocznij',
                'select-locale' => 'Selecteer Locatie',
                'title' => 'Uw Bagisto-installatie',
                'welcome-title' => 'Welkom bij Bagisto',
            ],

            'server-requirements' => [
                'calendar' => 'Kalender',
                'ctype' => 'cType',
                'curl' => 'cURL',
                'dom' => 'DOM',
                'fileinfo' => 'Bestandsinformatie',
                'filter' => 'Filter',
                'gd' => 'GD',
                'hash' => 'Hash',
                'intl' => 'Intl',
                'json' => 'JSON',
                'mbstring' => 'mbstring',
                'openssl' => 'OpenSSL',
                'pcre' => 'PCRE',
                'pdo' => 'PDO',
                'php' => 'PHP',
                'php-version' => ':version of hoger',
                'session' => 'Sessie',
                'title' => 'Serververeisten',
                'tokenizer' => 'Tokenizer',
                'xml' => 'XML',
            ],

            'arabic' => 'Arabski',
            'back' => 'Wstecz',
            'bagisto' => 'Bagisto',
            'bagisto-info' => 'Projekt społecznościowy',
            'bagisto-logo' => 'Logo Bagisto',
            'bengali' => 'Bengalski',
            'catalan' => 'Kataloński',
            'chinese' => 'Chiński',
            'continue' => 'Kontynuuj',
            'dutch' => 'Holenderski',
            'english' => 'Angielski',
            'french' => 'Francuski',
            'german' => 'Niemiecki',
            'hebrew' => 'Hebrajski',
            'hindi' => 'Hinduski',
            'indonesian' => 'Indonezyjski',
            'installation-description' => 'Instalacja Bagisto zazwyczaj obejmuje kilka kroków. Oto ogólny zarys procesu instalacji Bagisto',
            'installation-info' => 'Cieszymy się, że tu jesteś!',
            'installation-title' => 'Witaj w instalacji',
            'italian' => 'Włoski',
            'japanese' => 'Japoński',
            'persian' => 'Perski',
            'polish' => 'Polski',
            'portuguese' => 'Portugalski (Brazylijski)',
            'russian' => 'Rosyjski',
            'sinhala' => 'Syngaleski',
            'spanish' => 'Hiszpański',
            'title' => 'Instalator Bagisto',
            'turkish' => 'Turecki',
            'ukrainian' => 'Ukraiński',
            'webkul' => 'Webkul',
        ],
    ],
];
