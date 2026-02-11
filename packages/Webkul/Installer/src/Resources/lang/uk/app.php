<?php

return [
    'seeders' => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'За замовчуванням',
            ],

            'attribute-groups' => [
                'description' => 'Опис',
                'general' => 'Загальні',
                'inventories' => 'Запаси',
                'meta-description' => 'Мета-опис',
                'price' => 'Ціна',
                'rma' => 'RMA',
                'settings' => 'Налаштування',
                'shipping' => 'Доставка',
            ],

            'attributes' => [
                'allow-rma' => 'Дозволити RMA',
                'brand' => 'Бренд',
                'color' => 'Колір',
                'cost' => 'Вартість',
                'description' => 'Опис',
                'featured' => 'Рекомендовані',
                'guest-checkout' => 'Гостьова покупка',
                'height' => 'Висота',
                'length' => 'Довжина',
                'manage-stock' => 'Управління запасами',
                'meta-description' => 'Мета-опис',
                'meta-keywords' => 'Мета-ключові слова',
                'meta-title' => 'Мета-заголовок',
                'name' => 'Назва',
                'new' => 'Новинка',
                'price' => 'Ціна',
                'product-number' => 'Номер продукту',
                'rma-rules' => 'Правила RMA',
                'short-description' => 'Короткий опис',
                'size' => 'Розмір',
                'sku' => 'Артикул',
                'special-price' => 'Спеціальна ціна',
                'special-price-from' => 'Спеціальна ціна від',
                'special-price-to' => 'Спеціальна ціна до',
                'status' => 'Статус',
                'tax-category' => 'Категорія податків',
                'url-key' => 'URL-ключ',
                'visible-individually' => 'Видимий окремо',
                'weight' => 'Вага',
                'width' => 'Ширина',
            ],

            'attribute-options' => [
                'black' => 'Чорний',
                'green' => 'Зелений',
                'l' => 'L',
                'm' => 'M',
                'red' => 'Червоний',
                's' => 'S',
                'white' => 'Білий',
                'xl' => 'XL',
                'yellow' => 'Жовтий',
            ],
        ],

        'category' => [
            'categories' => [
                'description' => 'Опис кореневої категорії',
                'name' => 'Коренева',
            ],
        ],

        'cms' => [
            'pages' => [
                'about-us' => [
                    'content' => 'Зміст сторінки Про нас',
                    'title' => 'Про нас',
                ],

                'contact-us' => [
                    'content' => 'Зміст сторінки Зв\'яжіться з нами',
                    'title' => 'Зв\'яжіться з нами',
                ],

                'customer-service' => [
                    'content' => 'Зміст сторінки Обслуговування клієнтів',
                    'title' => 'Обслуговування клієнтів',
                ],

                'payment-policy' => [
                    'content' => 'Зміст сторінки Політика оплати',
                    'title' => 'Політика оплати',
                ],

                'privacy-policy' => [
                    'content' => 'Зміст сторінки Політика конфіденційності',
                    'title' => 'Політика конфіденційності',
                ],

                'refund-policy' => [
                    'content' => 'Зміст сторінки Політики повернення',
                    'title' => 'Політика повернення',
                ],

                'return-policy' => [
                    'content' => 'Зміст сторінки Політики повернення',
                    'title' => 'Політика повернення',
                ],

                'shipping-policy' => [
                    'content' => 'Зміст сторінки Політика доставки',
                    'title' => 'Політика доставки',
                ],

                'terms-conditions' => [
                    'content' => 'Зміст сторінки Умови та положення',
                    'title' => 'Умови та положення',
                ],

                'terms-of-use' => [
                    'content' => 'Зміст сторінки Умови використання',
                    'title' => 'Умови використання',
                ],

                'whats-new' => [
                    'content' => 'Зміст сторінки Що нового',
                    'title' => 'Що нового',
                ],
            ],
        ],

        'core' => [
            'channels' => [
                'meta-description' => 'Мета-опис демонстраційного магазину',
                'meta-keywords' => 'Мета-ключові слова демонстраційного магазину',
                'meta-title' => 'Демонстраційний магазин',
                'name' => 'За замовчуванням',
            ],

            'currencies' => [
                'AED' => 'Дирхам ОАЕ',
                'ARS' => 'Аргентинське Песо',
                'AUD' => 'Австралійський Долар',
                'BDT' => 'Бангладеська Така',
                'BHD' => 'бахрейнський динар',
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

            'locales' => [
                'ar' => 'Арабська',
                'bn' => 'Бенгальська',
                'ca' => 'Каталонська',
                'de' => 'Німецька',
                'en' => 'Англійська',
                'es' => 'Іспанська',
                'fa' => 'Перська',
                'fr' => 'Французька',
                'he' => 'Іврит',
                'hi_IN' => 'Гінді',
                'id' => 'Індонезійська',
                'it' => 'Італійська',
                'ja' => 'Японська',
                'nl' => 'Нідерландська',
                'pl' => 'Польська',
                'pt_BR' => 'Бразильська португальська',
                'ru' => 'Російська',
                'sin' => 'Сінгальська',
                'tr' => 'Турецька',
                'uk' => 'Українська',
                'zh_CN' => 'Китайська (спрощена)',
            ],
        ],

        'customer' => [
            'customer-groups' => [
                'general' => 'Загальний',
                'guest' => 'Гість',
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
                        'btn-title' => 'Переглянути колекції',
                        'description' => 'Представляємо наші нові сміливі колекції! Підніміть свій стиль завдяки сміливим дизайнам та яскравим заявам. Відкрийте для себе вишукані малюнки та смілі кольори, які переосмислюють ваш гардероб. Готуйтеся прийняти надзвичайне!',
                        'title' => 'Готуйтеся до наших нових сміливих колекцій!',
                    ],

                    'name' => 'Сміливі колекції',
                ],

                'bold-collections-2' => [
                    'content' => [
                        'btn-title' => 'Переглянути Колекції',
                        'description' => 'Наші Сміливі Колекції тут, щоб переосмислити ваш гардероб безстрашними дизайнами та яскравими, насиченими кольорами. Від сміливих візерунків до потужних відтінків — це ваш шанс вирватися з буденності та ступити в незвичайне.',
                        'title' => 'Розкрийте Свою Сміливість з Нашою Новою Колекцією!',
                    ],

                    'name' => 'Сміливі Колекції',
                ],

                'booking-products' => [
                    'name' => 'Продукти Бронювання',

                    'options' => [
                        'title' => 'Забронювати Квитки',
                    ],
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
                        'about-us' => 'Про нас',
                        'contact-us' => 'Зв\'яжіться з нами',
                        'customer-service' => 'Служба підтримки',
                        'payment-policy' => 'Політика оплати',
                        'privacy-policy' => 'Політика конфіденційності',
                        'refund-policy' => 'Політика повернення коштів',
                        'return-policy' => 'Політика повернення',
                        'shipping-policy' => 'Політика доставки',
                        'terms-conditions' => 'Умови та положення',
                        'terms-of-use' => 'Умови використання',
                        'whats-new' => 'Що нового',
                    ],
                ],

                'game-container' => [
                    'content' => [
                        'sub-title-1' => 'Наші колекції',
                        'sub-title-2' => 'Наші колекції',
                        'title' => 'Гра з нашими новими додатками!',
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
                        'emi-available-info' => 'EMI безкоштовно доступно на всіх основних кредитних картках',
                        'free-shipping-info' => 'Насолоджуйтеся безкоштовною доставкою на всі замовлення',
                        'product-replace-info' => 'Доступна легка заміна продукту!',
                        'time-support-info' => 'Присвячена підтримка 24/7 через чат та електронну пошту',
                    ],

                    'name' => 'Вміст послуг',

                    'title' => [
                        'emi-available' => 'EMI доступно',
                        'free-shipping' => 'Безкоштовна доставка',
                        'product-replace' => 'Заміна продукту',
                        'time-support' => 'Підтримка 24/7',
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
                        'title' => 'Гра з нашими новими додатками!',
                    ],

                    'name' => 'Топові колекції',
                ],
            ],
        ],

        'user' => [
            'roles' => [
                'description' => 'Ця роль надає користувачам всі права доступу',
                'name' => 'Адміністратор',
            ],

            'users' => [
                'name' => 'Приклад',
            ],
        ],

        'sample-categories' => [
            'category-translation' => [
                '2' => [
                    'description' => '<p>Чоловіки</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Чоловіки',
                    'slug' => 'mens',
                    'url-path' => 'men',
                ],

                '3' => [
                    'description' => '<p>Діти</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Діти',
                    'slug' => 'kids',
                    'url-path' => 'kids',
                ],

                '4' => [
                    'description' => '<p>Жінки</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Жінки',
                    'slug' => 'womens',
                    'url-path' => 'woman',
                ],

                '5' => [
                    'description' => '<p>Діловий Одяг</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Діловий Одяг',
                    'slug' => 'formal-wear-men',
                    'url-path' => 'men/formal-wear-men',
                ],

                '6' => [
                    'description' => '<p>Повсякденний Одяг</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Повсякденний Одяг',
                    'slug' => 'casual-wear-men',
                    'url-path' => 'men/casual-wear-men',
                ],

                '7' => [
                    'description' => '<p>Спортивний Одяг</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Спортивний Одяг',
                    'slug' => 'active-wear',
                    'url-path' => 'men/active-wear',
                ],

                '8' => [
                    'description' => '<p>Взуття</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Взуття',
                    'slug' => 'footwear',
                    'url-path' => 'men/footwear',
                ],

                '9' => [
                    'description' => '<p><span>Діловий Одяг</span></p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Діловий Одяг',
                    'slug' => 'formal-wear-female',
                    'url-path' => 'woman/formal-wear-female',
                ],

                '10' => [
                    'description' => '<p>Повсякденний Одяг</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Повсякденний Одяг',
                    'slug' => 'casual-wear-female',
                    'url-path' => 'woman/casual-wear-female',
                ],

                '11' => [
                    'description' => '<p>Спортивний Одяг</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Спортивний Одяг',
                    'slug' => 'active-wear-female',
                    'url-path' => 'woman/active-wear-female',
                ],

                '12' => [
                    'description' => '<p>Взуття</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Взуття',
                    'slug' => 'footwear-female',
                    'url-path' => 'woman/footwear-female',
                ],

                '13' => [
                    'description' => '<p>Одяг для Дівчаток</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => 'Одяг для Дівчаток',
                    'name' => 'Одяг для Дівчаток',
                    'slug' => 'girls-clothing',
                    'url-path' => 'kids/girls-clothing',
                ],

                '14' => [
                    'description' => '<p>Одяг для Хлопчиків</p>',
                    'meta-description' => 'Мода для Хлопчиків',
                    'meta-keywords' => '',
                    'meta-title' => 'Одяг для Хлопчиків',
                    'name' => 'Одяг для Хлопчиків',
                    'slug' => 'boys-clothing',
                    'url-path' => 'kids/boys-clothing',
                ],

                '15' => [
                    'description' => '<p>Взуття для Дівчаток</p>',
                    'meta-description' => 'Колекція Модного Взуття для Дівчаток',
                    'meta-keywords' => '',
                    'meta-title' => 'Взуття для Дівчаток',
                    'name' => 'Взуття для Дівчаток',
                    'slug' => 'girls-footwear',
                    'url-path' => 'kids/girls-footwear',
                ],

                '16' => [
                    'description' => '<p>Взуття для Хлопчиків</p>',
                    'meta-description' => 'Колекція Стильного Взуття для Хлопчиків',
                    'meta-keywords' => '',
                    'meta-title' => 'Взуття для Хлопчиків',
                    'name' => 'Взуття для Хлопчиків',
                    'slug' => 'boys-footwear',
                    'url-path' => 'kids/boys-footwear',
                ],

                '17' => [
                    'description' => '<p>Здоров\'я</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Здоров\'я',
                    'slug' => 'wellness',
                    'url-path' => 'wellness',
                ],

                '18' => [
                    'description' => '<p>Завантажуваний Урок Йоги</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Завантажуваний Урок Йоги',
                    'slug' => 'downloadable-yoga-tutorial',
                    'url-path' => 'wellness/downloadable-yoga-tutorial',
                ],

                '19' => [
                    'description' => '<p>Колекція Електронних Книг</p>',
                    'meta-description' => 'Колекція Електронних Книг',
                    'meta-keywords' => '',
                    'meta-title' => 'Колекція Електронних Книг',
                    'name' => 'Електронні Книги',
                    'slug' => 'e-books',
                    'url-path' => 'wellness/e-books',
                ],

                '20' => [
                    'description' => '<p>Кінопропуск</p>',
                    'meta-description' => 'Зануртесь у магію 10 фільмів щомісяця без додаткової плати.',
                    'meta-keywords' => '',
                    'meta-title' => 'Щомісячний Кінопропуск CineXperience',
                    'name' => 'Кінопропуск',
                    'slug' => 'movie-pass',
                    'url-path' => 'wellness/movie-pass',
                ],

                '21' => [
                    'description' => '<p>Легко керуйте та продавайте свої продукти на основі бронювання з нашою бездоганною системою бронювання.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Бронювання',
                    'slug' => 'bookings',
                    'url-path' => '',
                ],

                '22' => [
                    'description' => '<p>Бронювання прийомів дозволяє клієнтам планувати часові слоти для послуг або консультацій з підприємствами або професіоналами.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Бронювання Прийомів',
                    'slug' => 'appointment-booking',
                    'url-path' => '',
                ],

                '23' => [
                    'description' => '<p>Бронювання подій дозволяє фізичним особам або групам реєструватися або бронювати місця на публічні або приватні заходи.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Бронювання Подій',
                    'slug' => 'event-booking',
                    'url-path' => '',
                ],

                '24' => [
                    'description' => '<p>Бронювання залів дозволяє фізичним особам, організаціям або групам резервувати громадські простори для різних заходів.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Бронювання Залів',
                    'slug' => 'community-hall-bookings',
                    'url-path' => '',
                ],

                '25' => [
                    'description' => '<p>Бронювання столиків дозволяє клієнтам заздалегідь резервувати столики в ресторанах, кафе або закладах харчування.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Бронювання Столиків',
                    'slug' => 'table-booking',
                    'url-path' => '',
                ],

                '26' => [
                    'description' => '<p>Бронювання оренди полегшує резервування предметів або нерухомості для тимчасового використання.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Бронювання Оренди',
                    'slug' => 'rental-booking',
                    'url-path' => '',
                ],

                '27' => [
                    'description' => '<p>Відкрийте для себе найновішу споживчу електроніку, створену для того, щоб тримати вас на зв\'язку, продуктивним та розважатися.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Електроніка',
                    'slug' => 'electronics',
                    'url-path' => '',
                ],

                '28' => [
                    'description' => '<p>Відкрийте для себе смартфони, зарядні пристрої, чохли та інші необхідні речі для зв\'язку в дорозі.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Мобільні Телефони та Аксесуари',
                    'slug' => 'mobile-phones-accessories',
                    'url-path' => '',
                ],

                '29' => [
                    'description' => '<p>Знайдіть потужні ноутбуки та портативні планшети для роботи, навчання та розваг.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Ноутбуки та Планшети',
                    'slug' => 'laptops-tablets',
                    'url-path' => '',
                ],

                '30' => [
                    'description' => '<p>Купуйте навушники, вкладиші та колонки для кристально чистого звуку.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Аудіопристрої',
                    'slug' => 'audio-devices',
                    'url-path' => '',
                ],

                '31' => [
                    'description' => '<p>Спростіть життя з розумним освітленням, термостатами, системами безпеки та іншим.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Розумний Дім та Автоматизація',
                    'slug' => 'smart-home-automation',
                    'url-path' => '',
                ],

                '32' => [
                    'description' => '<p>Покращте свій житловий простір функціональними та стильними товарами для дому та кухні.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Товари для Дому',
                    'slug' => 'household',
                    'url-path' => '',
                ],

                '33' => [
                    'description' => '<p>Переглядайте блендери, аерофритюрниці, кавоварки та інше для спрощення приготування їжі.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Кухонна Техніка',
                    'slug' => 'kitchen-appliances',
                    'url-path' => '',
                ],

                '34' => [
                    'description' => '<p>Досліджуйте набори посуду, прилади, столовий посуд та сервіровку для ваших кулінарних потреб.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Посуд та Столова',
                    'slug' => 'cookware-dining',
                    'url-path' => '',
                ],

                '35' => [
                    'description' => '<p>Додайте комфорт та чарівність з диванами, столами, настінним мистецтвом та домашнім декором.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Меблі та Декор',
                    'slug' => 'furniture-decor',
                    'url-path' => '',
                ],

                '36' => [
                    'description' => '<p>Тримайте свій простір у чистоті з пилососами, чистячими засобами, віниками та органайзерами.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Чистячі Засоби',
                    'slug' => 'cleaning-supplies',
                    'url-path' => '',
                ],

                '37' => [
                    'description' => '<p>Розпаліть свою уяву або організуйте робочий простір з широким вибором книг та канцтоварів.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Книги та Канцтовари',
                    'slug' => 'books-stationery',
                    'url-path' => '',
                ],

                '38' => [
                    'description' => '<p>Зануртесь у бестселери, біографії, книги з саморозвитку та інше.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Художні та Нехудожні Книги',
                    'slug' => 'fiction-non-fiction-books',
                    'url-path' => '',
                ],

                '39' => [
                    'description' => '<p>Знайдіть підручники, довідкові матеріали та посібники для всіх вікових категорій.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Освітні та Академічні',
                    'slug' => 'educational-academic',
                    'url-path' => '',
                ],

                '40' => [
                    'description' => '<p>Купуйте ручки, блокноти, планувальники та офісне приладдя для продуктивності.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Офісне Приладдя',
                    'slug' => 'office-supplies',
                    'url-path' => '',
                ],

                '41' => [
                    'description' => '<p>Досліджуйте фарби, пензлі, скетчбуки та набори для творчості своїми руками для креативних людей.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Товари для Творчості та Рукоділля',
                    'slug' => 'art-craft-materials',
                    'url-path' => '',
                ],
            ],
        ],
    ],

    'installer' => [
        'middleware' => [
            'already-installed' => 'Додаток вже встановлено.',
        ],

        'index' => [
            'create-administrator' => [
                'admin' => 'Адміністратор',
                'bagisto' => 'Bagisto',
                'confirm-password' => 'Підтвердити пароль',
                'email' => 'Електронна пошта',
                'email-address' => 'admin@example.com',
                'password' => 'Пароль',
                'title' => 'Створити адміністратора',
            ],

            'environment-configuration' => [
                'algerian-dinar' => 'Алжирський динар (DZD)',
                'allowed-currencies' => 'Дозволені валюти',
                'allowed-locales' => 'Дозволені локалі',
                'application-name' => 'Назва програми',
                'argentine-peso' => 'Аргентинський песо (ARS)',
                'australian-dollar' => 'Австралійський долар (AUD)',
                'bagisto' => 'Bagisto',
                'bangladeshi-taka' => 'Бангладеська така (BDT)',
                'bahraini-dinar' => 'бахрейнський динар (BHD)',
                'brazilian-real' => 'Бразильський реал (BRL)',
                'british-pound-sterling' => 'Британський фунт стерлінгів (GBP)',
                'canadian-dollar' => 'Канадський долар (CAD)',
                'cfa-franc-bceao' => 'CFA франк BCEAO (XOF)',
                'cfa-franc-beac' => 'CFA франк BEAC (XAF)',
                'chilean-peso' => 'Чилійське песо (CLP)',
                'chinese-yuan' => 'Китайський юань (CNY)',
                'colombian-peso' => 'Колумбійське песо (COP)',
                'czech-koruna' => 'Чеська крона (CZK)',
                'danish-krone' => 'Датська крона (DKK)',
                'database-connection' => 'Підключення до бази даних',
                'database-hostname' => 'Ім\'я хоста бази даних',
                'database-name' => 'Назва бази даних',
                'database-password' => 'Пароль бази даних',
                'database-port' => 'Порт бази даних',
                'database-prefix' => 'Префікс бази даних',
                'database-prefix-help' => 'Префікс повинен містити 4 символи і може містити лише літери, цифри та підкреслення.',
                'database-username' => 'Ім\'я користувача бази даних',
                'default-currency' => 'Типова валюта',
                'default-locale' => 'Типове місце знаходження',
                'default-timezone' => 'Типова часова зона',
                'default-url' => 'Типовий URL',
                'default-url-link' => 'https://localhost',
                'egyptian-pound' => 'Єгипетський фунт (EGP)',
                'euro' => 'Євро (EUR)',
                'fijian-dollar' => 'Фіджійський долар (FJD)',
                'hong-kong-dollar' => 'Гонконгський долар (HKD)',
                'hungarian-forint' => 'Угорський форинт (HUF)',
                'indian-rupee' => 'Індійська рупія (INR)',
                'indonesian-rupiah' => 'Індонезійська рупія (IDR)',
                'israeli-new-shekel' => 'Ізраїльський новий шекель (ILS)',
                'japanese-yen' => 'Японська єна (JPY)',
                'jordanian-dinar' => 'Йорданський динар (JOD)',
                'kazakhstani-tenge' => 'Казахстанський тенге (KZT)',
                'kuwaiti-dinar' => 'Кувейтський динар (KWD)',
                'lebanese-pound' => 'Ліванський фунт (LBP)',
                'libyan-dinar' => 'Лівійський динар (LYD)',
                'malaysian-ringgit' => 'Малайзійський ринггіт (MYR)',
                'mauritian-rupee' => 'Маврикійська рупія (MUR)',
                'mexican-peso' => 'Мексиканське песо (MXN)',
                'moroccan-dirham' => 'Марокканський дирхам (MAD)',
                'mysql' => 'MySQL',
                'nepalese-rupee' => 'Непальська рупія (NPR)',
                'new-taiwan-dollar' => 'Новий тайванський долар (TWD)',
                'new-zealand-dollar' => 'Новозеландський долар (NZD)',
                'nigerian-naira' => 'Нігерійська наїра (NGN)',
                'norwegian-krone' => 'Норвезька крона (NOK)',
                'omani-rial' => 'Оманський ріал (OMR)',
                'pakistani-rupee' => 'Пакистанська рупія (PKR)',
                'panamanian-balboa' => 'Панамський бальбоа (PAB)',
                'paraguayan-guarani' => 'Парагвайський гуарані (PYG)',
                'peruvian-nuevo-sol' => 'Перуанський новий сол (PEN)',
                'pgsql' => 'PgSQL',
                'philippine-peso' => 'Філіппінське песо (PHP)',
                'polish-zloty' => 'Польський злотий (PLN)',
                'qatari-rial' => 'Катарський ріал (QAR)',
                'romanian-leu' => 'Румунський лей (RON)',
                'russian-ruble' => 'Російський рубль (RUB)',
                'saudi-riyal' => 'Саудівський ріал (SAR)',
                'select-timezone' => 'Вибрати часовий пояс',
                'singapore-dollar' => 'Сінгапурський долар (SGD)',
                'south-african-rand' => 'Південноафриканський ренд (ZAR)',
                'south-korean-won' => 'Південнокорейський вон (KRW)',
                'sqlsrv' => 'SQLSRV',
                'sri-lankan-rupee' => 'Шрі-ланкійська рупія (LKR)',
                'swedish-krona' => 'Шведська крона (SEK)',
                'swiss-franc' => 'Швейцарський франк (CHF)',
                'thai-baht' => 'Тайський бат (THB)',
                'title' => 'Налаштування магазину',
                'tunisian-dinar' => 'Туніський динар (TND)',
                'turkish-lira' => 'Турецька ліра (TRY)',
                'ukrainian-hryvnia' => 'Українська гривня (UAH)',
                'united-arab-emirates-dirham' => 'Дирхам Об\'єднаних Арабських Еміратів (AED)',
                'united-states-dollar' => 'Долар США (USD)',
                'uzbekistani-som' => 'Узбецький сум (UZS)',
                'venezuelan-bolívar' => 'Венесуельський болівар (VEF)',
                'vietnamese-dong' => 'В\'єтнамський донг (VND)',
                'warning-message' => 'Увага! Налаштування мови системи та валюти за замовчуванням є постійними і не можуть бути змінені після встановлення.',
                'zambian-kwacha' => 'Замбійський квача (ZMW)',
            ],

            'sample-products' => [
                'no' => 'Ні',
                'note' => 'Примітка: Час індексації залежить від кількості вибраних локалей. Цей процес може зайняти до 2 хвилин.',
                'sample-products' => 'Зразки продукції',
                'title' => 'Зразки продукції',
                'yes' => 'Так',
            ],

            'installation-processing' => [
                'bagisto' => 'Установка Bagisto',
                'bagisto-info' => 'Створення таблиць бази даних, це може зайняти кілька хвилин',
                'title' => 'Установка',
            ],

            'installation-completed' => [
                'admin-panel' => 'Панель адміністратора',
                'bagisto-forums' => 'Форум Bagisto',
                'customer-panel' => 'Панель клієнта',
                'explore-bagisto-extensions' => 'Досліджуйте розширення Bagisto',
                'title' => 'Установка завершена',
                'title-info' => 'Bagisto успішно встановлено на вашій системі.',
            ],

            'ready-for-installation' => [
                'create-database-tables' => 'Створити таблиці бази даних',
                'drop-existing-tables' => 'Видалити існуючі таблиці',
                'install' => 'Встановлення',
                'install-info' => 'Bagisto для встановлення',
                'install-info-button' => 'Натисніть кнопку нижче, щоб',
                'populate-database-tables' => 'Заповнення таблиць бази даних',
                'start-installation' => 'Почати встановлення',
                'title' => 'Готовий до встановлення',
            ],

            'start' => [
                'locale' => 'Локаль',
                'main' => 'Початок',
                'select-locale' => 'Вибір локалі',
                'title' => 'Ваша установка Bagisto',
                'welcome-title' => 'Ласкаво просимо до Bagisto',
            ],

            'server-requirements' => [
                'calendar' => 'Календар',
                'ctype' => 'cType',
                'curl' => 'cURL',
                'dom' => 'dom',
                'fileinfo' => 'Інформація про файли',
                'filter' => 'Фільтр',
                'gd' => 'GD',
                'hash' => 'Хеш',
                'intl' => 'intl',
                'json' => 'JSON',
                'mbstring' => 'mbstring',
                'openssl' => 'openssl',
                'pcre' => 'pcre',
                'pdo' => 'pdo',
                'php' => 'PHP',
                'php-version' => ':version або вище',
                'session' => 'сесія',
                'title' => 'Вимоги до сервера',
                'tokenizer' => 'токенізатор',
                'xml' => 'XML',
            ],

            'arabic' => 'Арабська',
            'back' => 'Назад',
            'bagisto' => 'Bagisto',
            'bagisto-info' => 'Спільний проект спільноти від Webkul',
            'bagisto-logo' => 'Логотип Bagisto',
            'bengali' => 'Бенгальська',
            'catalan' => 'Каталонська',
            'chinese' => 'Китайська',
            'continue' => 'Продовжити',
            'dutch' => 'Голландська',
            'english' => 'Англійська',
            'french' => 'Французька',
            'german' => 'Німецька',
            'hebrew' => 'Іврит',
            'hindi' => 'Гінді',
            'indonesian' => 'Індонезійська',
            'installation-description' => 'Встановлення Bagisto зазвичай включає кілька етапів. Ось загальний огляд процесу встановлення для Bagisto',
            'installation-info' => 'Ми раді вас бачити тут!',
            'installation-title' => 'Ласкаво просимо до встановлення Bagisto',
            'italian' => 'Італійська',
            'japanese' => 'Японська',
            'persian' => 'Перська',
            'polish' => 'Польська',
            'portuguese' => 'Бразильська португальська',
            'russian' => 'Російська',
            'sinhala' => 'Сингальська',
            'spanish' => 'Іспанська',
            'title' => 'Установник Bagisto',
            'turkish' => 'Турецька',
            'ukrainian' => 'Українська',
            'webkul' => 'Webkul',
        ],
    ],
];
