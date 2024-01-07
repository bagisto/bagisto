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
                'AFN' => 'Ізраїльський шекель',
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
                        'free-shipping'   => 'Безкоштовна доставка',
                        'product-replace' => 'Заміна продукту',
                        'emi-available'   => 'EMI доступно',
                        'time-support'    => 'Підтримка 24/7',
                    ],

                    'description' => [
                        'free-shipping-info'   => 'Насолоджуйтеся безкоштовною доставкою на всі замовлення',
                        'product-replace-info' => 'Доступна легка заміна продукту!',
                        'emi-available-info'   => 'EMI безкоштовно доступно на всіх основних кредитних картках',
                        'time-support-info'    => 'Присвячена підтримка 24/7 через чат та електронну пошту',
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
            'start' => [
                'locale'        => 'Локаль',
                'main'          => 'Початок',
                'select-locale' => 'Вибір локалі',
                'title'         => 'Ваша установка Bagisto',
                'welcome-title' => 'Ласкаво просимо до Bagisto 2.0.',
            ],

            'server-requirements' => [
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
                'allowed-locales'     => 'Дозволені локалі',
                'allowed-currencies'  => 'Дозволені валюти',
                'application-name'    => 'Назва програми',
                'bagisto'             => 'Bagisto',
                'chinese-yuan'        => 'Китайський юань (CNY)',
                'dirham'              => 'Дирхам (AED)',
                'default-url'         => 'Стандартний URL',
                'default-url-link'    => 'https://localhost',
                'default-currency'    => 'Стандартна валюта',
                'default-timezone'    => 'Стандартна часова зона',
                'default-locale'      => 'Стандартна локаль',
                'database-connection' => 'Підключення до бази даних',
                'database-hostname'   => 'Ім\'я сервера бази даних',
                'database-port'       => 'Порт підключення до бази даних',
                'database-name'       => 'Назва бази даних',
                'database-username'   => 'Ім\'я користувача бази даних',
                'database-prefix'     => 'Префікс бази даних',
                'database-password'   => 'Пароль бази даних',
                'euro'                => 'Євро (EUR)',
                'iranian'             => 'Іранський ріал (IRR)',
                'israeli'             => 'Ізраїльський шекель (AFN)',
                'japanese-yen'        => 'Японська єна (JPY)',
                'mysql'               => 'Mysql',
                'pgsql'               => 'pgSQL',
                'pound'               => 'Фунт стерлінгів (GBP)',
                'rupee'               => 'Індійська рупія (INR)',
                'russian-ruble'       => 'Російський рубль (RUB)',
                'sqlsrv'              => 'SQLSRV',
                'saudi'               => 'Саудівський ріял (SAR)',
                'title'               => 'Налаштування середовища',
                'turkish-lira'        => 'Турецька ліра (TRY)',
                'usd'                 => 'Долар США (USD)',
                'ukrainian-hryvnia'   => 'Українська гривня (UAH)',
                'warning-message'     => 'Увага! Налаштування мов системи за замовчуванням, а також основна валюта є постійними і більше не можуть бути змінені.',
            ],

            'ready-for-installation' => [
                'create-databsae-table'   => 'Створення таблиці бази даних',
                'install'                 => 'Встановлення',
                'install-info'            => 'Bagisto для встановлення',
                'install-info-button'     => 'Натисніть кнопку нижче, щоб',
                'populate-database-table' => 'Заповнення таблиць бази даних',
                'start-installation'      => 'Почати встановлення',
                'title'                   => 'Готовий до встановлення',
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

            'installation-completed' => [
                'admin-panel'                => 'Панель адміністратора',
                'bagisto-forums'             => 'Форум Bagisto',
                'customer-panel'             => 'Панель клієнта',
                'explore-bagisto-extensions' => 'Досліджуйте розширення Bagisto',
                'title'                      => 'Установка завершена',
                'title-info'                 => 'Bagisto успішно встановлено на вашій системі.',
            ],

            'arabic'                   => 'Arabic',
            'bengali'                  => 'Bengali',
            'bagisto-logo'             => 'Bagisto Logo',
            'back'                     => 'Back',
            'bagisto-info'             => 'a Community Project by',
            'bagisto'                  => 'Bagisto',
            'chinese'                  => 'Chinese',
            'continue'                 => 'Continue',
            'dutch'                    => 'Dutch',
            'english'                  => 'English',
            'french'                   => 'French',
            'german'                   => 'German',
            'hebrew'                   => 'Hebrew',
            'hindi'                    => 'Hindi',
            'installation-title'       => 'Welcome to Installation',
            'installation-info'        => 'We are happy to see you here!',
            'installation-description' => 'Bagisto installation typically involves several steps. Here\'s a general outline of the installation process for Bagisto:',
            'italian'                  => 'Italian',
            'japanese'                 => 'Japanese',
            'persian'                  => 'Persian',
            'polish'                   => 'Polish',
            'portuguese'               => 'Brazilian Portuguese',
            'russian'                  => 'Russian',
            'spanish'                  => 'Spanish',
            'sinhala'                  => 'Sinhala',
            'skip'                     => 'Skip',
            'save-configuration'       => 'Save configuration',
            'title'                    => 'Bagisto Installer',
            'turkish'                  => 'Turkish',
            'ukrainian'                => 'Ukrainian',
            'webkul'                   => 'Webkul',
        ],
    ],
];
