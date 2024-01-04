<?php

return [
    'seeders' => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'الافتراضي',
            ],

            'attribute-groups' => [
                'description'       => 'الوصف',
                'general'           => 'عام',
                'inventories'       => 'المخزونات',
                'meta-description'  => 'الوصف الواجب',
                'price'             => 'السعر',
                'shipping'          => 'الشحن',
                'settings'          => 'الإعدادات',
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
                'meta-title'           => 'العنوان الواجب',
                'meta-keywords'        => 'الكلمات الرئيسية الواجبة',
                'meta-description'     => 'الوصف الواجب',
                'manage-stock'         => 'إدارة المخزون',
                'new'                  => 'جديد',
                'name'                 => 'الاسم',
                'product-number'       => 'رقم المنتج',
                'price'                => 'السعر',
                'sku'                  => 'رمز المنتج',
                'status'               => 'الحالة',
                'short-description'    => 'وصف مختصر',
                'special-price'        => 'السعر الخاص',
                'special-price-from'   => 'السعر الخاص من',
                'special-price-to'     => 'السعر الخاص حتى',
                'size'                 => 'الحجم',
                'tax-category'         => 'فئة الضريبة',
                'url-key'              => 'الرابط المميز',
                'visible-individually' => 'مرئي بشكل فردي',
                'width'                => 'العرض',
                'weight'               => 'الوزن',
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

                'refund-policy' => [
                    'content' => 'محتوى صفحة سياسة الاسترداد',
                    'title'   => 'سياسة الاسترداد',
                ],

                'return-policy' => [
                    'content' => 'محتوى صفحة سياسة الإرجاع',
                    'title'   => 'سياسة الإرجاع',
                ],

                'terms-conditions' => [
                    'content' => 'محتوى صفحة الشروط والأحكام',
                    'title'   => 'الشروط والأحكام',
                ],

                'terms-of-use' => [
                    'content' => 'محتوى صفحة شروط الاستخدام',
                    'title'   => 'شروط الاستخدام',
                ],

                'contact-us' => [
                    'content' => 'محتوى صفحة اتصل بنا',
                    'title'   => 'اتصل بنا',
                ],

                'customer-service' => [
                    'content' => 'محتوى صفحة خدمة العملاء',
                    'title'   => 'خدمة العملاء',
                ],

                'whats-new' => [
                    'content' => 'محتوى صفحة ما الجديد',
                    'title'   => 'ما الجديد',
                ],

                'payment-policy' => [
                    'content' => 'محتوى صفحة سياسة الدفع',
                    'title'   => 'سياسة الدفع',
                ],

                'shipping-policy' => [
                    'content' => 'محتوى صفحة سياسة الشحن',
                    'title'   => 'سياسة الشحن',
                ],

                'privacy-policy' => [
                    'content' => 'محتوى صفحة سياسة الخصوصية',
                    'title'   => 'سياسة الخصوصية',
                ],
            ],
        ],

        'core' => [
            'channels' => [
                'meta-title'       => 'متجر تجريبي',
                'meta-keywords'    => 'الكلمات الرئيسية للمتجر التجريبي',
                'meta-description' => 'وصف متجر تجريبي',
                'name'             => 'افتراضي',
            ],

            'currencies' => [
                'CNY' => 'اليوان الصيني',
                'AED' => 'الدرهم',
                'EUR' => 'اليورو',
                'INR' => 'الروبية الهندية',
                'IRR' => 'الريال الإيراني',
                'AFN' => 'الشيكل الإسرائيلي',
                'JPY' => 'الين الياباني',
                'GBP' => 'الجنيه الاسترليني',
                'RUB' => 'الروبل الروسي',
                'SAR' => 'الريال السعودي',
                'TRY' => 'الليرة التركية',
                'USD' => 'الدولار الأمريكي',
                'UAH' => 'الهريفنا الأوكرانية',
            ],

            'locales' => [
                'ar'    => 'العربية',
                'bn'    => 'البنغالية',
                'de'    => 'الألمانية',
                'es'    => 'الإسبانية',
                'en'    => 'الإنجليزية',
                'fr'    => 'الفرنسية',
                'fa'    => 'الفارسية',
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
                'guest'     => 'زائر',
                'general'   => 'عام',
                'wholesale' => 'جملة',
            ],
        ],

        'inventory' => [
            'inventory-sources' => [
                'name' => 'افتراضي',
            ],
        ],

        'shop' => [
            'theme-customizations' => [
                'image-carousel' => [
                    'name' => 'عرض الصور',

                    'sliders' => [
                        'title' => 'استعد للمجموعة الجديدة',
                    ],
                ],

                'offer-information' => [
                    'name' => 'معلومات العرض',

                    'content' => [
                        'title' => 'احصل على خصم يصل إلى 40% على طلبك الأول. تسوق الآن',
                    ],
                ],

                'categories-collections' => [
                    'name' => 'تصنيفات المجموعات',
                ],

                'new-products' => [
                    'name' => 'منتجات جديدة',

                    'options' => [
                        'title' => 'منتجات جديدة',
                    ],
                ],

                'top-collections' => [
                    'name' => 'أفضل المجموعات',

                    'content' => [
                        'sub-title-1' => 'مجموعاتنا',
                        'sub-title-2' => 'مجموعاتنا',
                        'sub-title-3' => 'مجموعاتنا',
                        'sub-title-4' => 'مجموعاتنا',
                        'sub-title-5' => 'مجموعاتنا',
                        'sub-title-6' => 'مجموعاتنا',
                        'title'       => 'اللعبة مع إضافاتنا الجديدة!',
                    ],
                ],

                'bold-collections' => [
                    'name' => 'مجموعات بارزة',

                    'content' => [
                        'btn-title'   => 'عرض الكل',
                        'description' => 'نقدم لك مجموعاتنا البارزة الجديدة! قم بتحسين أناقتك مع تصاميم جريئة وعبارات حيوية. استكشف أنماطًا بارزة وألوانًا جريئة تعيد تعريف خزانتك. استعد لاعتناق الاستثنائية!',
                        'title'       => 'استعد لمجموعاتنا البارزة الجديدة!',
                    ],
                ],

                'featured-collections' => [
                    'name' => 'مجموعات مميزة',

                    'options' => [
                        'title' => 'منتجات مميزة',
                    ],
                ],

                'game-container' => [
                    'name' => 'حاوية اللعبة',

                    'content' => [
                        'sub-title-1' => 'مجموعاتنا',
                        'sub-title-2' => 'مجموعاتنا',
                        'title'       => 'اللعبة مع إضافاتنا الجديدة!',
                    ],
                ],

                'all-products' => [
                    'name' => 'جميع المنتجات',

                    'options' => [
                        'title' => 'جميع المنتجات',
                    ],
                ],

                'footer-links' => [
                    'name' => 'روابط الذيل',

                    'options' => [
                        'about-us'         => 'معلومات عنا',
                        'contact-us'       => 'اتصل بنا',
                        'customer-service' => 'خدمة العملاء',
                        'privacy-policy'   => 'سياسة الخصوصية',
                        'payment-policy'   => 'سياسة الدفع',
                        'return-policy'    => 'سياسة الإرجاع',
                        'refund-policy'    => 'سياسة الاسترداد',
                        'shipping-policy'  => 'سياسة الشحن',
                        'terms-of-use'     => 'شروط الاستخدام',
                        'terms-conditions' => 'الشروط والأحكام',
                        'whats-new'        => 'ما الجديد',
                    ],
                ],

                'services-content' => [
                    'name'  => 'محتوى الخدمات',

                    'title' => [
                        'free-shipping'   => 'الشحن المجاني',
                        'product-replace' => 'استبدال المنتج',
                        'emi-available'   => 'توفر EMI',
                        'time-support'    => 'الدعم على مدار الساعة',
                    ],

                    'description' => [
                        'free-shipping-info'   => 'استمتع بالشحن المجاني على جميع الطلبات',
                        'product-replace-info' => 'استبدال المنتج بسهولة متاح!',
                        'emi-available-info'   => 'توفر EMI بدون تكلفة على جميع بطاقات الائتمان الرئيسية',
                        'time-support-info'    => 'دعم مخصص على مدار الساعة عبر الدردشة والبريد الإلكتروني',
                    ],
                ],
            ],
        ],

        'user' => [
            'users' => [
                'name' => 'مثال',
            ],

            'roles' => [
                'description' => 'سيكون لدى مستخدمي هذا الدور وصولًا كاملاً',
                'name'        => 'مدير',
            ],
        ],
    ],

    'installer' => [
        'index' => [
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
                'php'         => 'PHP',
                'php-version' => '8.1 أو أعلى',
                'pcre'        => 'pcre',
                'pdo'         => 'pdo',
                'session'     => 'الجلسة',
                'title'       => 'متطلبات الخادم',
                'tokenizer'   => 'tokenizer',
                'xml'         => 'XML',
            ],

            'environment-configuration' => [
                'allowed-locales'     => 'اللغات المسموح بها',
                'allowed-currencies'  => 'العملات المسموح بها',
                'application-name'    => 'اسم التطبيق',
                'bagisto'             => 'Bagisto',
                'chinese-yuan'        => 'اليوان الصيني (CNY)',
                'dirham'              => 'الدرهم (AED)',
                'default-url'         => 'الرابط الافتراضي',
                'default-url-link'    => 'https://localhost',
                'default-currency'    => 'العملة الافتراضية',
                'default-timezone'    => 'المنطقة الزمنية الافتراضية',
                'default-locale'      => 'اللغة الافتراضية',
                'database-connection' => 'اتصال قاعدة البيانات',
                'database-hostname'   => 'اسم الخادم لقاعدة البيانات',
                'database-port'       => 'منفذ قاعدة البيانات',
                'database-name'       => 'اسم قاعدة البيانات',
                'database-username'   => 'اسم مستخدم قاعدة البيانات',
                'database-prefix'     => 'بادئة قاعدة البيانات',
                'database-password'   => 'كلمة مرور قاعدة البيانات',
                'euro'                => 'اليورو (EUR)',
                'iranian'             => 'ريال إيراني (IRR)',
                'israeli'             => 'شيقل إسرائيلي (AFN)',
                'japanese-yen'        => 'ين ياباني (JPY)',
                'mysql'               => 'MySQL',
                'pgsql'               => 'pgSQL',
                'pound'               => 'جنيه إسترليني (GBP)',
                'rupee'               => 'روبية هندية (INR)',
                'russian-ruble'       => 'روبل روسي (RUB)',
                'sqlsrv'              => 'SQLSRV',
                'saudi'               => 'ريال سعودي (SAR)',
                'title'               => 'تكوين البيئة',
                'turkish-lira'        => 'ليرة تركية (TRY)',
                'usd'                 => 'دولار أمريكي (USD)',
                'ukrainian-hryvnia'   => 'هريفنيا أوكرانية (UAH)',
                'warning-message'     => 'تنبيه! إعدادات لغات النظام الافتراضية والعملة الافتراضية دائمة ولا يمكن تغييرها مرة أخرى أبدًا.',
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

            'installation-processing' => [
                'bagisto'          => 'تثبيت Bagisto',
                'bagisto-info'     => 'إنشاء جداول قاعدة البيانات، وقد يستغرق ذلك بضع دقائق',
                'title'            => 'التثبيت',
            ],

            'create-administrator' => [
                'admin'            => 'المشرف',
                'bagisto'          => 'Bagisto',
                'confirm-password' => 'تأكيد كلمة المرور',
                'email'            => 'البريد الإلكتروني',
                'email-address'    => 'admin@example.com',
                'password'         => 'كلمة المرور',
                'title'            => 'إنشاء المسؤول',
            ],

            'installation-completed' => [
                'admin-panel'                => 'لوحة المشرف',
                'bagisto-forums'             => 'منتديات Bagisto',
                'customer-panel'             => 'لوحة العميل',
                'explore-bagisto-extensions' => 'استكشاف امتدادات Bagisto',
                'title'                      => 'اكتمال التثبيت',
                'title-info'                 => 'تم تثبيت Bagisto بنجاح على نظامك.',
            ],

            'arabic'                   => 'العربية',
            'bengali'                  => 'البنغالية',
            'bagisto-logo'             => 'شعار Bagisto',
            'back'                     => 'رجوع',
            'bagisto-info'             => 'مشروع مجتمعي من قبل',
            'bagisto'                  => 'Bagisto',
            'chinese'                  => 'الصينية',
            'continue'                 => 'متابعة',
            'dutch'                    => 'هولندي',
            'english'                  => 'الإنجليزية',
            'french'                   => 'الفرنسية',
            'german'                   => 'ألماني',
            'hebrew'                   => 'العبرية',
            'hindi'                    => 'الهندية',
            'installation-title'       => 'مرحبًا بك في التثبيت',
            'installation-info'        => 'نحن سعداء برؤيتك هنا!',
            'installation-description' => 'تتضمن عملية تثبيت Bagisto عادة عدة خطوات. إليك مخطط عام لعملية التثبيت لـ Bagisto:',
            'italian'                  => 'الإيطالية',
            'japanese'                 => 'اليابانية',
            'persian'                  => 'الفارسية',
            'polish'                   => 'البولندية',
            'portuguese'               => 'البرتغالية البرازيلية',
            'russian'                  => 'الروسية',
            'spanish'                  => 'الإسبانية',
            'sinhala'                  => 'السنهالية',
            'skip'                     => 'تخطى',
            'save-configuration'       => 'حفظ الإعدادات',
            'title'                    => 'مثبت Bagisto',
            'turkish'                  => 'التركية',
            'ukrainian'                => 'الأوكرانية',
            'webkul'                   => 'Webkul',
        ],
    ],
];
