<?php

return [
    'seeders' => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'پیش‌فرض',
            ],

            'attribute-groups' => [
                'description'      => 'توضیحات',
                'general'          => 'عمومی',
                'inventories'      => 'موجودی‌ها',
                'meta-description' => 'توضیحات متا',
                'price'            => 'قیمت',
                'settings'         => 'تنظیمات',
                'shipping'         => 'حمل و نقل',
            ],

            'attributes' => [
                'brand'                => 'برند',
                'color'                => 'رنگ',
                'cost'                 => 'هزینه',
                'description'          => 'توضیحات',
                'featured'             => 'ویژگی‌دار',
                'guest-checkout'       => 'خرید مهمان',
                'height'               => 'ارتفاع',
                'length'               => 'طول',
                'manage-stock'         => 'مدیریت موجودی',
                'meta-description'     => 'توضیحات متا',
                'meta-keywords'        => 'کلمات کلیدی متا',
                'meta-title'           => 'عنوان متا',
                'name'                 => 'نام',
                'new'                  => 'جدید',
                'price'                => 'قیمت',
                'product-number'       => 'شماره محصول',
                'short-description'    => 'توضیح کوتاه',
                'size'                 => 'اندازه',
                'sku'                  => 'SKU',
                'special-price'        => 'قیمت ویژه',
                'special-price-from'   => 'قیمت ویژه از',
                'special-price-to'     => 'قیمت ویژه تا',
                'status'               => 'وضعیت',
                'tax-category'         => 'دسته مالیاتی',
                'url-key'              => 'کلید URL',
                'visible-individually' => 'نمایش انفرادی',
                'weight'               => 'وزن',
                'width'                => 'عرض',
            ],

            'attribute-options' => [
                'black'  => 'سیاه',
                'green'  => 'سبز',
                'l'      => 'L',
                'm'      => 'M',
                'red'    => 'قرمز',
                's'      => 'S',
                'white'  => 'سفید',
                'xl'     => 'XL',
                'yellow' => 'زرد',
            ],
        ],

        'category' => [
            'categories' => [
                'description' => 'توضیح دسته اصلی',
                'name'        => 'اصلی',
            ],
        ],

        'cms' => [
            'pages' => [
                'about-us' => [
                    'content' => 'محتوای درباره ما',
                    'title'   => 'درباره ما',
                ],

                'contact-us' => [
                    'content' => 'محتوای تماس با ما',
                    'title'   => 'تماس با ما',
                ],

                'customer-service' => [
                    'content' => 'محتوای خدمات مشتری',
                    'title'   => 'خدمات مشتری',
                ],

                'payment-policy' => [
                    'content' => 'محتوای سیاست پرداخت',
                    'title'   => 'سیاست پرداخت',
                ],

                'privacy-policy' => [
                    'content' => 'محتوای سیاست حفظ حریم خصوصی',
                    'title'   => 'سیاست حفظ حریم خصوصی',
                ],

                'refund-policy' => [
                    'content' => 'محتوای سیاست بازپرداخت',
                    'title'   => 'سیاست بازپرداخت',
                ],

                'return-policy' => [
                    'content' => 'محتوای سیاست بازگشت',
                    'title'   => 'سیاست بازگشت',
                ],

                'shipping-policy' => [
                    'content' => 'محتوای سیاست حمل و نقل',
                    'title'   => 'سیاست حمل و نقل',
                ],

                'terms-conditions' => [
                    'content' => 'محتوای شرایط و مقررات',
                    'title'   => 'شرایط و مقررات',
                ],

                'terms-of-use' => [
                    'content' => 'محتوای شرایط استفاده',
                    'title'   => 'شرایط استفاده',
                ],

                'whats-new' => [
                    'content' => 'محتوای جدید چیست',
                    'title'   => 'جدید چیست',
                ],
            ],
        ],

        'core' => [
            'channels' => [
                'name'             => 'پیش‌فرض',
                'meta-title'       => 'فروشگاه نمونه',
                'meta-keywords'    => 'فروشگاه نمونه کلمات کلیدی متا',
                'meta-description' => 'فروشگاه نمونه توضیحات متا',
            ],

            'currencies' => [
                'AED' => 'درهم امارات متحده',
                'ARS' => 'پزوی آرژانتین',
                'AUD' => 'دلار استرالیا',
                'BDT' => 'تاکای بنگلادش',
                'BRL' => 'رئال برزیل',
                'CAD' => 'دلار کانادا',
                'CHF' => 'فرانک سوئیس',
                'CLP' => 'پزوی شیلی',
                'CNY' => 'یوان چینی',
                'COP' => 'پزوی کلمبیایی',
                'CZK' => 'کرونای چک',
                'DKK' => 'کرون دانمارک',
                'DZD' => 'دینار الجزایری',
                'EGP' => 'پوند مصری',
                'EUR' => 'یورو',
                'FJD' => 'دلار فیجی',
                'GBP' => 'پوند بریتانیا',
                'HKD' => 'دلار هنگ کنگ',
                'HUF' => 'فورینت مجارستان',
                'IDR' => 'روپیه اندونزی',
                'ILS' => 'شقل جدید اسرائیل',
                'INR' => 'روپیه هندی',
                'JOD' => 'دینار اردنی',
                'JPY' => 'ین ژاپنی',
                'KRW' => 'وون کره جنوبی',
                'KWD' => 'دینار کویتی',
                'KZT' => 'تنگه قزاقستان',
                'LBP' => 'لیره لبنانی',
                'LKR' => 'روپیه سریلانکایی',
                'LYD' => 'دینار لیبی',
                'MAD' => 'درهم مراکش',
                'MUR' => 'روپیه موریس',
                'MXN' => 'پزوی مکزیک',
                'MYR' => 'رینگیت مالزی',
                'NGN' => 'نایرای نیجریه',
                'NOK' => 'کرون نروژ',
                'NPR' => 'روپیه نپالی',
                'NZD' => 'دلار نیوزیلند',
                'OMR' => 'ریال عمانی',
                'PAB' => 'بالبوای پاناما',
                'PEN' => 'سول پرو',
                'PHP' => 'پزوی فیلیپینی',
                'PKR' => 'روپیه پاکستانی',
                'PLN' => 'زوتی لهستانی',
                'PYG' => 'گوارانی پاراگوئه',
                'QAR' => 'ریال قطر',
                'RON' => 'لئوی رومانیایی',
                'RUB' => 'روبل روسی',
                'SAR' => 'ریال سعودی',
                'SEK' => 'کرون سوئد',
                'SGD' => 'دلار سنگاپور',
                'THB' => 'بات تایلندی',
                'TND' => 'دینار تونسی',
                'TRY' => 'لیره ترکیه',
                'TWD' => 'دلار تایوان جدید',
                'UAH' => 'هریونیای اوکراین',
                'USD' => 'دلار امریکا',
                'UZS' => 'سوم ازبکستان',
                'VEF' => 'بولیوار ونزوئلا',
                'VND' => 'دانگ ویتنامی',
                'XAF' => 'فرانک CFA BEAC',
                'XOF' => 'فرانک CFA BCEAO',
                'ZAR' => 'راند آفریقای جنوبی',
                'ZMW' => 'کواچای زامبیا',
            ],

            'locales' => [
                'ar'    => 'عربی',
                'bn'    => 'بنگالی',
                'de'    => 'آلمانی',
                'en'    => 'انگلیسی',
                'es'    => 'اسپانیایی',
                'fa'    => 'فارسی',
                'fr'    => 'فرانسوی',
                'he'    => 'عبری',
                'hi_IN' => 'هندی',
                'it'    => 'ایتالیایی',
                'ja'    => 'ژاپنی',
                'nl'    => 'هلندی',
                'pl'    => 'لهستانی',
                'pt_BR' => 'پرتغالی برزیل',
                'ru'    => 'روسی',
                'sin'   => 'سینهالی',
                'tr'    => 'ترکی',
                'uk'    => 'اوکراینی',
                'zh_CN' => 'چینی',
            ],
        ],

        'customer' => [
            'customer-groups' => [
                'general'   => 'عمومی',
                'guest'     => 'مهمان',
                'wholesale' => 'عمده',
            ],
        ],

        'inventory' => [
            'inventory-sources' => [
                'name' => 'پیش‌فرض',
            ],
        ],

        'shop' => [
            'theme-customizations' => [
                'all-products' => [
                    'name' => 'همه محصولات',

                    'options' => [
                        'title' => 'همه محصولات',
                    ],
                ],

                'bold-collections' => [
                    'content' => [
                        'btn-title'   => 'مشاهده مجموعه‌ها',
                        'description' => 'معرفی مجموعه‌های جسور جدید ما! سبک خود را با طراحی‌های جسور و اظهارات جذاب بالا ببرید. الگوها و رنگ‌های جسوری را کشف کنید که لباس‌درمانی‌تان را بازتعریف می‌کنند. برای پذیرش بی‌نظیری آماده شوید!',
                        'title'       => 'برای مجموعه‌های جسور جدیدمان آماده شوید!',
                    ],

                    'name' => 'مجموعه‌های جسور',
                ],

                'categories-collections' => [
                    'name' => 'مجموعه‌های دسته‌بندی',
                ],

                'footer-links'           => [
                    'name' => 'پیوندهای فوتر',

                    'options' => [
                        'about-us'         => 'درباره ما',
                        'contact-us'       => 'تماس با ما',
                        'customer-service' => 'خدمات مشتریان',
                        'payment-policy'   => 'سیاست پرداخت',
                        'privacy-policy'   => 'سیاست حریم خصوصی',
                        'refund-policy'    => 'سیاست بازپرداخت',
                        'return-policy'    => 'سیاست بازگشت',
                        'shipping-policy'  => 'سیاست حمل و نقل',
                        'terms-conditions' => 'شرایط و مقررات',
                        'terms-of-use'     => 'شرایط استفاده',
                        'whats-new'        => 'چه خبر است',
                    ],
                ],

                'featured-collections' => [
                    'name' => 'مجموعه‌های ویژه',

                    'options' => [
                        'title' => 'محصولات ویژه',
                    ],
                ],

                'game-container' => [
                    'name' => 'ظروف بازی',

                    'content' => [
                        'sub-title-1' => 'مجموعه‌های ما',
                        'sub-title-2' => 'مجموعه‌های ما',
                        'title'       => 'با افزودن‌های جدیدمان بازی کنید!',
                    ],
                ],

                'image-carousel' => [
                    'name' => 'اسلایدر تصاویر',

                    'sliders' => [
                        'title' => 'آماده‌اید برای مجموعه جدید',
                    ],
                ],

                'new-products' => [
                    'name' => 'محصولات جدید',

                    'options' => [
                        'title' => 'محصولات جدید',
                    ],
                ],

                'offer-information' => [
                    'content' => [
                        'title' => 'تا 40٪ تخفیف در سفارش اولتان، همین الان سفارش دهید',
                    ],

                    'name' => 'اطلاعات پیشنهاد',
                ],

                'services-content' => [
                    'description' => [
                        'emi-available-info'   => 'EMI بدون هزینه در تمام کارت‌های اعتباری اصلی در دسترس است',
                        'free-shipping-info'   => 'از ارسال رایگان در تمام سفارش‌ها لذت ببرید',
                        'product-replace-info' => 'تعویض آسان محصول در دسترس است!',
                        'time-support-info'    => 'پشتیبانی اختصاصی 24/7 از طریق چت و ایمیل',
                    ],

                    'name' => 'محتوای خدمات',

                    'title' => [
                        'emi-available'   => 'EMI در دسترس است',
                        'free-shipping'   => 'ارسال رایگان',
                        'product-replace' => 'تعویض محصول',
                        'time-support'    => 'پشتیبانی 24/7',
                    ],
                ],

                'top-collections' => [
                    'content' => [
                        'sub-title-1' => 'مجموعه‌های ما',
                        'sub-title-2' => 'مجموعه‌های ما',
                        'sub-title-3' => 'مجموعه‌های ما',
                        'sub-title-4' => 'مجموعه‌های ما',
                        'sub-title-5' => 'مجموعه‌های ما',
                        'sub-title-6' => 'مجموعه‌های ما',
                        'title'       => 'با افزودن‌های جدیدمان بازی کنید!',
                    ],

                    'name' => 'مجموعه‌های برتر',
                ],
            ],
        ],

        'user' => [
            'roles' => [
                'description' => 'این نقش دسترسی‌هایی را برای کاربران فراهم می‌کند',
                'name'        => 'مدیر سیستم',
            ],

            'users' => [
                'name' => 'مثال',
            ],
        ],

        'sample-products' => [
            'category-translation' => [
                '20' => [
                    'description'      => 'Men Category Description',
                    'meta-description' => 'Men Category Meta Description',
                    'meta-keywords'    => 'Men Category Meta Keywords',
                    'meta-title'       => 'Men Category Meta Title',
                ],

                '21' => [
                    'description'      => 'Winter Wear Category Description',
                    'meta-description' => 'Winter Wear Category Meta Description',
                    'meta-keywords'    => 'Winter Wear Category Meta Keywords',
                    'meta-title'       => 'Winter Wear Category Meta Title',
                ],
            ],

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
                    'description'      => 'کلاه تمام نخی Arctic Cozy شما را در ماه های سرد سرد، راحت و شیک نگه می دارد. این کلاه از مخلوط نرم و مقاومی از نخ اکریلیک ساخته شده است که طراحی شده است تا یک تناسب نرم و محکم را فراهم کند. طراحی کلاسیک آن آن را مناسب برای هر دو جنسیت می کند و یک لوازم جانبی چند منظوره است که به سبک های مختلف تکمیل می کند. بگویید که آیا شما برای یک روز غیر رسمی در شهر یا در برابر فضای باز برنامه ریزی می کنید، این کلاه یک لمسه راحتی و گرما به لباس شما اضافه می کند. مواد نرم و تنفس پذیر اطمینان می دهد که شما بدون قربانی کردن سبک خود راحت بمانید. کلاه تمام نخی Arctic Cozy نه فقط یک لوازم جانبی است؛ بلکه یک بیانیه از مد زمستان است. سادگی آن آسانی همراهی با لباس های مختلف را فراهم می کند و آن را به یک قطعه کلیدی در گاردرابه زمستان تبدیل می کند. ایده آل برای هدیه دادن یا به عنوان یک لذت برای خودتان، این کلاه یک اضافه کردن متفکرانه به هر گونه مجموعه زمستانی است. این یک لوازم جانبی چند منظوره است که فراتر از کارکردی است و یک لمسه گرما و سبک به نگاه شما اضافه می کند. با کلاه تمام نخی Arctic Cozy همراه باشید. بگویید که آیا شما یک روز غیر رسمی را لذت می برید یا با عناصر روبرو می شوید، اجازه دهید این کلاه همراه شما برای راحتی و سبک باشد. با کلاه تمام نخی Arctic Cozy ، گرما با زمان بی نهایت مد همراه است.',
                    'meta-description' => 'توضیحات متا',
                    'meta-keywords'    => 'متا1، متا2، متا3',
                    'meta-title'       => 'عنوان متا',
                    'name'             => 'کلاه تمام نخی Arctic Cozy بی جنسیت',
                    'sort-description' => 'با کلاه تمام نخی Arctic Cozy روزهای سرد را با سبکی دریابید. این کلاه کلاسیک از مخلوطی نرم و مقاوم از اکریلیک ساخته شده است و گرما و چند منظوره است. مناسب برای هر دو جنسیت، این کلاه لوازم جانبی ایده آلی برای لباس روزمره یا فضای باز است. گاردرابه زمستانی خود را بالا ببرید یا کسی را که ویژه است با این کلاه ضروری.',
                ],

                '2' => [
                    'description'      => 'شال زمستانی Arctic Bliss بیش از یک لوازم جانبی هوای سرد است؛ این یک بیانیه از گرما، راحتی و سبک برای فصل زمستان است. این شال با مراقبت از مخلوطی لوکس از اکریلیک و پشم طراحی شده است تا شما را در دمای سردترین دماها گرم و محکم نگه دارد. بافت نرم و لطیف نه تنها عایق در برابر سرما است، بلکه یک لمسه لوکس به گاردرابه زمستانی شما اضافه می کند. طراحی شال Arctic Bliss آن را هم شیک و هم چند منظوره می کند، که آن را به یک اضافه کردن کامل به لباس های زمستانی مختلف می کند. بگویید که آیا شما برای یک مناسبت ویژه لباس می پوشید یا به لباس روزمره خود لایه ای شیک اضافه می کنید، این شال به سبک شما به طور طبیعی می پیوندد. طول بیش از حد شال گزینه های استایلینگ قابل تنظیم را ارائه می دهد. آن را برای افزایش گرما بپیچید، آن را برای یک نگاه غیر رسمی بر روی شانه ها بگذارید یا با گره های مختلف برای بیان سبک منحصر به فرد خود آزمایش کنید. این چند منظوره بودن آن را به یک لوازم جانبی ضروری برای فصل زمستان تبدیل می کند. به دنبال هدیه کامل هستید؟ شال زمستانی Arctic Bliss یک انتخاب ایده آل است. بگویید که آیا شما یک عزیز را متعجب می کنید یا خودتان را درمان می کنید، این شال یک هدیه بی زمان و عملی است که در طول ماه های زمستانی قدردانی می شود. با شال زمستانی Arctic Bliss ، زمستان را با گرما و سبک در هماهنگی کامل در برابر بگیرید. گاردرابه زمستانی خود را با این لوازم جانبی ضروری که نه تنها شما را گرم نگه می دارد بلکه یک لمسه از سفیتا به گاردرابه سردتان اضافه می کند بالا ببرید.',
                    'meta-description' => 'توضیحات متا',
                    'meta-keywords'    => 'متا1، متا2، متا3',
                    'meta-title'       => 'عنوان متا',
                    'name'             => 'شال زمستانی Arctic Bliss شیک',
                    'sort-description' => 'تجربه آغوش گرما و سبک با شال زمستانی Arctic Bliss ما. این شال از مخلوطی لوکس از اکریلیک و پشم ساخته شده است و برای گرم نگه داشتن شما در روزهای سردترین طراحی شده است. طراحی شیک و چند منظوره آن به همراه طول بیش از حد، گزینه های استایلینگ قابل تنظیم را ارائه می دهد. گاردرابه زمستانی خود را بالا ببرید یا کسی را که ویژه است با این لوازم جانبی ضروری زمستانی.',
                ],

                '3' => [
                    'description'      => 'معرفی دستکش های زمستانی Arctic Touchscreen - جایی که گرما، سبک و اتصال به هم می رسند تا تجربه زمستانی شما را بهبود دهند. این دستکش ها از اکریلیک با کیفیت بالا ساخته شده اند و برای ارائه گرما و مقاومت استثنایی طراحی شده اند. انگشتان قابل استفاده با صفحه نمایش امکان اتصال شما را بدون افشای دستان خود به سرما فراهم می کنند. تماس ها را پاسخ دهید، پیام ها را ارسال کنید و دستگاه های خود را به راحتی هدایت کنید، همه اینها در حالی که دستان خود را گرم نگه می دارید. بطور معمول، لاینینگ عایق اضافی راحتی را افزایش می دهد و این دستکش ها را به انتخاب شما برای مقابله با سرما تبدیل می کند. بگویید که آیا شما در حال رفت و آمد هستید، امور را انجام می دهید یا از فعالیت های خارجی لذت می برید، این دستکش ها گرما و حفاظت مورد نیاز شما را فراهم می کنند. مچ بند های الاستیکی تنظیمی راه حلی امن را فراهم می کنند و جلوگیری از جریان هوای سرد و نگه داشتن دستکش ها در محل در طول فعالیت های روزانه شما. طراحی شیک یک لمسه از جلوه به لباس زمستانی شما اضافه می کند، که این دستکش ها همانند عملکرد آنها مد است. ایده آل برای هدیه دادن یا به عنوان یک لذت برای خودتان، دستکش های زمستانی Arctic Touchscreen یک لوازم جانبی ضروری برای افراد مدرن هستند. با خداحافظی از عدم راحتی خارج کردن دستکش های خود برای استفاده از دستگاه های خود و پذیرش ترکیب بی درز گرما، سبک و اتصال. با دستکش های زمستانی Arctic Touchscreen ، به راحتی متصل شوید، گرم بمانید و با اعتماد به نفس در فصل زمستان سبک باشید.',
                    'meta-description' => 'توضیحات متا',
                    'meta-keywords'    => 'متا1، متا2، متا3',
                    'meta-title'       => 'عنوان متا',
                    'name'             => 'دستکش های زمستانی Arctic Touchscreen',
                    'sort-description' => 'با دستکش های زمستانی Arctic Touchscreen متصل و گرم بمانید. این دستکش ها نه تنها از اکریلیک با کیفیت برای گرما و مقاومت ساخته شده اند، بلکه دارای طراحی قابل استفاده با صفحه نمایش هستند. با لاینینگ عایق، مچ بند الاستیکی برای تنظیم مناسب و ظاهری شیک، این دستکش ها برای استفاده روزانه در شرایط سرد مناسب هستند.',
                ],

                '4' => [
                    'description'      => 'معرفی جوراب های مخلوط پشم Arctic Warmth - همراهی ضروری شما برای پاهای گرم و راحت در فصل سرد است. این جوراب ها از ترکیبی از پشم مرینو، آکریلیک، نایلون و اسپندکس با کیفیت بالا ساخته شده اند و طراحی شده اند تا گرمای بی نظیر و راحتی را فراهم کنند. مخلوط پشم باعث می شود پاهای شما حتی در سرماهای سردتر هم گرم بمانند و این جوراب ها انتخابی مناسب برای ماجراجویی های زمستانی یا به سادگی راحتی در خانه هستند. بافت نرم و دلپذیر این جوراب ها حس لوکسی را در برابر پوست شما ایجاد می کند. با وجود این جوراب ها دیگر نیازی به پاهای سرد نخواهید داشت و از گرمای لطیفی که این جوراب های مخلوط پشم ارائه می دهند، لذت خواهید برد. طراحی شده برای مقاومت، این جوراب ها دارای پاشنه و انگشتان تقویت شده هستند که مقاومت اضافی را به مناطقی با سایش بالا اضافه می کند. این اطمینان را به شما می دهد که جوراب های شما آزمون زمان را پشت سر می گذارند و راحتی و دلپذیری بلند مدت را فراهم می کنند. طبیعت تنفس پذیر این ماده از بیش از حد گرم شدن جلوگیری می کند و به پاهای شما اجازه می دهد طول روز راحت و خشک بمانند. بگویید به طوری که شما برای یک پیاده روی زمستانی به فضای باز می روید یا در خانه برای راحتی بی نظیر می مانید، این جوراب ها تعادل کاملی از گرما و تنفس را ارائه می دهند. چند منظوره و شیک، این جوراب های مخلوط پشم برای مواقع مختلف مناسب هستند. آنها را با بوت های مورد علاقه خود برای یک ظاهر زمستانی مد روز هماهنگ کنید یا آنها را در خانه برای راحتی بی نظیر بپوشید. با جوراب های مخلوط پشم Arctic Warmth، گاردراب زمستانی خود را ارتقا دهید و به راحتی وارد دنیایی از راحتی بشوید که تمام فصل طول می کشد.',
                    'meta-description' => 'توضیحات متا',
                    'meta-keywords'    => 'متا1، متا2، متا3',
                    'meta-title'       => 'عنوان متا',
                    'name'             => 'جوراب های مخلوط پشم Arctic Warmth',
                    'sort-description' => 'تجربه گرمای بی نظیر و راحتی جوراب های مخلوط پشم Arctic Warmth ما را تجربه کنید. این جوراب ها از ترکیبی از پشم مرینو، آکریلیک، نایلون و اسپندکس ساخته شده اند و برای هوای سرد مناسب هستند. با پاشنه و انگشتان تقویت شده برای مقاومت، این جوراب های چند منظوره و شیک برای مواقع مختلف مناسب هستند.',
                ],

                '5' => [
                    'description'      => 'معرفی بسته لوازم جانبی زمستانی Arctic Frost - راه حلی کامل برای گرم نگه داشتن، شیک بودن و اتصال داشتن در روزهای سرد زمستان است. این مجموعه به طور دقیق چهار لوازم جانبی زمستانی اساسی را به هم می آورد تا یک مجموعه هماهنگ ایجاد شود. شال لوکس که از ترکیبی از آکریلیک و پشم بافته شده است، نه تنها یک لایه گرما اضافی ایجاد می کند بلکه لمسی از شیکی به گاردراب زمستانی شما می بخشد. کلاه نخی نرم که با مراقبت ساخته شده است، قول می دهد شما را گرم نگه دارد و به ظاهر شما زیبایی می بخشد. اما اینجا تمام نمی شود - بسته ما همچنین شامل دستکش های قابل استفاده با صفحه نمایش لمسی است. بدون از دست دادن گرما، به راحتی دستگاه های خود را مدیریت کنید. بگویید به طوری که شما تماس ها را پاسخ می دهید، پیام ها را ارسال می کنید یا لحظات زمستانی را در گوشی هوشمند خود ثبت می کنید، این دستکش ها راحتی را بدون کم کردن از شیکی به شما ارائه می دهند. بافت نرم و دلپذیر این جوراب ها حس لوکسی را در برابر پوست شما ایجاد می کند. با وجود این جوراب ها دیگر نیازی به پاهای سرد نخواهید داشت و از گرمای لطیفی که این جوراب های مخلوط پشم ارائه می دهند، لذت خواهید برد. بسته لوازم جانبی زمستانی Arctic Frost فقط در مورد عملکرد نیست؛ این یک بیانیه از مد زمستانی است. هر قطعه نه تنها برای محافظت شما از سرما طراحی شده است بلکه برای ارتقای سبک شما در فصل سرد نیز طراحی شده است. مواد انتخاب شده برای این بسته همزمان برای مقاومت و راحتی اولویت قائل شده اند، تضمین می کنند که شما می توانید با سبک زمستانی خود از طبیعت زمستانی لذت ببرید. بگویید به طوری که شما خود را مدل کنید یا به دنبال هدیه ای کامل هستید، بسته لوازم جانبی زمستانی Arctic Frost یک انتخاب چند منظوره است. در فصل تعطیلات کسی را خوشحال کنید یا گاردراب زمستانی خود را با این مجموعه شیک و کاربردی ارتقا دهید. با اطمینان از یخ زدگی، بدانید که شما لوازم جانبی کامل برای گرم نگه داشتن و شیک بودن دارید.',
                    'meta-description' => 'توضیحات متا',
                    'meta-keywords'    => 'متا1، متا2، متا3',
                    'meta-title'       => 'عنوان متا',
                    'name'             => 'بسته لوازم جانبی زمستانی Arctic Frost',
                    'sort-description' => 'با بسته لوازم جانبی زمستانی Arctic Frost ما با سرمای زمستانی همراه شوید. این مجموعه شامل یک شال لوکس، یک کلاه نخی، دستکش های قابل استفاده با صفحه نمایش لمسی و جوراب های مخلوط پشم است. شیک و کاربردی، این مجموعه از مواد با کیفیت بالا ساخته شده است و همزمان مقاومت و راحتی را تضمین می کند. گاردراب زمستانی خود را ارتقا دهید یا کسی را در فصل تعطیلات خوشحال کنید با این مجموعه شیک و کاربردی. با اطمینان از یخ زدگی، بدانید که شما لوازم جانبی کامل برای گرم نگه داشتن و شیک بودن دارید.',
                ],

                '6' => [
                    'description'      => 'معرفی بسته لوازم جانبی زمستانی Arctic Frost - راه حلی کامل برای گرم نگه داشتن، شیک بودن و اتصال داشتن در روزهای سرد زمستان است. این مجموعه به طور دقیق چهار لوازم جانبی زمستانی اساسی را به هم می آورد تا یک مجموعه هماهنگ ایجاد شود. شال لوکس که از ترکیبی از آکریلیک و پشم بافته شده است، نه تنها یک لایه گرما اضافی ایجاد می کند بلکه لمسی از شیکی به گاردراب زمستانی شما می بخشد. کلاه نخی نرم که با مراقبت ساخته شده است، قول می دهد شما را گرم نگه دارد و به ظاهر شما زیبایی می بخشد. اما اینجا تمام نمی شود - بسته ما همچنین شامل دستکش های قابل استفاده با صفحه نمایش لمسی است. بدون از دست دادن گرما، به راحتی دستگاه های خود را مدیریت کنید. بگویید به طوری که شما تماس ها را پاسخ می دهید، پیام ها را ارسال می کنید یا لحظات زمستانی را در گوشی هوشمند خود ثبت می کنید، این دستکش ها راحتی را بدون کم کردن از شیکی به شما ارائه می دهند. بافت نرم و دلپذیر این جوراب ها حس لوکسی را در برابر پوست شما ایجاد می کند. با وجود این جوراب ها دیگر نیازی به پاهای سرد نخواهید داشت و از گرمای لطیفی که این جوراب های مخلوط پشم ارائه می دهند، لذت خواهید برد. بسته لوازم جانبی زمستانی Arctic Frost فقط در مورد عملکرد نیست؛ این یک بیانیه از مد زمستانی است. هر قطعه نه تنها برای محافظت شما از سرما طراحی شده است بلکه برای ارتقای سبک شما در فصل سرد نیز طراحی شده است. مواد انتخاب شده برای این بسته همزمان برای مقاومت و راحتی اولویت قائل شده اند، تضمین می کنند که شما می توانید با سبک زمستانی خود از طبیعت زمستانی لذت ببرید. بگویید به طوری که شما خود را مدل کنید یا به دنبال هدیه ای کامل هستید، بسته لوازم جانبی زمستانی Arctic Frost یک انتخاب چند منظوره است. در فصل تعطیلات کسی را خوشحال کنید یا گاردراب زمستانی خود را با این مجموعه شیک و کاربردی ارتقا دهید. با اطمینان از یخ زدگی، بدانید که شما لوازم جانبی کامل برای گرم نگه داشتن و شیک بودن دارید.',
                    'meta-description' => 'توضیحات متا',
                    'meta-keywords'    => 'متا1، متا2، متا3',
                    'meta-title'       => 'عنوان متا',
                    'name'             => 'بسته لوازم جانبی زمستانی Arctic Frost',
                    'sort-description' => 'با بسته لوازم جانبی زمستانی Arctic Frost ما با سرمای زمستانی همراه شوید. این مجموعه شامل یک شال لوکس، یک کلاه نخی، دستکش های قابل استفاده با صفحه نمایش لمسی و جوراب های مخلوط پشم است. شیک و کاربردی، این مجموعه از مواد با کیفیت بالا ساخته شده است و همزمان مقاومت و راحتی را تضمین می کند. گاردراب زمستانی خود را ارتقا دهید یا کسی را در فصل تعطیلات خوشحال کنید با این مجموعه شیک و کاربردی. با اطمینان از یخ زدگی، بدانید که شما لوازم جانبی کامل برای گرم نگه داشتن و شیک بودن دارید.',
                ],

                '7' => [
                    'description'      => 'معرفی کت پوشاک OmniHeat Men\'s Solid Hooded Puffer Jacket، راه حلی برای گرم نگه داشتن و شیک بودن در فصل های سردتر است. این کت پوشاک با دوام و گرما در ذهن طراحی شده است، تضمین می کند که به همراه شما باشد. طراحی هود نه تنها یک لمسه از سبک را اضافه می کند، بلکه گرمای اضافی را فراهم می کند و شما را از بادها و آب و هوای سرد محافظت می کند. آستین های کامل پوشش کامل را تضمین می کنند و از شانه تا مچ دست شما را گرم نگه می دارند. با جیب های درج کننده، این کت پوشاک پافر امکاناتی را برای حمل و نقل لوازم ضروری یا گرم نگه داشتن دستان شما فراهم می کند. پر کننده مصنوعی عایق شده گرمای بهبود یافته ارائه می دهد و برای مقابله با روزها و شب های سرد مناسب است. این کت پوشاک از پلی استر مقاوم و زیربنایی ساخته شده است و برای مقاومت در برابر عوامل جوی ساخته شده است. با 5 رنگ جذاب در دسترس، می توانید رنگی را که به سبک و ترجیح شما می خورد را انتخاب کنید. OmniHeat Men\'s Solid Hooded Puffer Jacket چند منظوره و کاربردی است و برای مواقع مختلف مناسب است، بگونه ای که به محل کار می روید، برای یک سفر غیر رسمی می روید یا در یک رویداد خارجی شرکت می کنید. با تجربه ترکیب کاملی از سبک، راحتی و کارایی با OmniHeat Men\'s Solid Hooded Puffer Jacket. به روز کردن گاردراب زمستانی خود و در حالی که در فضای باز در حال برخورد با سرما هستید، گرم و راحت بمانید. سرمایه گذاری در سبک و ایجاد یک بیانیه با این قطعه ضروری.',
                    'meta-description' => 'توضیحات متا',
                    'meta-keywords'    => 'متا1، متا2، متا3',
                    'meta-title'       => 'عنوان متا',
                    'name'             => 'OmniHeat Men\'s Solid Hooded Puffer Jacket',
                    'sort-description' => 'با کت پوشاک OmniHeat Men\'s Solid Hooded Puffer Jacket ما گرم و شیک بمانید. این کت پوشاک برای ارائه گرمای بی نظیر طراحی شده است و دارای جیب های درج کننده برای راحتی اضافی است. مواد عایق شده مطمئن می شود که در هوای سرد گرم و راحت باقی می مانید. با 5 رنگ جذاب در دسترس، این یک انتخاب چند منظوره برای مواقع مختلف است.',
                ],

                '8' => [
                    'description'      => 'معرفی کت پوشاک OmniHeat Men\'s Solid Hooded Puffer Jacket، راه حلی برای گرم نگه داشتن و شیک بودن در فصل های سردتر است. این کت پوشاک با دوام و گرما در ذهن طراحی شده است، تضمین می کند که به همراه شما باشد. طراحی هود نه تنها یک لمسه از سبک را اضافه می کند، بلکه گرمای اضافی را فراهم می کند و شما را از بادها و آب و هوای سرد محافظت می کند. آستین های کامل پوشش کامل را تضمین می کنند و از شانه تا مچ دست شما را گرم نگه می دارند. با جیب های درج کننده، این کت پوشاک پافر امکاناتی را برای حمل و نقل لوازم ضروری یا گرم نگه داشتن دستان شما فراهم می کند. پر کننده مصنوعی عایق شده گرمای بهبود یافته ارائه می دهد و برای مقابله با روزها و شب های سرد مناسب است. این کت پوشاک از پلی استر مقاوم و زیربنایی ساخته شده است و برای مقاومت در برابر عوامل جوی ساخته شده است. با 5 رنگ جذاب در دسترس، می توانید رنگی را که به سبک و ترجیح شما می خورد را انتخاب کنید. OmniHeat Men\'s Solid Hooded Puffer Jacket چند منظوره و کاربردی است و برای مواقع مختلف مناسب است، بگونه ای که به محل کار می روید، برای یک سفر غیر رسمی می روید یا در یک رویداد خارجی شرکت می کنید. با تجربه ترکیب کاملی از سبک، راحتی و کارایی با OmniHeat Men\'s Solid Hooded Puffer Jacket. به روز کردن گاردراب زمستانی خود و در حالی که در فضای باز در حال برخورد با سرما هستید، گرم و راحت بمانید. سرمایه گذاری در سبک و ایجاد یک بیانیه با این قطعه ضروری.',
                    'meta-description' => 'توضیحات متا',
                    'meta-keywords'    => 'متا1، متا2، متا3',
                    'meta-title'       => 'عنوان متا',
                    'name'             => 'OmniHeat Men\'s Solid Hooded Puffer Jacket-Blue-Yellow-M',
                    'sort-description' => 'با کت پوشاک OmniHeat Men\'s Solid Hooded Puffer Jacket ما گرم و شیک بمانید. این کت پوشاک برای ارائه گرمای بی نظیر طراحی شده است و دارای جیب های درج کننده برای راحتی اضافی است. مواد عایق شده مطمئن می شود که در هوای سرد گرم و راحت باقی می مانید. با 5 رنگ جذاب در دسترس، این یک انتخاب چند منظوره برای مواقع مختلف است.',
                ],

                '9' => [
                    'description'      => 'معرفی کت پوشاک OmniHeat Men\'s Solid Hooded Puffer Jacket، راه حلی برای گرم نگه داشتن و شیک بودن در فصل های سردتر است. این کت پوشاک با دوام و گرما در ذهن طراحی شده است، تضمین می کند که به همراه شما باشد. طراحی هود نه تنها یک لمسه از سبک را اضافه می کند، بلکه گرمای اضافی را فراهم می کند و شما را از بادها و آب و هوای سرد محافظت می کند. آستین های کامل پوشش کامل را تضمین می کنند و از شانه تا مچ دست شما را گرم نگه می دارند. با جیب های درج کننده، این کت پوشاک پافر امکاناتی را برای حمل و نقل لوازم ضروری یا گرم نگه داشتن دستان شما فراهم می کند. پر کننده مصنوعی عایق شده گرمای بهبود یافته ارائه می دهد و برای مقابله با روزها و شب های سرد مناسب است. این کت پوشاک از پلی استر مقاوم و زیربنایی ساخته شده است و برای مقاومت در برابر عوامل جوی ساخته شده است. با 5 رنگ جذاب در دسترس، می توانید رنگی را که به سبک و ترجیح شما می خورد را انتخاب کنید. OmniHeat Men\'s Solid Hooded Puffer Jacket چند منظوره و کاربردی است و برای مواقع مختلف مناسب است، بگونه ای که به محل کار می روید، برای یک سفر غیر رسمی می روید یا در یک رویداد خارجی شرکت می کنید. با تجربه ترکیب کاملی از سبک، راحتی و کارایی با OmniHeat Men\'s Solid Hooded Puffer Jacket. به روز کردن گاردراب زمستانی خود و در حالی که در فضای باز در حال برخورد با سرما هستید، گرم و راحت بمانید. سرمایه گذاری در سبک و ایجاد یک بیانیه با این قطعه ضروری.',
                    'meta-description' => 'توضیحات متا',
                    'meta-keywords'    => 'متا1، متا2، متا3',
                    'meta-title'       => 'عنوان متا',
                    'name'             => 'OmniHeat Men\'s Solid Hooded Puffer Jacket-Blue-Yellow-L',
                    'sort-description' => 'با کت پوشاک OmniHeat Men\'s Solid Hooded Puffer Jacket ما گرم و شیک بمانید. این کت پوشاک برای ارائه گرمای بی نظیر طراحی شده است و دارای جیب های درج کننده برای راحتی اضافی است. مواد عایق شده مطمئن می شود که در هوای سرد گرم و راحت باقی می مانید. با 5 رنگ جذاب در دسترس، این یک انتخاب چند منظوره برای مواقع مختلف است.',
                ],

                '10' => [
                    'description'      => 'معرفی کت پوشاک OmniHeat Men\'s Solid Hooded Puffer Jacket، راه حلی برای گرم نگه داشتن و شیک بودن در فصل های سردتر. این کت پوشاک با دوام و گرما در ذهن طراحی شده است تا به همراه شما باشد. طراحی هودی نه تنها یک لمسه از سبک اضافه می کند، بلکه گرمای اضافی را فراهم می کند و شما را از بادها و آب و هوای سرد محافظت می کند. آستین های کامل پوشش کامل را ارائه می دهند و اطمینان حاصل می کنند که از شانه تا مچ دست گرم و راحت باقی می مانید. با جیب های درج کننده، این کت پوشاک پافر امکانات را برای حمل و نقل لوازم ضروری یا گرم نگه داشتن دستان شما فراهم می کند. پر کننده مصنوعی عایق شده، گرمای بهبود یافته را ارائه می دهد و برای مبارزه با روزها و شب های سرد مناسب است. این کت پوشاک از پلی استر مقاوم و زیربنایی ساخته شده است و برای مقاومت در برابر عوامل جوی طراحی شده است. با 5 رنگ جذاب در دسترس، می توانید رنگی را که به سبک و ترجیح شما می خورد انتخاب کنید. چند منظوره و کاربردی، کت پوشاک OmniHeat Men\'s Solid Hooded Puffer Jacket برای مواقع مختلف مناسب است، بگویید به محل کار می روید، برای یک گشت و گذار غیر رسمی می روید یا در یک رویداد خارجی شرکت می کنید. تجربه ترکیب کاملی از سبک، راحتی و کارایی را با کت پوشاک OmniHeat Men\'s Solid Hooded Puffer Jacket داشته باشید. بهار خود را ارتقا دهید و در حالی که به فضای باز می پردازید، گرم و راحت باقی بمانید. سرمای را با سبک شکست دهید و با این قطعه ضروری، بیانیه ای بدهید.',
                    'meta-description' => 'توضیحات متا',
                    'meta-keywords'    => 'متا1، متا2، متا3',
                    'meta-title'       => 'عنوان متا',
                    'name'             => 'OmniHeat Men\'s Solid Hooded Puffer Jacket-Blue-Green-M',
                    'sort-description' => 'با کت پوشاک OmniHeat Men\'s Solid Hooded Puffer Jacket ما گرم و شیک بمانید. این کت پوشاک برای ارائه گرمای بی نظیر طراحی شده است و دارای جیب های درج کننده برای راحتی اضافی است. مواد عایق شده مطمئن می شود که در هوای سرد گرم و راحت باقی می مانید. با 5 رنگ جذاب در دسترس، این یک انتخاب چند منظوره برای مواقع مختلف است.',
                ],

                '11' => [
                    'description'      => 'معرفی کت پوشاک OmniHeat Men\'s Solid Hooded Puffer Jacket، راه حلی برای گرم نگه داشتن و شیک بودن در فصل های سردتر. این کت پوشاک با دوام و گرما در ذهن طراحی شده است تا به همراه شما باشد. طراحی هودی نه تنها یک لمسه از سبک اضافه می کند، بلکه گرمای اضافی را فراهم می کند و شما را از بادها و آب و هوای سرد محافظت می کند. آستین های کامل پوشش کامل را ارائه می دهند و اطمینان حاصل می کنند که از شانه تا مچ دست گرم و راحت باقی می مانید. با جیب های درج کننده، این کت پوشاک پافر امکانات را برای حمل و نقل لوازم ضروری یا گرم نگه داشتن دستان شما فراهم می کند. پر کننده مصنوعی عایق شده، گرمای بهبود یافته را ارائه می دهد و برای مبارزه با روزها و شب های سرد مناسب است. این کت پوشاک از پلی استر مقاوم و زیربنایی ساخته شده است و برای مقاومت در برابر عوامل جوی طراحی شده است. با 5 رنگ جذاب در دسترس، می توانید رنگی را که به سبک و ترجیح شما می خورد انتخاب کنید. چند منظوره و کاربردی، کت پوشاک OmniHeat Men\'s Solid Hooded Puffer Jacket برای مواقع مختلف مناسب است، بگویید به محل کار می روید، برای یک گشت و گذار غیر رسمی می روید یا در یک رویداد خارجی شرکت می کنید. تجربه ترکیب کاملی از سبک، راحتی و کارایی را با کت پوشاک OmniHeat Men\'s Solid Hooded Puffer Jacket داشته باشید. بهار خود را ارتقا دهید و در حالی که به فضای باز می پردازید، گرم و راحت باقی بمانید. سرمای را با سبک شکست دهید و با این قطعه ضروری، بیانیه ای بدهید.',
                    'meta-description' => 'توضیحات متا',
                    'meta-keywords'    => 'متا1، متا2، متا3',
                    'meta-title'       => 'عنوان متا',
                    'name'             => 'OmniHeat Men\'s Solid Hooded Puffer Jacket-Blue-Green-L',
                    'sort-description' => 'با کت پوشاک OmniHeat Men\'s Solid Hooded Puffer Jacket ما گرم و شیک بمانید. این کت پوشاک برای ارائه گرمای بی نظیر طراحی شده است و دارای جیب های درج کننده برای راحتی اضافی است. مواد عایق شده مطمئن می شود که در هوای سرد گرم و راحت باقی می مانید. با 5 رنگ جذاب در دسترس، این یک انتخاب چند منظوره برای مواقع مختلف است.',
                ],
            ],
        ],
    ],

    'installer' => [
        'index' => [
            'create-administrator' => [
                'admin'            => 'مدیر',
                'bagisto'          => 'Bagisto',
                'confirm-password' => 'تأیید رمز عبور',
                'download-sample'  => 'دانلود نمونه',
                'email'            => 'ایمیل',
                'email-address'    => 'admin@example.com',
                'password'         => 'رمز عبور',
                'sample-products'  => 'محصولات نمونه',
                'title'            => 'ایجاد مدیر',
            ],

            'environment-configuration' => [
                'algerian-dinar'              => 'دینار الجزایری (DZD)',
                'allowed-currencies'          => 'ارزهای مجاز',
                'allowed-locales'             => 'زبان‌های مجاز',
                'application-name'            => 'نام برنامه',
                'argentine-peso'              => 'پزوی آرژانتین (ARS)',
                'australian-dollar'           => 'دلار استرالیا (AUD)',
                'bagisto'                     => 'Bagisto',
                'bangladeshi-taka'            => 'تاکای بنگلادش (BDT)',
                'brazilian-real'              => 'رئال برزیل (BRL)',
                'british-pound-sterling'      => 'پوند استرلینگ بریتانیا (GBP)',
                'canadian-dollar'             => 'دلار کانادا (CAD)',
                'cfa-franc-bceao'             => 'فرانک CFA BCEAO (XOF)',
                'cfa-franc-beac'              => 'فرانک CFA BEAC (XAF)',
                'chilean-peso'                => 'پزوی شیلی (CLP)',
                'chinese-yuan'                => 'یوان چینی (CNY)',
                'colombian-peso'              => 'پزوی کلمبیا (COP)',
                'czech-koruna'                => 'کرونای چک (CZK)',
                'danish-krone'                => 'کرون دانمارکی (DKK)',
                'database-connection'         => 'اتصال به پایگاه داده',
                'database-hostname'           => 'نام میزبان پایگاه داده',
                'database-name'               => 'نام پایگاه داده',
                'database-password'           => 'رمز عبور پایگاه داده',
                'database-port'               => 'پورت پایگاه داده',
                'database-prefix'             => 'پیشوند پایگاه داده',
                'database-username'           => 'نام کاربری پایگاه داده',
                'default-currency'            => 'ارز پیشفرض',
                'default-locale'              => 'زبان پیشفرض',
                'default-timezone'            => 'منطقه زمانی پیشفرض',
                'default-url'                 => 'URL پیشفرض',
                'default-url-link'            => 'https://localhost',
                'egyptian-pound'              => 'پوند مصر (EGP)',
                'euro'                        => 'یورو (EUR)',
                'fijian-dollar'               => 'دلار فیجی (FJD)',
                'hong-kong-dollar'            => 'دلار هنگ‌کنگ (HKD)',
                'hungarian-forint'            => 'فورینت مجارستان (HUF)',
                'indian-rupee'                => 'روپیه هند (INR)',
                'indonesian-rupiah'           => 'روپیه اندونزی (IDR)',
                'israeli-new-shekel'          => 'شقل جدید اسرائیل (ILS)',
                'japanese-yen'                => 'ین ژاپن (JPY)',
                'jordanian-dinar'             => 'دینار اردن (JOD)',
                'kazakhstani-tenge'           => 'تنگه قزاقستان (KZT)',
                'kuwaiti-dinar'               => 'دینار کویت (KWD)',
                'lebanese-pound'              => 'پوند لبنان (LBP)',
                'libyan-dinar'                => 'دینار لیبی (LYD)',
                'malaysian-ringgit'           => 'رینگیت مالزی (MYR)',
                'mauritian-rupee'             => 'روپیه موریس (MUR)',
                'mexican-peso'                => 'پزوی مکزیک (MXN)',
                'moroccan-dirham'             => 'درهم مراکش (MAD)',
                'mysql'                       => 'MySQL',
                'nepalese-rupee'              => 'روپیه نپال (NPR)',
                'new-taiwan-dollar'           => 'دلار جدید تایوان (TWD)',
                'new-zealand-dollar'          => 'دلار نیوزیلند (NZD)',
                'nigerian-naira'              => 'نایرای نیجریه (NGN)',
                'norwegian-krone'             => 'کرون نروژی (NOK)',
                'omani-rial'                  => 'ریال عمان (OMR)',
                'pakistani-rupee'             => 'روپیه پاکستان (PKR)',
                'panamanian-balboa'           => 'بالبوای پاناما (PAB)',
                'paraguayan-guarani'          => 'گوارانی پاراگوئه (PYG)',
                'peruvian-nuevo-sol'          => 'سول جدید پرو (PEN)',
                'pgsql'                       => 'pgSQL',
                'philippine-peso'             => 'پزوی فیلیپین (PHP)',
                'polish-zloty'                => 'زلوتی لهستان (PLN)',
                'qatari-rial'                 => 'ریال قطر (QAR)',
                'romanian-leu'                => 'لئوی رومانی (RON)',
                'russian-ruble'               => 'روبل روسیه (RUB)',
                'saudi-riyal'                 => 'ریال عربستان سعودی (SAR)',
                'select-timezone'             => 'انتخاب منطقه زمانی',
                'singapore-dollar'            => 'دلار سنگاپور (SGD)',
                'south-african-rand'          => 'راند آفریقای جنوبی (ZAR)',
                'south-korean-won'            => 'وون کره جنوبی (KRW)',
                'sqlsrv'                      => 'SQLSRV',
                'sri-lankan-rupee'            => 'روپیه سری‌لانکا (LKR)',
                'swedish-krona'               => 'کرون سوئد (SEK)',
                'swiss-franc'                 => 'فرانک سوئیس (CHF)',
                'thai-baht'                   => 'بات تایلند (THB)',
                'title'                       => 'پیکربندی فروشگاه',
                'tunisian-dinar'              => 'دینار تونس (TND)',
                'turkish-lira'                => 'لیر ترکیه (TRY)',
                'ukrainian-hryvnia'           => 'هریونیای اوکراین (UAH)',
                'united-arab-emirates-dirham' => 'درهم امارات متحده عربی (AED)',
                'united-states-dollar'        => 'دلار ایالات متحده آمریکا (USD)',
                'uzbekistani-som'             => 'سوم ازبکستان (UZS)',
                'venezuelan-bolívar'          => 'بولیوار ونزوئلا (VEF)',
                'vietnamese-dong'             => 'دانگ ویتنامی (VND)',
                'warning-message'             => 'هشدار! تنظیمات زبان‌های پیشفرض سیستم و ارز پیشفرض برای همیشه ثابت و قابل تغییر نیستند.',
                'zambian-kwacha'              => 'کواچای زامبیا (ZMW)',
            ],

            'installation-processing' => [
                'bagisto'          => 'نصب Bagisto',
                'bagisto-info'     => 'ایجاد جداول پایگاه داده، این ممکن است چند لحظه طول بکشد',
                'title'            => 'نصب',
            ],

            'installation-completed' => [
                'admin-panel'                => 'پنل مدیریت',
                'bagisto-forums'             => 'انجمن Bagisto',
                'customer-panel'             => 'پنل مشتریان',
                'explore-bagisto-extensions' => 'کاوش در افزونه‌های Bagisto',
                'title'                      => 'نصب با موفقیت انجام شد',
                'title-info'                 => 'Bagisto با موفقیت بر روی سیستم شما نصب شده است.',
            ],

            'ready-for-installation' => [
                'create-database-table'   => 'ایجاد جدول پایگاه‌داده',
                'install'                 => 'نصب',
                'install-info'            => 'Bagisto برای نصب',
                'install-info-button'     => 'برای شروع کلیک کنید',
                'populate-database-table' => 'پر کردن جدول‌های پایگاه‌داده',
                'start-installation'      => 'شروع نصب',
                'title'                   => 'آماده نصب',
            ],

            'start' => [
                'locale'        => 'محلی',
                'main'          => 'شروع',
                'select-locale' => 'انتخاب محلی',
                'title'         => 'نصب Bagisto شما',
                'welcome-title' => 'خوش آمدید به Bagisto 2.0.',
            ],

            'server-requirements' => [
                'calendar'    => 'تقویم',
                'ctype'       => 'cType',
                'curl'        => 'cURL',
                'dom'         => 'DOM',
                'fileinfo'    => 'Fileinfo',
                'filter'      => 'فیلتر',
                'gd'          => 'GD',
                'hash'        => 'هش',
                'intl'        => 'Intl',
                'json'        => 'JSON',
                'mbstring'    => 'متن چندبایتی',
                'openssl'     => 'OpenSSL',
                'pcre'        => 'pcre',
                'pdo'         => 'pdo',
                'php'         => 'PHP',
                'php-version' => '8.1 یا بالاتر',
                'session'     => 'نشست',
                'title'       => 'نیازمندی‌های سرور',
                'tokenizer'   => 'Tokenizer',
                'xml'         => 'XML',
            ],

            'arabic'                   => 'عربی',
            'back'                     => 'بازگشت',
            'bagisto'                  => 'Bagisto',
            'bagisto-info'             => 'یک پروژه جامعه‌ای توسط',
            'bagisto-logo'             => 'لوگوی Bagisto',
            'bengali'                  => 'بنگالی',
            'chinese'                  => 'چینی',
            'continue'                 => 'ادامه',
            'dutch'                    => 'هلندی',
            'english'                  => 'انگلیسی',
            'french'                   => 'فرانسوی',
            'german'                   => 'آلمانی',
            'hebrew'                   => 'عبری',
            'hindi'                    => 'هندی',
            'installation-description' => 'نصب Bagisto به طور معمول شامل چندین مرحله است. در اینجا یک خلاصه عمومی از فرا یند نصب Bagisto آورده شده است:',
            'installation-info'        => 'خوشحالیم که شما را اینجا می‌بینیم!',
            'installation-title'       => 'به نصب Bagisto خوش آمدید',
            'italian'                  => 'ایتالیایی',
            'japanese'                 => 'ژاپنی',
            'persian'                  => 'فارسی',
            'polish'                   => 'لهستانی',
            'portuguese'               => 'پرتغالی برزیلی',
            'russian'                  => 'روسی',
            'sinhala'                  => 'سینهالا',
            'spanish'                  => 'اسپانیایی',
            'title'                    => 'نصب‌کننده Bagisto',
            'turkish'                  => 'ترکی',
            'ukrainian'                => 'اوکراینی',
            'webkul'                   => 'Webkul',
        ],
    ],
];
