<?php

return [
    'seeders' => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'پیش‌فرض',
            ],

            'attribute-groups' => [
                'description'       => 'توضیحات',
                'general'           => 'عمومی',
                'inventories'       => 'موجودی‌ها',
                'meta-description'  => 'توضیحات متا',
                'price'             => 'قیمت',
                'shipping'          => 'حمل و نقل',
                'settings'          => 'تنظیمات',
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
                'meta-title'           => 'عنوان متا',
                'meta-keywords'        => 'کلمات کلیدی متا',
                'meta-description'     => 'توضیحات متا',
                'manage-stock'         => 'مدیریت موجودی',
                'new'                  => 'جدید',
                'name'                 => 'نام',
                'product-number'       => 'شماره محصول',
                'price'                => 'قیمت',
                'sku'                  => 'SKU',
                'status'               => 'وضعیت',
                'short-description'    => 'توضیح کوتاه',
                'special-price'        => 'قیمت ویژه',
                'special-price-from'   => 'قیمت ویژه از',
                'special-price-to'     => 'قیمت ویژه تا',
                'size'                 => 'اندازه',
                'tax-category'         => 'دسته مالیاتی',
                'url-key'              => 'کلید URL',
                'visible-individually' => 'نمایش انفرادی',
                'width'                => 'عرض',
                'weight'               => 'وزن',
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

                'refund-policy' => [
                    'content' => 'محتوای سیاست بازپرداخت',
                    'title'   => 'سیاست بازپرداخت',
                ],

                'return-policy' => [
                    'content' => 'محتوای سیاست بازگشت',
                    'title'   => 'سیاست بازگشت',
                ],

                'terms-conditions' => [
                    'content' => 'محتوای شرایط و مقررات',
                    'title'   => 'شرایط و مقررات',
                ],

                'terms-of-use' => [
                    'content' => 'محتوای شرایط استفاده',
                    'title'   => 'شرایط استفاده',
                ],

                'contact-us' => [
                    'content' => 'محتوای تماس با ما',
                    'title'   => 'تماس با ما',
                ],

                'customer-service' => [
                    'content' => 'محتوای خدمات مشتری',
                    'title'   => 'خدمات مشتری',
                ],

                'whats-new' => [
                    'content' => 'محتوای جدید چیست',
                    'title'   => 'جدید چیست',
                ],

                'payment-policy' => [
                    'content' => 'محتوای سیاست پرداخت',
                    'title'   => 'سیاست پرداخت',
                ],

                'shipping-policy' => [
                    'content' => 'محتوای سیاست حمل و نقل',
                    'title'   => 'سیاست حمل و نقل',
                ],

                'privacy-policy' => [
                    'content' => 'محتوای سیاست حفظ حریم خصوصی',
                    'title'   => 'سیاست حفظ حریم خصوصی',
                ],
            ],
        ],

        'core' => [
            'channels' => [
                'meta-title'       => 'فروشگاه نمونه',
                'meta-keywords'    => 'فروشگاه نمونه کلمات کلیدی متا',
                'meta-description' => 'فروشگاه نمونه توضیحات متا',
                'name'             => 'پیش‌فرض',
            ],

            'currencies' => [
                'CNY' => 'یوان چین',
                'AED' => 'درهم',
                'EUR' => 'یورو',
                'INR' => 'روپیه هندی',
                'IRR' => 'ریال ایران',
                'ILS' => 'شقل اسرائیلی',
                'JPY' => 'ین ژاپن',
                'GBP' => 'پوند استرلینگ',
                'RUB' => 'روبل روسیه',
                'SAR' => 'ریال سعودی',
                'TRY' => 'لیر ترکیه',
                'USD' => 'دلار آمریکا',
                'UAH' => 'هریونیا اوکراین',
            ],

            'locales' => [
                'ar'    => 'عربی',
                'bn'    => 'بنگالی',
                'de'    => 'آلمانی',
                'es'    => 'اسپانیایی',
                'en'    => 'انگلیسی',
                'fr'    => 'فرانسوی',
                'fa'    => 'فارسی',
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
                'guest'     => 'مهمان',
                'general'   => 'عمومی',
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
                'image-carousel' => [
                    'name'    => 'اسلایدر تصاویر',

                    'sliders' => [
                        'title' => 'آماده‌اید برای مجموعه جدید',
                    ],
                ],

                'offer-information' => [
                    'name'    => 'اطلاعات پیشنهاد',

                    'content' => [
                        'title' => 'تا 40٪ تخفیف در سفارش اولتان، همین الان سفارش دهید',
                    ],
                ],

                'categories-collections' => [
                    'name' => 'مجموعه‌های دسته‌بندی',
                ],

                'new-products' => [
                    'name'    => 'محصولات جدید',

                    'options' => [
                        'title' => 'محصولات جدید',
                    ],
                ],

                'top-collections' => [
                    'name'    => 'مجموعه‌های برتر',

                    'content' => [
                        'sub-title-1' => 'مجموعه‌های ما',
                        'sub-title-2' => 'مجموعه‌های ما',
                        'sub-title-3' => 'مجموعه‌های ما',
                        'sub-title-4' => 'مجموعه‌های ما',
                        'sub-title-5' => 'مجموعه‌های ما',
                        'sub-title-6' => 'مجموعه‌های ما',
                        'title'       => 'با افزودن‌های جدیدمان بازی کنید!',
                    ],
                ],

                'bold-collections' => [
                    'name'    => 'مجموعه‌های جسور',

                    'content' => [
                        'btn-title'   => 'مشاهده همه',
                        'description' => 'معرفی مجموعه‌های جسور جدید ما! سبک خود را با طراحی‌های جسور و اظهارات جذاب بالا ببرید. الگوها و رنگ‌های جسوری را کشف کنید که لباس‌درمانی‌تان را بازتعریف می‌کنند. برای پذیرش بی‌نظیری آماده شوید!',
                        'title'       => 'برای مجموعه‌های جسور جدیدمان آماده شوید!',
                    ],
                ],

                'featured-collections' => [
                    'name'    => 'مجموعه‌های ویژه',

                    'options' => [
                        'title' => 'محصولات ویژه',
                    ],
                ],

                'game-container' => [
                    'name'    => 'ظروف بازی',

                    'content' => [
                        'sub-title-1' => 'مجموعه‌های ما',
                        'sub-title-2' => 'مجموعه‌های ما',
                        'title'       => 'با افزودن‌های جدیدمان بازی کنید!',
                    ],
                ],

                'all-products' => [
                    'name'    => 'همه محصولات',

                    'options' => [
                        'title' => 'همه محصولات',
                    ],
                ],

                'footer-links' => [
                    'name'    => 'پیوندهای فوتر',

                    'options' => [
                        'about-us'         => 'درباره ما',
                        'contact-us'       => 'تماس با ما',
                        'customer-service' => 'خدمات مشتریان',
                        'privacy-policy'   => 'سیاست حریم خصوصی',
                        'payment-policy'   => 'سیاست پرداخت',
                        'return-policy'    => 'سیاست بازگشت',
                        'refund-policy'    => 'سیاست بازپرداخت',
                        'shipping-policy'  => 'سیاست حمل و نقل',
                        'terms-of-use'     => 'شرایط استفاده',
                        'terms-conditions' => 'شرایط و مقررات',
                        'whats-new'        => 'چه خبر است',
                    ],
                ],

                'services-content' => [
                    'name'  => 'محتوای خدمات',

                    'title' => [
                        'free-shipping'     => 'ارسال رایگان',
                        'product-replace'   => 'تعویض محصول',
                        'emi-available'     => 'EMI در دسترس است',
                        'time-support'      => 'پشتیبانی 24/7',
                    ],

                    'description' => [
                        'free-shipping-info'     => 'از ارسال رایگان در تمام سفارش‌ها لذت ببرید',
                        'product-replace-info'   => 'تعویض آسان محصول در دسترس است!',
                        'emi-available-info'     => 'EMI بدون هزینه در تمام کارت‌های اعتباری اصلی در دسترس است',
                        'time-support-info'      => 'پشتیبانی اختصاصی 24/7 از طریق چت و ایمیل',
                    ],
                ],
            ],
        ],

        'user' => [
            'users' => [
                'name' => 'مثال',
            ],

            'roles' => [
                'description' => 'این نقش دسترسی‌هایی را برای کاربران فراهم می‌کند',
                'name'        => 'مدیر سیستم',
            ],
        ],
    ],
];
