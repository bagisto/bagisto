<?php

return [
    'seeders' => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'По подразбиране',
            ],

            'attribute-groups' => [
                'description'      => 'Описание',
                'general'          => 'Общ преглед',
                'inventories'      => 'Инвентаризация',
                'meta-description' => 'Мета описание',
                'price'            => 'Цена',
                'settings'         => 'Настройки',
                'shipping'         => 'Доставка',
            ],

            'attributes' => [
                'brand'                => 'Марка',
                'color'                => 'Цвят',
                'cost'                 => 'Разходи',
                'description'          => 'Описание',
                'featured'             => 'Препоръчано',
                'guest-checkout'       => 'Guest Checkout',
                'height'               => 'Височина',
                'length'               => 'Дължина',
                'manage-stock'         => 'Управление на продукти',
                'meta-description'     => 'Мета описание',
                'meta-keywords'        => 'Мета кл. думи',
                'meta-title'           => 'Мета заглавие',
                'name'                 => 'Име',
                'new'                  => 'Нов',
                'price'                => 'Цена',
                'product-number'       => 'Номер на продукта',
                'short-description'    => 'Кратко описание',
                'size'                 => 'Размер',
                'sku'                  => 'SKU номер',
                'special-price'        => 'Специална цена',
                'special-price-from'   => 'От дата',
                'special-price-to'     => 'До дата',
                'status'               => 'Статус',
                'tax-category'         => 'Данъчна категория',
                'url-key'              => 'URL ключ',
                'visible-individually' => 'Visible Individually',
                'weight'               => 'Тегло',
                'width'                => 'Ширина',
            ],

            'attribute-options' => [
                'black'  => 'Черно',
                'green'  => 'Зелено',
                'l'      => 'L',
                'm'      => 'M',
                'red'    => 'Червено',
                's'      => 'S',
                'white'  => 'Бяло',
                'xl'     => 'XL',
                'yellow' => 'Жълто',
            ],
        ],

        'category' => [
            'categories' => [
                'description' => 'Описание',
                'name'        => 'Главна категория',
            ],
        ],

        'cms' => [
            'pages' => [
                'about-us' => [
                    'content' => 'Описание "За нас"',
                    'title'   => 'За нас',
                ],

                'contact-us' => [
                    'content' => 'Описание "Контакти"',
                    'title'   => 'Контакти',
                ],

                'customer-service' => [
                    'content' => 'Описание за "Обслужване на клиенти"',
                    'title'   => 'Обслужване на клиенти',
                ],

                'payment-policy' => [
                    'content' => 'Описание за "Политика за плащане"',
                    'title'   => 'Политика за плащане',
                ],

                'privacy-policy' => [
                    'content' => 'Описание за "Политика за поверителност"',
                    'title'   => 'Политика за поверителност',
                ],

                'refund-policy' => [
                    'content' => 'Описание за "Политика за връщане"',
                    'title'   => 'Политика за връщане',
                ],

                'return-policy' => [
                    'content' => 'Описание за "Политика за връщане"',
                    'title'   => 'Политика за връщане',
                ],

                'shipping-policy' => [
                    'content' => 'Описание за "Политика за доставка"',
                    'title'   => 'Политика за доставка',
                ],

                'terms-conditions' => [
                    'content' => 'Описание за "Общи условия"',
                    'title'   => 'Общи условия',
                ],

                'terms-of-use' => [
                    'content' => 'Описание за "Общи условия"',
                    'title'   => 'Общи условия',
                ],

                'whats-new' => [
                    'content' => 'Описание за "Какво ново?"',
                    'title'   => 'Какво ново?',
                ],
            ],
        ],

        'core' => [
            'channels' => [
                'name'             => 'По подразбиране',
                'meta-title'       => 'Демо магазин',
                'meta-keywords'    => 'Мета кл. думи на Демо магазин',
                'meta-description' => 'Мета описание на Демо магазин',
            ],

            'currencies' => [
                'AED' => 'Обединените Арабски Емирства Дирам',
                'ARS' => 'Аржентинско песо',
                'AUD' => 'Австралийски долар',
                'BDT' => 'Бангладешка така',
                'BHD' => 'Бахрейнски динар',
                'BRL' => 'Бразилски реал',
                'BGN' => 'Български лев',
                'CAD' => 'Канадски долар',
                'CHF' => 'Швейцарски франк',
                'CLP' => 'Чилийско песо',
                'CNY' => 'Китайски юан',
                'COP' => 'Колумбийско песо',
                'CZK' => 'Чешка крона',
                'DKK' => 'Датска крона',
                'DZD' => 'Алжирски динар',
                'EGP' => 'Египетски фунт',
                'EUR' => 'Евро',
                'FJD' => 'Фиджийски долар',
                'GBP' => 'Британски паунд стерлинг',
                'HKD' => 'Хонконгски долар',
                'HUF' => 'Унгарски форинт',
                'IDR' => 'Индонезийска рупия',
                'ILS' => 'Израелски нов шекел',
                'INR' => 'Индийска рупия',
                'JOD' => 'Йордански динар',
                'JPY' => 'Японска йена',
                'KRW' => 'Южнокорейски вон',
                'KWD' => 'Кувейтски динар',
                'KZT' => 'Казахстански тенге',
                'LBP' => 'Ливански фунт',
                'LKR' => 'Шри Ланкийска рупия',
                'LYD' => 'Либийски динар',
                'MAD' => 'Марокански дирам',
                'MUR' => 'Маврицийска рупия',
                'MXN' => 'Мексиканско песо',
                'MYR' => 'Малайзийски ринггит',
                'NGN' => 'Нигерийска найра',
                'NOK' => 'Норвежка крона',
                'NPR' => 'Непалска рупия',
                'NZD' => 'Новозеландски долар',
                'OMR' => 'Омански риал',
                'PAB' => 'Панамски балова',
                'PEN' => 'Перуански нов сол',
                'PHP' => 'Филипинско песо',
                'PKR' => 'Пакистанска рупия',
                'PLN' => 'Полски злоти',
                'PYG' => 'Парагвайски гуарани',
                'QAR' => 'Катарски риал',
                'RON' => 'Румънски лей',
                'RUB' => 'Руска рубла',
                'SAR' => 'Саудитски риал',
                'SEK' => 'Шведска крона',
                'SGD' => 'Сингапурски долар',
                'THB' => 'Тайландски бат',
                'TND' => 'Тунизийски динар',
                'TRY' => 'Турска лира',
                'TWD' => 'Тайвански долар',
                'UAH' => 'Украинска гривна',
                'USD' => 'Американски долар',
                'UZS' => 'Узбекски сом',
                'VEF' => 'Венецуелски боливар',
                'VND' => 'Виетнамски донг',
                'XAF' => 'CFA Франк BEAC',
                'XOF' => 'CFA Франк BCEAO',
                'ZAR' => 'Южноафрикански ранд',
                'ZMW' => 'Замбийска квача',
            ],

            'locales' => [
                'ar'    => 'Арабски',
                'bn'    => 'Бенгалски',
                'bg'    => 'Български',
                'de'    => 'Немски',
                'en'    => 'Английски',
                'es'    => 'Испански',
                'fa'    => 'Персийски',
                'fr'    => 'Френски',
                'he'    => 'Иврит',
                'hi_IN' => 'Хинди',
                'it'    => 'Италиански',
                'ja'    => 'Японски',
                'nl'    => 'Холандски',
                'pl'    => 'Полски',
                'pt_BR' => 'Бразилски португалски',
                'ru'    => 'Руски',
                'sin'   => 'Сингалски',
                'tr'    => 'Турски',
                'uk'    => 'Украински',
                'zh_CN' => 'Китайски',
            ],
        ],

        'customer' => [
            'customer-groups' => [
                'general'   => 'Общ преглед',
                'guest'     => 'Гост',
                'wholesale' => 'Търговия на едро',
            ],
        ],

        'inventory' => [
            'inventory-sources' => [
                'name' => 'По подразбиране',
            ],
        ],

        'shop' => [
            'theme-customizations' => [
                'all-products' => [
                    'name' => 'Всички продукти',

                    'options' => [
                        'title' => 'Всички продукти',
                    ],
                ],

                'bold-collections' => [
                    'content' => [
                        'btn-title'   => 'Прегледай колекции',
                        'description' => 'Представяме Ви новите ни смели колекции. Повишете своя стил с впечатляващи дизайни и ярки цветове. Открийте уникални модели, които ще преобразят гардероба ви. Подгответе се да изживеете нещо изключително!',
                        'title'       => 'Очаквайте с нетърпение нашите нови смели колекции!',
                    ],

                    'name' => 'Смели колекции',
                ],

                'categories-collections' => [
                    'name' => 'Категории колекции',
                ],

                'featured-collections' => [
                    'name' => 'Избрани колекции',

                    'options' => [
                        'title' => 'Избрани продукти',
                    ],
                ],

                'footer-links' => [
                    'name' => 'Връзки в Футър-а',

                    'options' => [
                        'about-us'         => 'За нас',
                        'contact-us'       => 'Контакти',
                        'customer-service' => 'Обслужване на клиенти',
                        'payment-policy'   => 'Политика за плащане',
                        'privacy-policy'   => 'Политика за поверителност',
                        'refund-policy'    => 'Политика за връщане',
                        'return-policy'    => 'Политика за връщане',
                        'shipping-policy'  => 'Политика за доставка',
                        'terms-conditions' => 'Общи условия',
                        'terms-of-use'     => 'Общи условия',
                        'whats-new'        => 'Какво ново?',
                    ],
                ],

                'game-container' => [
                    'content' => [
                        'sub-title-1' => 'Нашите колекции',
                        'sub-title-2' => 'Нашите колекции',
                        'title'       => 'Играта с нашите нови добавки!',
                    ],

                    'name' => 'Контейнер за игра',
                ],

                'image-carousel' => [
                    'name' => 'Карусел от изображения',

                    'sliders' => [
                        'title' => 'Подгответе се за новата колекция!',
                    ],
                ],

                'new-products' => [
                    'name' => 'Нови продукти',

                    'options' => [
                        'title' => 'Нови продукти',
                    ],
                ],

                'offer-information' => [
                    'content' => [
                        'title' => 'Получете до 40% отстъпка от първата си поръчка! ПАЗАРУВАЙТЕ СЕГА!',
                    ],

                    'name' => 'Информация за офертата',
                ],

                'services-content' => [
                    'description' => [
                        'emi-available-info'   => 'Безплатно разсрочено плащане с всички основни кредитни карти',
                        'free-shipping-info'   => 'Безплатна доставка за всички поръчки',
                        'product-replace-info' => 'Лесна замяна на продукта!',
                        'time-support-info'    => 'Специална поддръжка 24/7 чрез чат и имейл',
                    ],

                    'name' => 'Информация за услугите',

                    'title' => [
                        'emi-available'   => 'Emi Available',
                        'free-shipping'   => 'Безплатна доставка',
                        'product-replace' => 'Замяна на продукт',
                        'time-support'    => 'Поддръжка 24/7',
                    ],
                ],

                'top-collections' => [
                    'content' => [
                        'sub-title-1' => 'Нашите колекции',
                        'sub-title-2' => 'Нашите колекции',
                        'sub-title-3' => 'Нашите колекции',
                        'sub-title-4' => 'Нашите колекции',
                        'sub-title-5' => 'Нашите колекции',
                        'sub-title-6' => 'Нашите колекции',
                        'title'       => 'Играта с нашите нови добавки!',
                    ],

                    'name' => 'Топ колекции',
                ],
            ],
        ],

        'user' => [
            'roles' => [
                'description' => 'Тази роля ще даде на потребителите пълен достъп.',
                'name'        => 'Администратор',
            ],

            'users' => [
                'name' => 'Пример',
            ],
        ],

        'sample-categories' => [
            'category-translation' => [
                '2' => [
                    'description'      => 'Описание на категорията "Мъже"',
                    'meta-description' => 'Мета описание на категорията "Мъже"',
                    'meta-keywords'    => 'Мета кл. думи на категорията "Мъже"',
                    'meta-title'       => 'Мета заглавие на категорията "Мъже"',
                    'name'             => 'Мъже',
                    'slug'             => 'muzhe',
                ],

                '3' => [
                    'description'      => 'Описание на категорията "Зимно облекло"',
                    'meta-description' => 'Мета описание на категорията "Зимно облекло"',
                    'meta-keywords'    => 'Мета кл. думи на категорията "Зимно облекло"',
                    'meta-title'       => 'Мета заглавие на категорията "Зимно облекло"',
                    'name'             => 'Зимно облекло',
                    'slug'             => 'zimno-obleklo',
                ],
            ],
        ],

        'sample-products' => [
            'product-flat' => [
                '1' => [
                    'description'       => 'The Arctic Cozy Knit Beanie is your go-to solution for staying warm, comfortable, and stylish during the colder months. Crafted from a soft and durable blend of acrylic knit, this beanie is designed to provide a cozy and snug fit. The classic design makes it suitable for both men and women, offering a versatile accessory that complements various styles. Whether you\'re heading out for a casual day in town or embracing the great outdoors, this beanie adds a touch of comfort and warmth to your ensemble. The soft and breathable material ensures that you stay cozy without sacrificing style. The Arctic Cozy Knit Beanie isn\'t just an accessory; it\'s a statement of winter fashion. Its simplicity makes it easy to pair with different outfits, making it a staple in your winter wardrobe. Ideal for gifting or as a treat for yourself, this beanie is a thoughtful addition to any winter ensemble. It\'s a versatile accessory that goes beyond functionality, adding a touch of warmth and style to your look. Embrace the essence of winter with the Arctic Cozy Knit Beanie. Whether you\'re enjoying a casual day out or facing the elements, let this beanie be your companion for comfort and style. Elevate your winter wardrobe with this classic accessory that effortlessly combines warmth with a timeless sense of fashion.',
                    'meta-description'  => 'meta description',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Meta Title',
                    'name'              => 'Arctic Cozy Knit Unisex Beanie',
                    'short-description' => 'Embrace the chilly days in style with our Arctic Cozy Knit Beanie. Crafted from a soft and durable blend of acrylic, this classic beanie offers warmth and versatility. Suitable for both men and women, it\'s the ideal accessory for casual or outdoor wear. Elevate your winter wardrobe or gift someone special with this essential beanie cap.',
                ],

                '2' => [
                    'description'       => 'The Arctic Bliss Winter Scarf is more than just a cold-weather accessory; it\'s a statement of warmth, comfort, and style for the winter season. Crafted with care from a luxurious blend of acrylic and wool, this scarf is designed to keep you cozy and snug even in the chilliest temperatures. The soft and plush texture not only provides insulation against the cold but also adds a touch of luxury to your winter wardrobe. The design of the Arctic Bliss Winter Scarf is both stylish and versatile, making it a perfect addition to a variety of winter outfits. Whether you\'re dressing up for a special occasion or adding a chic layer to your everyday look, this scarf complements your style effortlessly. The extra-long length of the scarf offers customizable styling options. Wrap it around for added warmth, drape it loosely for a casual look, or experiment with different knots to express your unique style. This versatility makes it a must-have accessory for the winter season. Looking for the perfect gift? The Arctic Bliss Winter Scarf is an ideal choice. Whether you\'re surprising a loved one or treating yourself, this scarf is a timeless and practical gift that will be cherished throughout the winter months. Embrace the winter with the Arctic Bliss Winter Scarf, where warmth meets style in perfect harmony. Elevate your winter wardrobe with this essential accessory that not only keeps you warm but also adds a touch of sophistication to your cold-weather ensemble.',
                    'meta-description'  => 'meta description',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Meta Title',
                    'name'              => 'Arctic Bliss Stylish Winter Scarf',
                    'short-description' => 'Experience the embrace of warmth and style with our Arctic Bliss Winter Scarf. Crafted from a luxurious blend of acrylic and wool, this cozy scarf is designed to keep you snug during the coldest days. Its stylish and versatile design, combined with an extra-long length, offers customizable styling options. Elevate your winter wardrobe or delight someone special with this essential winter accessory.',
                ],

                '3' => [
                    'description'       => 'Introducing the Arctic Touchscreen Winter Gloves – where warmth, style, and connectivity meet to enhance your winter experience. Crafted from high-quality acrylic, these gloves are designed to provide exceptional warmth and durability. The touchscreen-compatible fingertips allow you to stay connected without exposing your hands to the cold. Answer calls, send messages, and navigate your devices effortlessly, all while keeping your hands snug. The insulated lining adds an extra layer of coziness, making these gloves your go-to choice for facing the winter chill. Whether you\'re commuting, running errands, or enjoying outdoor activities, these gloves provide the warmth and protection you need. Elastic cuffs ensure a secure fit, preventing cold drafts and keeping the gloves in place during your daily activities. The stylish design adds a touch of flair to your winter ensemble, making these gloves as fashionable as they are functional. Ideal for gifting or as a treat for yourself, the Arctic Touchscreen Winter Gloves are a must-have accessory for the modern individual. Say goodbye to the inconvenience of removing your gloves to use your devices and embrace the seamless blend of warmth, style, and connectivity. Stay connected, stay warm, and stay stylish with the Arctic Touchscreen Winter Gloves – your reliable companion for conquering the winter season with confidence.',
                    'meta-description'  => 'meta description',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Meta Title',
                    'name'              => 'Arctic Touchscreen Winter Gloves',
                    'short-description' => 'Stay connected and warm with our Arctic Touchscreen Winter Gloves. These gloves are not only crafted from high-quality acrylic for warmth and durability but also feature a touchscreen-compatible design. With an insulated lining, elastic cuffs for a secure fit, and a stylish look, these gloves are perfect for daily wear in chilly conditions.',
                ],

                '4' => [
                    'description'       => 'Introducing the Arctic Warmth Wool Blend Socks – your essential companion for cozy and comfortable feet during the colder seasons. Crafted from a premium blend of Merino wool, acrylic, nylon, and spandex, these socks are designed to provide unparalleled warmth and comfort. The wool blend ensures that your feet stay toasty even in the coldest temperatures, making these socks the perfect choice for winter adventures or simply staying snug at home. The soft and cozy texture of the socks offers a luxurious feel against your skin. Say goodbye to chilly feet as you embrace the plush warmth provided by these wool blend socks. Designed for durability, the socks feature a reinforced heel and toe, adding extra strength to high-wear areas. This ensures that your socks withstand the test of time, providing long-lasting comfort and coziness. The breathable nature of the material prevents overheating, allowing your feet to stay comfortable and dry throughout the day. Whether you\'re heading outdoors for a winter hike or relaxing indoors, these socks offer the perfect balance of warmth and breathability. Versatile and stylish, these wool blend socks are suitable for various occasions. Pair them with your favorite boots for a fashionable winter look or wear them around the house for ultimate comfort. Elevate your winter wardrobe and prioritize comfort with the Arctic Warmth Wool Blend Socks. Treat your feet to the luxury they deserve and step into a world of coziness that lasts all season long.',
                    'meta-description'  => 'meta description',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Meta Title',
                    'name'              => 'Arctic Warmth Wool Blend Socks',
                    'short-description' => 'Experience the unmatched warmth and comfort of our Arctic Warmth Wool Blend Socks. Crafted from a blend of Merino wool, acrylic, nylon, and spandex, these socks offer ultimate coziness for cold weather. With a reinforced heel and toe for durability, these versatile and stylish socks are perfect for various occasions.',
                ],

                '5' => [
                    'description'       => 'Introducing the Arctic Frost Winter Accessories Bundle, your go-to solution for staying warm, stylish, and connected during the chilly winter days. This thoughtfully curated set brings together Four essential winter accessories to create a harmonious ensemble. The luxurious scarf, woven from a blend of acrylic and wool, not only adds a layer of warmth but also brings a touch of elegance to your winter wardrobe. The soft knit beanie, crafted with care, promises to keep you cozy while adding a fashionable flair to your look. But it doesn\'t end there – our bundle also includes touchscreen-compatible gloves. Stay connected without sacrificing warmth as you navigate your devices effortlessly. Whether you\'re answering calls, sending messages, or capturing winter moments on your smartphone, these gloves ensure convenience without compromising style. The soft and cozy texture of the socks offers a luxurious feel against your skin. Say goodbye to chilly feet as you embrace the plush warmth provided by these wool blend socks. The Arctic Frost Winter Accessories Bundle is not just about functionality; it\'s a statement of winter fashion. Each piece is designed not only to protect you from the cold but also to elevate your style during the frosty season. The materials chosen for this bundle prioritize both durability and comfort, ensuring that you can enjoy the winter wonderland in style. Whether you\'re treating yourself or searching for the perfect gift, the Arctic Frost Winter Accessories Bundle is a versatile choice. Delight someone special during the holiday season or elevate your own winter wardrobe with this stylish and functional ensemble. Embrace the frost with confidence, knowing that you have the perfect accessories to keep you warm and chic.',
                    'meta-description'  => 'meta description',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Meta Title',
                    'name'              => 'Arctic Frost Winter Accessories',
                    'short-description' => 'Embrace the winter chill with our Arctic Frost Winter Accessories Bundle. This curated set includes a luxurious scarf, a cozy beanie, touchscreen-compatible gloves and wool Blend Socks. Stylish and functional, this ensemble is crafted from high-quality materials, ensuring both durability and comfort. Elevate your winter wardrobe or delight someone special with this perfect gifting option.',
                ],

                '6' => [
                    'description'       => 'Introducing the Arctic Frost Winter Accessories Bundle, your go-to solution for staying warm, stylish, and connected during the chilly winter days. This thoughtfully curated set brings together Four essential winter accessories to create a harmonious ensemble. The luxurious scarf, woven from a blend of acrylic and wool, not only adds a layer of warmth but also brings a touch of elegance to your winter wardrobe. The soft knit beanie, crafted with care, promises to keep you cozy while adding a fashionable flair to your look. But it doesn\'t end there – our bundle also includes touchscreen-compatible gloves. Stay connected without sacrificing warmth as you navigate your devices effortlessly. Whether you\'re answering calls, sending messages, or capturing winter moments on your smartphone, these gloves ensure convenience without compromising style. The soft and cozy texture of the socks offers a luxurious feel against your skin. Say goodbye to chilly feet as you embrace the plush warmth provided by these wool blend socks. The Arctic Frost Winter Accessories Bundle is not just about functionality; it\'s a statement of winter fashion. Each piece is designed not only to protect you from the cold but also to elevate your style during the frosty season. The materials chosen for this bundle prioritize both durability and comfort, ensuring that you can enjoy the winter wonderland in style. Whether you\'re treating yourself or searching for the perfect gift, the Arctic Frost Winter Accessories Bundle is a versatile choice. Delight someone special during the holiday season or elevate your own winter wardrobe with this stylish and functional ensemble. Embrace the frost with confidence, knowing that you have the perfect accessories to keep you warm and chic.',
                    'meta-description'  => 'meta description',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Meta Title',
                    'name'              => 'Arctic Frost Winter Accessories Bundle',
                    'short-description' => 'Embrace the winter chill with our Arctic Frost Winter Accessories Bundle. This curated set includes a luxurious scarf, a cozy beanie, touchscreen-compatible gloves and wool Blend Socks. Stylish and functional, this ensemble is crafted from high-quality materials, ensuring both durability and comfort. Elevate your winter wardrobe or delight someone special with this perfect gifting option.',
                ],

                '7' => [
                    'description'       => 'Introducing the OmniHeat Men\'s Solid Hooded Puffer Jacket, your go-to solution for staying warm and fashionable during colder seasons. This jacket is crafted with durability and warmth in mind, ensuring it becomes your trusted companion. The hooded design not only adds a touch of style but also provides additional warmth, shielding you from cold winds and weather. The full sleeves offer complete coverage, ensuring you stay cozy from shoulder to wrist. Equipped with insert pockets, this puffer jacket provides convenience for carrying your essentials or keeping your hands warm. The insulated synthetic filling offers enhanced warmth, making it ideal for battling chilly days and nights. Made from a durable polyester shell and lining, this jacket is built to last and endure the elements. Available in 5 attractive colors, you can choose the one that suits your style and preference. Versatile and functional, the OmniHeat Men\'s Solid Hooded Puffer Jacket is suitable for various occasions, whether you\'re heading to work, going for a casual outing, or attending an outdoor event. Experience the perfect blend of style, comfort, and functionality with OmniHeat Men\'s Solid Hooded Puffer Jacket. Elevate your winter wardrobe and stay snug while embracing the outdoors. Beat the cold in style and make a statement with this essential piece.',
                    'meta-description'  => 'meta description',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Meta Title',
                    'name'              => 'OmniHeat Men\'s Solid Hooded Puffer Jacket',
                    'short-description' => 'Stay warm and stylish with our OmniHeat Men\'s Solid Hooded Puffer Jacket. This jacket is designed to provide ultimate warmth and features insert pockets for added convenience. The insulated material ensures you stay cozy in cold weather. Available in 5 attractive colors, making it a versatile choice for various occasions.',
                ],

                '8' => [
                    'description'       => 'Introducing the OmniHeat Men\'s Solid Hooded Puffer Jacket, your go-to solution for staying warm and fashionable during colder seasons. This jacket is crafted with durability and warmth in mind, ensuring it becomes your trusted companion. The hooded design not only adds a touch of style but also provides additional warmth, shielding you from cold winds and weather. The full sleeves offer complete coverage, ensuring you stay cozy from shoulder to wrist. Equipped with insert pockets, this puffer jacket provides convenience for carrying your essentials or keeping your hands warm. The insulated synthetic filling offers enhanced warmth, making it ideal for battling chilly days and nights. Made from a durable polyester shell and lining, this jacket is built to last and endure the elements. Available in 5 attractive colors, you can choose the one that suits your style and preference. Versatile and functional, the OmniHeat Men\'s Solid Hooded Puffer Jacket is suitable for various occasions, whether you\'re heading to work, going for a casual outing, or attending an outdoor event. Experience the perfect blend of style, comfort, and functionality with OmniHeat Men\'s Solid Hooded Puffer Jacket. Elevate your winter wardrobe and stay snug while embracing the outdoors. Beat the cold in style and make a statement with this essential piece.',
                    'meta-description'  => 'meta description',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Meta Title',
                    'name'              => 'OmniHeat Men\'s Solid Hooded Puffer Jacket-Blue-Yellow-M',
                    'short-description' => 'Stay warm and stylish with our OmniHeat Men\'s Solid Hooded Puffer Jacket. This jacket is designed to provide ultimate warmth and features insert pockets for added convenience. The insulated material ensures you stay cozy in cold weather. Available in 5 attractive colors, making it a versatile choice for various occasions.',
                ],

                '9' => [
                    'description'       => 'DescIntroducing the OmniHeat Men\'s Solid Hooded Puffer Jacket, your go-to solution for staying warm and fashionable during colder seasons. This jacket is crafted with durability and warmth in mind, ensuring it becomes your trusted companion. The hooded design not only adds a touch of style but also provides additional warmth, shielding you from cold winds and weather. The full sleeves offer complete coverage, ensuring you stay cozy from shoulder to wrist. Equipped with insert pockets, this puffer jacket provides convenience for carrying your essentials or keeping your hands warm. The insulated synthetic filling offers enhanced warmth, making it ideal for battling chilly days and nights. Made from a durable polyester shell and lining, this jacket is built to last and endure the elements. Available in 5 attractive colors, you can choose the one that suits your style and preference. Versatile and functional, the OmniHeat Men\'s Solid Hooded Puffer Jacket is suitable for various occasions, whether you\'re heading to work, going for a casual outing, or attending an outdoor event. Experience the perfect blend of style, comfort, and functionality with OmniHeat Men\'s Solid Hooded Puffer Jacket. Elevate your winter wardrobe and stay snug while embracing the outdoors. Beat the cold in style and make a statement with this essential piece.ription 9',
                    'meta-description'  => 'meta description',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Meta Title',
                    'name'              => 'OmniHeat Men\'s Solid Hooded Puffer Jacket-Blue-Yellow-L',
                    'short-description' => 'Stay warm and stylish with our OmniHeat Men\'s Solid Hooded Puffer Jacket. This jacket is designed to provide ultimate warmth and features insert pockets for added convenience. The insulated material ensures you stay cozy in cold weather. Available in 5 attractive colors, making it a versatile choice for various occasions.',
                ],

                '10' => [
                    'description'       => 'Introducing the OmniHeat Men\'s Solid Hooded Puffer Jacket, your go-to solution for staying warm and fashionable during colder seasons. This jacket is crafted with durability and warmth in mind, ensuring it becomes your trusted companion. The hooded design not only adds a touch of style but also provides additional warmth, shielding you from cold winds and weather. The full sleeves offer complete coverage, ensuring you stay cozy from shoulder to wrist. Equipped with insert pockets, this puffer jacket provides convenience for carrying your essentials or keeping your hands warm. The insulated synthetic filling offers enhanced warmth, making it ideal for battling chilly days and nights. Made from a durable polyester shell and lining, this jacket is built to last and endure the elements. Available in 5 attractive colors, you can choose the one that suits your style and preference. Versatile and functional, the OmniHeat Men\'s Solid Hooded Puffer Jacket is suitable for various occasions, whether you\'re heading to work, going for a casual outing, or attending an outdoor event. Experience the perfect blend of style, comfort, and functionality with OmniHeat Men\'s Solid Hooded Puffer Jacket. Elevate your winter wardrobe and stay snug while embracing the outdoors. Beat the cold in style and make a statement with this essential piece.',
                    'meta-description'  => 'meta description',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Meta Title',
                    'name'              => 'OmniHeat Men\'s Solid Hooded Puffer Jacket-Blue-Green-M',
                    'short-description' => 'Stay warm and stylish with our OmniHeat Men\'s Solid Hooded Puffer Jacket. This jacket is designed to provide ultimate warmth and features insert pockets for added convenience. The insulated material ensures you stay cozy in cold weather. Available in 5 attractive colors, making it a versatile choice for various occasions.',
                ],

                '11' => [
                    'description'       => 'Introducing the OmniHeat Men\'s Solid Hooded Puffer Jacket, your go-to solution for staying warm and fashionable during colder seasons. This jacket is crafted with durability and warmth in mind, ensuring it becomes your trusted companion. The hooded design not only adds a touch of style but also provides additional warmth, shielding you from cold winds and weather. The full sleeves offer complete coverage, ensuring you stay cozy from shoulder to wrist. Equipped with insert pockets, this puffer jacket provides convenience for carrying your essentials or keeping your hands warm. The insulated synthetic filling offers enhanced warmth, making it ideal for battling chilly days and nights. Made from a durable polyester shell and lining, this jacket is built to last and endure the elements. Available in 5 attractive colors, you can choose the one that suits your style and preference. Versatile and functional, the OmniHeat Men\'s Solid Hooded Puffer Jacket is suitable for various occasions, whether you\'re heading to work, going for a casual outing, or attending an outdoor event. Experience the perfect blend of style, comfort, and functionality with OmniHeat Men\'s Solid Hooded Puffer Jacket. Elevate your winter wardrobe and stay snug while embracing the outdoors. Beat the cold in style and make a statement with this essential piece.',
                    'meta-description'  => 'meta description',
                    'meta-keywords'     => 'meta1, meta2, meta3',
                    'meta-title'        => 'Meta Title',
                    'name'              => 'OmniHeat Men\'s Solid Hooded Puffer Jacket-Blue-Green-L',
                    'short-description' => 'Stay warm and stylish with our OmniHeat Men\'s Solid Hooded Puffer Jacket. This jacket is designed to provide ultimate warmth and features insert pockets for added convenience. The insulated material ensures you stay cozy in cold weather. Available in 5 attractive colors, making it a versatile choice for various occasions.',
                ],
            ],

            'product-attribute-values' => [
                '1' => [
                    'description'      => 'The Arctic Cozy Knit Beanie is your go-to solution for staying warm, comfortable, and stylish during the colder months. Crafted from a soft and durable blend of acrylic knit, this beanie is designed to provide a cozy and snug fit. The classic design makes it suitable for both men and women, offering a versatile accessory that complements various styles. Whether you\'re heading out for a casual day in town or embracing the great outdoors, this beanie adds a touch of comfort and warmth to your ensemble. The soft and breathable material ensures that you stay cozy without sacrificing style. The Arctic Cozy Knit Beanie isn\'t just an accessory; it\'s a statement of winter fashion. Its simplicity makes it easy to pair with different outfits, making it a staple in your winter wardrobe. Ideal for gifting or as a treat for yourself, this beanie is a thoughtful addition to any winter ensemble. It\'s a versatile accessory that goes beyond functionality, adding a touch of warmth and style to your look. Embrace the essence of winter with the Arctic Cozy Knit Beanie. Whether you\'re enjoying a casual day out or facing the elements, let this beanie be your companion for comfort and style. Elevate your winter wardrobe with this classic accessory that effortlessly combines warmth with a timeless sense of fashion.',
                    'meta-description' => 'meta description',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Meta Title',
                    'name'             => 'Arctic Cozy Knit Unisex Beanie',
                    'sort-description' => 'Embrace the chilly days in style with our Arctic Cozy Knit Beanie. Crafted from a soft and durable blend of acrylic, this classic beanie offers warmth and versatility. Suitable for both men and women, it\'s the ideal accessory for casual or outdoor wear. Elevate your winter wardrobe or gift someone special with this essential beanie cap.',
                ],

                '2' => [
                    'description'      => 'The Arctic Bliss Winter Scarf is more than just a cold-weather accessory; it\'s a statement of warmth, comfort, and style for the winter season. Crafted with care from a luxurious blend of acrylic and wool, this scarf is designed to keep you cozy and snug even in the chilliest temperatures. The soft and plush texture not only provides insulation against the cold but also adds a touch of luxury to your winter wardrobe. The design of the Arctic Bliss Winter Scarf is both stylish and versatile, making it a perfect addition to a variety of winter outfits. Whether you\'re dressing up for a special occasion or adding a chic layer to your everyday look, this scarf complements your style effortlessly. The extra-long length of the scarf offers customizable styling options. Wrap it around for added warmth, drape it loosely for a casual look, or experiment with different knots to express your unique style. This versatility makes it a must-have accessory for the winter season. Looking for the perfect gift? The Arctic Bliss Winter Scarf is an ideal choice. Whether you\'re surprising a loved one or treating yourself, this scarf is a timeless and practical gift that will be cherished throughout the winter months. Embrace the winter with the Arctic Bliss Winter Scarf, where warmth meets style in perfect harmony. Elevate your winter wardrobe with this essential accessory that not only keeps you warm but also adds a touch of sophistication to your cold-weather ensemble.',
                    'meta-description' => 'meta description',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Meta Title',
                    'name'             => 'Arctic Bliss Stylish Winter Scarf',
                    'sort-description' => 'Experience the embrace of warmth and style with our Arctic Bliss Winter Scarf. Crafted from a luxurious blend of acrylic and wool, this cozy scarf is designed to keep you snug during the coldest days. Its stylish and versatile design, combined with an extra-long length, offers customizable styling options. Elevate your winter wardrobe or delight someone special with this essential winter accessory.',
                ],

                '3' => [
                    'description'      => 'Introducing the Arctic Touchscreen Winter Gloves – where warmth, style, and connectivity meet to enhance your winter experience. Crafted from high-quality acrylic, these gloves are designed to provide exceptional warmth and durability. The touchscreen-compatible fingertips allow you to stay connected without exposing your hands to the cold. Answer calls, send messages, and navigate your devices effortlessly, all while keeping your hands snug. The insulated lining adds an extra layer of coziness, making these gloves your go-to choice for facing the winter chill. Whether you\'re commuting, running errands, or enjoying outdoor activities, these gloves provide the warmth and protection you need. Elastic cuffs ensure a secure fit, preventing cold drafts and keeping the gloves in place during your daily activities. The stylish design adds a touch of flair to your winter ensemble, making these gloves as fashionable as they are functional. Ideal for gifting or as a treat for yourself, the Arctic Touchscreen Winter Gloves are a must-have accessory for the modern individual. Say goodbye to the inconvenience of removing your gloves to use your devices and embrace the seamless blend of warmth, style, and connectivity. Stay connected, stay warm, and stay stylish with the Arctic Touchscreen Winter Gloves – your reliable companion for conquering the winter season with confidence.',
                    'meta-description' => 'meta description',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Meta Title',
                    'name'             => 'Arctic Touchscreen Winter Gloves',
                    'sort-description' => 'Stay connected and warm with our Arctic Touchscreen Winter Gloves. These gloves are not only crafted from high-quality acrylic for warmth and durability but also feature a touchscreen-compatible design. With an insulated lining, elastic cuffs for a secure fit, and a stylish look, these gloves are perfect for daily wear in chilly conditions.',
                ],

                '4' => [
                    'description'      => 'Introducing the Arctic Warmth Wool Blend Socks – your essential companion for cozy and comfortable feet during the colder seasons. Crafted from a premium blend of Merino wool, acrylic, nylon, and spandex, these socks are designed to provide unparalleled warmth and comfort. The wool blend ensures that your feet stay toasty even in the coldest temperatures, making these socks the perfect choice for winter adventures or simply staying snug at home. The soft and cozy texture of the socks offers a luxurious feel against your skin. Say goodbye to chilly feet as you embrace the plush warmth provided by these wool blend socks. Designed for durability, the socks feature a reinforced heel and toe, adding extra strength to high-wear areas. This ensures that your socks withstand the test of time, providing long-lasting comfort and coziness. The breathable nature of the material prevents overheating, allowing your feet to stay comfortable and dry throughout the day. Whether you\'re heading outdoors for a winter hike or relaxing indoors, these socks offer the perfect balance of warmth and breathability. Versatile and stylish, these wool blend socks are suitable for various occasions. Pair them with your favorite boots for a fashionable winter look or wear them around the house for ultimate comfort. Elevate your winter wardrobe and prioritize comfort with the Arctic Warmth Wool Blend Socks. Treat your feet to the luxury they deserve and step into a world of coziness that lasts all season long.',
                    'meta-description' => 'meta description',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Meta Title',
                    'name'             => 'Arctic Warmth Wool Blend Socks',
                    'sort-description' => 'Experience the unmatched warmth and comfort of our Arctic Warmth Wool Blend Socks. Crafted from a blend of Merino wool, acrylic, nylon, and spandex, these socks offer ultimate coziness for cold weather. With a reinforced heel and toe for durability, these versatile and stylish socks are perfect for various occasions.',
                ],

                '5' => [
                    'description'      => 'Introducing the Arctic Frost Winter Accessories Bundle, your go-to solution for staying warm, stylish, and connected during the chilly winter days. This thoughtfully curated set brings together Four essential winter accessories to create a harmonious ensemble. The luxurious scarf, woven from a blend of acrylic and wool, not only adds a layer of warmth but also brings a touch of elegance to your winter wardrobe. The soft knit beanie, crafted with care, promises to keep you cozy while adding a fashionable flair to your look. But it doesn\'t end there – our bundle also includes touchscreen-compatible gloves. Stay connected without sacrificing warmth as you navigate your devices effortlessly. Whether you\'re answering calls, sending messages, or capturing winter moments on your smartphone, these gloves ensure convenience without compromising style. The soft and cozy texture of the socks offers a luxurious feel against your skin. Say goodbye to chilly feet as you embrace the plush warmth provided by these wool blend socks. The Arctic Frost Winter Accessories Bundle is not just about functionality; it\'s a statement of winter fashion. Each piece is designed not only to protect you from the cold but also to elevate your style during the frosty season. The materials chosen for this bundle prioritize both durability and comfort, ensuring that you can enjoy the winter wonderland in style. Whether you\'re treating yourself or searching for the perfect gift, the Arctic Frost Winter Accessories Bundle is a versatile choice. Delight someone special during the holiday season or elevate your own winter wardrobe with this stylish and functional ensemble. Embrace the frost with confidence, knowing that you have the perfect accessories to keep you warm and chic.',
                    'meta-description' => 'meta description',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Meta Title',
                    'name'             => 'Arctic Frost Winter Accessories',
                    'sort-description' => 'Embrace the winter chill with our Arctic Frost Winter Accessories Bundle. This curated set includes a luxurious scarf, a cozy beanie, touchscreen-compatible gloves and wool Blend Socks. Stylish and functional, this ensemble is crafted from high-quality materials, ensuring both durability and comfort. Elevate your winter wardrobe or delight someone special with this perfect gifting option.',
                ],

                '6' => [
                    'description'      => 'Introducing the Arctic Frost Winter Accessories Bundle, your go-to solution for staying warm, stylish, and connected during the chilly winter days. This thoughtfully curated set brings together Four essential winter accessories to create a harmonious ensemble. The luxurious scarf, woven from a blend of acrylic and wool, not only adds a layer of warmth but also brings a touch of elegance to your winter wardrobe. The soft knit beanie, crafted with care, promises to keep you cozy while adding a fashionable flair to your look. But it doesn\'t end there – our bundle also includes touchscreen-compatible gloves. Stay connected without sacrificing warmth as you navigate your devices effortlessly. Whether you\'re answering calls, sending messages, or capturing winter moments on your smartphone, these gloves ensure convenience without compromising style. The soft and cozy texture of the socks offers a luxurious feel against your skin. Say goodbye to chilly feet as you embrace the plush warmth provided by these wool blend socks. The Arctic Frost Winter Accessories Bundle is not just about functionality; it\'s a statement of winter fashion. Each piece is designed not only to protect you from the cold but also to elevate your style during the frosty season. The materials chosen for this bundle prioritize both durability and comfort, ensuring that you can enjoy the winter wonderland in style. Whether you\'re treating yourself or searching for the perfect gift, the Arctic Frost Winter Accessories Bundle is a versatile choice. Delight someone special during the holiday season or elevate your own winter wardrobe with this stylish and functional ensemble. Embrace the frost with confidence, knowing that you have the perfect accessories to keep you warm and chic.',
                    'meta-description' => 'meta description',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Meta Title',
                    'name'             => 'Arctic Frost Winter Accessories Bundle',
                    'sort-description' => 'Embrace the winter chill with our Arctic Frost Winter Accessories Bundle. This curated set includes a luxurious scarf, a cozy beanie, touchscreen-compatible gloves and wool Blend Socks. Stylish and functional, this ensemble is crafted from high-quality materials, ensuring both durability and comfort. Elevate your winter wardrobe or delight someone special with this perfect gifting option.',
                ],

                '7' => [
                    'description'      => 'Introducing the OmniHeat Men\'s Solid Hooded Puffer Jacket, your go-to solution for staying warm and fashionable during colder seasons. This jacket is crafted with durability and warmth in mind, ensuring it becomes your trusted companion. The hooded design not only adds a touch of style but also provides additional warmth, shielding you from cold winds and weather. The full sleeves offer complete coverage, ensuring you stay cozy from shoulder to wrist. Equipped with insert pockets, this puffer jacket provides convenience for carrying your essentials or keeping your hands warm. The insulated synthetic filling offers enhanced warmth, making it ideal for battling chilly days and nights. Made from a durable polyester shell and lining, this jacket is built to last and endure the elements. Available in 5 attractive colors, you can choose the one that suits your style and preference. Versatile and functional, the OmniHeat Men\'s Solid Hooded Puffer Jacket is suitable for various occasions, whether you\'re heading to work, going for a casual outing, or attending an outdoor event. Experience the perfect blend of style, comfort, and functionality with OmniHeat Men\'s Solid Hooded Puffer Jacket. Elevate your winter wardrobe and stay snug while embracing the outdoors. Beat the cold in style and make a statement with this essential piece.',
                    'meta-description' => 'meta description',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Meta Title',
                    'name'             => 'OmniHeat Men\'s Solid Hooded Puffer Jacket',
                    'sort-description' => 'Stay warm and stylish with our OmniHeat Men\'s Solid Hooded Puffer Jacket. This jacket is designed to provide ultimate warmth and features insert pockets for added convenience. The insulated material ensures you stay cozy in cold weather. Available in 5 attractive colors, making it a versatile choice for various occasions.',
                ],

                '8' => [
                    'description'      => 'Introducing the OmniHeat Men\'s Solid Hooded Puffer Jacket, your go-to solution for staying warm and fashionable during colder seasons. This jacket is crafted with durability and warmth in mind, ensuring it becomes your trusted companion. The hooded design not only adds a touch of style but also provides additional warmth, shielding you from cold winds and weather. The full sleeves offer complete coverage, ensuring you stay cozy from shoulder to wrist. Equipped with insert pockets, this puffer jacket provides convenience for carrying your essentials or keeping your hands warm. The insulated synthetic filling offers enhanced warmth, making it ideal for battling chilly days and nights. Made from a durable polyester shell and lining, this jacket is built to last and endure the elements. Available in 5 attractive colors, you can choose the one that suits your style and preference. Versatile and functional, the OmniHeat Men\'s Solid Hooded Puffer Jacket is suitable for various occasions, whether you\'re heading to work, going for a casual outing, or attending an outdoor event. Experience the perfect blend of style, comfort, and functionality with OmniHeat Men\'s Solid Hooded Puffer Jacket. Elevate your winter wardrobe and stay snug while embracing the outdoors. Beat the cold in style and make a statement with this essential piece.',
                    'meta-description' => 'meta description',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Meta Title',
                    'name'             => 'OmniHeat Men\'s Solid Hooded Puffer Jacket-Blue-Yellow-M',
                    'sort-description' => 'Stay warm and stylish with our OmniHeat Men\'s Solid Hooded Puffer Jacket. This jacket is designed to provide ultimate warmth and features insert pockets for added convenience. The insulated material ensures you stay cozy in cold weather. Available in 5 attractive colors, making it a versatile choice for various occasions.',
                ],

                '9' => [
                    'description'      => 'DescIntroducing the OmniHeat Men\'s Solid Hooded Puffer Jacket, your go-to solution for staying warm and fashionable during colder seasons. This jacket is crafted with durability and warmth in mind, ensuring it becomes your trusted companion. The hooded design not only adds a touch of style but also provides additional warmth, shielding you from cold winds and weather. The full sleeves offer complete coverage, ensuring you stay cozy from shoulder to wrist. Equipped with insert pockets, this puffer jacket provides convenience for carrying your essentials or keeping your hands warm. The insulated synthetic filling offers enhanced warmth, making it ideal for battling chilly days and nights. Made from a durable polyester shell and lining, this jacket is built to last and endure the elements. Available in 5 attractive colors, you can choose the one that suits your style and preference. Versatile and functional, the OmniHeat Men\'s Solid Hooded Puffer Jacket is suitable for various occasions, whether you\'re heading to work, going for a casual outing, or attending an outdoor event. Experience the perfect blend of style, comfort, and functionality with OmniHeat Men\'s Solid Hooded Puffer Jacket. Elevate your winter wardrobe and stay snug while embracing the outdoors. Beat the cold in style and make a statement with this essential piece.ription 9',
                    'meta-description' => 'meta description',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Meta Title',
                    'name'             => 'OmniHeat Men\'s Solid Hooded Puffer Jacket-Blue-Yellow-L',
                    'sort-description' => 'Stay warm and stylish with our OmniHeat Men\'s Solid Hooded Puffer Jacket. This jacket is designed to provide ultimate warmth and features insert pockets for added convenience. The insulated material ensures you stay cozy in cold weather. Available in 5 attractive colors, making it a versatile choice for various occasions.',
                ],

                '10' => [
                    'description'      => 'Introducing the OmniHeat Men\'s Solid Hooded Puffer Jacket, your go-to solution for staying warm and fashionable during colder seasons. This jacket is crafted with durability and warmth in mind, ensuring it becomes your trusted companion. The hooded design not only adds a touch of style but also provides additional warmth, shielding you from cold winds and weather. The full sleeves offer complete coverage, ensuring you stay cozy from shoulder to wrist. Equipped with insert pockets, this puffer jacket provides convenience for carrying your essentials or keeping your hands warm. The insulated synthetic filling offers enhanced warmth, making it ideal for battling chilly days and nights. Made from a durable polyester shell and lining, this jacket is built to last and endure the elements. Available in 5 attractive colors, you can choose the one that suits your style and preference. Versatile and functional, the OmniHeat Men\'s Solid Hooded Puffer Jacket is suitable for various occasions, whether you\'re heading to work, going for a casual outing, or attending an outdoor event. Experience the perfect blend of style, comfort, and functionality with OmniHeat Men\'s Solid Hooded Puffer Jacket. Elevate your winter wardrobe and stay snug while embracing the outdoors. Beat the cold in style and make a statement with this essential piece.',
                    'meta-description' => 'meta description',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Meta Title',
                    'name'             => 'OmniHeat Men\'s Solid Hooded Puffer Jacket-Blue-Green-M',
                    'sort-description' => 'Stay warm and stylish with our OmniHeat Men\'s Solid Hooded Puffer Jacket. This jacket is designed to provide ultimate warmth and features insert pockets for added convenience. The insulated material ensures you stay cozy in cold weather. Available in 5 attractive colors, making it a versatile choice for various occasions.',
                ],

                '11' => [
                    'description'      => 'Introducing the OmniHeat Men\'s Solid Hooded Puffer Jacket, your go-to solution for staying warm and fashionable during colder seasons. This jacket is crafted with durability and warmth in mind, ensuring it becomes your trusted companion. The hooded design not only adds a touch of style but also provides additional warmth, shielding you from cold winds and weather. The full sleeves offer complete coverage, ensuring you stay cozy from shoulder to wrist. Equipped with insert pockets, this puffer jacket provides convenience for carrying your essentials or keeping your hands warm. The insulated synthetic filling offers enhanced warmth, making it ideal for battling chilly days and nights. Made from a durable polyester shell and lining, this jacket is built to last and endure the elements. Available in 5 attractive colors, you can choose the one that suits your style and preference. Versatile and functional, the OmniHeat Men\'s Solid Hooded Puffer Jacket is suitable for various occasions, whether you\'re heading to work, going for a casual outing, or attending an outdoor event. Experience the perfect blend of style, comfort, and functionality with OmniHeat Men\'s Solid Hooded Puffer Jacket. Elevate your winter wardrobe and stay snug while embracing the outdoors. Beat the cold in style and make a statement with this essential piece.',
                    'meta-description' => 'meta description',
                    'meta-keywords'    => 'meta1, meta2, meta3',
                    'meta-title'       => 'Meta Title',
                    'name'             => 'OmniHeat Men\'s Solid Hooded Puffer Jacket-Blue-Green-L',
                    'sort-description' => 'Stay warm and stylish with our OmniHeat Men\'s Solid Hooded Puffer Jacket. This jacket is designed to provide ultimate warmth and features insert pockets for added convenience. The insulated material ensures you stay cozy in cold weather. Available in 5 attractive colors, making it a versatile choice for various occasions.',
                ],
            ],

            'product-bundle-option-translations' => [
                '1' => [
                    'label' => 'Bundle Option 1',
                ],

                '2' => [
                    'label' => 'Bundle Option 1',
                ],

                '3' => [
                    'label' => 'Bundle Option 2',
                ],

                '4' => [
                    'label' => 'Bundle Option 2',
                ],
            ],
        ],
    ],

    'installer' => [
        'index' => [
            'create-administrator' => [
                'admin'            => 'Администратор',
                'bagisto'          => 'Bagisto',
                'confirm-password' => 'Потвърдете парола',
                'email'            => 'Имейл',
                'email-address'    => 'admin@example.com',
                'password'         => 'Парола',
                'title'            => 'Създай Администратор',
            ],

            'environment-configuration' => [
                'algerian-dinar'              => 'Алжирски динар (DZD)',
                'allowed-currencies'          => 'Разрешени валути',
                'allowed-locales'             => 'Allowed Locales',
                'application-name'            => 'Име на приложението',
                'argentine-peso'              => 'Аржентинско песо (ARS)',
                'australian-dollar'           => 'Австралийски долар (AUD)',
                'bagisto'                     => 'Bagisto',
                'bangladeshi-taka'            => 'Бангладешка така (BDT)',
                'bahraini-dinar'              => 'Бахрейнски динар (BHD)',
                'brazilian-real'              => 'Бразилски реал (BRL)',
                'british-pound-sterling'      => 'Британски лири стерлинги (GBP)',
                'bulgarian-lev'               => 'Български лев (BGN)',
                'canadian-dollar'             => 'Канадски долар (CAD)',
                'cfa-franc-bceao'             => 'CFA Франк BCEAO (XOF)',
                'cfa-franc-beac'              => 'CFA Франк BEAC (XAF)',
                'chilean-peso'                => 'Чилийско песо (CLP)',
                'chinese-yuan'                => 'Китайски юан (CNY)',
                'colombian-peso'              => 'Колумбийско песо (COP)',
                'czech-koruna'                => 'Чешка крона (CZK)',
                'danish-krone'                => 'Датска крона (DKK)',
                'database-connection'         => 'Връзка с база данни',
                'database-hostname'           => 'Хост на база данни',
                'database-name'               => 'Име на база данни',
                'database-password'           => 'Парола на база данни',
                'database-port'               => 'Порт на база данни',
                'database-prefix'             => 'Префикс на база данни',
                'database-username'           => 'Потребителско име на база данни',
                'default-currency'            => 'Валута по подразбиране',
                'default-locale'              => 'Език по подразбиране',
                'default-timezone'            => 'Часова зона по подразбиране',
                'default-url'                 => 'URL адрес по подразбиране',
                'default-url-link'            => 'https://localhost',
                'egyptian-pound'              => 'Египетска лира (EGP)',
                'euro'                        => 'Евро (EUR)',
                'fijian-dollar'               => 'Fijian Dollar (FJD)',
                'hong-kong-dollar'            => 'Hong Kong Dollar (HKD)',
                'hungarian-forint'            => 'Hungarian Forint (HUF)',
                'indian-rupee'                => 'Indian Rupee (INR)',
                'indonesian-rupiah'           => 'Indonesian Rupiah (IDR)',
                'israeli-new-shekel'          => 'Israeli New Shekel (ILS)',
                'japanese-yen'                => 'Japanese Yen (JPY)',
                'jordanian-dinar'             => 'Jordanian Dinar (JOD)',
                'kazakhstani-tenge'           => 'Kazakhstani Tenge (KZT)',
                'kuwaiti-dinar'               => 'Kuwaiti Dinar (KWD)',
                'lebanese-pound'              => 'Lebanese Pound (LBP)',
                'libyan-dinar'                => 'Libyan Dinar (LYD)',
                'malaysian-ringgit'           => 'Malaysian Ringgit (MYR)',
                'mauritian-rupee'             => 'Mauritian Rupee (MUR)',
                'mexican-peso'                => 'Mexican Peso (MXN)',
                'moroccan-dirham'             => 'Moroccan Dirham (MAD)',
                'mysql'                       => 'Mysql',
                'nepalese-rupee'              => 'Nepalese Rupee (NPR)',
                'new-taiwan-dollar'           => 'New Taiwan Dollar (TWD)',
                'new-zealand-dollar'          => 'New Zealand Dollar (NZD)',
                'nigerian-naira'              => 'Nigerian Naira (NGN)',
                'norwegian-krone'             => 'Norwegian Krone (NOK)',
                'omani-rial'                  => 'Omani Rial (OMR)',
                'pakistani-rupee'             => 'Pakistani Rupee (PKR)',
                'panamanian-balboa'           => 'Panamanian Balboa (PAB)',
                'paraguayan-guarani'          => 'Paraguayan Guarani (PYG)',
                'peruvian-nuevo-sol'          => 'Peruvian Nuevo Sol (PEN)',
                'pgsql'                       => 'pgSQL',
                'philippine-peso'             => 'Philippine Peso (PHP)',
                'polish-zloty'                => 'Polish Zloty (PLN)',
                'qatari-rial'                 => 'Qatari Rial (QAR)',
                'romanian-leu'                => 'Romanian Leu (RON)',
                'russian-ruble'               => 'Russian Ruble (RUB)',
                'saudi-riyal'                 => 'Saudi Riyal (SAR)',
                'select-timezone'             => 'Select Timezone',
                'singapore-dollar'            => 'Singapore Dollar (SGD)',
                'south-african-rand'          => 'South African Rand (ZAR)',
                'south-korean-won'            => 'South Korean Won (KRW)',
                'sqlsrv'                      => 'SQLSRV',
                'sri-lankan-rupee'            => 'Sri Lankan Rupee (LKR)',
                'swedish-krona'               => 'Swedish Krona (SEK)',
                'swiss-franc'                 => 'Swiss Franc (CHF)',
                'thai-baht'                   => 'Thai Baht (THB)',
                'title'                       => 'Конфигурация на магазина',
                'tunisian-dinar'              => 'Tunisian Dinar (TND)',
                'turkish-lira'                => 'Turkish Lira (TRY)',
                'ukrainian-hryvnia'           => 'Ukrainian Hryvnia (UAH)',
                'united-arab-emirates-dirham' => 'United Arab Emirates Dirham (AED)',
                'united-states-dollar'        => 'United States Dollar (USD)',
                'uzbekistani-som'             => 'Uzbekistani Som (UZS)',
                'venezuelan-bolívar'          => 'Venezuelan Bolívar (VEF)',
                'vietnamese-dong'             => 'Vietnamese Dong (VND)',
                'warning-message'             => 'Beware! The settings for your default system language and default currency are permanent and cannot be changed once set.',
                'zambian-kwacha'              => 'Zambian Kwacha (ZMW)',
            ],

            'sample-products' => [
                'download-sample' => 'download-sample',
                'no'              => 'Не',
                'sample-products' => 'Примерни продукти',
                'title'           => 'Примерни продукти',
                'yes'             => 'Да',
            ],

            'installation-processing' => [
                'bagisto'      => 'Installation Bagisto',
                'bagisto-info' => 'Creating the database tables, this can take a few moments',
                'title'        => 'Installation',
            ],

            'installation-completed' => [
                'admin-panel'                => 'Администраторски панел',
                'bagisto-forums'             => 'Bagisto Форум',
                'customer-panel'             => 'Клиентски панел',
                'explore-bagisto-extensions' => 'Разгледайте разширенията на Bagisto',
                'title'                      => 'Инсталацията е завършена',
                'title-info'                 => 'Bagisto is Successfully installed on your system.',
            ],

            'ready-for-installation' => [
                'create-databsae-table'   => 'Създай нова таблица в базата данни"',
                'install'                 => 'Инсталация',
                'install-info'            => 'Bagisto за инсталация',
                'install-info-button'     => 'Кликнете върху бутона по-долу, за да',
                'populate-database-table' => 'Попълнете таблиците в базата данни',
                'start-installation'      => 'Започнете инсталацията',
                'title'                   => 'Готов за инсталация',
            ],

            'start' => [
                'locale'        => 'Език',
                'main'          => 'Начало',
                'select-locale' => 'Избери език',
                'title'         => 'Вашата инсталация на Bagisto',
                'welcome-title' => 'Добре дошли в Bagisto!',
            ],

            'server-requirements' => [
                'calendar'    => 'Calendar',
                'ctype'       => 'cType',
                'curl'        => 'cURL',
                'dom'         => 'dom',
                'fileinfo'    => 'fileInfo',
                'filter'      => 'Filter',
                'gd'          => 'GD',
                'hash'        => 'Hash',
                'intl'        => 'intl',
                'json'        => 'JSON',
                'mbstring'    => 'mbstring',
                'openssl'     => 'openssl',
                'pcre'        => 'pcre',
                'pdo'         => 'pdo',
                'php'         => 'PHP',
                'php-version' => '8.1 или по-висока',
                'session'     => 'session',
                'title'       => 'Системни изисквания',
                'tokenizer'   => 'tokenizer',
                'xml'         => 'XML',
            ],

            'arabic'                   => 'Арабски',
            'back'                     => 'Назад',
            'bagisto'                  => 'Bagisto',
            'bagisto-info'             => 'е Обществен проект от',
            'bagisto-logo'             => 'Bagisto лого',
            'bengali'                  => 'Bengali',
            'chinese'                  => 'Chinese',
            'continue'                 => 'Продължи',
            'dutch'                    => 'Dutch',
            'english'                  => 'English',
            'french'                   => 'French',
            'german'                   => 'German',
            'hebrew'                   => 'Hebrew',
            'hindi'                    => 'Hindi',
            'installation-description' => 'Инсталацията на Bagisto обикновено включва няколко стъпки. Ето общ преглед на процеса на инсталация:',
            'installation-info'        => 'Радваме се да Ви видим тук!',
            'installation-title'       => 'Добре дошли в инсталацията',
            'italian'                  => 'Italian',
            'japanese'                 => 'Japanese',
            'persian'                  => 'Persian',
            'polish'                   => 'Polish',
            'portuguese'               => 'Brazilian Portuguese',
            'russian'                  => 'Russian',
            'sinhala'                  => 'Sinhala',
            'spanish'                  => 'Spanish',
            'title'                    => 'Bagisto Installer',
            'turkish'                  => 'Turkish',
            'ukrainian'                => 'Ukrainian',
            'webkul'                   => 'Webkul',
        ],
    ],
];
