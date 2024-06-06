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
    ],

    'installer' => [
        'index' => [
            'create-administrator' => [
                'admin'            => 'مدیر',
                'bagisto'          => 'Bagisto',
                'confirm-password' => 'تایید رمز عبور',
                'email'            => 'ایمیل',
                'email-address'    => 'admin@example.com',
                'password'         => 'رمز عبور',
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
                'zambian-kwach'               => 'کواچای زامبیا (ZMW)',
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
