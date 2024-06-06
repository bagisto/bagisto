<?php

return [
    'seeders' => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'По умолчанию',
            ],

            'attribute-groups' => [
                'description'      => 'Описание',
                'general'          => 'Общее',
                'inventories'      => 'Инвентарь',
                'meta-description' => 'Мета-описание',
                'price'            => 'Цена',
                'settings'         => 'Настройки',
                'shipping'         => 'Доставка',
            ],

            'attributes' => [
                'brand'                => 'Бренд',
                'color'                => 'Цвет',
                'cost'                 => 'Стоимость',
                'description'          => 'Описание',
                'featured'             => 'Популярный',
                'guest-checkout'       => 'Гостевой заказ',
                'height'               => 'Высота',
                'length'               => 'Длина',
                'manage-stock'         => 'Управление запасами',
                'meta-description'     => 'Мета-описание',
                'meta-keywords'        => 'Мета-ключевые слова',
                'meta-title'           => 'Мета-заголовок',
                'name'                 => 'Название',
                'new'                  => 'Новый',
                'price'                => 'Цена',
                'product-number'       => 'Артикул',
                'short-description'    => 'Краткое описание',
                'size'                 => 'Размер',
                'sku'                  => 'Артикул товара (SKU)',
                'special-price'        => 'Специальная цена',
                'special-price-from'   => 'Специальная цена от',
                'special-price-to'     => 'Специальная цена до',
                'status'               => 'Статус',
                'tax-category'         => 'Категория налога',
                'url-key'              => 'Ключ URL',
                'visible-individually' => 'Видимость по отдельности',
                'weight'               => 'Вес',
                'width'                => 'Ширина',
            ],

            'attribute-options' => [
                'black'  => 'Черный',
                'green'  => 'Зеленый',
                'l'      => 'L',
                'm'      => 'M',
                'red'    => 'Красный',
                's'      => 'S',
                'white'  => 'Белый',
                'xl'     => 'XL',
                'yellow' => 'Желтый',
            ],
        ],

        'category' => [
            'categories' => [
                'description' => 'Описание корневой категории',
                'name'        => 'Корень',
            ],
        ],

        'cms' => [
            'pages' => [
                'about-us' => [
                    'content' => 'Содержание страницы "О нас"',
                    'title'   => 'О нас',
                ],

                'contact-us' => [
                    'content' => 'Содержание страницы "Свяжитесь с нами"',
                    'title'   => 'Свяжитесь с нами',
                ],

                'customer-service' => [
                    'content' => 'Содержание страницы "Служба поддержки клиентов"',
                    'title'   => 'Служба поддержки клиентов',
                ],

                'payment-policy' => [
                    'content' => 'Содержание страницы "Политика оплаты"',
                    'title'   => 'Политика оплаты',
                ],

                'privacy-policy' => [
                    'content' => 'Содержание страницы "Политика конфиденциальности"',
                    'title'   => 'Политика конфиденциальности',
                ],

                'refund-policy' => [
                    'content' => 'Содержание страницы "Политика возврата"',
                    'title'   => 'Политика возврата',
                ],

                'return-policy' => [
                    'content' => 'Содержание страницы "Политика возврата"',
                    'title'   => 'Политика возврата',
                ],

                'shipping-policy' => [
                    'content' => 'Содержание страницы "Политика доставки"',
                    'title'   => 'Политика доставки',
                ],

                'terms-conditions' => [
                    'content' => 'Содержание страницы "Условия и положения"',
                    'title'   => 'Условия и положения',
                ],

                'terms-of-use' => [
                    'content' => 'Содержание страницы "Условия использования"',
                    'title'   => 'Условия использования',
                ],

                'whats-new' => [
                    'content' => 'Содержание страницы "Что нового"',
                    'title'   => 'Что нового',
                ],
            ],
        ],

        'core' => [
            'channels' => [
                'meta-description' => 'Мета-описание демонстрационного магазина',
                'meta-keywords'    => 'Мета-ключевые слова демонстрационного магазина',
                'meta-title'       => 'Демонстрационный магазин',
                'name'             => 'По умолчанию',
            ],

            'currencies' => [
                'AED' => 'Дирхам ОАЭ',
                'ARS' => 'Аргентинское песо',
                'AUD' => 'Австралийский доллар',
                'BDT' => 'Бангладешская така',
                'BRL' => 'Бразильский реал',
                'CAD' => 'Канадский доллар',
                'CHF' => 'Швейцарский франк',
                'CLP' => 'Чилийское песо',
                'CNY' => 'Китайский юань',
                'COP' => 'Колумбийское песо',
                'CZK' => 'Чешская крона',
                'DKK' => 'Датская крона',
                'DZD' => 'Алжирский динар',
                'EGP' => 'Египетский фунт',
                'EUR' => 'Евро',
                'FJD' => 'Фиджийский доллар',
                'GBP' => 'Фунт стерлингов',
                'HKD' => 'Гонконгский доллар',
                'HUF' => 'Венгерский форинт',
                'IDR' => 'Индонезийская рупия',
                'ILS' => 'Израильский новый шекель',
                'INR' => 'Индийская рупия',
                'JOD' => 'Иорданский динар',
                'JPY' => 'Японская иена',
                'KRW' => 'Южнокорейская вона',
                'KWD' => 'Кувейтский динар',
                'KZT' => 'Казахстанский тенге',
                'LBP' => 'Ливанский фунт',
                'LKR' => 'Шри-ланкийская рупия',
                'LYD' => 'Ливийский динар',
                'MAD' => 'Марокканский дирхам',
                'MUR' => 'Маврикийская рупия',
                'MXN' => 'Мексиканское песо',
                'MYR' => 'Малайзийский ринггит',
                'NGN' => 'Нигерийская найра',
                'NOK' => 'Норвежская крона',
                'NPR' => 'Непальская рупия',
                'NZD' => 'Новозеландский доллар',
                'OMR' => 'Оманский риал',
                'PAB' => 'Панамский бальбоа',
                'PEN' => 'Перуанский новый соль',
                'PHP' => 'Филиппинское песо',
                'PKR' => 'Пакистанская рупия',
                'PLN' => 'Польский злотый',
                'PYG' => 'Парагвайский гуарани',
                'QAR' => 'Катарский риал',
                'RON' => 'Румынский лей',
                'RUB' => 'Российский рубль',
                'SAR' => 'Саудовский риял',
                'SEK' => 'Шведская крона',
                'SGD' => 'Сингапурский доллар',
                'THB' => 'Таиландский бат',
                'TND' => 'Тунисский динар',
                'TRY' => 'Турецкая лира',
                'TWD' => 'Новый тайваньский доллар',
                'UAH' => 'Украинская гривна',
                'USD' => 'Доллар США',
                'UZS' => 'Узбекский сум',
                'VEF' => 'Венесуэльский боливар',
                'VND' => 'Вьетнамский донг',
                'XAF' => 'Франк КФА ВЕАС',
                'XOF' => 'Франк КФА ВСЕАО',
                'ZAR' => 'Южноафриканский рэнд',
                'ZMW' => 'Замбийская квача',
            ],

            'locales' => [
                'ar'    => 'Арабский',
                'bn'    => 'Бенгальский',
                'de'    => 'Немецкий',
                'en'    => 'Английский',
                'es'    => 'Испанский',
                'fa'    => 'Персидский',
                'fr'    => 'Французский',
                'he'    => 'Иврит',
                'hi_IN' => 'Хинди',
                'it'    => 'Итальянский',
                'ja'    => 'Японский',
                'nl'    => 'Голландский',
                'pl'    => 'Польский',
                'pt_BR' => 'Бразильский португальский',
                'ru'    => 'Русский',
                'sin'   => 'Сингальский',
                'tr'    => 'Турецкий',
                'uk'    => 'Украинский',
                'zh_CN' => 'Китайский',
            ],
        ],

        'customer' => [
            'customer-groups' => [
                'general'   => 'Общий',
                'guest'     => 'Гость',
                'wholesale' => 'Оптовый',
            ],
        ],

        'inventory' => [
            'inventory-sources' => [
                'name' => 'По умолчанию',
            ],
        ],

        'shop' => [
            'theme-customizations' => [
                'all-products' => [
                    'name' => 'Все товары',

                    'options' => [
                        'title' => 'Все товары',
                    ],
                ],

                'bold-collections' => [
                    'content' => [
                        'btn-title'   => 'Просмотреть коллекции',
                        'description' => 'Представляем наши новые смелые коллекции! Поднимите свой стиль с смелыми дизайнами и яркими заявлениями. Исследуйте выдающиеся узоры и яркие цвета, которые переопределяют ваш гардероб. Готовьтесь встретить нечто необычное!',
                        'title'       => 'Подготовьтесь к нашим новым смелым коллекциям!',
                    ],

                    'name' => 'Смелые коллекции',
                ],

                'categories-collections' => [
                    'name' => 'Категории и коллекции',
                ],

                'featured-collections' => [
                    'name' => 'Избранные коллекции',

                    'options' => [
                        'title' => 'Популярные товары',
                    ],
                ],

                'footer-links' => [
                    'name' => 'Ссылки внизу страницы',

                    'options' => [
                        'about-us'         => 'О нас',
                        'contact-us'       => 'Свяжитесь с нами',
                        'customer-service' => 'Служба поддержки',
                        'payment-policy'   => 'Политика оплаты',
                        'privacy-policy'   => 'Политика конфиденциальности',
                        'refund-policy'    => 'Политика возврата средств',
                        'return-policy'    => 'Политика возврата',
                        'shipping-policy'  => 'Политика доставки',
                        'terms-conditions' => 'Условия и положения',
                        'terms-of-use'     => 'Условия использования',
                        'whats-new'        => 'Что нового',
                    ],
                ],

                'game-container' => [
                    'content' => [
                        'sub-title-1' => 'Наши коллекции',
                        'sub-title-2' => 'Наши коллекции',
                        'title'       => 'Игра с нашими новыми добавлениями!',
                    ],

                    'name' => 'Игровой контейнер',
                ],

                'image-carousel' => [
                    'name' => 'Карусель изображений',

                    'sliders' => [
                        'title' => 'Готовьтесь к новой коллекции',
                    ],
                ],

                'new-products' => [
                    'name' => 'Новые товары',

                    'options' => [
                        'title' => 'Новые товары',
                    ],
                ],

                'offer-information' => [
                    'content' => [
                        'title' => 'Скидка до 40% на ваш первый заказ! ПОКУПАЙТЕ СЕЙЧАС',
                    ],

                    'name' => 'Информация о предложениях',
                ],

                'services-content' => [
                    'description' => [
                        'emi-available-info'   => 'EMI без затрат доступно на всех основных кредитных картах',
                        'free-shipping-info'   => 'Наслаждайтесь бесплатной доставкой на все заказы',
                        'product-replace-info' => 'Доступна легкая замена продукта!',
                        'time-support-info'    => 'Посвященная поддержка 24/7 через чат и электронную почту',
                    ],

                    'name' => 'Содержание услуг',

                    'title' => [
                        'emi-available'   => 'EMI доступно',
                        'free-shipping'   => 'Бесплатная доставка',
                        'product-replace' => 'Замена продукта',
                        'time-support'    => 'Поддержка 24/7',
                    ],
                ],

                'top-collections' => [
                    'content' => [
                        'sub-title-1' => 'Наши коллекции',
                        'sub-title-2' => 'Наши коллекции',
                        'sub-title-3' => 'Наши коллекции',
                        'sub-title-4' => 'Наши коллекции',
                        'sub-title-5' => 'Наши коллекции',
                        'sub-title-6' => 'Наши коллекции',
                        'title'       => 'Игра с нашими новыми добавлениями!',
                    ],

                    'name' => 'Лучшие коллекции',
                ],
            ],
        ],

        'user' => [
            'roles' => [
                'description' => 'Эта роль предоставляет пользователям полный доступ',
                'name'        => 'Администратор',
            ],

            'users' => [
                'name' => 'Пример',
            ],
        ],
    ],

    'installer' => [
        'index' => [
            'create-administrator' => [
                'admin'            => 'Администратор',
                'bagisto'          => 'Bagisto',
                'confirm-password' => 'Подтвердите пароль',
                'email'            => 'E-mail',
                'email-address'    => 'admin@example.com',
                'password'         => 'Пароль',
                'title'            => 'Создать администратора',
            ],

            'environment-configuration' => [
                'algerian-dinar'              => 'Алжирский динар (DZD)',
                'allowed-currencies'          => 'Разрешенные валюты',
                'allowed-locales'             => 'Разрешенные локали',
                'application-name'            => 'Название приложения',
                'argentine-peso'              => 'Аргентинское песо (ARS)',
                'australian-dollar'           => 'Австралийский доллар (AUD)',
                'bagisto'                     => 'Bagisto',
                'bangladeshi-taka'            => 'Бангладешская така (BDT)',
                'brazilian-real'              => 'Бразильский реал (BRL)',
                'british-pound-sterling'      => 'Фунт стерлингов (GBP)',
                'canadian-dollar'             => 'Канадский доллар (CAD)',
                'cfa-franc-bceao'             => 'Франк CFA BCEAO (XOF)',
                'cfa-franc-beac'              => 'Франк CFA BEAC (XAF)',
                'chilean-peso'                => 'Чилийское песо (CLP)',
                'chinese-yuan'                => 'Китайский юань (CNY)',
                'colombian-peso'              => 'Колумбийское песо (COP)',
                'czech-koruna'                => 'Чешская крона (CZK)',
                'danish-krone'                => 'Датская крона (DKK)',
                'database-connection'         => 'Подключение к базе данных',
                'database-hostname'           => 'Имя хоста базы данных',
                'database-name'               => 'Имя базы данных',
                'database-password'           => 'Пароль базы данных',
                'database-port'               => 'Порт базы данных',
                'database-prefix'             => 'Префикс базы данных',
                'database-username'           => 'Имя пользователя базы данных',
                'default-currency'            => 'Валюта по умолчанию',
                'default-locale'              => 'Локаль по умолчанию',
                'default-timezone'            => 'Часовой пояс по умолчанию',
                'default-url'                 => 'URL по умолчанию',
                'default-url-link'            => 'https://localhost',
                'egyptian-pound'              => 'Египетский фунт (EGP)',
                'euro'                        => 'Евро (EUR)',
                'fijian-dollar'               => 'Фиджийский доллар (FJD)',
                'hong-kong-dollar'            => 'Гонконгский доллар (HKD)',
                'hungarian-forint'            => 'Венгерский форинт (HUF)',
                'indian-rupee'                => 'Индийская рупия (INR)',
                'indonesian-rupiah'           => 'Индонезийская рупия (IDR)',
                'israeli-new-shekel'          => 'Израильский новый шекель (ILS)',
                'japanese-yen'                => 'Японская иена (JPY)',
                'jordanian-dinar'             => 'Иорданский динар (JOD)',
                'kazakhstani-tenge'           => 'Казахский тенге (KZT)',
                'kuwaiti-dinar'               => 'Кувейтский динар (KWD)',
                'lebanese-pound'              => 'Ливанский фунт (LBP)',
                'libyan-dinar'                => 'Ливийский динар (LYD)',
                'malaysian-ringgit'           => 'Малайзийский ринггит (MYR)',
                'mauritian-rupee'             => 'Маврикийская рупия (MUR)',
                'mexican-peso'                => 'Мексиканское песо (MXN)',
                'moroccan-dirham'             => 'Марокканский дирхам (MAD)',
                'mysql'                       => 'MySQL',
                'nepalese-rupee'              => 'Непальская рупия (NPR)',
                'new-taiwan-dollar'           => 'Новый тайваньский доллар (TWD)',
                'new-zealand-dollar'          => 'Новозеландский доллар (NZD)',
                'nigerian-naira'              => 'Нигерийская найра (NGN)',
                'norwegian-krone'             => 'Норвежская крона (NOK)',
                'omani-rial'                  => 'Оманский риал (OMR)',
                'pakistani-rupee'             => 'Пакистанская рупия (PKR)',
                'panamanian-balboa'           => 'Панамский бальбоа (PAB)',
                'paraguayan-guarani'          => 'Парагвайский гуарани (PYG)',
                'peruvian-nuevo-sol'          => 'Перуанский новый соль (PEN)',
                'pgsql'                       => 'PgSQL',
                'philippine-peso'             => 'Филиппинское песо (PHP)',
                'polish-zloty'                => 'Польский злотый (PLN)',
                'qatari-rial'                 => 'Катарский риал (QAR)',
                'romanian-leu'                => 'Румынский лей (RON)',
                'russian-ruble'               => 'Российский рубль (RUB)',
                'saudi-riyal'                 => 'Саудовский риял (SAR)',
                'select-timezone'             => 'Выберите часовой пояс',
                'singapore-dollar'            => 'Сингапурский доллар (SGD)',
                'south-african-rand'          => 'Южноафриканский рэнд (ZAR)',
                'south-korean-won'            => 'Южнокорейская вона (KRW)',
                'sqlsrv'                      => 'SQLSRV',
                'sri-lankan-rupee'            => 'Шри-Ланкийская рупия (LKR)',
                'swedish-krona'               => 'Шведская крона (SEK)',
                'swiss-franc'                 => 'Швейцарский франк (CHF)',
                'thai-baht'                   => 'Таиландский бат (THB)',
                'title'                       => 'Настройки магазина',
                'tunisian-dinar'              => 'Тунисский динар (TND)',
                'turkish-lira'                => 'Турецкая лира (TRY)',
                'ukrainian-hryvnia'           => 'Украинская гривна (UAH)',
                'united-arab-emirates-dirham' => 'Дирхам Объединенных Арабских Эмиратов (AED)',
                'united-states-dollar'        => 'Доллар США (USD)',
                'uzbekistani-som'             => 'Узбекский сум (UZS)',
                'venezuelan-bolívar'          => 'Венесуэльский боливар (VEF)',
                'vietnamese-dong'             => 'Вьетнамский донг (VND)',
                'warning-message'             => 'Внимание! Настройки вашего языка системы по умолчанию, а также валюты по умолчанию являются постоянными и больше никогда не могут быть изменены.',
                'zambian-kwacha'              => 'Замбийская квача (ZMW)',
            ],

            'installation-processing' => [
                'bagisto'          => 'Установка Bagisto',
                'bagisto-info'     => 'Создание таблиц в базе данных может занять несколько моментов',
                'title'            => 'Установка',
            ],

            'installation-completed' => [
                'admin-panel'                => 'Панель администратора',
                'bagisto-forums'             => 'Форум Bagisto',
                'customer-panel'             => 'Панель клиента',
                'explore-bagisto-extensions' => 'Изучите расширения Bagisto',
                'title'                      => 'Установка завершена',
                'title-info'                 => 'Bagisto успешно установлен на вашей системе.',
            ],

            'ready-for-installation' => [
                'create-databsae-table'   => 'Создать таблицу базы данных',
                'install'                 => 'Установка',
                'install-info'            => 'Bagisto для установки',
                'install-info-button'     => 'Нажмите кнопку ниже, чтобы',
                'populate-database-table' => 'Заполнить таблицы базы данных',
                'start-installation'      => 'Начать установку',
                'title'                   => 'Готово к установке',
            ],

            'start' => [
                'locale'        => 'Локаль',
                'main'          => 'Начало',
                'select-locale' => 'Выбрать локаль',
                'title'         => 'Ваша установка Bagisto',
                'welcome-title' => 'Добро пожаловать в Bagisto 2.0.',
            ],

            'server-requirements' => [
                'calendar'    => 'Календарь',
                'ctype'       => 'cType',
                'curl'        => 'cURL',
                'dom'         => 'DOM',
                'fileinfo'    => 'Информация о файле',
                'filter'      => 'Фильтр',
                'gd'          => 'GD',
                'hash'        => 'Хэш',
                'intl'        => 'intl',
                'json'        => 'JSON',
                'mbstring'    => 'mbstring',
                'openssl'     => 'OpenSSL',
                'pcre'        => 'PCRE',
                'pdo'         => 'PDO',
                'php'         => 'PHP',
                'php-version' => '8.1 или выше',
                'session'     => 'Сессия',
                'title'       => 'Требования к серверу',
                'tokenizer'   => 'tokenizer',
                'xml'         => 'XML',
            ],

            'arabic'                   => 'Арабский',
            'back'                     => 'Назад',
            'bagisto'                  => 'Bagisto',
            'bagisto-info'             => 'Проект сообщества от',
            'bagisto-logo'             => 'Логотип Bagisto',
            'bengali'                  => 'Бенгальский',
            'chinese'                  => 'Китайский',
            'continue'                 => 'Продолжить',
            'dutch'                    => 'Голландский',
            'english'                  => 'Английский',
            'french'                   => 'Французский',
            'german'                   => 'Немецкий',
            'hebrew'                   => 'Иврит',
            'hindi'                    => 'Хинди',
            'installation-description' => 'Установка Bagisto обычно включает несколько шагов. Вот общий контур процесса установки для Bagisto:',
            'installation-info'        => 'Мы рады видеть вас здесь!',
            'installation-title'       => 'Добро пожаловать к установке',
            'italian'                  => 'Итальянский',
            'japanese'                 => 'Японский',
            'persian'                  => 'Персидский',
            'polish'                   => 'Польский',
            'portuguese'               => 'Португальский (Бразильский)',
            'russian'                  => 'Русский',
            'sinhala'                  => 'Сингальский',
            'spanish'                  => 'Испанский',
            'title'                    => 'Установщик Bagisto',
            'turkish'                  => 'Турецкий',
            'ukrainian'                => 'Украинский',
            'webkul'                   => 'Webkul',
        ],
    ],
];
