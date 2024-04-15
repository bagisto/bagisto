<?php

return [
    'seeders' => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'الافتراضي',
            ],

            'attribute-groups' => [
                'description'      => 'الوصف',
                'general'          => 'عام',
                'inventories'      => 'المخزونات',
                'meta-description' => 'الوصف الواجب',
                'price'            => 'السعر',
                'settings'         => 'الإعدادات',
                'shipping'         => 'الشحن',
            ],

            'attributes' => [
                'brand'                => 'العلامة التجارية',
                'color'                => 'اللون',
                'cost'                 => 'التكلفة',
                'description'          => 'الوصف',
                'featured'             => 'مميز',
                'guest-checkout'       => 'الدفع كضيف',
                'height'               => 'الارتفاع',
                'length'               => 'الطول',
                'manage-stock'         => 'إدارة المخزون',
                'meta-description'     => 'الوصف الواجب',
                'meta-keywords'        => 'الكلمات الرئيسية الواجبة',
                'meta-title'           => 'العنوان الواجب',
                'name'                 => 'الاسم',
                'new'                  => 'جديد',
                'price'                => 'السعر',
                'product-number'       => 'رقم المنتج',
                'short-description'    => 'وصف مختصر',
                'size'                 => 'الحجم',
                'sku'                  => 'رمز المنتج',
                'special-price'        => 'السعر الخاص',
                'special-price-from'   => 'السعر الخاص من',
                'special-price-to'     => 'السعر الخاص حتى',
                'status'               => 'الحالة',
                'tax-category'         => 'فئة الضريبة',
                'url-key'              => 'الرابط المميز',
                'visible-individually' => 'مرئي بشكل فردي',
                'weight'               => 'الوزن',
                'width'                => 'العرض',
            ],

            'attribute-options' => [
                'black'  => 'أسود',
                'green'  => 'أخضر',
                'l'      => 'كبير',
                'm'      => 'وسط',
                'red'    => 'أحمر',
                's'      => 'صغير',
                'white'  => 'أبيض',
                'xl'     => 'كبير جداً',
                'yellow' => 'أصفر',
            ],
        ],

        'category' => [
            'categories' => [
                'description' => 'وصف الفئة الرئيسية',
                'name'        => 'الرئيسية',
            ],
        ],

        'cms' => [
            'pages' => [
                'about-us' => [
                    'content' => 'محتوى صفحة من نحن',
                    'title'   => 'من نحن',
                ],

                'contact-us' => [
                    'content' => 'محتوى صفحة اتصل بنا',
                    'title'   => 'اتصل بنا',
                ],

                'customer-service' => [
                    'content' => 'محتوى صفحة خدمة العملاء',
                    'title'   => 'خدمة العملاء',
                ],

                'payment-policy'   => [
                    'content' => 'محتوى صفحة سياسة الدفع',
                    'title'   => 'سياسة الدفع',
                ],

                'privacy-policy' => [
                    'content' => 'محتوى صفحة سياسة الخصوصية',
                    'title'   => 'سياسة الخصوصية',
                ],

                'refund-policy' => [
                    'content' => 'محتوى صفحة سياسة الاسترداد',
                    'title'   => 'سياسة الاسترداد',
                ],

                'return-policy' => [
                    'content' => 'محتوى صفحة سياسة الإرجاع',
                    'title'   => 'سياسة الإرجاع',
                ],

                'shipping-policy' => [
                    'content' => 'محتوى صفحة سياسة الشحن',
                    'title'   => 'سياسة الشحن',
                ],

                'terms-conditions' => [
                    'content' => 'محتوى صفحة الشروط والأحكام',
                    'title'   => 'الشروط والأحكام',
                ],

                'terms-of-use' => [
                    'content' => 'محتوى صفحة شروط الاستخدام',
                    'title'   => 'شروط الاستخدام',
                ],

                'whats-new' => [
                    'content' => 'محتوى صفحة ما الجديد',
                    'title'   => 'ما الجديد',
                ],
            ],
        ],

        'core' => [
            'channels' => [
                'meta-description' => 'وصف متجر تجريبي',
                'meta-keywords'    => 'الكلمات الرئيسية للمتجر التجريبي',
                'meta-title'       => 'متجر تجريبي',
                'name'             => 'افتراضي',
            ],

            'currencies' => [
                'AED' => 'الدرهم',
                'AFN' => 'الشيكل الإسرائيلي',
                'CNY' => 'اليوان الصيني',
                'EUR' => 'اليورو',
                'GBP' => 'الجنيه الاسترليني',
                'INR' => 'الروبية الهندية',
                'IRR' => 'الريال الإيراني',
                'JPY' => 'الين الياباني',
                'RUB' => 'الروبل الروسي',
                'SAR' => 'الريال السعودي',
                'TRY' => 'الليرة التركية',
                'UAH' => 'الهريفنا الأوكرانية',
                'USD' => 'الدولار الأمريكي',
            ],

            'locales' => [
                'ar'    => 'العربية',
                'bn'    => 'البنغالية',
                'de'    => 'الألمانية',
                'en'    => 'الإنجليزية',
                'es'    => 'الإسبانية',
                'fa'    => 'الفارسية',
                'fr'    => 'الفرنسية',
                'he'    => 'العبرية',
                'hi_IN' => 'الهندية',
                'it'    => 'الإيطالية',
                'ja'    => 'اليابانية',
                'nl'    => 'الهولندية',
                'pl'    => 'البولندية',
                'pt_BR' => 'البرتغالية البرازيلية',
                'ru'    => 'الروسية',
                'sin'   => 'السينهالية',
                'tr'    => 'التركية',
                'uk'    => 'الأوكرانية',
                'zh_CN' => 'الصينية',
            ],
        ],

        'customer' => [
            'customer-groups' => [
                'general'   => 'عام',
                'guest'     => 'زائر',
                'wholesale' => 'جملة',
            ],
        ],

        'inventory' => [
            'inventory-sources' => [
                'name' => 'افتراضي',
            ],
        ],

        'shop'      => [
            'theme-customizations' => [
                'all-products' => [
                    'name' => 'جميع المنتجات',

                    'options' => [
                        'title' => 'جميع المنتجات',
                    ],
                ],

                'bold-collections' => [
                    'content' => [
                        'btn-title'   => 'عرض الكل',
                        'description' => 'نقدم لك مجموعاتنا البارزة الجديدة! قم بتحسين أناقتك مع تصاميم جريئة وعبارات حيوية. استكشف أنماطًا بارزة وألوانًا جريئة تعيد تعريف خزانتك. استعد لاعتناق الاستثنائية!',
                        'title'       => 'استعد لمجموعاتنا البارزة الجديدة!',
                    ],

                    'name' => 'مجموعات بارزة',
                ],

                'categories-collections' => [
                    'name' => 'تصنيفات المجموعات',
                ],

                'featured-collections' => [
                    'name' => 'مجموعات مميزة',

                    'options' => [
                        'title' => 'منتجات مميزة',
                    ],
                ],

                'footer-links' => [
                    'name' => 'روابط الذيل',

                    'options' => [
                        'about-us'         => 'معلومات عنا',
                        'contact-us'       => 'اتصل بنا',
                        'customer-service' => 'خدمة العملاء',
                        'payment-policy'   => 'سياسة الدفع',
                        'privacy-policy'   => 'سياسة الخصوصية',
                        'refund-policy'    => 'سياسة الاسترداد',
                        'return-policy'    => 'سياسة الإرجاع',
                        'shipping-policy'  => 'سياسة الشحن',
                        'terms-conditions' => 'الشروط والأحكام',
                        'terms-of-use'     => 'شروط الاستخدام',
                        'whats-new'        => 'ما الجديد',
                    ],
                ],

                'game-container' => [
                    'content' => [
                        'sub-title-1' => 'مجموعاتنا',
                        'sub-title-2' => 'مجموعاتنا',
                        'title'       => 'اللعبة مع إضافاتنا الجديدة!',
                    ],

                    'name' => 'حاوية اللعبة',
                ],

                'image-carousel' => [
                    'name' => 'عرض الصور',

                    'sliders' => [
                        'title' => 'استعد للمجموعة الجديدة',
                    ],
                ],

                'new-products' => [
                    'name'    => 'منتجات جديدة',

                    'options' => [
                        'title' => 'منتجات جديدة',
                    ],
                ],

                'offer-information' => [
                    'content' => [
                        'title' => 'احصل على خصم يصل إلى 40% على طلبك الأول. تسوق الآن',
                    ],

                    'name' => 'معلومات العرض',
                ],

                'services-content' => [
                    'description' => [
                        'emi-available-info'   => 'توفر EMI بدون تكلفة على جميع بطاقات الائتمان الرئيسية',
                        'free-shipping-info'   => 'استمتع بالشحن المجاني على جميع الطلبات',
                        'product-replace-info' => 'استبدال المنتج بسهولة متاح!',
                        'time-support-info'    => 'دعم مخصص على مدار الساعة عبر الدردشة والبريد الإلكتروني',
                    ],

                    'name' => 'محتوى الخدمات',

                    'title' => [
                        'emi-available'   => 'توفر EMI',
                        'free-shipping'   => 'الشحن المجاني',
                        'product-replace' => 'استبدال المنتج',
                        'time-support'    => 'الدعم على مدار الساعة',
                    ],
                ],

                'top-collections' => [
                    'content' => [
                        'sub-title-1' => 'مجموعاتنا',
                        'sub-title-2' => 'مجموعاتنا',
                        'sub-title-3' => 'مجموعاتنا',
                        'sub-title-4' => 'مجموعاتنا',
                        'sub-title-5' => 'مجموعاتنا',
                        'sub-title-6' => 'مجموعاتنا',
                        'title'       => 'اللعبة مع إضافاتنا الجديدة!',
                    ],

                    'name' => 'أفضل المجموعات',
                ],
            ],
        ],

        'user' => [
            'roles' => [
                'description' => 'سيكون لدى مستخدمي هذا الدور وصولًا كاملاً',
                'name'        => 'مدير',
            ],

            'users' => [
                'name' => 'مثال',
            ],
        ],
    ],

    'installer' => [
        'index' => [
            'create-administrator' => [
                'admin'            => 'المشرف',
                'bagisto'          => 'Bagisto',
                'confirm-password' => 'تأكيد كلمة المرور',
                'email'            => 'البريد الإلكتروني',
                'email-address'    => 'admin@example.com',
                'password'         => 'كلمة المرور',
                'title'            => 'إنشاء المسؤول',
            ],

            'environment-configuration' => [
                'allowed-currencies'  => 'العملات المسموح بها',
                'allowed-locales'     => 'اللغات المسموح بها',
                'application-name'    => 'اسم التطبيق',
                'bagisto'             => 'Bagisto',
                'chinese-yuan'        => 'اليوان الصيني (CNY)',
                'database-connection' => 'اتصال قاعدة البيانات',
                'database-hostname'   => 'اسم الخادم لقاعدة البيانات',
                'database-name'       => 'اسم قاعدة البيانات',
                'database-password'   => 'كلمة مرور قاعدة البيانات',
                'database-port'       => 'منفذ قاعدة البيانات',
                'database-prefix'     => 'بادئة قاعدة البيانات',
                'database-username'   => 'اسم مستخدم قاعدة البيانات',
                'default-currency'    => 'العملة الافتراضية',
                'default-locale'      => 'اللغة الافتراضية',
                'default-timezone'    => 'المنطقة الزمنية الافتراضية',
                'default-url'         => 'الرابط الافتراضي',
                'default-url-link'    => 'https://localhost',
                'dirham'              => 'الدرهم (AED)',
                'euro'                => 'اليورو (EUR)',
                'iranian'             => 'ريال إيراني (IRR)',
                'israeli'             => 'شيقل إسرائيلي (AFN)',
                'japanese-yen'        => 'ين ياباني (JPY)',
                'mysql'               => 'MySQL',
                'pgsql'               => 'pgSQL',
                'pound'               => 'جنيه إسترليني (GBP)',
                'rupee'               => 'روبية هندية (INR)',
                'russian-ruble'       => 'روبل روسي (RUB)',
                'saudi'               => 'ريال سعودي (SAR)',
                'select-timezone'     => 'اختر المجال الزمني',
                'sqlsrv'              => 'SQLSRV',
                'title'               => 'تكوين البيئة',
                'turkish-lira'        => 'ليرة تركية (TRY)',
                'ukrainian-hryvnia'   => 'هريفنيا أوكرانية (UAH)',
                'usd'                 => 'دولار أمريكي (USD)',
                'warning-message'     => 'تنبيه! إعدادات لغات النظام الافتراضية والعملة الافتراضية دائمة ولا يمكن تغييرها مرة أخرى أبدًا.',
            ],

            'installation-processing' => [
                'bagisto'          => 'تثبيت Bagisto',
                'bagisto-info'     => 'إنشاء جداول قاعدة البيانات، وقد يستغرق ذلك بضع دقائق',
                'title'            => 'التثبيت',
            ],

            'installation-completed' => [
                'admin-panel'                => 'لوحة المشرف',
                'bagisto-forums'             => 'منتديات Bagisto',
                'customer-panel'             => 'لوحة العميل',
                'explore-bagisto-extensions' => 'استكشاف امتدادات Bagisto',
                'title'                      => 'اكتمال التثبيت',
                'title-info'                 => 'تم تثبيت Bagisto بنجاح على نظامك.',
            ],

            'ready-for-installation' => [
                'create-databsae-table'   => 'إنشاء جدول قاعدة البيانات',
                'install'                 => 'التثبيت',
                'install-info'            => 'Bagisto للتثبيت',
                'install-info-button'     => 'انقر على الزر أدناه ل',
                'populate-database-table' => 'ملء جداول قاعدة البيانات',
                'start-installation'      => 'بدء التثبيت',
                'title'                   => 'جاهز للتثبيت',
            ],

            'start' => [
                'locale'        => 'اللغة',
                'main'          => 'بداية',
                'select-locale' => 'اختر اللغة',
                'title'         => 'تثبيت Bagisto الخاص بك',
                'welcome-title' => 'مرحبًا بك في Bagisto 2.0.',
            ],

            'server-requirements' => [
                'calendar'    => 'التقويم',
                'ctype'       => 'cType',
                'curl'        => 'cURL',
                'dom'         => 'dom',
                'fileinfo'    => 'معلومات الملف',
                'filter'      => 'المرشح',
                'gd'          => 'GD',
                'hash'        => 'التجزئة',
                'intl'        => 'intl',
                'json'        => 'JSON',
                'mbstring'    => 'mbstring',
                'openssl'     => 'openssl',
                'pcre'        => 'pcre',
                'pdo'         => 'pdo',
                'php'         => 'PHP',
                'php-version' => '8.1 أو أعلى',
                'session'     => 'الجلسة',
                'title'       => 'متطلبات الخادم',
                'tokenizer'   => 'tokenizer',
                'xml'         => 'XML',
            ],

            'arabic'                   => 'العربية',
            'back'                     => 'رجوع',
            'bagisto'                  => 'Bagisto',
            'bagisto-info'             => 'مشروع مجتمعي من قبل',
            'bagisto-logo'             => 'شعار Bagisto',
            'bengali'                  => 'البنغالية',
            'chinese'                  => 'الصينية',
            'continue'                 => 'متابعة',
            'dutch'                    => 'هولندي',
            'english'                  => 'الإنجليزية',
            'french'                   => 'الفرنسية',
            'german'                   => 'ألماني',
            'hebrew'                   => 'العبرية',
            'hindi'                    => 'الهندية',
            'installation-description' => 'تتضمن عملية تثبيت Bagisto عادة عدة خطوات. إليك مخطط عام لعملية التثبيت لـ Bagisto:',
            'installation-info'        => 'نحن سعداء برؤيتك هنا!',
            'installation-title'       => 'مرحبًا بك في التثبيت',
            'italian'                  => 'الإيطالية',
            'japanese'                 => 'اليابانية',
            'persian'                  => 'الفارسية',
            'polish'                   => 'البولندية',
            'portuguese'               => 'البرتغالية البرازيلية',
            'russian'                  => 'الروسية',
            'save-configuration'       => 'حفظ الإعدادات',
            'sinhala'                  => 'السنهالية',
            'skip'                     => 'تخطى',
            'spanish'                  => 'الإسبانية',
            'title'                    => 'مثبت Bagisto',
            'turkish'                  => 'التركية',
            'ukrainian'                => 'الأوكرانية',
            'webkul'                   => 'Webkul',
        ],
    ],
];
