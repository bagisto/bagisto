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
                'ILS' => 'الشيكل الإسرائيلي',
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
                'application-name'    => 'اسم التطبيق',
                'arabic'              => 'العربية',
                'bagisto'             => 'Bagisto',
                'bengali'             => 'البنغالية',
                'chinese-yuan'        => 'اليوان الصيني (CNY)',
                'chinese'             => 'الصينية',
                'dirham'              => 'الدرهم (AED)',
                'default-url'         => 'العنوان الافتراضي',
                'default-url-link'    => 'https://localhost',
                'default-currency'    => 'العملة الافتراضية',
                'default-timezone'    => 'المنطقة الزمنية الافتراضية',
                'default-locale'      => 'اللغة الافتراضية',
                'dutch'               => 'الهولندية',
                'database-connection' => 'اتصال قاعدة البيانات',
                'database-hostname'   => 'اسم مضيف قاعدة البيانات',
                'database-port'       => 'منفذ قاعدة البيانات',
                'database-name'       => 'اسم قاعدة البيانات',
                'database-username'   => 'اسم مستخدم قاعدة البيانات',
                'database-prefix'     => 'بادئة قاعدة البيانات',
                'database-password'   => 'كلمة مرور قاعدة البيانات',
                'euro'                => 'اليورو (EUR)',
                'english'             => 'الإنجليزية',
                'french'              => 'الفرنسية',
                'hebrew'              => 'العبرية',
                'hindi'               => 'الهندية',
                'iranian'             => 'الريال الإيراني (IRR)',
                'israeli'             => 'الشيكل الإسرائيلي (ILS)',
                'italian'             => 'الإيطالية',
                'japanese-yen'        => 'الين الياباني (JPY)',
                'japanese'            => 'اليابانية',
                'mysql'               => 'Mysql',
                'pgsql'               => 'pgSQL',
                'pound'               => 'الجنيه الإسترليني (GBP)',
                'persian'             => 'الفارسية',
                'polish'              => 'البولندية',
                'portuguese'          => 'البرتغالية البرازيلية',
                'rupee'               => 'الروبية الهندية (INR)',
                'russian-ruble'       => 'الروبل الروسي (RUB)',
                'russian'             => 'الروسية',
                'sqlsrv'              => 'SQLSRV',
                'saudi'               => 'الريال السعودي (SAR)',
                'spanish'             => 'الإسبانية',
                'sinhala'             => 'السينهالية',
                'title'               => 'تكوين البيئة',
                'turkish-lira'        => 'الليرة التركية (TRY)',
                'turkish'             => 'التركية',
                'usd'                 => 'الدولار الأمريكي (USD)',
                'ukrainian-hryvnia'   => 'الهريفنيا الأوكرانية (UAH)',
                'ukrainian'           => 'الأوكرانية',
            ],

            'ready-for-installation' => [
                'create-databsae-table'   => 'إنشاء جدول قاعدة البيانات',
                'install'                 => 'التثبيت',
                'install-info'            => 'Bagisto للتثبيت',
                'install-info-button'     => 'انقر على الزر أدناه لـ',
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

            'email-configuration' => [
                'encryption'           => 'التشفير',
                'enter-username'       => 'أدخل اسم المستخدم',
                'enter-password'       => 'أدخل كلمة المرور',
                'outgoing-mail-server' => 'خادم البريد الصادر',
                'outgoing-email'       => 'smpt.mailtrap.io',
                'password'             => 'كلمة المرور',
                'store-email'          => 'عنوان البريد الإلكتروني للمتجر',
                'enter-store-email'    => 'أدخل عنوان البريد الإلكتروني للمتجر',
                'server-port'          => 'منفذ الخادم',
                'server-port-code'     => '3306',
                'title'                => 'تكوين البريد الإلكتروني',
                'username'             => 'اسم المستخدم',
            ],

            'installation-completed' => [
                'admin-panel'                => 'لوحة المشرف',
                'bagisto-forums'             => 'منتديات Bagisto',
                'customer-panel'             => 'لوحة العميل',
                'explore-bagisto-extensions' => 'استكشاف امتدادات Bagisto',
                'title'                      => 'اكتمال التثبيت',
                'title-info'                 => 'تم تثبيت Bagisto بنجاح على نظامك.',
            ],

            'bagisto-logo'             => 'شعار Bagisto',
            'back'                     => 'عودة',
            'bagisto-info'             => 'مشروع مجتمعي من قبل',
            'bagisto'                  => 'Bagisto',
            'continue'                 => 'متابعة',
            'installation-title'       => 'مرحبًا بك في التثبيت',
            'installation-info'        => 'نحن سعداء برؤيتك هنا!',
            'installation-description' => 'تتضمن عملية تثبيت Bagisto عادة عدة خطوات. إليك مخطط عام لعملية التثبيت لـ Bagisto:',
            'skip'                     => 'تخطي',
            'save-configuration'       => 'حفظ التكوين',
            'title'                    => 'مثبت Bagisto',
            'webkul'                   => 'Webkul',
        ],
    ],
];
