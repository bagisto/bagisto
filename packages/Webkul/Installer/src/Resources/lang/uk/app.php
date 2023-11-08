<?php

return [
    'seeders' => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'За замовчуванням',
            ],

            'attribute-groups' => [
                'description'       => 'Опис',
                'general'           => 'Загальні',
                'inventories'       => 'Запаси',
                'meta-description'  => 'Мета-опис',
                'price'             => 'Ціна',
                'shipping'          => 'Доставка',
                'settings'          => 'Налаштування',
            ],

            'attributes' => [
                'brand'                => 'Бренд',
                'color'                => 'Колір',
                'cost'                 => 'Вартість',
                'description'          => 'Опис',
                'featured'             => 'Рекомендовані',
                'guest-checkout'       => 'Гостьова покупка',
                'height'               => 'Висота',
                'length'               => 'Довжина',
                'meta-title'           => 'Мета-заголовок',
                'meta-keywords'        => 'Мета-ключові слова',
                'meta-description'     => 'Мета-опис',
                'manage-stock'         => 'Управління запасами',
                'new'                  => 'Новинка',
                'name'                 => 'Назва',
                'product-number'       => 'Номер продукту',
                'price'                => 'Ціна',
                'sku'                  => 'Артикул',
                'status'               => 'Статус',
                'short-description'    => 'Короткий опис',
                'special-price'        => 'Спеціальна ціна',
                'special-price-from'   => 'Спеціальна ціна від',
                'special-price-to'     => 'Спеціальна ціна до',
                'size'                 => 'Розмір',
                'tax-category'         => 'Категорія податків',
                'url-key'              => 'URL-ключ',
                'visible-individually' => 'Видимий окремо',
                'width'                => 'Ширина',
                'weight'               => 'Вага',
            ],

            'attribute-options' => [
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

        'category' => [
            'categories' => [
                'description' => 'Опис кореневої категорії',
                'name'        => 'Коренева',
            ],
        ],

        'cms' => [
            'pages' => [
                'about-us' => [
                    'content' => 'Зміст сторінки Про нас',
                    'title'   => 'Про нас',
                ],

                'refund-policy' => [
                    'content' => 'Зміст сторінки Політики повернення',
                    'title'   => 'Політика повернення',
                ],

                'return-policy' => [
                    'content' => 'Зміст сторінки Політики повернення',
                    'title'   => 'Політика повернення',
                ],

                'terms-conditions' => [
                    'content' => 'Зміст сторінки Умови та положення',
                    'title'   => 'Умови та положення',
                ],

                'terms-of-use' => [
                    'content' => 'Зміст сторінки Умови використання',
                    'title'   => 'Умови використання',
                ],

                'contact-us' => [
                    'content' => 'Зміст сторінки Зв\'яжіться з нами',
                    'title'   => 'Зв\'яжіться з нами',
                ],

                'customer-service' => [
                    'content' => 'Зміст сторінки Обслуговування клієнтів',
                    'title'   => 'Обслуговування клієнтів',
                ],

                'whats-new' => [
                    'content' => 'Зміст сторінки Що нового',
                    'title'   => 'Що нового',
                ],

                'payment-policy' => [
                    'content' => 'Зміст сторінки Політика оплати',
                    'title'   => 'Політика оплати',
                ],

                'shipping-policy' => [
                    'content' => 'Зміст сторінки Політика доставки',
                    'title'   => 'Політика доставки',
                ],

                'privacy-policy' => [
                    'content' => 'Зміст сторінки Політика конфіденційності',
                    'title'   => 'Політика конфіденційності',
                ],
            ],
        ],

        'core' => [
            'channels' => [
                'meta-title'       => 'Демонстраційний магазин',
                'meta-keywords'    => 'Мета-ключові слова демонстраційного магазину',
                'meta-description' => 'Мета-опис демонстраційного магазину',
                'name'             => 'За замовчуванням',
            ],

            'currencies' => [
                'CNY' => 'Китайський юань',
                'AED' => 'Дирхам',
                'EUR' => 'Євро',
                'INR' => 'Індійська рупія',
                'IRR' => 'Іранський ріал',
                'ILS' => 'Ізраїльський шекель',
                'JPY' => 'Японська єна',
                'GBP' => 'Фунт стерлінгів',
                'RUB' => 'Російський рубль',
                'SAR' => 'Саудівський ріял',
                'TRY' => 'Турецька ліра',
                'USD' => 'Долар США',
                'UAH' => 'Українська гривня',
            ],

            'locales' => [
                'ar'    => 'Арабська',
                'bn'    => 'Бенгальська',
                'de'    => 'Німецька',
                'es'    => 'Іспанська',
                'en'    => 'Англійська',
                'fr'    => 'Французька',
                'fa'    => 'Перська',
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

        'customer' => [
            'customer-groups' => [
                'guest'     => 'Гість',
                'general'   => 'Загальний',
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
                'image-carousel' => [
                    'name'  => 'Карусель зображень',

                    'sliders' => [
                        'title' => 'Готуйтеся до нової колекції',
                    ],
                ],

                'offer-information' => [
                    'name' => 'Інформація про пропозицію',

                    'content' => [
                        'title' => 'ЗНИЖКА до 40% на ваше 1-ше замовлення ЗАРАЗ',
                    ],
                ],

                'categories-collections' => [
                    'name' => 'Колекції за категоріями',
                ],

                'new-products' => [
                    'name' => 'Нові продукти',

                    'options' => [
                        'title' => 'Нові продукти',
                    ],
                ],

                'top-collections' => [
                    'name' => 'Топові колекції',

                    'content' => [
                        'sub-title-1' => 'Наші колекції',
                        'sub-title-2' => 'Наші колекції',
                        'sub-title-3' => 'Наші колекції',
                        'sub-title-4' => 'Наші колекції',
                        'sub-title-5' => 'Наші колекції',
                        'sub-title-6' => 'Наші колекції',
                        'title'       => 'Гра з нашими новими додатками!',
                    ],
                ],

                'bold-collections' => [
                    'name' => 'Сміливі колекції',

                    'content' => [
                        'btn-title'   => 'Переглянути все',
                        'description' => 'Представляємо наші нові сміливі колекції! Підніміть свій стиль завдяки сміливим дизайнам та яскравим заявам. Відкрийте для себе вишукані малюнки та смілі кольори, які переосмислюють ваш гардероб. Готуйтеся прийняти надзвичайне!',
                        'title'       => 'Готуйтеся до наших нових сміливих колекцій!',
                    ],
                ],

                'featured-collections' => [
                    'name' => 'Виділені колекції',

                    'options' => [
                        'title' => 'Рекомендовані продукти',
                    ],
                ],

                'game-container' => [
                    'name' => 'Контейнер з грою',

                    'content' => [
                        'sub-title-1' => 'Наші колекції',
                        'sub-title-2' => 'Наші колекції',
                        'title'       => 'Гра з нашими новими додатками!',
                    ],
                ],

                'all-products' => [
                    'name' => 'Усі продукти',

                    'options' => [
                        'title' => 'Усі продукти',
                    ],
                ],

                'footer-links' => [
                    'name' => 'Посилання у нижньому колонтитулі',

                    'options' => [
                        'about-us'         => 'Про нас',
                        'contact-us'       => 'Зв\'яжіться з нами',
                        'customer-service' => 'Служба підтримки',
                        'privacy-policy'   => 'Політика конфіденційності',
                        'payment-policy'   => 'Політика оплати',
                        'return-policy'    => 'Політика повернення',
                        'refund-policy'    => 'Політика повернення коштів',
                        'shipping-policy'  => 'Політика доставки',
                        'terms-of-use'     => 'Умови використання',
                        'terms-conditions' => 'Умови та положення',
                        'whats-new'        => 'Що нового',
                    ],
                ],

                'services-content' => [
                    'name'  => 'Вміст послуг',

                    'title' => [
                        'free-shipping'     => 'Безкоштовна доставка',
                        'product-replace'   => 'Заміна продукту',
                        'emi-available'     => 'EMI доступно',
                        'time-support'      => 'Підтримка 24/7',
                    ],

                    'description' => [
                        'free-shipping-info'     => 'Насолоджуйтеся безкоштовною доставкою на всі замовлення',
                        'product-replace-info'   => 'Доступна легка заміна продукту!',
                        'emi-available-info'     => 'EMI безкоштовно доступно на всіх основних кредитних картках',
                        'time-support-info'      => 'Присвячена підтримка 24/7 через чат та електронну пошту',
                    ],
                ],
            ],
        ],

        'user' => [
            'users' => [
                'name' => 'Приклад',
            ],

            'roles' => [
                'description' => 'Ця роль надає користувачам всі права доступу',
                'name'        => 'Адміністратор',
            ],
        ],
    ],

    'installer' => [
        'index' => [
            'server-requirements' => [
                'calendar'    => 'Календар',
                'ctype'       => 'cType',
                'curl'        => 'cURL',
                'dom'         => 'dom',
                'fileinfo'    => 'fileInfo',
                'filter'      => 'Фільтр',
                'gd'          => 'GD',
                'hash'        => 'Хеш',
                'intl'        => 'intl',
                'json'        => 'JSON',
                'mbstring'    => 'mbstring',
                'openssl'     => 'openssl',
                'php'         => 'PHP',
                'php-version' => '8.1 або вище',
                'pcre'        => 'pcre',
                'pdo'         => 'pdo',
                'session'     => 'сесія',
                'title'       => 'Вимоги до сервера',
                'tokenizer'   => 'токенізатор',
                'xml'         => 'XML',
            ],

            'environment-configuration' => [
                'application-name'    => 'Назва додатка',
                'arabic'              => 'Арабська',
                'bagisto'             => 'Bagisto',
                'bengali'             => 'Бенгальська',
                'chinese-yuan'        => 'Китайський юань (CNY)',
                'chinese'             => 'Китайська',
                'dirham'              => 'Дірхам (AED)',
                'default-url'         => 'За замовчуванням URL',
                'default-url-link'    => 'https://localhost',
                'default-currency'    => 'Валюта за замовчуванням',
                'default-timezone'    => 'Часовий пояс за замовчуванням',
                'default-locale'      => 'Локаль за замовчуванням',
                'dutch'               => 'Голландська',
                'database-connection' => 'Підключення до бази даних',
                'database-hostname'   => 'Назва хоста бази даних',
                'database-port'       => 'Порт бази даних',
                'database-name'       => 'Назва бази даних',
                'database-username'   => 'Ім\'я користувача бази даних',
                'database-prefix'     => 'Префікс бази даних',
                'database-password'   => 'Пароль бази даних',
                'euro'                => 'Євро (EUR)',
                'english'             => 'Англійська',
                'french'              => 'Французька',
                'hebrew'              => 'Іврит',
                'hindi'               => 'Гінді',
                'iranian'             => 'Іранський ріал (IRR)',
                'israeli'             => 'Ізраїльський шекель (ILS)',
                'italian'             => 'Італійська',
                'japanese-yen'        => 'Японська єна (JPY)',
                'japanese'            => 'Японська',
                'mysql'               => 'Mysql',
                'pgsql'               => 'pgSQL',
                'pound'               => 'Фунт стерлінгів (GBP)',
                'persian'             => 'Перська',
                'polish'              => 'Польська',
                'portuguese'          => 'Португальська (Бразильська)',
                'rupee'               => 'Індійська рупія (INR)',
                'russian-ruble'       => 'Російський рубль (RUB)',
                'russian'             => 'Російська',
                'sqlsrv'              => 'SQLSRV',
                'saudi'               => 'Саудівський ріял (SAR)',
                'spanish'             => 'Іспанська',
                'sinhala'             => 'Сингальська',
                'title'               => 'Конфігурація середовища',
                'turkish-lira'        => 'Турецька ліра (TRY)',
                'turkish'             => 'Турецька',
                'usd'                 => 'Долар США (USD)',
                'ukrainian-hryvnia'   => 'Українська гривня (UAH)',
                'ukrainian'           => 'Українська',
            ],

            'ready-for-installation' => [
                'create-database-table'   => 'Створити таблицю бази даних',
                'install'                 => 'Установка',
                'install-info'            => 'Bagisto для установки',
                'install-info-button'     => 'Клацніть кнопку нижче, щоб',
                'populate-database-table' => 'Заповнити таблиці бази даних',
                'start-installation'      => 'Почати установку',
                'title'                   => 'Готовий до установки',
            ],

            'installation-processing' => [
                'bagisto'          => 'Установка Bagisto',
                'bagisto-info'     => 'Створення таблиць бази даних, це може зайняти кілька хвилин',
                'title'            => 'Установка',
            ],

            'create-administrator' => [
                'admin'            => 'Адміністратор',
                'bagisto'          => 'Bagisto',
                'confirm-password' => 'Підтвердіть пароль',
                'email'            => 'Електронна пошта',
                'email-address'    => 'admin@example.com',
                'password'         => 'Пароль',
                'title'            => 'Створити адміністратора',
            ],

            'email-configuration' => [
                'encryption'           => 'Шифрування',
                'enter-username'       => 'Введіть ім\'я користувача',
                'enter-password'       => 'Введіть пароль',
                'outgoing-mail-server' => 'Вихідний поштовий сервер',
                'outgoing-email'       => 'smpt.mailtrap.io',
                'password'             => 'Пароль',
                'store-email'          => 'Адреса електронної пошти магазину',
                'enter-store-email'    => 'Введіть адресу електронної пошти магазину',
                'server-port'          => 'Порт сервера',
                'server-port-code'     => '3306',
                'title'                => 'Налаштування електронної пошти',
                'username'             => 'Ім\'я користувача',
            ],

            'installation-completed' => [
                'admin-panel'                => 'Панель адміністратора',
                'bagisto-forums'             => 'Форум Bagisto',
                'customer-panel'             => 'Панель клієнта',
                'explore-bagisto-extensions' => 'Досліджуйте розширення Bagisto',
                'title'                      => 'Установка завершена',
                'title-info'                 => 'Bagisto успішно встановлено на вашій системі.',
            ],

            'bagisto-logo'             => 'Логотип Bagisto',
            'back'                     => 'Назад',
            'bagisto-info'             => 'Проект спільноти',
            'bagisto'                  => 'Bagisto',
            'continue'                 => 'Продовжити',
            'installation-title'       => 'Ласкаво просимо до установки',
            'installation-info'        => 'Ми раді бачити вас тут!',
            'installation-description' => 'Зазвичай установка Bagisto включає кілька кроків. Ось загальний опис процесу установки Bagisto:',
            'skip'                     => 'Пропустити',
            'save-configuration'       => 'Зберегти конфігурацію',
            'title'                    => 'Установник Bagisto',
            'webkul'                   => 'Webkul',
        ],
    ],
];
