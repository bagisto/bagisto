<?php

return [
    'seeders' => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'За замовчуванням',
            ],

            'attribute-groups'   => [
                'description'       => 'Опис',
                'general'           => 'Загальні',
                'inventories'       => 'Запаси',
                'meta-description'  => 'Мета-опис',
                'price'             => 'Ціна',
                'settings'          => 'Налаштування',
                'shipping'          => 'Доставка',
            ],

            'attributes'         => [
                'brand'                => 'Бренд',
                'color'                => 'Колір',
                'cost'                 => 'Вартість',
                'description'          => 'Опис',
                'featured'             => 'Рекомендовані',
                'guest-checkout'       => 'Гостьова покупка',
                'height'               => 'Висота',
                'length'               => 'Довжина',
                'manage-stock'         => 'Управління запасами',
                'meta-description'     => 'Мета-опис',
                'meta-keywords'        => 'Мета-ключові слова',
                'meta-title'           => 'Мета-заголовок',
                'name'                 => 'Назва',
                'new'                  => 'Новинка',
                'price'                => 'Ціна',
                'product-number'       => 'Номер продукту',
                'short-description'    => 'Короткий опис',
                'size'                 => 'Розмір',
                'sku'                  => 'Артикул',
                'special-price-from'   => 'Спеціальна ціна від',
                'special-price-to'     => 'Спеціальна ціна до',
                'special-price'        => 'Спеціальна ціна',
                'status'               => 'Статус',
                'tax-category'         => 'Категорія податків',
                'url-key'              => 'URL-ключ',
                'visible-individually' => 'Видимий окремо',
                'weight'               => 'Вага',
                'width'                => 'Ширина',
            ],

            'attribute-options'  => [
                'black'  => 'Чорний',
                'green'  => 'Зелений',
                'l'      => 'L',
                'm'      => 'M',
                'red'    => 'Червоний',
                's'      => 'S',
                'white'  => 'Білий',
                'xl'     => 'XL',
                'yellow' => 'Жовтий',
            ],
        ],

        'category'  => [
            'categories' => [
                'description' => 'Опис кореневої категорії',
                'name'        => 'Коренева',
            ],
        ],

        'cms'       => [
            'pages' => [
                'about-us'         => [
                    'content' => 'Зміст сторінки Про нас',
                    'title'   => 'Про нас',
                ],

                'contact-us'       => [
                    'content' => 'Зміст сторінки Зв\'яжіться з нами',
                    'title'   => 'Зв\'яжіться з нами',
                ],

                'customer-service' => [
                    'content' => 'Зміст сторінки Обслуговування клієнтів',
                    'title'   => 'Обслуговування клієнтів',
                ],

                'payment-policy'   => [
                    'content' => 'Зміст сторінки Політика оплати',
                    'title'   => 'Політика оплати',
                ],

                'privacy-policy'   => [
                    'content' => 'Зміст сторінки Політика конфіденційності',
                    'title'   => 'Політика конфіденційності',
                ],

                'refund-policy'    => [
                    'content' => 'Зміст сторінки Політики повернення',
                    'title'   => 'Політика повернення',
                ],

                'return-policy'    => [
                    'content' => 'Зміст сторінки Політики повернення',
                    'title'   => 'Політика повернення',
                ],

                'shipping-policy'  => [
                    'content' => 'Зміст сторінки Політика доставки',
                    'title'   => 'Політика доставки',
                ],

                'terms-conditions' => [
                    'content' => 'Зміст сторінки Умови та положення',
                    'title'   => 'Умови та положення',
                ],

                'terms-of-use'     => [
                    'content' => 'Зміст сторінки Умови використання',
                    'title'   => 'Умови використання',
                ],

                'whats-new'        => [
                    'content' => 'Зміст сторінки Що нового',
                    'title'   => 'Що нового',
                ],
            ],
        ],

        'core'      => [
            'channels'   => [
                'meta-description' => 'Мета-опис демонстраційного магазину',
                'meta-keywords'    => 'Мета-ключові слова демонстраційного магазину',
                'meta-title'       => 'Демонстраційний магазин',
                'name'             => 'За замовчуванням',
            ],

            'currencies' => [
                'AED' => 'Дирхам',
                'AFN' => 'Ізраїльський шекель',
                'CNY' => 'Китайський юань',
                'EUR' => 'Євро',
                'GBP' => 'Фунт стерлінгів',
                'INR' => 'Індійська рупія',
                'IRR' => 'Іранський ріал',
                'JPY' => 'Японська єна',
                'RUB' => 'Російський рубль',
                'SAR' => 'Саудівський ріял',
                'TRY' => 'Турецька ліра',
                'UAH' => 'Українська гривня',
                'USD' => 'Долар США',
            ],

            'locales'    => [
                'ar'    => 'Арабська',
                'bn'    => 'Бенгальська',
                'de'    => 'Німецька',
                'en'    => 'Англійська',
                'es'    => 'Іспанська',
                'fa'    => 'Перська',
                'fr'    => 'Французька',
                'he'    => 'Іврит',
                'hi_IN' => 'Гінді',
                'it'    => 'Італійська',
                'ja'    => 'Японська',
                'nl'    => 'Нідерландська',
                'pl'    => 'Польська',
                'pt_BR' => 'Бразильська португальська',
                'ru'    => 'Російська',
                'sin'   => 'Сінгальська',
                'tr'    => 'Турецька',
                'uk'    => 'Українська',
                'zh_CN' => 'Китайська (спрощена)',
            ],
        ],

        'customer'  => [
            'customer-groups' => [
                'general'   => 'Загальний',
                'guest'     => 'Гість',
                'wholesale' => 'Оптовий',
            ],
        ],

        'inventory' => [
            'inventory-sources' => [
                'name' => 'За замовчуванням',
            ],
        ],

        'shop' => [
            'theme-customizations' => [
                'all-products'           => [
                    'name'    => 'Усі продукти',

                    'options' => [
                        'title' => 'Усі продукти',
                    ],
                ],

                'bold-collections'       => [
                    'content' => [
                        'btn-title'   => 'Переглянути все',
                        'description' => 'Представляємо наші нові сміливі колекції! Підніміть свій стиль завдяки сміливим дизайнам та яскравим заявам. Відкрийте для себе вишукані малюнки та смілі кольори, які переосмислюють ваш гардероб. Готуйтеся прийняти надзвичайне!',
                        'title'       => 'Готуйтеся до наших нових сміливих колекцій!',
                    ],

                    'name'    => 'Сміливі колекції',
                ],

                'categories-collections' => [
                    'name' => 'Колекції за категоріями',
                ],

                'featured-collections'   => [
                    'name'    => 'Виділені колекції',

                    'options' => [
                        'title' => 'Рекомендовані продукти',
                    ],
                ],

                'footer-links'           => [
                    'name'    => 'Посилання у нижньому колонтитулі',

                    'options' => [
                        'about-us'         => 'Про нас',
                        'contact-us'       => 'Зв\'яжіться з нами',
                        'customer-service' => 'Служба підтримки',
                        'payment-policy'   => 'Політика оплати',
                        'privacy-policy'   => 'Політика конфіденційності',
                        'refund-policy'    => 'Політика повернення коштів',
                        'return-policy'    => 'Політика повернення',
                        'shipping-policy'  => 'Політика доставки',
                        'terms-conditions' => 'Умови та положення',
                        'terms-of-use'     => 'Умови використання',
                        'whats-new'        => 'Що нового',
                    ],
                ],

                'game-container'         => [
                    'content' => [
                        'sub-title-1' => 'Наші колекції',
                        'sub-title-2' => 'Наші колекції',
                        'title'       => 'Гра з нашими новими додатками!',
                    ],

                    'name'    => 'Контейнер з грою',
                ],

                'image-carousel'         => [
                    'name'    => 'Карусель зображень',

                    'sliders' => [
                        'title' => 'Готуйтеся до нової колекції',
                    ],
                ],

                'new-products'           => [
                    'name'    => 'Нові продукти',

                    'options' => [
                        'title' => 'Нові продукти',
                    ],
                ],

                'offer-information'      => [
                    'content'   => [
                        'title' => 'ЗНИЖКА до 40% на ваше 1-ше замовлення ЗАРАЗ',
                    ],

                    'name'      => 'Інформація про пропозицію',
                ],

                'services-content'       => [
                    'description' => [
                        'emi-available-info'   => 'EMI безкоштовно доступно на всіх основних кредитних картках',
                        'free-shipping-info'   => 'Насолоджуйтеся безкоштовною доставкою на всі замовлення',
                        'product-replace-info' => 'Доступна легка заміна продукту!',
                        'time-support-info'    => 'Присвячена підтримка 24/7 через чат та електронну пошту',
                    ],

                    'name'        => 'Вміст послуг',

                    'title'       => [
                        'emi-available'   => 'EMI доступно',
                        'free-shipping'   => 'Безкоштовна доставка',
                        'product-replace' => 'Заміна продукту',
                        'time-support'    => 'Підтримка 24/7',
                    ],
                ],

                'top-collections'        => [
                    'content' => [
                        'sub-title-1' => 'Наші колекції',
                        'sub-title-2' => 'Наші колекції',
                        'sub-title-3' => 'Наші колекції',
                        'sub-title-4' => 'Наші колекції',
                        'sub-title-5' => 'Наші колекції',
                        'sub-title-6' => 'Наші колекції',
                        'title'       => 'Гра з нашими новими додатками!',
                    ],

                    'name' => 'Топові колекції',
                ],
            ],
        ],

        'user' => [
            'roles' => [
                'description' => 'Ця роль надає користувачам всі права доступу',
                'name'        => 'Адміністратор',
            ],

            'users' => [
                'name' => 'Приклад',
            ],
        ],
    ],

    'installer' => [
        'index' => [
            'create-administrator'      => [
                'admin'            => 'Адміністратор',
                'bagisto'          => 'Bagisto',
                'confirm-password' => 'Підтвердіть пароль',
                'email-address'    => 'admin@example.com',
                'email'            => 'Електронна пошта',
                'password'         => 'Пароль',
                'title'            => 'Створити адміністратора',
            ],

            'environment-configuration' => [
                'allowed-currencies'  => 'Дозволені валюти',
                'allowed-locales'     => 'Дозволені локалі',
                'application-name'    => 'Назва програми',
                'bagisto'             => 'Bagisto',
                'chinese-yuan'        => 'Китайський юань (CNY)',
                'database-connection' => 'Підключення до бази даних',
                'database-hostname'   => 'Ім\'я сервера бази даних',
                'database-name'       => 'Назва бази даних',
                'database-password'   => 'Пароль бази даних',
                'database-port'       => 'Порт підключення до бази даних',
                'database-prefix'     => 'Префікс бази даних',
                'database-username'   => 'Ім\'я користувача бази даних',
                'default-currency'    => 'Стандартна валюта',
                'default-locale'      => 'Стандартна локаль',
                'default-timezone'    => 'Стандартна часова зона',
                'default-url-link'    => 'https://localhost',
                'default-url'         => 'Стандартний URL',
                'dirham'              => 'Дирхам (AED)',
                'euro'                => 'Євро (EUR)',
                'iranian'             => 'Іранський ріал (IRR)',
                'israeli'             => 'Ізраїльський шекель (AFN)',
                'japanese-yen'        => 'Японська єна (JPY)',
                'mysql'               => 'Mysql',
                'pgsql'               => 'pgSQL',
                'pound'               => 'Фунт стерлінгів (GBP)',
                'rupee'               => 'Індійська рупія (INR)',
                'russian-ruble'       => 'Російський рубль (RUB)',
                'saudi'               => 'Саудівський ріял (SAR)',
                'select-timezone'     => 'Виберіть часовий пояс',
                'sqlsrv'              => 'SQLSRV',
                'title'               => 'Налаштування середовища',
                'turkish-lira'        => 'Турецька ліра (TRY)',
                'ukrainian-hryvnia'   => 'Українська гривня (UAH)',
                'usd'                 => 'Долар США (USD)',
                'warning-message'     => 'Увага! Налаштування мов системи за замовчуванням, а також основна валюта є постійними і більше не можуть бути змінені.',
            ],

            'installation-processing'   => [
                'bagisto-info'     => 'Створення таблиць бази даних, це може зайняти кілька хвилин',
                'bagisto'          => 'Установка Bagisto',
                'title'            => 'Установка',
            ],

            'installation-completed'    => [
                'admin-panel'                => 'Панель адміністратора',
                'bagisto-forums'             => 'Форум Bagisto',
                'customer-panel'             => 'Панель клієнта',
                'explore-bagisto-extensions' => 'Досліджуйте розширення Bagisto',
                'title-info'                 => 'Bagisto успішно встановлено на вашій системі.',
                'title'                      => 'Установка завершена',
            ],

            'ready-for-installation'    => [
                'create-databsae-table'   => 'Створення таблиці бази даних',
                'install-info-button'     => 'Натисніть кнопку нижче, щоб',
                'install-info'            => 'Bagisto для встановлення',
                'install'                 => 'Встановлення',
                'populate-database-table' => 'Заповнення таблиць бази даних',
                'start-installation'      => 'Почати встановлення',
                'title'                   => 'Готовий до встановлення',
            ],

            'start'                     => [
                'locale'        => 'Локаль',
                'main'          => 'Початок',
                'select-locale' => 'Вибір локалі',
                'title'         => 'Ваша установка Bagisto',
                'welcome-title' => 'Ласкаво просимо до Bagisto 2.0.',
            ],

            'server-requirements'       => [
                'calendar'    => 'Календар',
                'ctype'       => 'cType',
                'curl'        => 'cURL',
                'dom'         => 'dom',
                'fileinfo'    => 'Інформація про файли',
                'filter'      => 'Фільтр',
                'gd'          => 'GD',
                'hash'        => 'Хеш',
                'intl'        => 'intl',
                'json'        => 'JSON',
                'mbstring'    => 'mbstring',
                'openssl'     => 'openssl',
                'pcre'        => 'pcre',
                'pdo'         => 'pdo',
                'php-version' => '8.1 або вище',
                'php'         => 'PHP',
                'session'     => 'сесія',
                'title'       => 'Вимоги до сервера',
                'tokenizer'   => 'токенізатор',
                'xml'         => 'XML',
            ],

            'arabic'                    => 'Арабська',
            'back'                      => 'Назад',
            'bagisto-info'              => 'Спільний проект спільноти від Webkul',
            'bagisto-logo'              => 'Логотип Bagisto',
            'bagisto'                   => 'Bagisto',
            'bengali'                   => 'Бенгальська',
            'chinese'                   => 'Китайська',
            'continue'                  => 'Продовжити',
            'dutch'                     => 'Голландська',
            'english'                   => 'Англійська',
            'french'                    => 'Французька',
            'german'                    => 'Німецька',
            'hebrew'                    => 'Іврит',
            'hindi'                     => 'Гінді',
            'installation-description'  => 'Встановлення Bagisto, як правило, включає кілька етапів. Ось загальний опис процесу встановлення Bagisto:',
            'installation-info'         => 'Ми раді вас бачити тут!',
            'installation-title'        => 'Ласкаво просимо до встановлення Bagisto',
            'italian'                   => 'Італійська',
            'japanese'                  => 'Японська',
            'persian'                   => 'Перська',
            'polish'                    => 'Польська',
            'portuguese'                => 'Бразильська португальська',
            'russian'                   => 'Російська',
            'save-configuration'        => 'Зберегти конфігурацію',
            'sinhala'                   => 'Сингальська',
            'skip'                      => 'Пропустити',
            'spanish'                   => 'Іспанська',
            'title'                     => 'Установник Bagisto',
            'turkish'                   => 'Турецька',
            'ukrainian'                 => 'Українська',
            'webkul'                    => 'Webkul',
        ],
    ],
];
