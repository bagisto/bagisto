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

        'sample-categories' => [
            'category-translation' => [
                '2' => [
                    'description'      => 'Opis kategorii męskiej',
                    'meta-description' => 'Meta opis kategorii męskiej',
                    'meta-keywords'    => 'Meta słowa kluczowe kategorii męskiej',
                    'meta-title'       => 'Meta tytuł kategorii męskiej',
                    'name'             => 'Mężczyźni',
                    'slug'             => 'mężczyźni',
                ],

                '3' => [
                    'description'      => 'Opis kategorii odzieży zimowej',
                    'meta-description' => 'Meta opis kategorii odzieży zimowej',
                    'meta-keywords'    => 'Meta słowa kluczowe kategorii odzieży zimowej',
                    'meta-title'       => 'Meta tytuł kategorii odzieży zimowej',
                    'name'             => 'Odzież zimowa',
                    'slug'             => 'odzież zimowa',
                ],
            ],
        ],

        'sample-products' => [
            'product-flat' => [
                '1' => [
                    'description'       => 'Arctic Cozy Knit Beanie to Twoje rozwiązanie na utrzymanie ciepła, komfortu i stylu podczas chłodniejszych miesięcy. Wykonana z miękkiej i trwałej mieszanki akrylu, ta czapka zapewnia przytulne i dopasowane dopasowanie. Klasyczny design sprawia, że jest odpowiednia zarówno dla mężczyzn, jak i kobiet, oferując wszechstronny dodatek, który uzupełnia różne style. Niezależnie od tego, czy wychodzisz na casualowy dzień w mieście, czy cieszysz się na świeżym powietrzu, ta czapka dodaje nuty komfortu i ciepła do twojej stylizacji. Miękki i oddychający materiał zapewnia, że pozostajesz przytulny, nie rezygnując ze stylu. Arctic Cozy Knit Beanie to nie tylko dodatek; to oznaczenie zimowej mody. Jego prostota sprawia, że łatwo można go zestawić z różnymi strojami, co czyni go podstawą twojej zimowej garderoby. Idealny na prezent lub jako przyjemność dla siebie, ta czapka to przemyślany dodatek do każdej zimowej stylizacji. To wszechstronny dodatek, który wykracza poza funkcjonalność, dodając nutę ciepła i stylu do twojego wyglądu. Przyjmij esencję zimy z Arctic Cozy Knit Beanie. Niezależnie od tego, czy cieszysz się casualowym dniem na mieście, czy stajesz w obliczu elementów, pozwól tej czapce być twoim towarzyszem komfortu i stylu. Podnieś swoją zimową garderobę tym klasycznym dodatkiem, który doskonale łączy ciepło z ponadczasowym poczuciem mody.',
                    'meta-description'  => 'meta opis',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Meta Tytuł',
                    'name'              => 'Arctic Cozy Knit Unisex Beanie',
                    'short-description' => 'Przyjmij chłodne dni ze stylem dzięki naszej Arctic Cozy Knit Beanie. Wykonana z miękkiej i trwałej mieszanki akrylu, ta klasyczna czapka oferuje ciepło i wszechstronność. Odpowiednia zarówno dla mężczyzn, jak i kobiet, jest idealnym dodatkiem do noszenia na co dzień lub na zewnątrz. Podnieś swoją zimową garderobę lub obdaruj kogoś wyjątkowego tym niezbędnym czapką.',
                ],

                '2' => [
                    'description'       => 'Arctic Bliss Winter Scarf to nie tylko dodatek na zimę; to oznaczenie ciepła, komfortu i stylu na sezon zimowy. Wykonany z dbałością o szczegóły z luksusowej mieszanki akrylu i wełny, ten szal jest zaprojektowany, aby utrzymać cię w cieple i przytulności nawet w najzimniejszych temperaturach. Miękka i puszysta struktura nie tylko zapewnia izolację przed zimnem, ale także dodaje nutę luksusu do twojej zimowej garderoby. Projekt Arctic Bliss Winter Scarf jest zarówno stylowy, jak i wszechstronny, dzięki czemu jest idealnym dodatkiem do różnych zimowych strojów. Niezależnie od tego, czy ubierasz się na specjalną okazję, czy dodajesz elegancką warstwę do codziennego wyglądu, ten szal doskonale uzupełnia twój styl. Dodatkowo, długość szala pozwala na dostosowanie stylizacji. Owiń go dla dodatkowego ciepła, luźno go opuść dla casualowego wyglądu lub eksperymentuj z różnymi węzłami, aby wyrazić swój unikalny styl. Ta wszechstronność sprawia, że jest to niezbędny dodatek na sezon zimowy. Szukasz idealnego prezentu? Arctic Bliss Winter Scarf to idealny wybór. Niezależnie od tego, czy zaskakujesz kogoś bliskiego, czy obdarowujesz siebie, ten szal to ponadczasowy i praktyczny prezent, który będzie cieszyć przez cały zimowy sezon. Przyjmij zimę z Arctic Bliss Winter Scarf, gdzie ciepło spotyka się ze stylem w doskonałej harmonii. Podnieś swoją zimową garderobę tym niezbędnym dodatkiem, który nie tylko utrzymuje cię w cieple, ale także dodaje nutę wyrafinowania do twojej zimowej stylizacji.',
                    'meta-description'  => 'meta opis',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Meta Tytuł',
                    'name'              => 'Arctic Bliss Stylish Winter Scarf',
                    'short-description' => 'Doświadcz objęcia ciepła i stylu dzięki naszemu Arctic Bliss Winter Scarf. Wykonany z luksusowej mieszanki akrylu i wełny, ten przytulny szal zaprojektowany jest, aby utrzymać cię w cieple podczas najzimniejszych dni. Jego stylowy i wszechstronny design, połączony z dodatkowo długą długością, oferuje możliwości dostosowania stylizacji. Podnieś swoją zimową garderobę lub obdaruj kogoś wyjątkowego tym niezbędnym zimowym dodatkiem.',
                ],

                '3' => [
                    'description'       => 'Przedstawiamy Arctic Touchscreen Winter Gloves - miejsce, gdzie ciepło, styl i łączność spotykają się, aby wzmocnić twoje zimowe doświadczenie. Wykonane z wysokiej jakości akrylu, te rękawiczki są zaprojektowane, aby zapewnić wyjątkowe ciepło i trwałość. Palce kompatybilne z ekranem dotykowym pozwalają na pozostanie w kontakcie bez odsłaniania rąk na zimno. Odbieraj połączenia, wysyłaj wiadomości i nawiguj po urządzeniach bez wysiłku, jednocześnie trzymając ręce ciepłe. Izolowane wyściółki dodają dodatkowej warstwy przytulności, sprawiając, że te rękawiczki są idealnym wyborem do walki z zimnymi dniami i nocami. Niezależnie od tego, czy jesteś w drodze do pracy, załatwiasz sprawy czy cieszysz się aktywnościami na świeżym powietrzu, te rękawiczki zapewniają ciepło i ochronę, których potrzebujesz. Elastyczne mankiety zapewniają pewne dopasowanie, zapobiegając zimnym przeciągom i utrzymując rękawiczki na miejscu podczas codziennych czynności. Stylowy design dodaje nuty elegancji do twojej zimowej stylizacji, sprawiając, że te rękawiczki są zarówno modne, jak i funkcjonalne. Idealne na prezent lub jako przyjemność dla siebie, Arctic Touchscreen Winter Gloves to niezbędny dodatek dla nowoczesnej osoby. Pożegnaj niedogodność zdejmowania rękawiczek, aby korzystać z urządzeń i przyjmij płynne połączenie ciepła, stylu i łączności. Pozostań w kontakcie, pozostań ciepły i pozostań stylowy dzięki Arctic Touchscreen Winter Gloves - twojemu niezawodnemu towarzyszowi do pokonywania zimowego sezonu z pewnością siebie.',
                    'meta-description'  => 'meta opis',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Meta Tytuł',
                    'name'              => 'Arctic Touchscreen Winter Gloves',
                    'short-description' => 'Pozostań w kontakcie i ciepły dzięki naszym Arctic Touchscreen Winter Gloves. Te rękawiczki nie tylko są wykonane z wysokiej jakości akrylu dla ciepła i trwałości, ale także posiadają kompatybilny z ekranem dotykowym design. Dzięki izolowanej wyściółce, elastycznym mankietom dla pewnego dopasowania i stylowemu wyglądowi, te rękawiczki są idealne do codziennego noszenia w chłodnych warunkach.',
                ],

                '4' => [
                    'description'       => 'Przedstawiamy Arctic Warmth Wool Blend Socks - niezbędnego towarzysza dla ciepłych i wygodnych stóp podczas chłodniejszych pór roku. Wykonane z wysokiej jakości mieszanki wełny merino, akrylu, nylonu i elastanu, te skarpety zapewniają niezrównane ciepło i komfort. Mieszanka wełny sprawia, że twoje stopy pozostają ciepłe nawet w najzimniejszych temperaturach, co czyni te skarpety doskonałym wyborem na zimowe przygody lub po prostu do przytulania się w domu. Miękka i przytulna tekstura skarpet oferuje luksusowe uczucie na skórze. Pożegnaj zimne stopy, gdy cieszysz się przytulnym ciepłem, które zapewniają te skarpety z mieszanki wełny. Zaprojektowane z myślą o trwałości, skarpety posiadają wzmocnioną piętę i palce, dodając dodatkową wytrzymałość w miejscach narażonych na zużycie. Zapewnia to, że twoje skarpety przetrwają próbę czasu, zapewniając długotrwały komfort i przytulność. Oddychający materiał zapobiega przegrzewaniu, pozwalając stopom pozostać komfortowym i suchym przez cały dzień. Bez względu na to, czy udajesz się na zimowy spacer czy odpoczywasz w domu, te skarpety oferują doskonałą równowagę między ciepłem a oddychalnością. Uniwersalne i stylowe, te skarpety z mieszanki wełny nadają się do różnych okazji. Połącz je ze swoimi ulubionymi butami, aby stworzyć modny zimowy look lub nosić w domu dla maksymalnego komfortu. Podnieś swoją zimową garderobę i postaw na komfort z Arctic Warmth Wool Blend Socks. Obdarz swoje stopy luksusem, na który zasługują, i wkrocz w świat przytulności, która trwa przez cały sezon.',
                    'meta-description'  => 'meta description',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Meta Title',
                    'name'              => 'Arctic Warmth Wool Blend Socks',
                    'short-description' => 'Poczuj niezrównane ciepło i komfort naszych Arctic Warmth Wool Blend Socks. Wykonane z mieszanki wełny merino, akrylu, nylonu i elastanu, te skarpety oferują ostateczne przytulenie w chłodną pogodę. Z wzmocnioną piętą i palcami dla trwałości, te wszechstronne i stylowe skarpety są idealne na różne okazje.',
                ],

                '5' => [
                    'description'       => 'Przedstawiamy Arctic Frost Winter Accessories Bundle - Twoje rozwiązanie na ciepłe, stylowe i połączone zimowe dni. Ten starannie dobrany zestaw łączy cztery niezbędne akcesoria zimowe, tworząc harmonijną całość. Luksusowy szal, wykonany z mieszanki akrylu i wełny, nie tylko dodaje ciepła, ale także dodaje elegancji do twojej zimowej garderoby. Miękka czapka z dzianiny, wykonana z dbałością, obiecuje utrzymać cię w przytulnym cieple, dodając modny akcent do twojego wyglądu. Ale to się nie kończy - nasz zestaw zawiera również rękawiczki kompatybilne z ekranem dotykowym. Pozostań połączony bez rezygnacji z ciepła, gdy swobodnie korzystasz ze swoich urządzeń. Bez względu na to, czy odbierasz połączenia, wysyłasz wiadomości czy rejestrujesz zimowe chwile na swoim smartfonie, te rękawiczki zapewniają wygodę bez kompromisów w stylu. Miękka i przytulna tekstura skarpet oferuje luksusowe uczucie na skórze. Pożegnaj zimne stopy, gdy cieszysz się przytulnym ciepłem, które zapewniają te skarpety z mieszanki wełny. Arctic Frost Winter Accessories Bundle to nie tylko funkcjonalność; to deklaracja zimowej mody. Każdy element został zaprojektowany nie tylko po to, aby chronić cię przed zimnem, ale także podnieść twój styl podczas mroźnej pory roku. Wybrane materiały do tego zestawu priorytetowo traktują zarówno trwałość, jak i komfort, zapewniając, że możesz cieszyć się zimowym krajobrazem w stylu. Bez względu na to, czy obdarzasz siebie czy szukasz idealnego prezentu, Arctic Frost Winter Accessories Bundle to wszechstronny wybór. Rozpieszczaj kogoś wyjątkowego w okresie świątecznym lub podnieś swoją własną zimową garderobę tym stylowym i funkcjonalnym zestawem. Przyjmij mroźną aurę z pewnością, wiedząc, że masz idealne akcesoria, które utrzymają cię ciepłym i eleganckim.',
                    'meta-description'  => 'meta description',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Meta Title',
                    'name'              => 'Arctic Frost Winter Accessories',
                    'short-description' => 'Przyjmij zimowy chłód z naszym Arctic Frost Winter Accessories Bundle. Ten starannie dobrany zestaw zawiera luksusowy szal, przytulną czapkę, rękawiczki kompatybilne z ekranem dotykowym i skarpety z mieszanki wełny. Stylowy i funkcjonalny, ten zespół jest wykonany z wysokiej jakości materiałów, zapewniając zarówno trwałość, jak i komfort. Podnieś swoją zimową garderobę lub obdaruj kogoś wyjątkowego tym doskonałym prezentem.',
                ],

                '6' => [
                    'description'       => 'Przedstawiamy zestaw Arctic Frost Winter Accessories Bundle, Twoje rozwiązanie na zimne, stylowe i połączone dni zimowe. Ten starannie dobrany zestaw łączy cztery niezbędne akcesoria zimowe, tworząc harmonijną całość. Luksusowy szal, wykonany z mieszanki akrylu i wełny, nie tylko dodaje warstwy ciepła, ale także dodaje elegancji do Twojej zimowej garderoby. Miękka czapka z dzianiny, wykonana z dbałością o szczegóły, obiecuje utrzymać Cię w cieple, dodając modny akcent do Twojego wyglądu. Ale to nie koniec - nasz zestaw zawiera również rękawiczki kompatybilne z ekranem dotykowym. Pozostań połączony bez rezygnacji z ciepła, gdy bezproblemowo korzystasz z urządzeń. Bez względu na to, czy odbierasz połączenia, wysyłasz wiadomości czy rejestrujesz zimowe chwile na smartfonie, te rękawiczki zapewniają wygodę bez kompromisów w stylu. Miękka i przytulna tekstura skarpet oferuje luksusowe uczucie na skórze. Pożegnaj zimne stopy, gdy przytulasz się do ciepła zapewnianego przez te skarpety z mieszanki wełny. Arctic Frost Winter Accessories Bundle to nie tylko funkcjonalność; to deklaracja mody zimowej. Każdy element został zaprojektowany nie tylko po to, aby chronić Cię przed zimnem, ale także podkreślić Twój styl podczas mroźnej pory roku. Materiały wybrane do tego zestawu priorytetowo traktują zarówno trwałość, jak i komfort, zapewniając, że możesz cieszyć się zimowym krajobrazem w stylu. Bez względu na to, czy obdarowujesz siebie czy szukasz idealnego prezentu, Arctic Frost Winter Accessories Bundle to wszechstronny wybór. Rozpieszczaj kogoś wyjątkowego w okresie świątecznym lub podnieś swoją własną zimową garderobę tym stylowym i funkcjonalnym zestawem. Przyjmij mroźne dni z pewnością, wiedząc, że masz idealne akcesoria, które utrzymają Cię w cieple i elegancji.',
                    'meta-description'  => 'meta description',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Meta Title',
                    'name'              => 'Zestaw Arctic Frost Winter Accessories Bundle',
                    'short-description' => 'Przyjmij zimowy chłód z naszym zestawem Arctic Frost Winter Accessories Bundle. Ten starannie dobrany zestaw zawiera luksusowy szal, przytulną czapkę, rękawiczki kompatybilne z ekranem dotykowym i skarpety z mieszanki wełny. Stylowy i funkcjonalny, ten zespół jest wykonany z wysokiej jakości materiałów, zapewniając zarówno trwałość, jak i komfort. Podnieś swoją zimową garderobę lub obdaruj kogoś wyjątkowego tym doskonałym prezentem.',
                ],

                '7' => [
                    'description'       => 'Przedstawiamy kurtkę OmniHeat Men\'s Solid Hooded Puffer Jacket, Twoje rozwiązanie na ciepłe i modne ubranie podczas chłodniejszych pór roku. Ta kurtka została zaprojektowana z myślą o trwałości i cieple, zapewniając, że stanie się Twoim zaufanym towarzyszem. Kaptur nie tylko dodaje stylu, ale także zapewnia dodatkowe ciepło, chroniąc Cię przed zimnymi wiatrami i warunkami atmosferycznymi. Pełne rękawy zapewniają kompletną ochronę, zapewniając, że pozostaniesz ciepły od ramienia do nadgarstka. Wyposażona w kieszenie, ta kurtka puchowa zapewnia wygodę w noszeniu niezbędnych przedmiotów lub utrzymaniu ciepłych rąk. Izolacyjne wypełnienie syntetyczne oferuje zwiększone ciepło, sprawiając, że jest idealna do walki z zimnymi dniami i nocami. Wykonana z wytrzymałej powłoki i podszewki z poliestru, ta kurtka jest stworzona, aby przetrwać i stawić czoła warunkom atmosferycznym. Dostępna w 5 atrakcyjnych kolorach, możesz wybrać ten, który odpowiada Twojemu stylowi i preferencjom. Uniwersalna i funkcjonalna, kurtka OmniHeat Men\'s Solid Hooded Puffer Jacket nadaje się do różnych okazji, czy idziesz do pracy, wychodzisz na nieformalne spotkanie czy uczestniczysz w wydarzeniu na świeżym powietrzu. Doświadcz idealnego połączenia stylu, komfortu i funkcjonalności dzięki kurtce OmniHeat Men\'s Solid Hooded Puffer Jacket. Podnieś swoją zimową garderobę i pozostań przytulny, jednocześnie ciesząc się na zewnątrz. Pokonaj zimno w stylu i zrób wrażenie tym niezbędnym elementem.',
                    'meta-description'  => 'meta description',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Meta Title',
                    'name'              => 'Kurtka OmniHeat Men\'s Solid Hooded Puffer Jacket',
                    'short-description' => 'Pozostań ciepły i stylowy dzięki naszej kurtce OmniHeat Men\'s Solid Hooded Puffer Jacket. Ta kurtka została zaprojektowana, aby zapewnić ostateczne ciepło i posiada kieszenie na wstawki dla dodatkowej wygody. Izolacyjny materiał zapewnia, że pozostaniesz przytulny w zimnej pogodzie. Dostępna w 5 atrakcyjnych kolorach, sprawia, że jest to wszechstronny wybór na różne okazje.',
                ],

                '8' => [
                    'description'       => 'Przedstawiamy kurtkę z kapturem OmniHeat Men\'s Solid Hooded Puffer Jacket, Twoje rozwiązanie na zimę, które zapewni Ci ciepło i modny wygląd. Ta kurtka została stworzona z myślą o trwałości i cieple, aby stać się Twoim zaufanym towarzyszem. Kaptur nie tylko dodaje stylu, ale także zapewnia dodatkowe ciepło, chroniąc przed zimnym wiatrem i pogodą. Długie rękawy zapewniają pełne pokrycie, zapewniając komfort od ramienia do nadgarstka. Wyposażona w kieszenie, ta kurtka puchowa zapewnia wygodę w noszeniu niezbędnych przedmiotów lub utrzymywaniu ciepła w dłoniach. Izolacyjne wypełnienie syntetyczne zapewnia dodatkowe ciepło, dzięki czemu jest idealna do walki z zimnymi dniami i nocami. Wykonana z wytrzymałego poliestru na zewnątrz i podszewki, ta kurtka jest stworzona, aby przetrwać różne warunki atmosferyczne. Dostępna w 5 atrakcyjnych kolorach, możesz wybrać ten, który pasuje do Twojego stylu i preferencji. Uniwersalna i funkcjonalna, kurtka z kapturem OmniHeat Men\'s Solid Hooded Puffer Jacket nadaje się na różne okazje, czy idziesz do pracy, wychodzisz na spacer czy uczestniczysz w wydarzeniu na świeżym powietrzu. Doświadcz idealnego połączenia stylu, komfortu i funkcjonalności dzięki kurtce z kapturem OmniHeat Men\'s Solid Hooded Puffer Jacket. Podnieś swoją zimową garderobę i pozostań ciepły, jednocześnie ciesząc się na zewnątrz. Pokonaj zimno w stylu i zrób wrażenie tym niezbędnym elementem.',
                    'meta-description'  => 'meta opis',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Meta Tytuł',
                    'name'              => 'Kurtka z kapturem OmniHeat Men\'s Solid Hooded Puffer Jacket-Niebieska-Żółta-M',
                    'short-description' => 'Pozostań ciepły i stylowy dzięki naszej kurtce z kapturem OmniHeat Men\'s Solid Hooded Puffer Jacket. Ta kurtka została zaprojektowana, aby zapewnić ostateczne ciepło i posiada kieszenie na wstawki dla dodatkowej wygody. Izolacyjny materiał zapewnia, że pozostaniesz przytulny w zimnej pogodzie. Dostępna w 5 atrakcyjnych kolorach, sprawia, że jest to wszechstronny wybór na różne okazje.',
                ],

                '9' => [
                    'description'       => 'Przedstawiamy kurtkę z kapturem OmniHeat Men\'s Solid Hooded Puffer Jacket, Twoje rozwiązanie na zimę, które zapewni Ci ciepło i modny wygląd. Ta kurtka została stworzona z myślą o trwałości i cieple, aby stać się Twoim zaufanym towarzyszem. Kaptur nie tylko dodaje stylu, ale także zapewnia dodatkowe ciepło, chroniąc przed zimnym wiatrem i pogodą. Długie rękawy zapewniają pełne pokrycie, zapewniając komfort od ramienia do nadgarstka. Wyposażona w kieszenie, ta kurtka puchowa zapewnia wygodę w noszeniu niezbędnych przedmiotów lub utrzymywaniu ciepła w dłoniach. Izolacyjne wypełnienie syntetyczne zapewnia dodatkowe ciepło, dzięki czemu jest idealna do walki z zimnymi dniami i nocami. Wykonana z wytrzymałego poliestru na zewnątrz i podszewki, ta kurtka jest stworzona, aby przetrwać różne warunki atmosferyczne. Dostępna w 5 atrakcyjnych kolorach, możesz wybrać ten, który pasuje do Twojego stylu i preferencji. Uniwersalna i funkcjonalna, kurtka z kapturem OmniHeat Men\'s Solid Hooded Puffer Jacket nadaje się na różne okazje, czy idziesz do pracy, wychodzisz na spacer czy uczestniczysz w wydarzeniu na świeżym powietrzu. Doświadcz idealnego połączenia stylu, komfortu i funkcjonalności dzięki kurtce z kapturem OmniHeat Men\'s Solid Hooded Puffer Jacket. Podnieś swoją zimową garderobę i pozostań ciepły, jednocześnie ciesząc się na zewnątrz. Pokonaj zimno w stylu i zrób wrażenie tym niezbędnym elementem.',
                    'meta-description'  => 'meta opis',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Meta Tytuł',
                    'name'              => 'Kurtka z kapturem OmniHeat Men\'s Solid Hooded Puffer Jacket-Niebieska-Żółta-L',
                    'short-description' => 'Pozostań ciepły i stylowy dzięki naszej kurtce z kapturem OmniHeat Men\'s Solid Hooded Puffer Jacket. Ta kurtka została zaprojektowana, aby zapewnić ostateczne ciepło i posiada kieszenie na wstawki dla dodatkowej wygody. Izolacyjny materiał zapewnia, że pozostaniesz przytulny w zimnej pogodzie. Dostępna w 5 atrakcyjnych kolorach, sprawia, że jest to wszechstronny wybór na różne okazje.',
                ],

                '10' => [
                    'description'       => 'Przedstawiamy kurtkę z kapturem OmniHeat Men\'s Solid Hooded Puffer Jacket, Twoje rozwiązanie na zimę, które zapewni Ci ciepło i modny wygląd. Ta kurtka została stworzona z myślą o trwałości i cieple, aby stać się Twoim zaufanym towarzyszem. Kaptur nie tylko dodaje stylu, ale także zapewnia dodatkowe ciepło, chroniąc przed zimnym wiatrem i pogodą. Długie rękawy zapewniają pełne pokrycie, zapewniając komfort od ramienia do nadgarstka. Wyposażona w kieszenie, ta kurtka puchowa zapewnia wygodę w noszeniu niezbędnych przedmiotów lub utrzymywaniu ciepła w dłoniach. Izolacyjne wypełnienie syntetyczne zapewnia dodatkowe ciepło, dzięki czemu jest idealna do walki z zimnymi dniami i nocami. Wykonana z wytrzymałego poliestru na zewnątrz i podszewki, ta kurtka jest stworzona, aby przetrwać różne warunki atmosferyczne. Dostępna w 5 atrakcyjnych kolorach, możesz wybrać ten, który pasuje do Twojego stylu i preferencji. Uniwersalna i funkcjonalna, kurtka z kapturem OmniHeat Men\'s Solid Hooded Puffer Jacket nadaje się na różne okazje, czy idziesz do pracy, wychodzisz na spacer czy uczestniczysz w wydarzeniu na świeżym powietrzu. Doświadcz idealnego połączenia stylu, komfortu i funkcjonalności dzięki kurtce z kapturem OmniHeat Men\'s Solid Hooded Puffer Jacket. Podnieś swoją zimową garderobę i pozostań ciepły, jednocześnie ciesząc się na zewnątrz. Pokonaj zimno w stylu i zrób wrażenie tym niezbędnym elementem.',
                    'meta-description'  => 'meta opis',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Meta Tytuł',
                    'name'              => 'Kurtka z kapturem OmniHeat Men\'s Solid Hooded Puffer Jacket-Niebieska-Zielona-M',
                    'short-description' => 'Pozostań ciepły i stylowy dzięki naszej kurtce z kapturem OmniHeat Men\'s Solid Hooded Puffer Jacket. Ta kurtka została zaprojektowana, aby zapewnić ostateczne ciepło i posiada kieszenie na wstawki dla dodatkowej wygody. Izolacyjny materiał zapewnia, że pozostaniesz przytulny w zimnej pogodzie. Dostępna w 5 atrakcyjnych kolorach, sprawia, że jest to wszechstronny wybór na różne okazje.',
                ],

                '11' => [
                    'description'       => 'Przedstawiamy kurtkę z kapturem OmniHeat Men\'s Solid Hooded Puffer Jacket, Twoje rozwiązanie na zimę, które zapewni Ci ciepło i modny wygląd. Ta kurtka została stworzona z myślą o trwałości i cieple, aby stać się Twoim zaufanym towarzyszem. Kaptur nie tylko dodaje stylu, ale także zapewnia dodatkowe ciepło, chroniąc przed zimnym wiatrem i pogodą. Długie rękawy zapewniają pełne pokrycie, zapewniając komfort od ramienia do nadgarstka. Wyposażona w kieszenie, ta kurtka puchowa zapewnia wygodę w noszeniu niezbędnych przedmiotów lub utrzymywaniu ciepła w dłoniach. Izolacyjne wypełnienie syntetyczne zapewnia dodatkowe ciepło, dzięki czemu jest idealna do walki z zimnymi dniami i nocami. Wykonana z wytrzymałego poliestru na zewnątrz i podszewki, ta kurtka jest stworzona, aby przetrwać różne warunki atmosferyczne. Dostępna w 5 atrakcyjnych kolorach, możesz wybrać ten, który pasuje do Twojego stylu i preferencji. Uniwersalna i funkcjonalna, kurtka z kapturem OmniHeat Men\'s Solid Hooded Puffer Jacket nadaje się na różne okazje, czy idziesz do pracy, wychodzisz na spacer czy uczestniczysz w wydarzeniu na świeżym powietrzu. Doświadcz idealnego połączenia stylu, komfortu i funkcjonalności dzięki kurtce z kapturem OmniHeat Men\'s Solid Hooded Puffer Jacket. Podnieś swoją zimową garderobę i pozostań ciepły, jednocześnie ciesząc się na zewnątrz. Pokonaj zimno w stylu i zrób wrażenie tym niezbędnym elementem.',
                    'meta-description'  => 'meta opis',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Meta Tytuł',
                    'name'              => 'Kurtka z kapturem OmniHeat Men\'s Solid Hooded Puffer Jacket-Niebieska-Zielona-L',
                    'short-description' => 'Pozostań ciepły i stylowy dzięki naszej kurtce z kapturem OmniHeat Men\'s Solid Hooded Puffer Jacket. Ta kurtka została zaprojektowana, aby zapewnić ostateczne ciepło i posiada kieszenie na wstawki dla dodatkowej wygody. Izolacyjny materiał zapewnia, że pozostaniesz przytulny w zimnej pogodzie. Dostępna w 5 atrakcyjnych kolorach, sprawia, że jest to wszechstronny wybór na różne okazje.',
                ],
            ],

            'product-attribute-values' => [
                '1' => [
                    'description'      => 'Arctic Cozy Knit Beanie to Twoje rozwiązanie na ciepłe, wygodne i stylowe zimowe miesiące. Wykonana z miękkiego i trwałego akrylowego dzianiny, ta czapka zapewnia przytulne i dopasowane dopasowanie. Klasyczny design sprawia, że jest odpowiednia zarówno dla mężczyzn, jak i kobiet, oferując wszechstronny dodatek, który uzupełnia różne style. Niezależnie od tego, czy wychodzisz na casualowy dzień w mieście, czy cieszysz się na świeżym powietrzu, ta czapka dodaje nuty komfortu i ciepła do twojej stylizacji. Miękki i oddychający materiał zapewnia, że pozostajesz przytulny, nie rezygnując ze stylu. Arctic Cozy Knit Beanie to nie tylko dodatek; to wyraz mody zimowej. Jego prostota sprawia, że łatwo można go zestawić z różnymi strojami, co czyni go podstawą twojej zimowej garderoby. Idealny na prezent lub jako przyjemność dla siebie, ta czapka to przemyślane uzupełnienie każdej zimowej stylizacji. To wszechstronny dodatek, który wykracza poza funkcjonalność, dodając nutę ciepła i stylu do twojego wyglądu. Przyjmij esencję zimy z Arctic Cozy Knit Beanie. Niezależnie od tego, czy cieszysz się na casualowy dzień, czy stajesz w obliczu elementów, pozwól tej czapce być twoim towarzyszem komfortu i stylu. Podnieś swoją zimową garderobę tym klasycznym dodatkiem, który doskonale łączy ciepło z ponadczasowym poczuciem mody.',
                    'meta-description' => 'meta opis',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Meta Tytuł',
                    'name'             => 'Arctic Cozy Knit Unisex Beanie',
                    'sort-description' => 'Przyjmij chłodne dni ze stylem dzięki naszej Arctic Cozy Knit Beanie. Wykonana z miękkiego i trwałego akrylu, ta klasyczna czapka oferuje ciepło i wszechstronność. Odpowiednia zarówno dla mężczyzn, jak i kobiet, jest idealnym dodatkiem do noszenia na co dzień lub na zewnątrz. Podnieś swoją zimową garderobę lub obdaruj kogoś wyjątkowego tym niezbędnym czapką.',
                ],

                '2' => [
                    'description'      => 'Arctic Bliss Winter Scarf to nie tylko dodatek na zimę; to wyraz ciepła, komfortu i stylu na sezon zimowy. Wykonany z dbałością o luksusowy blend akrylu i wełny, ten szal jest zaprojektowany, aby utrzymać cię w cieple i wygodzie nawet w najzimniejszych temperaturach. Miękka i puszysta struktura nie tylko zapewnia izolację przed zimnem, ale także dodaje nutę luksusu do twojej zimowej garderoby. Projekt Arctic Bliss Winter Scarf jest zarówno stylowy, jak i wszechstronny, dzięki czemu jest idealnym dodatkiem do różnych zimowych stylizacji. Niezależnie od tego, czy ubierasz się na specjalną okazję, czy dodajesz elegancką warstwę do codziennego wyglądu, ten szal doskonale uzupełnia twój styl. Dodatkowo, długość szala pozwala na dostosowanie stylizacji. Owiń go dla dodatkowego ciepła, luźno go opuść dla casualowego wyglądu lub eksperymentuj z różnymi węzłami, aby wyrazić swój unikalny styl. Ta wszechstronność sprawia, że jest to niezbędny dodatek na sezon zimowy. Szukasz idealnego prezentu? Arctic Bliss Winter Scarf to idealny wybór. Niezależnie od tego, czy zaskakujesz kogoś bliskiego, czy obdarowujesz siebie, ten szal to ponadczasowy i praktyczny prezent, który będzie cieszyć się przez cały zimowy sezon. Przyjmij zimę z Arctic Bliss Winter Scarf, gdzie ciepło spotyka się ze stylem w doskonałej harmonii. Podnieś swoją zimową garderobę tym niezbędnym dodatkiem, który nie tylko utrzymuje cię w cieple, ale także dodaje nutę wyrafinowania do twojej zimowej stylizacji.',
                    'meta-description' => 'meta opis',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Meta Tytuł',
                    'name'             => 'Arctic Bliss Stylish Winter Scarf',
                    'sort-description' => 'Doświadcz objęcia ciepła i stylu dzięki naszemu Arctic Bliss Winter Scarf. Wykonany z luksusowego blendu akrylu i wełny, ten przytulny szal jest zaprojektowany, aby utrzymać cię w cieple podczas najzimniejszych dni. Jego stylowy i wszechstronny design, połączony z długą długością, oferuje możliwości dostosowania stylizacji. Podnieś swoją zimową garderobę lub obdaruj kogoś wyjątkowego tym niezbędnym zimowym dodatkiem.',
                ],

                '3' => [
                    'description'      => 'Przedstawiamy Arctic Touchscreen Winter Gloves - miejsce, gdzie ciepło, styl i łączność spotykają się, aby wzmocnić twoje zimowe doświadczenie. Wykonane z wysokiej jakości akrylu, te rękawiczki są zaprojektowane, aby zapewnić wyjątkowe ciepło i trwałość. Palce kompatybilne z ekranem dotykowym pozwalają na pozostanie w kontakcie bez odsłaniania rąk na zimno. Odbieraj połączenia, wysyłaj wiadomości i nawiguj po urządzeniach bez wysiłku, jednocześnie trzymając ręce ciepłe. Izolacja dodaje dodatkową warstwę przytulności, sprawiając, że te rękawiczki są idealnym wyborem do walki z zimnymi dniami i nocami. Niezależnie od tego, czy jesteś w drodze do pracy, załatwiasz sprawy, czy cieszysz się na świeżym powietrzu, te rękawiczki zapewniają ciepło i ochronę, których potrzebujesz. Elastyczne mankiety zapewniają pewne dopasowanie, zapobiegając zimnym przeciągom i utrzymując rękawiczki na swoim miejscu podczas codziennych czynności. Stylowy design dodaje nutę elegancji do twojej zimowej stylizacji, sprawiając, że te rękawiczki są zarówno modne, jak i funkcjonalne. Idealne na prezent lub jako przyjemność dla siebie, Arctic Touchscreen Winter Gloves to niezbędny dodatek dla nowoczesnej osoby. Pożegnaj niedogodność zdejmowania rękawiczek, aby korzystać z urządzeń i przyjmij płynne połączenie ciepła, stylu i łączności. Pozostań w kontakcie, pozostań ciepły i pozostań stylowy dzięki Arctic Touchscreen Winter Gloves - twojemu niezawodnemu towarzyszowi do pokonywania zimowego sezonu z pewnością.',
                    'meta-description' => 'meta opis',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Meta Tytuł',
                    'name'             => 'Arctic Touchscreen Winter Gloves',
                    'sort-description' => 'Pozostań w kontakcie i ciepły dzięki naszym Arctic Touchscreen Winter Gloves. Te rękawiczki nie tylko są wykonane z wysokiej jakości akrylu dla ciepła i trwałości, ale także mają kompatybilny z ekranem dotykowym design. Dzięki izolacji, elastycznym mankietom dla pewnego dopasowania i stylowemu wyglądowi, te rękawiczki są idealne do codziennego noszenia w chłodnych warunkach.',
                ],

                '4' => [
                    'description'      => 'Przedstawiamy Arctic Warmth Wool Blend Socks - twojego niezbędnego towarzysza dla przytulnych i wygodnych stóp podczas chłodniejszych pór roku. Wykonane z premium blendu wełny merino, akrylu, nylonu i spandexu, te skarpety są zaprojektowane, aby zapewnić niezrównane ciepło i komfort. Blend wełny sprawia, że twoje stopy pozostają ciepłe nawet w najzimniejszych temperaturach, co czyni te skarpety idealnym wyborem na zimowe przygody lub po prostu na przytulne spędzanie czasu w domu. Miękka i przytulna struktura skarpet oferuje luksusowe uczucie na skórze. Pożegnaj zimne stopy, gdy przytulasz się do miękkości zapewnianej przez te skarpety z blendu wełny. Zaprojektowane z myślą o trwałości, skarpety posiadają wzmocnioną piętę i palce, co dodaje dodatkowej wytrzymałości w miejscach narażonych na duże obciążenia. Zapewnia to, że twoje skarpety przetrwają próbę czasu, zapewniając długotrwały komfort i przytulność. Oddychająca natura materiału zapobiega przegrzewaniu, pozwalając twoim stopom pozostać wygodnymi i suchymi przez cały dzień. Niezależnie od tego, czy wychodzisz na zimowy spacer, czy relaksujesz się w domu, te skarpety oferują doskonałą równowagę między ciepłem a oddychalnością. Wszechstronne i stylowe, te skarpety z blendu wełny są odpowiednie na różne okazje. Połącz je ze swoimi ulubionymi butami dla modnego zimowego wyglądu lub nosić w domu dla maksymalnego komfortu. Podnieś swoją zimową garderobę i postaw na komfort z Arctic Warmth Wool Blend Socks. Obdarz swoje stopy luksusem, na który zasługują, i wkrocz w świat przytulności, który trwa przez cały sezon.',
                    'meta-description' => 'meta opis',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Meta Tytuł',
                    'name'             => 'Arctic Warmth Wool Blend Socks',
                    'sort-description' => 'Doświadcz niezrównanego ciepła i komfortu naszych Arctic Warmth Wool Blend Socks. Wykonane z blendu wełny merino, akrylu, nylonu i spandexu, te skarpety oferują ostateczne przytulenie w chłodnej pogodzie. Z wzmocnioną piętą i palcami dla wytrzymałości, te wszechstronne i stylowe skarpety są idealne na różne okazje.',
                ],

                '5' => [
                    'description'      => 'Przedstawiamy Arctic Frost Winter Accessories Bundle, Twoje rozwiązanie na ciepłe, stylowe i połączone zimowe dni. Ten starannie dobrany zestaw łączy cztery niezbędne zimowe akcesoria, tworząc harmonijną stylizację. Luksusowy szal, wykonany z blendu akrylu i wełny, nie tylko dodaje warstwy ciepła, ale także wnosi nutę elegancji do twojej zimowej garderoby. Miękka dzianina czapka, wykonana z dbałością, obiecuje utrzymać cię w cieple, dodając modny akcent do twojego wyglądu. Ale to się nie kończy - nasz zestaw zawiera również rękawiczki kompatybilne z ekranem dotykowym. Pozostań w kontakcie, nie rezygnując z ciepła, gdy bez wysiłku nawigujesz po swoich urządzeniach. Niezależnie od tego, czy odbierasz połączenia, wysyłasz wiadomości, czy rejestrujesz zimowe chwile na swoim smartfonie, te rękawiczki zapewniają wygodę bez kompromisów w stylu. Miękka i przytulna struktura skarpet oferuje luksusowe uczucie na skórze. Pożegnaj zimne stopy, gdy przytulasz się do miękkości zapewnianej przez te skarpety z blendu wełny. Arctic Frost Winter Accessories Bundle to nie tylko funkcjonalność; to wyraz mody zimowej. Każdy element jest zaprojektowany nie tylko po to, aby chronić cię przed zimnem, ale także podnieść twój styl podczas mroźnej pory roku. Materiały wybrane do tego zestawu priorytetowo traktują zarówno trwałość, jak i komfort, zapewniając, że możesz cieszyć się zimowym krajobrazem w stylu. Niezależnie od tego, czy obdarowujesz siebie, czy szukasz idealnego prezentu, Arctic Frost Winter Accessories Bundle to wszechstronny wybór. Obdaruj kogoś wyjątkowego w okresie świątecznym lub podnieś swoją własną zimową garderobę tym stylowym i funkcjonalnym zestawem. Przyjmij mroźną pogodę z pewnością, wiedząc, że masz idealne akcesoria, które utrzymają cię w cieple i stylu.',
                    'meta-description' => 'meta opis',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Meta Tytuł',
                    'name'             => 'Arctic Frost Winter Accessories',
                    'sort-description' => 'Przyjmij chłód zimy dzięki naszemu Arctic Frost Winter Accessories Bundle. Ten starannie dobrany zestaw zawiera luksusowy szal, przytulną czapkę, rękawiczki kompatybilne z ekranem dotykowym i skarpety z blendu wełny. Stylowy i funkcjonalny, ten zestaw jest wykonany z wysokiej jakości materiałów, zapewniających zarówno trwałość, jak i komfort. Podnieś swoją zimową garderobę lub obdaruj kogoś wyjątkowego tym doskonałym prezentem.',
                ],

                '6' => [
                    'description'      => 'Przedstawiamy zestaw Arctic Frost Winter Accessories Bundle, który zapewni Ci ciepło, styl i połączenie podczas chłodnych zimowych dni. Ten starannie dobrany zestaw łączy cztery niezbędne akcesoria zimowe, tworząc harmonijną całość. Luksusowy szal, wykonany z mieszanki akrylu i wełny, nie tylko dodaje warstwy ciepła, ale także wprowadza elegancję do Twojej zimowej garderoby. Miękka czapka z dzianiny, wykonana z dbałością o szczegóły, obiecuje utrzymać Cię ciepłym, dodając jednocześnie modny akcent do Twojego wyglądu. Ale to nie koniec - nasz zestaw zawiera również rękawiczki kompatybilne z ekranem dotykowym. Pozostań w kontakcie, nie rezygnując z ciepła, gdy bezproblemowo korzystasz z urządzeń. Bez względu na to, czy odbierasz połączenia, wysyłasz wiadomości czy rejestrujesz zimowe chwile na smartfonie, te rękawiczki zapewniają wygodę bez kompromisów w stylu. Miękka i przytulna tekstura skarpet oferuje luksusowe uczucie na skórze. Pożegnaj zimne stopy, ciesząc się przyjemnym ciepłem zapewnianym przez te skarpetki z mieszanki wełny. Zestaw Arctic Frost Winter Accessories Bundle to nie tylko funkcjonalność; to deklaracja zimowej mody. Każdy element został zaprojektowany nie tylko po to, aby chronić Cię przed zimnem, ale także podnieść Twój styl podczas mroźnej pory roku. Wybrane materiały tego zestawu priorytetowo traktują zarówno trwałość, jak i komfort, zapewniając, że możesz cieszyć się zimowym krajobrazem w stylu. Bez względu na to, czy obdarowujesz siebie czy szukasz idealnego prezentu, zestaw Arctic Frost Winter Accessories Bundle to wszechstronny wybór. Rozpieszczaj kogoś wyjątkowego w okresie świątecznym lub podnieś swoją własną zimową garderobę tym stylowym i funkcjonalnym zespołem. Przyjmij mroźną aurę z pewnością, wiedząc, że masz idealne akcesoria, które utrzymają Cię ciepłym i eleganckim.',
                    'meta-description' => 'opis meta',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Tytuł meta',
                    'name'             => 'Zestaw Arctic Frost Winter Accessories Bundle',
                    'sort-description' => 'Przyjmij zimowy chłód z naszym zestawem Arctic Frost Winter Accessories Bundle. Ten starannie dobrany zestaw zawiera luksusowy szal, przytulną czapkę, rękawiczki kompatybilne z ekranem dotykowym i skarpety z mieszanki wełny. Stylowy i funkcjonalny, ten zespół jest wykonany z wysokiej jakości materiałów, zapewniających zarówno trwałość, jak i komfort. Podnieś swoją zimową garderobę lub rozpieszcz kogoś wyjątkowego tym doskonałym prezentem.',
                ],

                '7' => [
                    'description'      => 'Przedstawiamy męską kurtkę z kapturem OmniHeat Men\'s Solid Hooded Puffer Jacket, która zapewni Ci ciepło i modny wygląd podczas chłodniejszych pór roku. Ta kurtka została zaprojektowana z myślą o trwałości i cieple, aby stać się Twoim zaufanym towarzyszem. Kaptur nie tylko dodaje stylu, ale także zapewnia dodatkowe ciepło, chroniąc Cię przed zimnymi wiatrami i warunkami atmosferycznymi. Pełne rękawy zapewniają kompletną ochronę, zapewniając Ci przytulność od ramienia do nadgarstka. Wyposażona w kieszenie, ta kurtka puchowa zapewnia wygodę noszenia niezbędnych przedmiotów lub utrzymywania ciepłych rąk. Izolacyjne wypełnienie syntetyczne zapewnia zwiększone ciepło, dzięki czemu jest idealne do walki z zimnymi dniami i nocami. Wykonana z trwałej powłoki i podszewki z poliestru, ta kurtka jest stworzona, aby przetrwać i wytrzymać warunki atmosferyczne. Dostępna w 5 atrakcyjnych kolorach, możesz wybrać ten, który odpowiada Twojemu stylowi i preferencjom. Uniwersalna i funkcjonalna, męska kurtka z kapturem OmniHeat Men\'s Solid Hooded Puffer Jacket nadaje się do różnych okazji, czy idziesz do pracy, wychodzisz na niezobowiązujące wyjście czy uczestniczysz w wydarzeniu na świeżym powietrzu. Doświadcz idealnego połączenia stylu, komfortu i funkcjonalności dzięki męskiej kurtce z kapturem OmniHeat Men\'s Solid Hooded Puffer Jacket. Podnieś swoją zimową garderobę i pozostań przytulny, jednocześnie ciesząc się na zewnątrz. Pokonaj zimno w stylu i zrób wrażenie tym niezbędnym elementem.',
                    'meta-description' => 'opis meta',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Tytuł meta',
                    'name'             => 'Męska kurtka z kapturem OmniHeat Men\'s Solid Hooded Puffer Jacket',
                    'sort-description' => 'Zostań ciepły i stylowy dzięki naszej męskiej kurtce z kapturem OmniHeat Men\'s Solid Hooded Puffer Jacket. Ta kurtka została zaprojektowana, aby zapewnić ostateczne ciepło i posiada kieszenie na wstawki dla dodatkowej wygody. Izolacyjny materiał zapewnia przytulność w zimnej pogodzie. Dostępna w 5 atrakcyjnych kolorach, jest wszechstronnym wyborem na różne okazje.',
                ],

                '8' => [
                    'description'      => 'Przedstawiamy męską kurtkę z kapturem OmniHeat Men\'s Solid Hooded Puffer Jacket, która zapewni Ci ciepło i modny wygląd podczas chłodniejszych pór roku. Ta kurtka została zaprojektowana z myślą o trwałości i cieple, aby stać się Twoim zaufanym towarzyszem. Kaptur nie tylko dodaje stylu, ale także zapewnia dodatkowe ciepło, chroniąc Cię przed zimnymi wiatrami i warunkami atmosferycznymi. Pełne rękawy zapewniają kompletną ochronę, zapewniając Ci przytulność od ramienia do nadgarstka. Wyposażona w kieszenie, ta kurtka puchowa zapewnia wygodę noszenia niezbędnych przedmiotów lub utrzymywania ciepłych rąk. Izolacyjne wypełnienie syntetyczne zapewnia zwiększone ciepło, dzięki czemu jest idealne do walki z zimnymi dniami i nocami. Wykonana z trwałej powłoki i podszewki z poliestru, ta kurtka jest stworzona, aby przetrwać i wytrzymać warunki atmosferyczne. Dostępna w 5 atrakcyjnych kolorach, możesz wybrać ten, który odpowiada Twojemu stylowi i preferencjom. Uniwersalna i funkcjonalna, męska kurtka z kapturem OmniHeat Men\'s Solid Hooded Puffer Jacket nadaje się do różnych okazji, czy idziesz do pracy, wychodzisz na niezobowiązujące wyjście czy uczestniczysz w wydarzeniu na świeżym powietrzu. Doświadcz idealnego połączenia stylu, komfortu i funkcjonalności dzięki męskiej kurtce z kapturem OmniHeat Men\'s Solid Hooded Puffer Jacket. Podnieś swoją zimową garderobę i pozostań przytulny, jednocześnie ciesząc się na zewnątrz. Pokonaj zimno w stylu i zrób wrażenie tym niezbędnym elementem.',
                    'meta-description' => 'opis meta',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Tytuł meta',
                    'name'             => 'Męska kurtka z kapturem OmniHeat Men\'s Solid Hooded Puffer Jacket-Niebiesko-Żółta-M',
                    'sort-description' => 'Zostań ciepły i stylowy dzięki naszej męskiej kurtce z kapturem OmniHeat Men\'s Solid Hooded Puffer Jacket. Ta kurtka została zaprojektowana, aby zapewnić ostateczne ciepło i posiada kieszenie na wstawki dla dodatkowej wygody. Izolacyjny materiał zapewnia przytulność w zimnej pogodzie. Dostępna w 5 atrakcyjnych kolorach, jest wszechstronnym wyborem na różne okazje.',
                ],

                '9' => [
                    'description'      => 'Przedstawiamy męską kurtkę z kapturem OmniHeat Men\'s Solid Hooded Puffer Jacket, która zapewni Ci ciepło i modny wygląd podczas chłodniejszych pór roku. Ta kurtka została zaprojektowana z myślą o trwałości i cieple, aby stać się Twoim zaufanym towarzyszem. Kaptur nie tylko dodaje stylu, ale także zapewnia dodatkowe ciepło, chroniąc Cię przed zimnymi wiatrami i warunkami atmosferycznymi. Pełne rękawy zapewniają kompletną ochronę, zapewniając Ci przytulność od ramienia do nadgarstka. Wyposażona w kieszenie, ta kurtka puchowa zapewnia wygodę noszenia niezbędnych przedmiotów lub utrzymywania ciepłych rąk. Izolacyjne wypełnienie syntetyczne zapewnia zwiększone ciepło, dzięki czemu jest idealne do walki z zimnymi dniami i nocami. Wykonana z trwałej powłoki i podszewki z poliestru, ta kurtka jest stworzona, aby przetrwać i wytrzymać warunki atmosferyczne. Dostępna w 5 atrakcyjnych kolorach, możesz wybrać ten, który odpowiada Twojemu stylowi i preferencjom. Uniwersalna i funkcjonalna, męska kurtka z kapturem OmniHeat Men\'s Solid Hooded Puffer Jacket nadaje się do różnych okazji, czy idziesz do pracy, wychodzisz na niezobowiązujące wyjście czy uczestniczysz w wydarzeniu na świeżym powietrzu. Doświadcz idealnego połączenia stylu, komfortu i funkcjonalności dzięki męskiej kurtce z kapturem OmniHeat Men\'s Solid Hooded Puffer Jacket. Podnieś swoją zimową garderobę i pozostań przytulny, jednocześnie ciesząc się na zewnątrz. Pokonaj zimno w stylu i zrób wrażenie tym niezbędnym elementem.',
                    'meta-description' => 'opis meta',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Tytuł meta',
                    'name'             => 'Męska kurtka z kapturem OmniHeat Men\'s Solid Hooded Puffer Jacket-Niebiesko-Żółta-L',
                    'sort-description' => 'Zostań ciepły i stylowy dzięki naszej męskiej kurtce z kapturem OmniHeat Men\'s Solid Hooded Puffer Jacket. Ta kurtka została zaprojektowana, aby zapewnić ostateczne ciepło i posiada kieszenie na wstawki dla dodatkowej wygody. Izolacyjny materiał zapewnia przytulność w zimnej pogodzie. Dostępna w 5 atrakcyjnych kolorach, jest wszechstronnym wyborem na różne okazje.',
                ],

                '10' => [
                    'description'      => 'Przedstawiamy kurtkę OmniHeat Men\'s Solid Hooded Puffer Jacket, Twoje rozwiązanie na zimę, które zapewni Ci ciepło i modny wygląd. Ta kurtka została stworzona z myślą o trwałości i cieple, aby stać się Twoim zaufanym towarzyszem. Projekt z kapturem nie tylko dodaje stylu, ale także zapewnia dodatkowe ciepło, chroniąc przed zimnym wiatrem i pogodą. Długie rękawy zapewniają pełne pokrycie, abyś czuł się przytulnie od ramienia do nadgarstka. Wyposażona w kieszenie, ta kurtka puchowa zapewnia wygodę w noszeniu niezbędnych przedmiotów lub utrzymywaniu ciepłych rąk. Izolacyjne wypełnienie syntetyczne zapewnia zwiększone ciepło, dzięki czemu jest idealna do walki z zimnymi dniami i nocami. Wykonana z wytrzymałego poliestru na zewnątrz i podszewki, ta kurtka jest stworzona, aby przetrwać i wytrzymać warunki atmosferyczne. Dostępna w 5 atrakcyjnych kolorach, możesz wybrać ten, który pasuje do Twojego stylu i preferencji. Uniwersalna i funkcjonalna, kurtka OmniHeat Men\'s Solid Hooded Puffer Jacket nadaje się na różne okazje, czy idziesz do pracy, wychodzisz na spacer czy uczestniczysz w wydarzeniu na świeżym powietrzu. Doświadcz idealnego połączenia stylu, komfortu i funkcjonalności dzięki kurtce OmniHeat Men\'s Solid Hooded Puffer Jacket. Podnieś swoją zimową garderobę i pozostań przytulny, ciesząc się na zewnątrz. Pokonaj zimno w stylu i zrób wrażenie tym niezbędnym elementem.',
                    'meta-description' => 'opis meta',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Tytuł meta',
                    'name'             => 'Kurtka OmniHeat Men\'s Solid Hooded Puffer Jacket-Niebiesko-Zielona-M',
                    'sort-description' => 'Zostań ciepły i stylowy dzięki naszej kurtce OmniHeat Men\'s Solid Hooded Puffer Jacket. Ta kurtka została zaprojektowana, aby zapewnić ostateczne ciepło i posiada kieszenie na wstawki dla dodatkowej wygody. Izolacyjny materiał zapewnia przytulność w zimnej pogodzie. Dostępna w 5 atrakcyjnych kolorach, jest wszechstronnym wyborem na różne okazje.',
                ],

                '11' => [
                    'description'      => 'Przedstawiamy kurtkę OmniHeat Men\'s Solid Hooded Puffer Jacket, Twoje rozwiązanie na zimę, które zapewni Ci ciepło i modny wygląd. Ta kurtka została stworzona z myślą o trwałości i cieple, aby stać się Twoim zaufanym towarzyszem. Projekt z kapturem nie tylko dodaje stylu, ale także zapewnia dodatkowe ciepło, chroniąc przed zimnym wiatrem i pogodą. Długie rękawy zapewniają pełne pokrycie, abyś czuł się przytulnie od ramienia do nadgarstka. Wyposażona w kieszenie, ta kurtka puchowa zapewnia wygodę w noszeniu niezbędnych przedmiotów lub utrzymywaniu ciepłych rąk. Izolacyjne wypełnienie syntetyczne zapewnia zwiększone ciepło, dzięki czemu jest idealna do walki z zimnymi dniami i nocami. Wykonana z wytrzymałego poliestru na zewnątrz i podszewki, ta kurtka jest stworzona, aby przetrwać i wytrzymać warunki atmosferyczne. Dostępna w 5 atrakcyjnych kolorach, możesz wybrać ten, który pasuje do Twojego stylu i preferencji. Uniwersalna i funkcjonalna, kurtka OmniHeat Men\'s Solid Hooded Puffer Jacket nadaje się na różne okazje, czy idziesz do pracy, wychodzisz na spacer czy uczestniczysz w wydarzeniu na świeżym powietrzu. Doświadcz idealnego połączenia stylu, komfortu i funkcjonalności dzięki kurtce OmniHeat Men\'s Solid Hooded Puffer Jacket. Podnieś swoją zimową garderobę i pozostań przytulny, ciesząc się na zewnątrz. Pokonaj zimno w stylu i zrób wrażenie tym niezbędnym elementem.',
                    'meta-description' => 'opis meta',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Tytuł meta',
                    'name'             => 'Kurtka OmniHeat Men\'s Solid Hooded Puffer Jacket-Niebiesko-Zielona-L',
                    'sort-description' => 'Zostań ciepły i stylowy dzięki naszej kurtce OmniHeat Men\'s Solid Hooded Puffer Jacket. Ta kurtka została zaprojektowana, aby zapewnić ostateczne ciepło i posiada kieszenie na wstawki dla dodatkowej wygody. Izolacyjny materiał zapewnia przytulność w zimnej pogodzie. Dostępna w 5 atrakcyjnych kolorach, jest wszechstronnym wyborem na różne okazje.',
                ],
            ],

            'product-bundle-option-translations' => [
                '1' => [
                    'label' => 'Opcja pakietu 1',
                ],

                '2' => [
                    'label' => 'Opcja pakietu 1',
                ],

                '3' => [
                    'label' => 'Opcja pakietu 2',
                ],

                '4' => [
                    'label' => 'Opcja pakietu 2',
                ],
            ],
        ],
    ],

    'installer' => [
        'index' => [
            'create-administrator' => [
                'admin'            => 'Administrator',
                'bagisto'          => 'Bagisto',
                'confirm-password' => 'Potwierdź hasło',
                'email'            => 'Email',
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
                'warning-message'             => 'Uwaga! Ustawienia domyślnego języka systemowego i domyślnej waluty są trwałe i nie można ich zmienić po ustawieniu.',
                'zambian-kwacha'              => 'Kwacha Zambijska (ZMW)',
            ],

            'sample-products' => [
                'download-sample' => 'pobierz próbkę',
                'no'              => 'Nie',
                'sample-products' => 'Produkty próbne',
                'title'           => 'Produkty próbne',
                'yes'             => 'Tak',
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
                'welcome-title' => 'Welkom bij Bagisto',
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
            'installation-description' => 'Instalacja Bagisto zazwyczaj obejmuje kilka kroków. Oto ogólny zarys procesu instalacji Bagisto',
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
