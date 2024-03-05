<?php

return [
    'seeders'   => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'По умолчанию',
            ],

            'attribute-groups'   => [
                'description'       => 'Описание',
                'general'           => 'Общее',
                'inventories'       => 'Инвентарь',
                'meta-description'  => 'Мета-описание',
                'price'             => 'Цена',
                'settings'          => 'Настройки',
                'shipping'          => 'Доставка',
            ],

            'attributes'         => [
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
                'special-price-from'   => 'Специальная цена от',
                'special-price-to'     => 'Специальная цена до',
                'special-price'        => 'Специальная цена',
                'status'               => 'Статус',
                'tax-category'         => 'Категория налога',
                'url-key'              => 'Ключ URL',
                'visible-individually' => 'Видимость по отдельности',
                'weight'               => 'Вес',
                'width'                => 'Ширина',
            ],

            'attribute-options'  => [
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

        'category'  => [
            'categories' => [
                'description' => 'Описание корневой категории',
                'name'        => 'Корень',
            ],
        ],

        'cms'       => [
            'pages' => [
                'about-us'         => [
                    'content' => 'Содержание страницы "О нас"',
                    'title'   => 'О нас',
                ],

                'contact-us'       => [
                    'content' => 'Содержание страницы "Свяжитесь с нами"',
                    'title'   => 'Свяжитесь с нами',
                ],

                'customer-service' => [
                    'content' => 'Содержание страницы "Служба поддержки клиентов"',
                    'title'   => 'Служба поддержки клиентов',
                ],

                'payment-policy'   => [
                    'content' => 'Содержание страницы "Политика оплаты"',
                    'title'   => 'Политика оплаты',
                ],

                'privacy-policy'   => [
                    'content' => 'Содержание страницы "Политика конфиденциальности"',
                    'title'   => 'Политика конфиденциальности',
                ],

                'refund-policy'    => [
                    'content' => 'Содержание страницы "Политика возврата"',
                    'title'   => 'Политика возврата',
                ],

                'return-policy'    => [
                    'content' => 'Содержание страницы "Политика возврата"',
                    'title'   => 'Политика возврата',
                ],

                'shipping-policy'  => [
                    'content' => 'Содержание страницы "Политика доставки"',
                    'title'   => 'Политика доставки',
                ],

                'terms-conditions' => [
                    'content' => 'Содержание страницы "Условия и положения"',
                    'title'   => 'Условия и положения',
                ],

                'terms-of-use'     => [
                    'content' => 'Содержание страницы "Условия использования"',
                    'title'   => 'Условия использования',
                ],

                'whats-new'        => [
                    'content' => 'Содержание страницы "Что нового"',
                    'title'   => 'Что нового',
                ],
            ],
        ],

        'core'      => [
            'channels'   => [
                'meta-description' => 'Мета-описание демонстрационного магазина',
                'meta-keywords'    => 'Мета-ключевые слова демонстрационного магазина',
                'meta-title'       => 'Демонстрационный магазин',
                'name'             => 'По умолчанию',
            ],

            'currencies' => [
                'AED' => 'Дирхам',
                'AFN' => 'Израильский шекель',
                'CNY' => 'Китайский юань',
                'EUR' => 'Евро',
                'GBP' => 'Фунт стерлингов',
                'INR' => 'Индийская рупия',
                'IRR' => 'Иранский риал',
                'JPY' => 'Японская йена',
                'RUB' => 'Российский рубль',
                'SAR' => 'Саудовский риял',
                'TRY' => 'Турецкая лира',
                'UAH' => 'Украинская гривна',
                'USD' => 'Доллар США',
            ],

            'locales'    => [
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

        'customer'  => [
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

        'shop'      => [
            'theme-customizations' => [
                'all-products'           => [
                    'name'    => 'Все товары',

                    'options' => [
                        'title' => 'Все товары',
                    ],
                ],

                'bold-collections'       => [
                    'content' => [
                        'btn-title'   => 'Посмотреть все',
                        'description' => 'Представляем наши новые смелые коллекции! Поднимите свой стиль с смелыми дизайнами и яркими заявлениями. Исследуйте выдающиеся узоры и яркие цвета, которые переопределяют ваш гардероб. Готовьтесь встретить нечто необычное!',
                        'title'       => 'Подготовьтесь к нашим новым смелым коллекциям!',
                    ],

                    'name' => 'Смелые коллекции',
                ],

                'categories-collections' => [
                    'name' => 'Категории и коллекции',
                ],

                'featured-collections'   => [
                    'name'    => 'Избранные коллекции',

                    'options' => [
                        'title' => 'Популярные товары',
                    ],
                ],

                'footer-links'           => [
                    'name'    => 'Ссылки внизу страницы',

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

                'game-container'         => [
                    'content' => [
                        'sub-title-1' => 'Наши коллекции',
                        'sub-title-2' => 'Наши коллекции',
                        'title'       => 'Игра с нашими новыми добавлениями!',
                    ],

                    'name'    => 'Игровой контейнер',
                ],

                'image-carousel'         => [
                    'name'    => 'Карусель изображений',

                    'sliders' => [
                        'title' => 'Готовьтесь к новой коллекции',
                    ],
                ],

                'new-products'           => [
                    'name'    => 'Новые товары',

                    'options' => [
                        'title' => 'Новые товары',
                    ],
                ],

                'offer-information'      => [
                    'content' => [
                        'title' => 'Скидка до 40% на ваш первый заказ! ПОКУПАЙТЕ СЕЙЧАС',
                    ],

                    'name'    => 'Информация о предложениях',
                ],

                'services-content'       => [
                    'description' => [
                        'emi-available-info'   => 'EMI без затрат доступно на всех основных кредитных картах',
                        'free-shipping-info'   => 'Наслаждайтесь бесплатной доставкой на все заказы',
                        'product-replace-info' => 'Доступна легкая замена продукта!',
                        'time-support-info'    => 'Посвященная поддержка 24/7 через чат и электронную почту',
                    ],

                    'name'        => 'Содержание услуг',

                    'title'       => [
                        'emi-available'   => 'EMI доступно',
                        'free-shipping'   => 'Бесплатная доставка',
                        'product-replace' => 'Замена продукта',
                        'time-support'    => 'Поддержка 24/7',
                    ],
                ],

                'top-collections'        => [
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

        'user'      => [
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
            'create-administrator'      => [
                'admin'            => 'Администратор',
                'bagisto'          => 'Bagisto',
                'confirm-password' => 'Подтвердите пароль',
                'email-address'    => 'admin@example.com',
                'email'            => 'E-mail',
                'password'         => 'Пароль',
                'title'            => 'Создать администратора',
            ],

            'environment-configuration' => [
                'allowed-currencies'  => 'Разрешенные валюты',
                'allowed-locales'     => 'Разрешенные языки',
                'application-name'    => 'Имя приложения',
                'bagisto'             => 'Bagisto',
                'chinese-yuan'        => 'Китайский юань (CNY)',
                'database-connection' => 'Подключение к базе данных',
                'database-hostname'   => 'Имя хоста базы данных',
                'database-name'       => 'Имя базы данных',
                'database-password'   => 'Пароль базы данных',
                'database-port'       => 'Порт базы данных',
                'database-prefix'     => 'Префикс базы данных',
                'database-username'   => 'Имя пользователя базы данных',
                'default-currency'    => 'Валюта по умолчанию',
                'default-locale'      => 'Локаль по умолчанию',
                'default-timezone'    => 'Часовой пояс по умолчанию',
                'default-url-link'    => 'https://localhost',
                'default-url'         => 'URL по умолчанию',
                'dirham'              => 'Дирхам (AED)',
                'euro'                => 'Евро (EUR)',
                'iranian'             => 'Иранский риал (IRR)',
                'israeli'             => 'Израильский шекель (AFN)',
                'japanese-yen'        => 'Японская йена (JPY)',
                'mysql'               => 'Mysql',
                'pgsql'               => 'pgSQL',
                'pound'               => 'Фунт стерлингов (GBP)',
                'rupee'               => 'Индийская рупия (INR)',
                'russian-ruble'       => 'Российский рубль (RUB)',
                'saudi'               => 'Саудовский риял (SAR)',
                'select-timezone'     => 'Выберите часовой пояс',
                'sqlsrv'              => 'SQLSRV',
                'title'               => 'Настройка окружения',
                'turkish-lira'        => 'Турецкая лира (TRY)',
                'ukrainian-hryvnia'   => 'Украинская гривна (UAH)',
                'usd'                 => 'Доллар США (USD)',
                'warning-message'     => 'Внимание! Настройки языков системы по умолчанию и базовой валюты являются постоянными и больше не могут быть изменены.',
            ],

            'installation-processing'   => [
                'bagisto-info'     => 'Создание таблиц в базе данных может занять несколько моментов',
                'bagisto'          => 'Установка Bagisto',
                'title'            => 'Установка',
            ],

            'installation-completed'    => [
                'admin-panel'                => 'Панель администратора',
                'bagisto-forums'             => 'Форум Bagisto',
                'customer-panel'             => 'Панель клиента',
                'explore-bagisto-extensions' => 'Изучите расширения Bagisto',
                'title-info'                 => 'Bagisto успешно установлен на вашей системе.',
                'title'                      => 'Установка завершена',
            ],

            'ready-for-installation'    => [
                'create-databsae-table'   => 'Создать таблицу базы данных',
                'install-info-button'     => 'Нажмите кнопку ниже, чтобы',
                'install-info'            => 'Bagisto для установки',
                'install'                 => 'Установка',
                'populate-database-table' => 'Заполнить таблицы базы данных',
                'start-installation'      => 'Начать установку',
                'title'                   => 'Готово к установке',
            ],

            'start'                     => [
                'locale'        => 'Локаль',
                'main'          => 'Начало',
                'select-locale' => 'Выбрать локаль',
                'title'         => 'Ваша установка Bagisto',
                'welcome-title' => 'Добро пожаловать в Bagisto 2.0.',
            ],

            'server-requirements'       => [
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
                'php-version' => '8.1 или выше',
                'php'         => 'PHP',
                'session'     => 'Сессия',
                'title'       => 'Требования к серверу',
                'tokenizer'   => 'tokenizer',
                'xml'         => 'XML',
            ],

            'arabic'                    => 'Арабский',
            'back'                      => 'Назад',
            'bagisto-info'              => 'Проект сообщества от',
            'bagisto-logo'              => 'Логотип Bagisto',
            'bagisto'                   => 'Bagisto',
            'bengali'                   => 'Бенгальский',
            'chinese'                   => 'Китайский',
            'continue'                  => 'Продолжить',
            'dutch'                     => 'Голландский',
            'english'                   => 'Английский',
            'french'                    => 'Французский',
            'german'                    => 'Немецкий',
            'hebrew'                    => 'Иврит',
            'hindi'                     => 'Хинди',
            'installation-description'  => 'Установка Bagisto обычно включает несколько шагов. Вот общий контур процесса установки для Bagisto:',
            'installation-info'         => 'Мы рады видеть вас здесь!',
            'installation-title'        => 'Добро пожаловать к установке',
            'italian'                   => 'Итальянский',
            'japanese'                  => 'Японский',
            'persian'                   => 'Персидский',
            'polish'                    => 'Польский',
            'portuguese'                => 'Португальский (Бразильский)',
            'russian'                   => 'Русский',
            'save-configuration'        => 'Сохранить конфигурацию',
            'sinhala'                   => 'Сингальский',
            'skip'                      => 'Пропустить',
            'spanish'                   => 'Испанский',
            'title'                     => 'Установщик Bagisto',
            'turkish'                   => 'Турецкий',
            'ukrainian'                 => 'Украинский',
            'webkul'                    => 'Webkul',
        ],
    ],
];
