<?php

return [
    'seeders' => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'پیش‌فرض',
            ],

            'attribute-groups' => [
                'description' => 'توضیحات',
                'general' => 'عمومی',
                'inventories' => 'موجودی‌ها',
                'meta-description' => 'توضیحات متا',
                'price' => 'قیمت',
                'rma' => 'RMA',
                'settings' => 'تنظیمات',
                'shipping' => 'حمل و نقل',
            ],

            'attributes' => [
                'allow-rma' => 'اجازه‌ی مرجوعی',
                'brand' => 'برند',
                'color' => 'رنگ',
                'cost' => 'هزینه',
                'description' => 'توضیحات',
                'featured' => 'ویژگی‌دار',
                'guest-checkout' => 'خرید مهمان',
                'height' => 'ارتفاع',
                'length' => 'طول',
                'manage-stock' => 'مدیریت موجودی',
                'meta-description' => 'توضیحات متا',
                'meta-keywords' => 'کلمات کلیدی متا',
                'meta-title' => 'عنوان متا',
                'name' => 'نام',
                'new' => 'جدید',
                'price' => 'قیمت',
                'product-number' => 'شماره محصول',
                'rma-rules' => 'قوانین مرجوعی',
                'short-description' => 'توضیح کوتاه',
                'size' => 'اندازه',
                'sku' => 'SKU',
                'special-price' => 'قیمت ویژه',
                'special-price-from' => 'قیمت ویژه از',
                'special-price-to' => 'قیمت ویژه تا',
                'status' => 'وضعیت',
                'tax-category' => 'دسته مالیاتی',
                'url-key' => 'کلید URL',
                'visible-individually' => 'نمایش انفرادی',
                'weight' => 'وزن',
                'width' => 'عرض',
            ],

            'attribute-options' => [
                'black' => 'سیاه',
                'green' => 'سبز',
                'l' => 'L',
                'm' => 'M',
                'red' => 'قرمز',
                's' => 'S',
                'white' => 'سفید',
                'xl' => 'XL',
                'yellow' => 'زرد',
            ],
        ],

        'category' => [
            'categories' => [
                'description' => 'توضیح دسته اصلی',
                'name' => 'اصلی',
            ],
        ],

        'cms' => [
            'pages' => [
                'about-us' => [
                    'content' => 'محتوای درباره ما',
                    'title' => 'درباره ما',
                ],

                'contact-us' => [
                    'content' => 'محتوای تماس با ما',
                    'title' => 'تماس با ما',
                ],

                'customer-service' => [
                    'content' => 'محتوای خدمات مشتری',
                    'title' => 'خدمات مشتری',
                ],

                'payment-policy' => [
                    'content' => 'محتوای سیاست پرداخت',
                    'title' => 'سیاست پرداخت',
                ],

                'privacy-policy' => [
                    'content' => 'محتوای سیاست حفظ حریم خصوصی',
                    'title' => 'سیاست حفظ حریم خصوصی',
                ],

                'refund-policy' => [
                    'content' => 'محتوای سیاست بازپرداخت',
                    'title' => 'سیاست بازپرداخت',
                ],

                'return-policy' => [
                    'content' => 'محتوای سیاست بازگشت',
                    'title' => 'سیاست بازگشت',
                ],

                'shipping-policy' => [
                    'content' => 'محتوای سیاست حمل و نقل',
                    'title' => 'سیاست حمل و نقل',
                ],

                'terms-conditions' => [
                    'content' => 'محتوای شرایط و مقررات',
                    'title' => 'شرایط و مقررات',
                ],

                'terms-of-use' => [
                    'content' => 'محتوای شرایط استفاده',
                    'title' => 'شرایط استفاده',
                ],

                'whats-new' => [
                    'content' => 'محتوای جدید چیست',
                    'title' => 'جدید چیست',
                ],
            ],
        ],

        'core' => [
            'channels' => [
                'meta-description' => 'فروشگاه نمونه توضیحات متا',
                'meta-keywords' => 'فروشگاه نمونه کلمات کلیدی متا',
                'meta-title' => 'فروشگاه نمونه',
                'name' => 'پیش‌فرض',
            ],

            'currencies' => [
                'AED' => 'درهم امارات متحده',
                'ARS' => 'پزوی آرژانتین',
                'AUD' => 'دلار استرالیا',
                'BDT' => 'تاکای بنگلادش',
                'BHD' => 'دینار بحرین',
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
                'ar' => 'عربی',
                'bn' => 'بنگالی',
                'ca' => 'کاتالان',
                'de' => 'آلمانی',
                'en' => 'انگلیسی',
                'es' => 'اسپانیایی',
                'fa' => 'فارسی',
                'fr' => 'فرانسوی',
                'he' => 'عبری',
                'hi_IN' => 'هندی',
                'id' => 'اندونزیایی',
                'it' => 'ایتالیایی',
                'ja' => 'ژاپنی',
                'nl' => 'هلندی',
                'pl' => 'لهستانی',
                'pt_BR' => 'پرتغالی برزیل',
                'ru' => 'روسی',
                'sin' => 'سینهالی',
                'tr' => 'ترکی',
                'uk' => 'اوکراینی',
                'zh_CN' => 'چینی',
            ],
        ],

        'customer' => [
            'customer-groups' => [
                'general' => 'عمومی',
                'guest' => 'مهمان',
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
                        'btn-title' => 'مشاهده مجموعه‌ها',
                        'description' => 'معرفی مجموعه‌های جسور جدید ما! سبک خود را با طراحی‌های جسور و اظهارات جذاب بالا ببرید. الگوها و رنگ‌های جسوری را کشف کنید که لباس‌درمانی‌تان را بازتعریف می‌کنند. برای پذیرش بی‌نظیری آماده شوید!',
                        'title' => 'برای مجموعه‌های جسور جدیدمان آماده شوید!',
                    ],

                    'name' => 'مجموعه‌های جسور',
                ],

                'bold-collections-2' => [
                    'content' => [
                        'btn-title' => 'مشاهده مجموعه‌ها',
                        'description' => 'مجموعه‌های جسورانه ما اینجا هستند تا کمد لباس شما را با طراحی‌های بی‌باک و رنگ‌های درخشان و پرجنب‌وجوش بازتعریف کنند. از الگوهای جسورانه تا رنگ‌های قدرتمند، این فرصت شماست که از معمولی فاصله بگیرید و به فوق‌العاده قدم بگذارید.',
                        'title' => 'جسارت خود را با مجموعه جدید ما آزاد کنید!',
                    ],

                    'name' => 'مجموعه‌های جسورانه',
                ],

                'booking-products' => [
                    'name' => 'محصولات رزرو',

                    'options' => [
                        'title' => 'رزرو بلیط',
                    ],
                ],

                'categories-collections' => [
                    'name' => 'مجموعه‌های دسته‌بندی',
                ],

                'featured-collections' => [
                    'name' => 'مجموعه‌های ویژه',

                    'options' => [
                        'title' => 'محصولات ویژه',
                    ],
                ],

                'footer-links' => [
                    'name' => 'پیوندهای فوتر',

                    'options' => [
                        'about-us' => 'درباره ما',
                        'contact-us' => 'تماس با ما',
                        'customer-service' => 'خدمات مشتریان',
                        'payment-policy' => 'سیاست پرداخت',
                        'privacy-policy' => 'سیاست حریم خصوصی',
                        'refund-policy' => 'سیاست بازپرداخت',
                        'return-policy' => 'سیاست بازگشت',
                        'shipping-policy' => 'سیاست حمل و نقل',
                        'terms-conditions' => 'شرایط و مقررات',
                        'terms-of-use' => 'شرایط استفاده',
                        'whats-new' => 'چه خبر است',
                    ],
                ],

                'game-container' => [
                    'content' => [
                        'sub-title-1' => 'مجموعه‌های ما',
                        'sub-title-2' => 'مجموعه‌های ما',
                        'title' => 'با افزودن‌های جدیدمان بازی کنید!',
                    ],

                    'name' => 'ظروف بازی',
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
                        'emi-available-info' => 'EMI بدون هزینه در تمام کارت‌های اعتباری اصلی در دسترس است',
                        'free-shipping-info' => 'از ارسال رایگان در تمام سفارش‌ها لذت ببرید',
                        'product-replace-info' => 'تعویض آسان محصول در دسترس است!',
                        'time-support-info' => 'پشتیبانی اختصاصی 24/7 از طریق چت و ایمیل',
                    ],

                    'name' => 'محتوای خدمات',

                    'title' => [
                        'emi-available' => 'EMI در دسترس است',
                        'free-shipping' => 'ارسال رایگان',
                        'product-replace' => 'تعویض محصول',
                        'time-support' => 'پشتیبانی 24/7',
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
                        'title' => 'با افزودن‌های جدیدمان بازی کنید!',
                    ],

                    'name' => 'مجموعه‌های برتر',
                ],
            ],
        ],

        'user' => [
            'roles' => [
                'description' => 'این نقش دسترسی‌هایی را برای کاربران فراهم می‌کند',
                'name' => 'مدیر سیستم',
            ],

            'users' => [
                'name' => 'مثال',
            ],
        ],

        'sample-categories' => [
            'category-translation' => [
                '2' => [
                    'description' => '<p>مردانه</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'مردانه',
                    'slug' => 'mens',
                    'url-path' => 'men',
                ],

                '3' => [
                    'description' => '<p>کودکان</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'کودکان',
                    'slug' => 'kids',
                    'url-path' => 'kids',
                ],

                '4' => [
                    'description' => '<p>زنانه</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'زنانه',
                    'slug' => 'womens',
                    'url-path' => 'woman',
                ],

                '5' => [
                    'description' => '<p>لباس رسمی</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'لباس رسمی',
                    'slug' => 'formal-wear-men',
                    'url-path' => 'men/formal-wear-men',
                ],

                '6' => [
                    'description' => '<p>لباس راحتی</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'لباس راحتی',
                    'slug' => 'casual-wear-men',
                    'url-path' => 'men/casual-wear-men',
                ],

                '7' => [
                    'description' => '<p>لباس ورزشی</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'لباس ورزشی',
                    'slug' => 'active-wear',
                    'url-path' => 'men/active-wear',
                ],

                '8' => [
                    'description' => '<p>کفش</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'کفش',
                    'slug' => 'footwear',
                    'url-path' => 'men/footwear',
                ],

                '9' => [
                    'description' => '<p>لباس دخترانه</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'لباس دخترانه',
                    'slug' => 'formal-wear-female',
                    'url-path' => 'woman/formal-wear-female',
                ],

                '10' => [
                    'description' => '<p>لباس پسرانه</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'لباس پسرانه',
                    'slug' => 'casual-wear-female',
                    'url-path' => 'woman/casual-wear-female',
                ],

                '11' => [
                    'description' => '<p>کفش دخترانه</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'کفش دخترانه',
                    'slug' => 'active-wear-female',
                    'url-path' => 'woman/active-wear-female',
                ],

                '12' => [
                    'description' => '<p>کفش پسرانه</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'کفش پسرانه',
                    'slug' => 'footwear-female',
                    'url-path' => 'woman/footwear-female',
                ],

                '13' => [
                    'description' => '<p>لباس رسمی</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => 'Girls Clothing',
                    'name' => 'لباس رسمی',
                    'slug' => 'girls-clothing',
                    'url-path' => 'kids/girls-clothing',
                ],

                '14' => [
                    'description' => '<p>لباس راحتی</p>',
                    'meta-description' => 'Boys Fashion',
                    'meta-keywords' => '',
                    'meta-title' => 'Boys Clothing',
                    'name' => 'لباس راحتی',
                    'slug' => 'boys-clothing',
                    'url-path' => 'kids/boys-clothing',
                ],

                '15' => [
                    'description' => '<p>لباس ورزشی</p>',
                    'meta-description' => 'Girls Fashionable Footwear Collection',
                    'meta-keywords' => '',
                    'meta-title' => 'Girls Footwear',
                    'name' => 'لباس ورزشی',
                    'slug' => 'girls-footwear',
                    'url-path' => 'kids/girls-footwear',
                ],

                '16' => [
                    'description' => '<p>کفش</p>',
                    'meta-description' => 'Boys Stylish Footwear Collection',
                    'meta-keywords' => '',
                    'meta-title' => 'Boys Footwear',
                    'name' => 'کفش',
                    'slug' => 'boys-footwear',
                    'url-path' => 'kids/boys-footwear',
                ],

                '17' => [
                    'description' => '<p>سلامت</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'سلامت',
                    'slug' => 'wellness',
                    'url-path' => 'wellness',
                ],

                '18' => [
                    'description' => '<p>آموزش یوگای قابل دانلود</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'آموزش یوگای قابل دانلود',
                    'slug' => 'downloadable-yoga-tutorial',
                    'url-path' => 'wellness/downloadable-yoga-tutorial',
                ],

                '19' => [
                    'description' => '<p>کتاب‌های الکترونیکی</p>',
                    'meta-description' => 'Books Collection',
                    'meta-keywords' => '',
                    'meta-title' => 'Books Collection',
                    'name' => 'کتاب‌های الکترونیکی',
                    'slug' => 'e-books',
                    'url-path' => 'wellness/e-books',
                ],

                '20' => [
                    'description' => '<p>بلیط سینما</p>',
                    'meta-description' => 'Immerse yourself in the magic of 10 movies each month without extra charges. Valid nationwide with no blackout dates, this pass offers exclusive perks and concession discounts, making it a must-have for movie enthusiasts.',
                    'meta-keywords' => '',
                    'meta-title' => 'CineXperience Monthly Movie Pass',
                    'name' => 'بلیط سینما',
                    'slug' => 'movie-pass',
                    'url-path' => 'wellness/movie-pass',
                ],

                '21' => [
                    'description' => '<p>رزروها</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'رزروها',
                    'slug' => 'bookings',
                    'url-path' => '',
                ],

                '22' => [
                    'description' => '<p>رزرو قرار</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'رزرو قرار',
                    'slug' => 'appointment-booking',
                    'url-path' => '',
                ],

                '23' => [
                    'description' => '<p>رزرو رویداد</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'رزرو رویداد',
                    'slug' => 'event-booking',
                    'url-path' => '',
                ],

                '24' => [
                    'description' => '<p>رزرو سالن اجتماعی</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'رزرو سالن اجتماعی',
                    'slug' => 'community-hall-bookings',
                    'url-path' => '',
                ],

                '25' => [
                    'description' => '<p>رزرو میز</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'رزرو میز',
                    'slug' => 'table-booking',
                    'url-path' => '',
                ],

                '26' => [
                    'description' => '<p>رزرو اجاره</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'رزرو اجاره',
                    'slug' => 'rental-booking',
                    'url-path' => '',
                ],

                '27' => [
                    'description' => '<p>الکترونیک</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'الکترونیک',
                    'slug' => 'electronics',
                    'url-path' => '',
                ],

                '28' => [
                    'description' => '<p>موبایل و لوازم جانبی</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'موبایل و لوازم جانبی',
                    'slug' => 'mobile-phones-accessories',
                    'url-path' => '',
                ],

                '29' => [
                    'description' => '<p>لپ‌تاپ و تبلت</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'لپ‌تاپ و تبلت',
                    'slug' => 'laptops-tablets',
                    'url-path' => '',
                ],

                '30' => [
                    'description' => '<p>دستگاه‌های صوتی</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'دستگاه‌های صوتی',
                    'slug' => 'audio-devices',
                    'url-path' => '',
                ],

                '31' => [
                    'description' => '<p>خانه هوشمند و اتوماسیون</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'خانه هوشمند و اتوماسیون',
                    'slug' => 'smart-home-automation',
                    'url-path' => '',
                ],

                '32' => [
                    'description' => '<p>خانه</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'خانه',
                    'slug' => 'household',
                    'url-path' => '',
                ],

                '33' => [
                    'description' => '<p>لوازم آشپزخانه</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'لوازم آشپزخانه',
                    'slug' => 'kitchen-appliances',
                    'url-path' => '',
                ],

                '34' => [
                    'description' => '<p>ظروف پخت و پز و غذاخوری</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'ظروف پخت و پز و غذاخوری',
                    'slug' => 'cookware-dining',
                    'url-path' => '',
                ],

                '35' => [
                    'description' => '<p>مبلمان و دکوراسیون</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'مبلمان و دکوراسیون',
                    'slug' => 'furniture-decor',
                    'url-path' => '',
                ],

                '36' => [
                    'description' => '<p>لوازم نظافت</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'لوازم نظافت',
                    'slug' => 'cleaning-supplies',
                    'url-path' => '',
                ],

                '37' => [
                    'description' => '<p>کتاب و لوازم التحریر</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'کتاب و لوازم التحریر',
                    'slug' => 'books-stationery',
                    'url-path' => '',
                ],

                '38' => [
                    'description' => '<p>کتاب‌های داستانی و غیرداستانی</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'کتاب‌های داستانی و غیرداستانی',
                    'slug' => 'fiction-non-fiction-books',
                    'url-path' => '',
                ],

                '39' => [
                    'description' => '<p>آموزشی و دانشگاهی</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'آموزشی و دانشگاهی',
                    'slug' => 'educational-academic',
                    'url-path' => '',
                ],

                '40' => [
                    'description' => '<p>لوازم اداری</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'لوازم اداری',
                    'slug' => 'office-supplies',
                    'url-path' => '',
                ],

                '41' => [
                    'description' => '<p>مواد هنری و صنایع دستی</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'مواد هنری و صنایع دستی',
                    'slug' => 'art-craft-materials',
                    'url-path' => '',
                ],
            ],
        ],
    ],

    'installer' => [
        'middleware' => [
            'already-installed' => 'برنامه در حال حاضر نصب شده است.',
        ],

        'index' => [
            'create-administrator' => [
                'admin' => 'مدیر',
                'bagisto' => 'Bagisto',
                'confirm-password' => 'تأیید رمز عبور',
                'email' => 'ایمیل',
                'email-address' => 'admin@example.com',
                'password' => 'رمز عبور',
                'title' => 'ایجاد مدیر',
            ],

            'environment-configuration' => [
                'algerian-dinar' => 'دینار الجزایری (DZD)',
                'allowed-currencies' => 'ارزهای مجاز',
                'allowed-locales' => 'زبان‌های مجاز',
                'application-name' => 'نام برنامه',
                'argentine-peso' => 'پزوی آرژانتین (ARS)',
                'australian-dollar' => 'دلار استرالیا (AUD)',
                'bagisto' => 'Bagisto',
                'bangladeshi-taka' => 'تاکای بنگلادش (BDT)',
                'bahraini-dinar' => 'دینار بحرین (BHD)',
                'brazilian-real' => 'رئال برزیل (BRL)',
                'british-pound-sterling' => 'پوند استرلینگ بریتانیا (GBP)',
                'canadian-dollar' => 'دلار کانادا (CAD)',
                'cfa-franc-bceao' => 'فرانک CFA BCEAO (XOF)',
                'cfa-franc-beac' => 'فرانک CFA BEAC (XAF)',
                'chilean-peso' => 'پزوی شیلی (CLP)',
                'chinese-yuan' => 'یوان چینی (CNY)',
                'colombian-peso' => 'پزوی کلمبیا (COP)',
                'czech-koruna' => 'کرونای چک (CZK)',
                'danish-krone' => 'کرون دانمارکی (DKK)',
                'database-connection' => 'اتصال به پایگاه داده',
                'database-hostname' => 'نام میزبان پایگاه داده',
                'database-name' => 'نام پایگاه داده',
                'database-password' => 'رمز عبور پایگاه داده',
                'database-port' => 'پورت پایگاه داده',
                'database-prefix' => 'پیشوند پایگاه داده',
                'database-prefix-help' => 'پیشوند باید 4 کاراکتر طول داشته باشد و می‌تواند فقط شامل حروف، اعداد و زیرخط باشد.',
                'database-username' => 'نام کاربری پایگاه داده',
                'default-currency' => 'ارز پیشفرض',
                'default-locale' => 'زبان پیشفرض',
                'default-timezone' => 'منطقه زمانی پیشفرض',
                'default-url' => 'URL پیشفرض',
                'default-url-link' => 'https://localhost',
                'egyptian-pound' => 'پوند مصر (EGP)',
                'euro' => 'یورو (EUR)',
                'fijian-dollar' => 'دلار فیجی (FJD)',
                'hong-kong-dollar' => 'دلار هنگ‌کنگ (HKD)',
                'hungarian-forint' => 'فورینت مجارستان (HUF)',
                'indian-rupee' => 'روپیه هند (INR)',
                'indonesian-rupiah' => 'روپیه اندونزی (IDR)',
                'israeli-new-shekel' => 'شقل جدید اسرائیل (ILS)',
                'japanese-yen' => 'ین ژاپن (JPY)',
                'jordanian-dinar' => 'دینار اردن (JOD)',
                'kazakhstani-tenge' => 'تنگه قزاقستان (KZT)',
                'kuwaiti-dinar' => 'دینار کویت (KWD)',
                'lebanese-pound' => 'پوند لبنان (LBP)',
                'libyan-dinar' => 'دینار لیبی (LYD)',
                'malaysian-ringgit' => 'رینگیت مالزی (MYR)',
                'mauritian-rupee' => 'روپیه موریس (MUR)',
                'mexican-peso' => 'پزوی مکزیک (MXN)',
                'moroccan-dirham' => 'درهم مراکش (MAD)',
                'mysql' => 'MySQL',
                'nepalese-rupee' => 'روپیه نپال (NPR)',
                'new-taiwan-dollar' => 'دلار جدید تایوان (TWD)',
                'new-zealand-dollar' => 'دلار نیوزیلند (NZD)',
                'nigerian-naira' => 'نایرای نیجریه (NGN)',
                'norwegian-krone' => 'کرون نروژی (NOK)',
                'omani-rial' => 'ریال عمان (OMR)',
                'pakistani-rupee' => 'روپیه پاکستان (PKR)',
                'panamanian-balboa' => 'بالبوای پاناما (PAB)',
                'paraguayan-guarani' => 'گوارانی پاراگوئه (PYG)',
                'peruvian-nuevo-sol' => 'سول جدید پرو (PEN)',
                'pgsql' => 'pgSQL',
                'philippine-peso' => 'پزوی فیلیپین (PHP)',
                'polish-zloty' => 'زلوتی لهستان (PLN)',
                'qatari-rial' => 'ریال قطر (QAR)',
                'romanian-leu' => 'لئوی رومانی (RON)',
                'russian-ruble' => 'روبل روسیه (RUB)',
                'saudi-riyal' => 'ریال عربستان سعودی (SAR)',
                'select-timezone' => 'انتخاب منطقه زمانی',
                'singapore-dollar' => 'دلار سنگاپور (SGD)',
                'south-african-rand' => 'راند آفریقای جنوبی (ZAR)',
                'south-korean-won' => 'وون کره جنوبی (KRW)',
                'sqlsrv' => 'SQLSRV',
                'sri-lankan-rupee' => 'روپیه سری‌لانکا (LKR)',
                'swedish-krona' => 'کرون سوئد (SEK)',
                'swiss-franc' => 'فرانک سوئیس (CHF)',
                'thai-baht' => 'بات تایلند (THB)',
                'title' => 'پیکربندی فروشگاه',
                'tunisian-dinar' => 'دینار تونس (TND)',
                'turkish-lira' => 'لیر ترکیه (TRY)',
                'ukrainian-hryvnia' => 'هریونیای اوکراین (UAH)',
                'united-arab-emirates-dirham' => 'درهم امارات متحده عربی (AED)',
                'united-states-dollar' => 'دلار ایالات متحده آمریکا (USD)',
                'uzbekistani-som' => 'سوم ازبکستان (UZS)',
                'venezuelan-bolívar' => 'بولیوار ونزوئلا (VEF)',
                'vietnamese-dong' => 'دانگ ویتنامی (VND)',
                'warning-message' => 'مراقب باشید! تنظیمات زبان سیستم پیش‌فرض و ارز پیش‌فرض شما دائمی هستند و پس از تنظیم نمی‌توان آنها را تغییر داد.',
                'zambian-kwacha' => 'کواچای زامبیا (ZMW)',
            ],

            'sample-products' => [
                'no' => 'خیر',
                'sample-products' => 'محصولات نمونه',
                'title' => 'محصولات نمونه',
                'yes' => 'بله',
            ],

            'installation-processing' => [
                'bagisto' => 'نصب Bagisto',
                'bagisto-info' => 'ایجاد جداول پایگاه داده، این ممکن است چند لحظه طول بکشد',
                'title' => 'نصب',
            ],

            'installation-completed' => [
                'admin-panel' => 'پنل مدیریت',
                'bagisto-forums' => 'انجمن Bagisto',
                'customer-panel' => 'پنل مشتریان',
                'explore-bagisto-extensions' => 'کاوش در افزونه‌های Bagisto',
                'title' => 'نصب با موفقیت انجام شد',
                'title-info' => 'Bagisto با موفقیت بر روی سیستم شما نصب شده است.',
            ],

            'ready-for-installation' => [
                'create-database-table' => 'ایجاد جدول پایگاه‌داده',
                'install' => 'نصب',
                'install-info' => 'Bagisto برای نصب',
                'install-info-button' => 'برای شروع کلیک کنید',
                'populate-database-table' => 'پر کردن جدول‌های پایگاه‌داده',
                'start-installation' => 'شروع نصب',
                'title' => 'آماده نصب',
            ],

            'start' => [
                'locale' => 'محلی',
                'main' => 'شروع',
                'select-locale' => 'انتخاب محلی',
                'title' => 'نصب Bagisto شما',
                'welcome-title' => 'خوش آمدید به Bagisto',
            ],

            'server-requirements' => [
                'calendar' => 'تقویم',
                'ctype' => 'cType',
                'curl' => 'cURL',
                'dom' => 'DOM',
                'fileinfo' => 'Fileinfo',
                'filter' => 'فیلتر',
                'gd' => 'GD',
                'hash' => 'هش',
                'intl' => 'Intl',
                'json' => 'JSON',
                'mbstring' => 'متن چندبایتی',
                'openssl' => 'OpenSSL',
                'pcre' => 'pcre',
                'pdo' => 'pdo',
                'php' => 'PHP',
                'php-version' => '8.1 یا بالاتر',
                'session' => 'نشست',
                'title' => 'نیازمندی‌های سرور',
                'tokenizer' => 'Tokenizer',
                'xml' => 'XML',
            ],

            'arabic' => 'عربی',
            'back' => 'بازگشت',
            'bagisto' => 'Bagisto',
            'bagisto-info' => 'یک پروژه جامعه‌ای توسط',
            'bagisto-logo' => 'لوگوی Bagisto',
            'bengali' => 'بنگالی',
            'catalan' => 'کاتالان',
            'chinese' => 'چینی',
            'continue' => 'ادامه',
            'dutch' => 'هلندی',
            'english' => 'انگلیسی',
            'french' => 'فرانسوی',
            'german' => 'آلمانی',
            'hebrew' => 'عبری',
            'hindi' => 'هندی',
            'indonesian' => 'اندونزیایی',
            'installation-description' => 'نصب Bagisto معمولاً شامل چندین مرحله است. در اینجا یک نمای کلی از فرآیند نصب برای Bagisto آورده شده است',
            'installation-info' => 'خوشحالیم که شما را اینجا می‌بینیم!',
            'installation-title' => 'به نصب Bagisto خوش آمدید',
            'italian' => 'ایتالیایی',
            'japanese' => 'ژاپنی',
            'persian' => 'فارسی',
            'polish' => 'لهستانی',
            'portuguese' => 'پرتغالی برزیلی',
            'russian' => 'روسی',
            'sinhala' => 'سینهالا',
            'spanish' => 'اسپانیایی',
            'title' => 'نصب‌کننده Bagisto',
            'turkish' => 'ترکی',
            'ukrainian' => 'اوکراینی',
            'webkul' => 'Webkul',
        ],
    ],
];
