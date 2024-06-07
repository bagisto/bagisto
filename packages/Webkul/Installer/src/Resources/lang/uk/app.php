<?php

return [
    'seeders' => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'За замовчуванням',
            ],

            'attribute-groups' => [
                'description'      => 'Опис',
                'general'          => 'Загальні',
                'inventories'      => 'Запаси',
                'meta-description' => 'Мета-опис',
                'price'            => 'Ціна',
                'settings'         => 'Налаштування',
                'shipping'         => 'Доставка',
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
                'special-price'        => 'Спеціальна ціна',
                'special-price-from'   => 'Спеціальна ціна від',
                'special-price-to'     => 'Спеціальна ціна до',
                'status'               => 'Статус',
                'tax-category'         => 'Категорія податків',
                'url-key'              => 'URL-ключ',
                'visible-individually' => 'Видимий окремо',
                'weight'               => 'Вага',
                'width'                => 'Ширина',
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

                'contact-us' => [
                    'content' => 'Зміст сторінки Зв\'яжіться з нами',
                    'title'   => 'Зв\'яжіться з нами',
                ],

                'customer-service' => [
                    'content' => 'Зміст сторінки Обслуговування клієнтів',
                    'title'   => 'Обслуговування клієнтів',
                ],

                'payment-policy' => [
                    'content' => 'Зміст сторінки Політика оплати',
                    'title'   => 'Політика оплати',
                ],

                'privacy-policy' => [
                    'content' => 'Зміст сторінки Політика конфіденційності',
                    'title'   => 'Політика конфіденційності',
                ],

                'refund-policy' => [
                    'content' => 'Зміст сторінки Політики повернення',
                    'title'   => 'Політика повернення',
                ],

                'return-policy' => [
                    'content' => 'Зміст сторінки Політики повернення',
                    'title'   => 'Політика повернення',
                ],

                'shipping-policy' => [
                    'content' => 'Зміст сторінки Політика доставки',
                    'title'   => 'Політика доставки',
                ],

                'terms-conditions' => [
                    'content' => 'Зміст сторінки Умови та положення',
                    'title'   => 'Умови та положення',
                ],

                'terms-of-use' => [
                    'content' => 'Зміст сторінки Умови використання',
                    'title'   => 'Умови використання',
                ],

                'whats-new' => [
                    'content' => 'Зміст сторінки Що нового',
                    'title'   => 'Що нового',
                ],
            ],
        ],

        'core' => [
            'channels' => [
                'name'             => 'За замовчуванням',
                'meta-title'       => 'Демонстраційний магазин',
                'meta-keywords'    => 'Мета-ключові слова демонстраційного магазину',
                'meta-description' => 'Мета-опис демонстраційного магазину',
            ],

            'currencies' => [
                'AED' => 'Дирхам ОАЕ',
                'ARS' => 'Аргентинське Песо',
                'AUD' => 'Австралійський Долар',
                'BDT' => 'Бангладеська Така',
                'BRL' => 'Бразильський Реал',
                'CAD' => 'Канадський Долар',
                'CHF' => 'Швейцарський Франк',
                'CLP' => 'Чилійське Песо',
                'CNY' => 'Китайський Юань',
                'COP' => 'Колумбійське Песо',
                'CZK' => 'Чеська Крона',
                'DKK' => 'Датська Крона',
                'DZD' => 'Алжирський Динар',
                'EGP' => 'Єгипетський Фунт',
                'EUR' => 'Євро',
                'FJD' => 'Фіджійський Долар',
                'GBP' => 'Британський Фунт Стерлінгів',
                'HKD' => 'Гонконгський Долар',
                'HUF' => 'Угорський Форинт',
                'IDR' => 'Індонезійська Рупія',
                'ILS' => 'Новий Ізраїльський Шекель',
                'INR' => 'Індійська Рупія',
                'JOD' => 'Йорданський Динар',
                'JPY' => 'Японська Єна',
                'KRW' => 'Південнокорейський Вон',
                'KWD' => 'Кувейтський Динар',
                'KZT' => 'Казахстанський Тенге',
                'LBP' => 'Ліванський Фунт',
                'LKR' => 'Шрі-Ланкійська Рупія',
                'LYD' => 'Лівійський Динар',
                'MAD' => 'Марокканський Дирхам',
                'MUR' => 'Маврикійська Рупія',
                'MXN' => 'Мексиканське Песо',
                'MYR' => 'Малайзійський Ринггіт',
                'NGN' => 'Нігерійська Найра',
                'NOK' => 'Норвезька Крона',
                'NPR' => 'Непальська Рупія',
                'NZD' => 'Новозеландський Долар',
                'OMR' => 'Оманський Ріал',
                'PAB' => 'Панамський Бальбоа',
                'PEN' => 'Перуанський Нуево Соль',
                'PHP' => 'Філіппінське Песо',
                'PKR' => 'Пакистанська Рупія',
                'PLN' => 'Польський Злотий',
                'PYG' => 'Парагвайський Гуарані',
                'QAR' => 'Катарський Ріал',
                'RON' => 'Румунський Лей',
                'RUB' => 'Російський Рубль',
                'SAR' => 'Саудівський Ріял',
                'SEK' => 'Шведська Крона',
                'SGD' => 'Сінгапурський Долар',
                'THB' => 'Тайський Бат',
                'TND' => 'Туніський Динар',
                'TRY' => 'Турецька Ліра',
                'TWD' => 'Новий Тайванський Долар',
                'UAH' => 'Українська Гривня',
                'USD' => 'Долар США',
                'UZS' => 'Узбецький Сом',
                'VEF' => 'Венесуельський Болівар',
                'VND' => 'Вʼєтнамський Донг',
                'XAF' => 'Франк КФА BEAC',
                'XOF' => 'Франк КФА BCEAO',
                'ZAR' => 'Південноафриканський Ранд',
                'ZMW' => 'Замбійська Квача',
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

        'customer' => [
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
                'all-products' => [
                    'name' => 'Усі продукти',

                    'options' => [
                        'title' => 'Усі продукти',
                    ],
                ],

                'bold-collections' => [
                    'content' => [
                        'btn-title'   => 'Переглянути колекції',
                        'description' => 'Представляємо наші нові сміливі колекції! Підніміть свій стиль завдяки сміливим дизайнам та яскравим заявам. Відкрийте для себе вишукані малюнки та смілі кольори, які переосмислюють ваш гардероб. Готуйтеся прийняти надзвичайне!',
                        'title'       => 'Готуйтеся до наших нових сміливих колекцій!',
                    ],

                    'name' => 'Сміливі колекції',
                ],

                'categories-collections' => [
                    'name' => 'Колекції за категоріями',
                ],

                'featured-collections' => [
                    'name' => 'Виділені колекції',

                    'options' => [
                        'title' => 'Рекомендовані продукти',
                    ],
                ],

                'footer-links' => [
                    'name' => 'Посилання у нижньому колонтитулі',

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

                'game-container' => [
                    'content' => [
                        'sub-title-1' => 'Наші колекції',
                        'sub-title-2' => 'Наші колекції',
                        'title'       => 'Гра з нашими новими додатками!',
                    ],

                    'name' => 'Контейнер з грою',
                ],

                'image-carousel' => [
                    'name' => 'Карусель зображень',

                    'sliders' => [
                        'title' => 'Готуйтеся до нової колекції',
                    ],
                ],

                'new-products' => [
                    'name' => 'Нові продукти',

                    'options' => [
                        'title' => 'Нові продукти',
                    ],
                ],

                'offer-information' => [
                    'content' => [
                        'title' => 'ЗНИЖКА до 40% на ваше 1-ше замовлення ЗАРАЗ',
                    ],

                    'name' => 'Інформація про пропозицію',
                ],

                'services-content' => [
                    'description' => [
                        'emi-available-info'   => 'EMI безкоштовно доступно на всіх основних кредитних картках',
                        'free-shipping-info'   => 'Насолоджуйтеся безкоштовною доставкою на всі замовлення',
                        'product-replace-info' => 'Доступна легка заміна продукту!',
                        'time-support-info'    => 'Присвячена підтримка 24/7 через чат та електронну пошту',
                    ],

                    'name' => 'Вміст послуг',

                    'title' => [
                        'emi-available'   => 'EMI доступно',
                        'free-shipping'   => 'Безкоштовна доставка',
                        'product-replace' => 'Заміна продукту',
                        'time-support'    => 'Підтримка 24/7',
                    ],
                ],

                'top-collections' => [
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
            'create-administrator' => [
                'admin'            => 'Адміністратор',
                'bagisto'          => 'Bagisto',
                'confirm-password' => 'Підтвердити пароль',
                'download-sample'  => 'завантажити-зразок',
                'email'            => 'Електронна пошта',
                'email-address'    => 'admin@example.com',
                'password'         => 'Пароль',
                'sample-products'  => 'Зразкові товари',
                'title'            => 'Створити адміністратора',
            ],

            'environment-configuration' => [
                'algerian-dinar'              => 'Алжирський динар (DZD)',
                'allowed-currencies'          => 'Дозволені валюти',
                'allowed-locales'             => 'Дозволені локалі',
                'application-name'            => 'Назва програми',
                'argentine-peso'              => 'Аргентинський песо (ARS)',
                'australian-dollar'           => 'Австралійський долар (AUD)',
                'bagisto'                     => 'Bagisto',
                'bangladeshi-taka'            => 'Бангладеська така (BDT)',
                'brazilian-real'              => 'Бразильський реал (BRL)',
                'british-pound-sterling'      => 'Британський фунт стерлінгів (GBP)',
                'canadian-dollar'             => 'Канадський долар (CAD)',
                'cfa-franc-bceao'             => 'CFA франк BCEAO (XOF)',
                'cfa-franc-beac'              => 'CFA франк BEAC (XAF)',
                'chilean-peso'                => 'Чилійське песо (CLP)',
                'chinese-yuan'                => 'Китайський юань (CNY)',
                'colombian-peso'              => 'Колумбійське песо (COP)',
                'czech-koruna'                => 'Чеська крона (CZK)',
                'danish-krone'                => 'Датська крона (DKK)',
                'database-connection'         => 'Підключення до бази даних',
                'database-hostname'           => 'Ім\'я хоста бази даних',
                'database-name'               => 'Назва бази даних',
                'database-password'           => 'Пароль бази даних',
                'database-port'               => 'Порт бази даних',
                'database-prefix'             => 'Префікс бази даних',
                'database-username'           => 'Ім\'я користувача бази даних',
                'default-currency'            => 'Типова валюта',
                'default-locale'              => 'Типове місце знаходження',
                'default-timezone'            => 'Типова часова зона',
                'default-url'                 => 'Типовий URL',
                'default-url-link'            => 'https://localhost',
                'egyptian-pound'              => 'Єгипетський фунт (EGP)',
                'euro'                        => 'Євро (EUR)',
                'fijian-dollar'               => 'Фіджійський долар (FJD)',
                'hong-kong-dollar'            => 'Гонконгський долар (HKD)',
                'hungarian-forint'            => 'Угорський форинт (HUF)',
                'indian-rupee'                => 'Індійська рупія (INR)',
                'indonesian-rupiah'           => 'Індонезійська рупія (IDR)',
                'israeli-new-shekel'          => 'Ізраїльський новий шекель (ILS)',
                'japanese-yen'                => 'Японська єна (JPY)',
                'jordanian-dinar'             => 'Йорданський динар (JOD)',
                'kazakhstani-tenge'           => 'Казахстанський тенге (KZT)',
                'kuwaiti-dinar'               => 'Кувейтський динар (KWD)',
                'lebanese-pound'              => 'Ліванський фунт (LBP)',
                'libyan-dinar'                => 'Лівійський динар (LYD)',
                'malaysian-ringgit'           => 'Малайзійський ринггіт (MYR)',
                'mauritian-rupee'             => 'Маврикійська рупія (MUR)',
                'mexican-peso'                => 'Мексиканське песо (MXN)',
                'moroccan-dirham'             => 'Марокканський дирхам (MAD)',
                'mysql'                       => 'MySQL',
                'nepalese-rupee'              => 'Непальська рупія (NPR)',
                'new-taiwan-dollar'           => 'Новий тайванський долар (TWD)',
                'new-zealand-dollar'          => 'Новозеландський долар (NZD)',
                'nigerian-naira'              => 'Нігерійська наїра (NGN)',
                'norwegian-krone'             => 'Норвезька крона (NOK)',
                'omani-rial'                  => 'Оманський ріал (OMR)',
                'pakistani-rupee'             => 'Пакистанська рупія (PKR)',
                'panamanian-balboa'           => 'Панамський бальбоа (PAB)',
                'paraguayan-guarani'          => 'Парагвайський гуарані (PYG)',
                'peruvian-nuevo-sol'          => 'Перуанський новий сол (PEN)',
                'pgsql'                       => 'PgSQL',
                'philippine-peso'             => 'Філіппінське песо (PHP)',
                'polish-zloty'                => 'Польський злотий (PLN)',
                'qatari-rial'                 => 'Катарський ріал (QAR)',
                'romanian-leu'                => 'Румунський лей (RON)',
                'russian-ruble'               => 'Російський рубль (RUB)',
                'saudi-riyal'                 => 'Саудівський ріал (SAR)',
                'select-timezone'             => 'Вибрати часовий пояс',
                'singapore-dollar'            => 'Сінгапурський долар (SGD)',
                'south-african-rand'          => 'Південноафриканський ренд (ZAR)',
                'south-korean-won'            => 'Південнокорейський вон (KRW)',
                'sqlsrv'                      => 'SQLSRV',
                'sri-lankan-rupee'            => 'Шрі-ланкійська рупія (LKR)',
                'swedish-krona'               => 'Шведська крона (SEK)',
                'swiss-franc'                 => 'Швейцарський франк (CHF)',
                'thai-baht'                   => 'Тайський бат (THB)',
                'title'                       => 'Налаштування магазину',
                'tunisian-dinar'              => 'Туніський динар (TND)',
                'turkish-lira'                => 'Турецька ліра (TRY)',
                'ukrainian-hryvnia'           => 'Українська гривня (UAH)',
                'united-arab-emirates-dirham' => 'Дирхам Об\'єднаних Арабських Еміратів (AED)',
                'united-states-dollar'        => 'Долар США (USD)',
                'uzbekistani-som'             => 'Узбецький сум (UZS)',
                'venezuelan-bolívar'          => 'Венесуельський болівар (VEF)',
                'vietnamese-dong'             => 'В\'єтнамський донг (VND)',
                'warning-message'             => 'Увага! Налаштування типової мови системи та типової валюти є постійними і більше не можуть бути змінені.',
                'zambian-kwacha'              => 'Замбійський квача (ZMW)',
            ],

            'installation-processing' => [
                'bagisto'          => 'Установка Bagisto',
                'bagisto-info'     => 'Створення таблиць бази даних, це може зайняти кілька хвилин',
                'title'            => 'Установка',
            ],

            'installation-completed' => [
                'admin-panel'                => 'Панель адміністратора',
                'bagisto-forums'             => 'Форум Bagisto',
                'customer-panel'             => 'Панель клієнта',
                'explore-bagisto-extensions' => 'Досліджуйте розширення Bagisto',
                'title'                      => 'Установка завершена',
                'title-info'                 => 'Bagisto успішно встановлено на вашій системі.',
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
                'pcre'        => 'pcre',
                'pdo'         => 'pdo',
                'php'         => 'PHP',
                'php-version' => '8.1 або вище',
                'session'     => 'сесія',
                'title'       => 'Вимоги до сервера',
                'tokenizer'   => 'токенізатор',
                'xml'         => 'XML',
            ],

            'arabic'                   => 'Арабська',
            'back'                     => 'Назад',
            'bagisto'                  => 'Bagisto',
            'bagisto-info'             => 'Спільний проект спільноти від Webkul',
            'bagisto-logo'             => 'Логотип Bagisto',
            'bengali'                  => 'Бенгальська',
            'chinese'                  => 'Китайська',
            'continue'                 => 'Продовжити',
            'dutch'                    => 'Голландська',
            'english'                  => 'Англійська',
            'french'                   => 'Французька',
            'german'                   => 'Німецька',
            'hebrew'                   => 'Іврит',
            'hindi'                    => 'Гінді',
            'installation-description' => 'Встановлення Bagisto, як правило, включає кілька етапів. Ось загальний опис процесу встановлення Bagisto:',
            'installation-info'        => 'Ми раді вас бачити тут!',
            'installation-title'       => 'Ласкаво просимо до встановлення Bagisto',
            'italian'                  => 'Італійська',
            'japanese'                 => 'Японська',
            'persian'                  => 'Перська',
            'polish'                   => 'Польська',
            'portuguese'               => 'Бразильська португальська',
            'russian'                  => 'Російська',
            'sinhala'                  => 'Сингальська',
            'spanish'                  => 'Іспанська',
            'title'                    => 'Установник Bagisto',
            'turkish'                  => 'Турецька',
            'ukrainian'                => 'Українська',
            'webkul'                   => 'Webkul',
        ],
    ],
];
