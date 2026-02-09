<?php

return [
    'seeders' => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'الافتراضي',
            ],

            'attribute-groups' => [
                'description' => 'الوصف',
                'general' => 'عام',
                'inventories' => 'المخزونات',
                'meta-description' => 'الوصف الواجب',
                'price' => 'السعر',
                'rma' => 'RMA',
                'settings' => 'الإعدادات',
                'shipping' => 'الشحن',
            ],

            'attributes' => [
                'allow-rma' => 'السماح بالإرجاع',
                'brand' => 'العلامة التجارية',
                'color' => 'اللون',
                'cost' => 'التكلفة',
                'description' => 'الوصف',
                'featured' => 'مميز',
                'guest-checkout' => 'الدفع كضيف',
                'height' => 'الارتفاع',
                'length' => 'الطول',
                'manage-stock' => 'إدارة المخزون',
                'meta-description' => 'الوصف الواجب',
                'meta-keywords' => 'الكلمات الرئيسية الواجبة',
                'meta-title' => 'العنوان الواجب',
                'name' => 'الاسم',
                'new' => 'جديد',
                'price' => 'السعر',
                'product-number' => 'رقم المنتج',
                'rma-rules' => 'قواعد الإرجاع',
                'short-description' => 'وصف مختصر',
                'size' => 'الحجم',
                'sku' => 'رمز المنتج',
                'special-price' => 'السعر الخاص',
                'special-price-from' => 'السعر الخاص من',
                'special-price-to' => 'السعر الخاص حتى',
                'status' => 'الحالة',
                'tax-category' => 'فئة الضريبة',
                'url-key' => 'الرابط المميز',
                'visible-individually' => 'مرئي بشكل فردي',
                'weight' => 'الوزن',
                'width' => 'العرض',
            ],

            'attribute-options' => [
                'black' => 'أسود',
                'green' => 'أخضر',
                'l' => 'كبير',
                'm' => 'وسط',
                'red' => 'أحمر',
                's' => 'صغير',
                'white' => 'أبيض',
                'xl' => 'كبير جداً',
                'yellow' => 'أصفر',
            ],
        ],

        'category' => [
            'categories' => [
                'description' => 'وصف الفئة الرئيسية',
                'name' => 'الرئيسية',
            ],
        ],

        'cms' => [
            'pages' => [
                'about-us' => [
                    'content' => 'محتوى صفحة من نحن',
                    'title' => 'من نحن',
                ],

                'contact-us' => [
                    'content' => 'محتوى صفحة اتصل بنا',
                    'title' => 'اتصل بنا',
                ],

                'customer-service' => [
                    'content' => 'محتوى صفحة خدمة العملاء',
                    'title' => 'خدمة العملاء',
                ],

                'payment-policy' => [
                    'content' => 'محتوى صفحة سياسة الدفع',
                    'title' => 'سياسة الدفع',
                ],

                'privacy-policy' => [
                    'content' => 'محتوى صفحة سياسة الخصوصية',
                    'title' => 'سياسة الخصوصية',
                ],

                'refund-policy' => [
                    'content' => 'محتوى صفحة سياسة الاسترداد',
                    'title' => 'سياسة الاسترداد',
                ],

                'return-policy' => [
                    'content' => 'محتوى صفحة سياسة الإرجاع',
                    'title' => 'سياسة الإرجاع',
                ],

                'shipping-policy' => [
                    'content' => 'محتوى صفحة سياسة الشحن',
                    'title' => 'سياسة الشحن',
                ],

                'terms-conditions' => [
                    'content' => 'محتوى صفحة الشروط والأحكام',
                    'title' => 'الشروط والأحكام',
                ],

                'terms-of-use' => [
                    'content' => 'محتوى صفحة شروط الاستخدام',
                    'title' => 'شروط الاستخدام',
                ],

                'whats-new' => [
                    'content' => 'محتوى صفحة ما الجديد',
                    'title' => 'ما الجديد',
                ],
            ],
        ],

        'core' => [
            'channels' => [
                'meta-description' => 'وصف متجر تجريبي',
                'meta-keywords' => 'الكلمات الرئيسية للمتجر التجريبي',
                'meta-title' => 'متجر تجريبي',
                'name' => 'افتراضي',
            ],

            'currencies' => [
                'AED' => 'الدرهم الإماراتي',
                'ARS' => 'البيزو الأرجنتيني',
                'AUD' => 'الدولار الأسترالي',
                'BDT' => 'التاكا البنغلاديشي',
                'BHD' => 'دينار بحريني',
                'BRL' => 'الريال البرازيلي',
                'CAD' => 'الدولار الكندي',
                'CHF' => 'الفرنك السويسري',
                'CLP' => 'البيزو التشيلي',
                'CNY' => 'اليوان الصيني',
                'COP' => 'البيزو الكولومبي',
                'CZK' => 'الكرونة التشيكية',
                'DKK' => 'الكرونة الدنماركية',
                'DZD' => 'الدينار الجزائري',
                'EGP' => 'الجنيه المصري',
                'EUR' => 'اليورو',
                'FJD' => 'الدولار الفيجي',
                'GBP' => 'الجنيه الاسترليني',
                'HKD' => 'الدولار الهونغ كونغي',
                'HUF' => 'الفورنت المجري',
                'IDR' => 'الروبية الإندونيسية',
                'ILS' => 'الشيكل الإسرائيلي',
                'INR' => 'الروبية الهندية',
                'JOD' => 'الدينار الأردني',
                'JPY' => 'الين الياباني',
                'KRW' => 'الوون الكوري الجنوبي',
                'KWD' => 'الدينار الكويتي',
                'KZT' => 'التينغ الكازاخستاني',
                'LBP' => 'الليرة اللبنانية',
                'LKR' => 'الروبية السريلانكية',
                'LYD' => 'الدينار الليبي',
                'MAD' => 'الدرهم المغربي',
                'MUR' => 'الروبية الموريشية',
                'MXN' => 'البيزو المكسيكي',
                'MYR' => 'الرينغيت الماليزي',
                'NGN' => 'النايرا النيجيرية',
                'NOK' => 'الكرونة النرويجية',
                'NPR' => 'الروبية النيبالية',
                'NZD' => 'الدولار النيوزيلندي',
                'OMR' => 'الريال العماني',
                'PAB' => 'البالبوا البنمي',
                'PEN' => 'السول البيروفي',
                'PHP' => 'البيزو الفلبيني',
                'PKR' => 'الروبية الباكستانية',
                'PLN' => 'الزلوتي البولندي',
                'PYG' => 'الغواراني الباراغواياني',
                'QAR' => 'الريال القطري',
                'RON' => 'الليو الروماني',
                'RUB' => 'الروبل الروسي',
                'SAR' => 'الريال السعودي',
                'SEK' => 'الكرونة السويدية',
                'SGD' => 'الدولار السنغافوري',
                'THB' => 'الباخت التايلاندي',
                'TND' => 'الدينار التونسي',
                'TRY' => 'الليرة التركية',
                'TWD' => 'الدولار التايواني',
                'UAH' => 'الهريفنيا الأوكرانية',
                'USD' => 'الدولار الأمريكي',
                'UZS' => 'السوم الأوزبكستاني',
                'VEF' => 'البوليفار الفنزويلي',
                'VND' => 'الدونج الفيتنامي',
                'XAF' => 'فرنك وسط أفريقي',
                'XOF' => 'فرنك غرب أفريقي',
                'ZAR' => 'الراند الجنوب أفريقي',
                'ZMW' => 'الكواشا الزامبي',
            ],

            'locales' => [
                'ar' => 'العربية',
                'bn' => 'البنغالية',
                'ca' => 'الكتالونية',
                'de' => 'الألمانية',
                'en' => 'الإنجليزية',
                'es' => 'الإسبانية',
                'fa' => 'الفارسية',
                'fr' => 'الفرنسية',
                'he' => 'العبرية',
                'hi_IN' => 'الهندية',
                'id' => 'الإندونيسية',
                'it' => 'الإيطالية',
                'ja' => 'اليابانية',
                'nl' => 'الهولندية',
                'pl' => 'البولندية',
                'pt_BR' => 'البرتغالية البرازيلية',
                'ru' => 'الروسية',
                'sin' => 'السينهالية',
                'tr' => 'التركية',
                'uk' => 'الأوكرانية',
                'zh_CN' => 'الصينية',
            ],
        ],

        'customer' => [
            'customer-groups' => [
                'general' => 'عام',
                'guest' => 'زائر',
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
                'all-products' => [
                    'name' => 'جميع المنتجات',

                    'options' => [
                        'title' => 'مجموعة الأطفال',
                    ],
                ],

                'bold-collections' => [
                    'content' => [
                        'btn-title' => 'عرض المجموعات',
                        'description' => 'نقدم لك مجموعاتنا البارزة الجديدة! قم بتحسين أناقتك مع تصاميم جريئة وعبارات حيوية. استكشف أنماطًا بارزة وألوانًا جريئة تعيد تعريف خزانتك. استعد لاعتناق الاستثنائية!',
                        'title' => 'استعد لمجموعاتنا البارزة الجديدة!',
                    ],

                    'name' => 'مجموعات بارزة',
                ],

                'bold-collections-2' => [
                    'content' => [
                        'btn-title' => 'عرض المجموعات',
                        'description' => 'مجموعاتنا الجريئة موجودة هنا لإعادة تعريف خزانتك بتصميمات شجاعة وألوان نابضة بالحياة. من الأنماط الجريئة إلى الألوان القوية، هذه فرصتك للابتعاد عن المألوف والدخول إلى الاستثنائي.',
                        'title' => 'أطلق جرأتك مع مجموعتنا الجديدة!',
                    ],

                    'name' => 'مجموعات بارزة',
                ],

                'booking-products' => [
                    'name' => 'منتجات الحجز',

                    'options' => [
                        'title' => 'حجز التذاكر',
                    ],
                ],

                'categories-collections' => [
                    'name' => 'تصنيفات المجموعات',
                ],

                'featured-collections' => [
                    'name' => 'مجموعات مميزة',

                    'options' => [
                        'title' => 'مجموعات الرجال',
                    ],
                ],

                'footer-links' => [
                    'name' => 'روابط الذيل',

                    'options' => [
                        'about-us' => 'معلومات عنا',
                        'contact-us' => 'اتصل بنا',
                        'customer-service' => 'خدمة العملاء',
                        'payment-policy' => 'سياسة الدفع',
                        'privacy-policy' => 'سياسة الخصوصية',
                        'refund-policy' => 'سياسة الاسترداد',
                        'return-policy' => 'سياسة الإرجاع',
                        'shipping-policy' => 'سياسة الشحن',
                        'terms-conditions' => 'الشروط والأحكام',
                        'terms-of-use' => 'شروط الاستخدام',
                        'whats-new' => 'ما الجديد',
                    ],
                ],

                'game-container' => [
                    'content' => [
                        'sub-title-1' => 'مجموعاتنا',
                        'sub-title-2' => 'مجموعاتنا',
                        'title' => 'اللعبة مع إضافاتنا الجديدة!',
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
                    'name' => 'منتجات جديدة',

                    'options' => [
                        'title' => 'مجموعات النساء',
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
                        'emi-available-info' => 'توفر EMI بدون تكلفة على جميع بطاقات الائتمان الرئيسية',
                        'free-shipping-info' => 'استمتع بالشحن المجاني على جميع الطلبات',
                        'product-replace-info' => 'استبدال المنتج بسهولة متاح!',
                        'time-support-info' => 'دعم مخصص على مدار الساعة عبر الدردشة والبريد الإلكتروني',
                    ],

                    'name' => 'محتوى الخدمات',

                    'title' => [
                        'emi-available' => 'توفر EMI',
                        'free-shipping' => 'الشحن المجاني',
                        'product-replace' => 'استبدال المنتج',
                        'time-support' => 'الدعم على مدار الساعة',
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
                        'title' => 'اللعبة مع إضافاتنا الجديدة!',
                    ],

                    'name' => 'أفضل المجموعات',
                ],
            ],
        ],

        'user' => [
            'roles' => [
                'description' => 'سيكون لدى مستخدمي هذا الدور وصولًا كاملاً',
                'name' => 'مدير',
            ],

            'users' => [
                'name' => 'مثال',
            ],
        ],

        'sample-categories' => [
            'category-translation' => [
                '2' => [
                    'description' => '<p>رجال</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'رجال',
                    'slug' => 'رجال',
                    'url-path' => '',
                ],

                '3' => [
                    'description' => '<p>أطفال</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'أطفال',
                    'slug' => 'اطفال',
                    'url-path' => '',
                ],

                '4' => [
                    'description' => '<p>امرأة</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'امرأة',
                    'slug' => 'امراة',
                    'url-path' => '',
                ],

                '5' => [
                    'description' => '<p>ملابس رسمية</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'ملابس رسمية',
                    'slug' => 'ملابس رسمية للرجال',
                    'url-path' => '',
                ],

                '6' => [
                    'description' => '<p>لباس غير رسمي</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'لباس غير رسمي',
                    'slug' => 'ملابس كاجوال للرجال',
                    'url-path' => '',
                ],

                '7' => [
                    'description' => '<p>ارتداء نشط</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'ارتداء نشط',
                    'slug' => 'ارتداء-نشط',
                    'url-path' => '',
                ],

                '8' => [
                    'description' => '<p>ارتداء القدم</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'ارتداء القدم',
                    'slug' => 'ارتداء-القدم',
                    'url-path' => '',
                ],

                '9' => [
                    'description' => '<p>ملابس رسمية</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'ملابس رسمية',
                    'slug' => 'ملابس رسمية نسائية',
                    'url-path' => '',
                ],

                '10' => [
                    'description' => '<p>لباس غير رسمي</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'لباس غير رسمي',
                    'slug' => 'ملابس كاجوال نسائية',
                    'url-path' => '',
                ],

                '11' => [
                    'description' => '<p>ملابس نشطة</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'ملابس نشطة',
                    'slug' => 'ملابس نشطة أنثى',
                    'url-path' => '',
                ],

                '12' => [
                    'description' => '<p>ارتداء القدم</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'ارتداء القدم',
                    'slug' => 'ارتداء الأحذية-أنثى',
                    'url-path' => '',
                ],

                '13' => [
                    'description' => '<p>ملابس بنات</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'ملابس بنات',
                    'slug' => 'ملابس الفتيات',
                    'url-path' => '',
                ],

                '14' => [
                    'description' => '<p>ملابس الأولاد</p>',
                    'meta-description' => 'أزياء الأولاد',
                    'meta-keywords' => '',
                    'meta-title' => 'ملابس الأولاد',
                    'name' => 'ملابس الأولاد',
                    'slug' => 'ملابس-الاولاد',
                    'url-path' => '',
                ],

                '15' => [
                    'description' => '<p>أحذية بنات</p>',
                    'meta-description' => 'مجموعة أحذية الفتيات العصرية',
                    'meta-keywords' => '',
                    'meta-title' => 'أحذية بنات',
                    'name' => 'أحذية بنات',
                    'slug' => 'احذية-بنات',
                    'url-path' => '',
                ],

                '16' => [
                    'description' => '<p>أحذية الأولاد</p>',
                    'meta-description' => 'مجموعة الأحذية الأنيقة للأولاد',
                    'meta-keywords' => '',
                    'meta-title' => 'أحذية الأولاد',
                    'name' => 'أحذية الأولاد',
                    'slug' => 'احذية-الاولاد',
                    'url-path' => '',
                ],

                '17' => [
                    'description' => '<p>صحة</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'صحة',
                    'slug' => 'صحة',
                    'url-path' => '',
                ],

                '18' => [
                    'description' => '<p>دروس اليوغا للتحميل</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'دروس اليوغا للتحميل',
                    'slug' => 'دروس-اليوغا-للتحميل',
                    'url-path' => '',
                ],

                '19' => [
                    'description' => '<p>مجموعة كتب</p>',
                    'meta-description' => 'مجموعة كتب',
                    'meta-keywords' => '',
                    'meta-title' => 'مجموعة كتب',
                    'name' => 'الكتب الإلكترونية',
                    'slug' => 'الكتب-الالكترونية',
                    'url-path' => '',
                ],

                '20' => [
                    'description' => '<p>ممر الفيلم</p>',
                    'meta-description' => 'انغمس في سحر 10 أفلام كل شهر دون رسوم إضافية. صالحة في جميع أنحاء البلاد بدون تواريخ حجب، وتوفر هذه البطاقة امتيازات حصرية وخصومات امتياز، مما يجعلها ضرورية لعشاق الأفلام.',
                    'meta-keywords' => '',
                    'meta-title' => 'تذكرة مشاهدة الأفلام الشهرية من CineXperience',
                    'name' => 'ممر الفيلم',
                    'slug' => 'ممر-الفيلم',
                    'url-path' => '',
                ],

                '21' => [
                    'description' => '<p>Easily manage and sell your booking-based products with our seamless booking system. Whether you offer appointments, rentals, events, or reservations, our solution ensures a smooth experience for both businesses and customers. With real-time availability, flexible scheduling, and automated notifications, you can streamline your booking process effortlessly. Enhance customer convenience and maximize your sales with our powerful booking product solution!</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Bookings',
                    'slug' => 'bookings',
                    'url-path' => '',
                ],

                '22' => [
                    'description' => '<p>Appointment booking allows customers to schedule time slots for services or consultations with businesses or professionals. This system is commonly used in industries such as healthcare, beauty, education, and personal services. It helps streamline scheduling, reduce wait times, and improve customer satisfaction by offering convenient, time-based reservations.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Appointment Booking',
                    'slug' => 'appointment-booking',
                    'url-path' => '',
                ],

                '23' => [
                    'description' => '<p>Event booking allows individuals or groups to register or reserve spots for public or private events such as concerts, workshops, conferences, or parties. It typically includes options for selecting dates, seating types, and ticket categories, providing organizers with efficient attendee management and ensuring a smooth entry process.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Event Booking',
                    'slug' => 'event-booking',
                    'url-path' => '',
                ],

                '24' => [
                    'description' => '<p>Community hall booking enables individuals, organizations, or groups to reserve community spaces for various events such as weddings, meetings, cultural programs, or social gatherings. This system helps manage availability, schedule bookings, and handle logistics like capacity, amenities, and rental duration. It ensures efficient use of public or private halls while offering a convenient way for users to organize their events.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Community Hall Bookings',
                    'slug' => 'community-hall-bookings',
                    'url-path' => '',
                ],

                '25' => [
                    'description' => '<p>Table booking enables customers to reserve tables at restaurants, cafes, or dining venues in advance. It helps manage seating capacity, reduce wait times, and provide a better dining experience. This system is especially useful during peak hours, special events, or for accommodating large groups with specific preferences.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Table Booking',
                    'slug' => 'table-booking',
                    'url-path' => '',
                ],

                '26' => [
                    'description' => '<p>Rental booking facilitates the reservation of items or properties for temporary use, such as vehicles, equipment, vacation homes, or meeting spaces. It includes features for selecting rental periods, checking availability, and managing payments. This system supports both short-term and long-term rentals, enhancing convenience for both providers and renters.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Rental Booking',
                    'slug' => 'rental-booking',
                    'url-path' => '',
                ],

                '27' => [
                    'description' => '<p>Explore the latest in consumer electronics, designed to keep you connected, productive, and entertained. Whether you\'re upgrading your devices or looking for smart solutions, we have everything you need.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Electronics',
                    'slug' => 'electronics',
                    'url-path' => '',
                ],

                '28' => [
                    'description' => '<p>Discover smartphones, chargers, cases, and other essentials for staying connected on the go.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Mobile Phones & Accessories',
                    'slug' => 'mobile-phones-accessories',
                    'url-path' => '',
                ],

                '29' => [
                    'description' => '<p>Find powerful laptops and portable tablets for work, study, and play.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Laptops & Tablets',
                    'slug' => 'laptops-tablets',
                    'url-path' => '',
                ],

                '30' => [
                    'description' => '<p>Shop headphones, earbuds, and speakers to enjoy crystal-clear sound and immersive audio experiences.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Audio Devices',
                    'slug' => 'audio-devices',
                    'url-path' => '',
                ],

                '31' => [
                    'description' => '<p>Make life easier with smart lighting, thermostats, security systems, and more.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Smart Home & Automation',
                    'slug' => 'smart-home-automation',
                    'url-path' => '',
                ],

                '32' => [
                    'description' => '<p>Upgrade your living space with functional and stylish home and kitchen essentials. From cooking to cleaning, find products that enhance comfort and efficiency.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Household',
                    'slug' => 'household',
                    'url-path' => '',
                ],

                '33' => [
                    'description' => '<p>Browse blenders, air fryers, coffee makers, and more to simplify meal prep.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Kitchen Appliances',
                    'slug' => 'kitchen-appliances',
                    'url-path' => '',
                ],

                '34' => [
                    'description' => '<p>Explore cookware sets, utensils, dinnerware, and serveware for your culinary needs.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Cookware & Dining',
                    'slug' => 'cookware-dining',
                    'url-path' => '',
                ],

                '35' => [
                    'description' => '<p>Add comfort and charm with sofas, tables, wall art, and home accents.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Furniture & Decor',
                    'slug' => 'furniture-decor',
                    'url-path' => '',
                ],

                '36' => [
                    'description' => '<p>Keep your space spotless with vacuums, cleaning sprays, brooms, and organizers.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Cleaning Supplies',
                    'slug' => 'cleaning-supplies',
                    'url-path' => '',
                ],

                '37' => [
                    'description' => '<p>Ignite your imagination or organize your workspace with a wide selection of books and stationery. Perfect for readers, students, professionals, and artists.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Books & Stationery',
                    'slug' => 'books-stationery',
                    'url-path' => '',
                ],

                '38' => [
                    'description' => '<p>Dive into bestselling novels, biographies, self-help, and more.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Fiction & Non-Fiction Books',
                    'slug' => 'fiction-non-fiction-books',
                    'url-path' => '',
                ],

                '39' => [
                    'description' => '<p>Find textbooks, reference materials, and study guides for all ages.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Educational & Academic',
                    'slug' => 'educational-academic',
                    'url-path' => '',
                ],

                '40' => [
                    'description' => '<p>Shop pens, notebooks, planners, and office essentials for productivity.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Office Supplies',
                    'slug' => 'office-supplies',
                    'url-path' => '',
                ],

                '41' => [
                    'description' => '<p>Explore paints, brushes, sketchbooks, and DIY craft kits for creatives.</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'Art & Craft Materials',
                    'slug' => 'art-craft-materials',
                    'url-path' => '',
                ],
            ],
        ],
    ],

    'installer' => [
        'middleware' => [
            'already-installed' => 'التطبيق مثبت بالفعل.',
        ],

        'index' => [
            'create-administrator' => [
                'admin' => 'مدير',
                'bagisto' => 'Bagisto',
                'confirm-password' => 'تأكيد كلمة المرور',
                'email' => 'البريد الإلكتروني',
                'email-address' => 'admin@example.com',
                'password' => 'كلمة المرور',
                'title' => 'إنشاء المسؤول',
            ],

            'environment-configuration' => [
                'algerian-dinar' => 'الدينار الجزائري (DZD)',
                'allowed-currencies' => 'العملات المسموح بها',
                'allowed-locales' => 'اللغات المسموح بها',
                'application-name' => 'اسم التطبيق',
                'argentine-peso' => 'البيزو الأرجنتيني (ARS)',
                'australian-dollar' => 'الدولار الأسترالي (AUD)',
                'bagisto' => 'Bagisto',
                'bangladeshi-taka' => 'التاكا البنغلاديشي (BDT)',
                'bahraini-dinar' => 'دينار بحريني (BHD)',
                'brazilian-real' => 'الريال البرازيلي (BRL)',
                'british-pound-sterling' => 'الجنيه الإسترليني البريطاني (GBP)',
                'canadian-dollar' => 'الدولار الكندي (CAD)',
                'cfa-franc-bceao' => 'فرنك CFA BCEAO (XOF)',
                'cfa-franc-beac' => 'فرنك CFA BEAC (XAF)',
                'chilean-peso' => 'البيزو التشيلي (CLP)',
                'chinese-yuan' => 'اليوان الصيني (CNY)',
                'colombian-peso' => 'البيزو الكولومبي (COP)',
                'czech-koruna' => 'الكرونة التشيكية (CZK)',
                'danish-krone' => 'الكرونة الدنماركية (DKK)',
                'database-connection' => 'اتصال قاعدة البيانات',
                'database-hostname' => 'اسم مضيف قاعدة البيانات',
                'database-name' => 'اسم قاعدة البيانات',
                'database-password' => 'كلمة مرور قاعدة البيانات',
                'database-port' => 'منفذ قاعدة البيانات',
                'database-prefix' => 'بادئة قاعدة البيانات',
                'database-prefix-help' => 'يجب أن تكون البادئة بطول 4 أحرف ويمكن أن تحتوي فقط على أحرف وأرقام وشرطات سفلية.',
                'database-username' => 'اسم مستخدم قاعدة البيانات',
                'default-currency' => 'العملة الافتراضية',
                'default-locale' => 'اللغة الافتراضية',
                'default-timezone' => 'المنطقة الزمنية الافتراضية',
                'default-url' => 'الرابط الافتراضي',
                'default-url-link' => 'https://localhost',
                'egyptian-pound' => 'الجنيه المصري (EGP)',
                'euro' => 'اليورو (EUR)',
                'fijian-dollar' => 'الدولار الفيجي (FJD)',
                'hong-kong-dollar' => 'الدولار الهونغ كونغي (HKD)',
                'hungarian-forint' => 'الفورنت الهنغاري (HUF)',
                'indian-rupee' => 'الروبية الهندية (INR)',
                'indonesian-rupiah' => 'الروبية الإندونيسية (IDR)',
                'israeli-new-shekel' => 'الشيكل الإسرائيلي الجديد (ILS)',
                'japanese-yen' => 'الين الياباني (JPY)',
                'jordanian-dinar' => 'الدينار الأردني (JOD)',
                'kazakhstani-tenge' => 'التينغ الكازاخستاني (KZT)',
                'kuwaiti-dinar' => 'الدينار الكويتي (KWD)',
                'lebanese-pound' => 'الجنيه اللبناني (LBP)',
                'libyan-dinar' => 'الدينار الليبي (LYD)',
                'malaysian-ringgit' => 'الرينغيت الماليزي (MYR)',
                'mauritian-rupee' => 'الروبية الموريشية (MUR)',
                'mexican-peso' => 'البيزو المكسيكي (MXN)',
                'moroccan-dirham' => 'الدرهم المغربي (MAD)',
                'mysql' => 'MySQL',
                'nepalese-rupee' => 'الروبية النيبالية (NPR)',
                'new-taiwan-dollar' => 'الدولار التايواني الجديد (TWD)',
                'new-zealand-dollar' => 'الدولار النيوزيلندي (NZD)',
                'nigerian-naira' => 'النايرا النيجيري (NGN)',
                'norwegian-krone' => 'الكرونة النرويجية (NOK)',
                'omani-rial' => 'الريال العماني (OMR)',
                'pakistani-rupee' => 'الروبية الباكستانية (PKR)',
                'panamanian-balboa' => 'البالبوا البنمي (PAB)',
                'paraguayan-guarani' => 'الغواراني الباراغواي (PYG)',
                'peruvian-nuevo-sol' => 'السول البيروفي الجديد (PEN)',
                'pgsql' => 'PgSQL',
                'philippine-peso' => 'البيزو الفلبيني (PHP)',
                'polish-zloty' => 'الزلوتي البولندي (PLN)',
                'qatari-rial' => 'الريال القطري (QAR)',
                'romanian-leu' => 'الليو الروماني (RON)',
                'russian-ruble' => 'الروبل الروسي (RUB)',
                'saudi-riyal' => 'الريال السعودي (SAR)',
                'select-timezone' => 'اختر المنطقة الزمنية',
                'singapore-dollar' => 'الدولار السنغافوري (SGD)',
                'south-african-rand' => 'الراند الجنوب أفريقي (ZAR)',
                'south-korean-won' => 'الوون الكوري الجنوبي (KRW)',
                'sqlsrv' => 'SQLSRV',
                'sri-lankan-rupee' => 'الروبية السريلانكية (LKR)',
                'swedish-krona' => 'الكرونا السويدية (SEK)',
                'swiss-franc' => 'الفرنك السويسري (CHF)',
                'thai-baht' => 'الباهت التايلاندي (THB)',
                'title' => 'إعدادات المتجر',
                'tunisian-dinar' => 'الدينار التونسي (TND)',
                'turkish-lira' => 'الليرة التركية (TRY)',
                'ukrainian-hryvnia' => 'الهريفنيا الأوكرانية (UAH)',
                'united-arab-emirates-dirham' => 'الدرهم الإماراتي (AED)',
                'united-states-dollar' => 'الدولار الأمريكي (USD)',
                'uzbekistani-som' => 'السوم الأوزبكستاني (UZS)',
                'venezuelan-bolívar' => 'البوليفار الفنزويلي (VEF)',
                'vietnamese-dong' => 'الدونج الفيتنامي (VND)',
                'warning-message' => 'احذر! إعدادات لغة النظام الافتراضية والعملات الافتراضية دائمة ولا يمكن تغييرها بمجرد تعيينها.',
                'zambian-kwacha' => 'الكواشا الزامبي (ZMW)',
            ],

            'sample-products' => [
                'download-sample' => 'تحميل العينة',
                'no' => 'لا',
                'sample-products' => 'منتجات العينة',
                'title' => 'منتجات العينة',
                'yes' => 'نعم',
            ],

            'installation-processing' => [
                'bagisto' => 'تثبيت Bagisto',
                'bagisto-info' => 'إنشاء جداول قاعدة البيانات، وقد يستغرق ذلك بضع دقائق',
                'title' => 'التثبيت',
            ],

            'installation-completed' => [
                'admin-panel' => 'لوحة المشرف',
                'bagisto-forums' => 'منتديات Bagisto',
                'customer-panel' => 'لوحة العميل',
                'explore-bagisto-extensions' => 'استكشاف امتدادات Bagisto',
                'title' => 'اكتمال التثبيت',
                'title-info' => 'تم تثبيت Bagisto بنجاح على نظامك.',
            ],

            'ready-for-installation' => [
                'create-database-table' => 'إنشاء جدول قاعدة البيانات',
                'install' => 'التثبيت',
                'install-info' => 'Bagisto للتثبيت',
                'install-info-button' => 'انقر على الزر أدناه ل',
                'populate-database-table' => 'ملء جداول قاعدة البيانات',
                'start-installation' => 'بدء التثبيت',
                'title' => 'جاهز للتثبيت',
            ],

            'start' => [
                'locale' => 'اللغة',
                'main' => 'بداية',
                'select-locale' => 'اختر اللغة',
                'title' => 'تثبيت Bagisto الخاص بك',
                'welcome-title' => 'مرحبًا بك في Bagisto',
            ],

            'server-requirements' => [
                'calendar' => 'التقويم',
                'ctype' => 'cType',
                'curl' => 'cURL',
                'dom' => 'dom',
                'fileinfo' => 'معلومات الملف',
                'filter' => 'المرشح',
                'gd' => 'GD',
                'hash' => 'التجزئة',
                'intl' => 'intl',
                'json' => 'JSON',
                'mbstring' => 'mbstring',
                'openssl' => 'openssl',
                'pcre' => 'pcre',
                'pdo' => 'pdo',
                'php' => 'PHP',
                'php-version' => '8.1 أو أعلى',
                'session' => 'الجلسة',
                'title' => 'متطلبات الخادم',
                'tokenizer' => 'tokenizer',
                'xml' => 'XML',
            ],

            'arabic' => 'العربية',
            'back' => 'رجوع',
            'bagisto' => 'Bagisto',
            'bagisto-info' => 'مشروع مجتمعي من قبل',
            'bagisto-logo' => 'شعار Bagisto',
            'bengali' => 'البنغالية',
            'catalan' => 'الكاتالونية',
            'chinese' => 'الصينية',
            'continue' => 'متابعة',
            'dutch' => 'هولندي',
            'english' => 'الإنجليزية',
            'french' => 'الفرنسية',
            'german' => 'ألماني',
            'hebrew' => 'العبرية',
            'hindi' => 'الهندية',
            'indonesian' => 'الإندونيسية',
            'installation-description' => 'عادة ما تتضمن عملية تثبيت Bagisto عدة خطوات. إليك نظرة عامة عامة على عملية التثبيت لBagisto',
            'installation-info' => 'نحن سعداء برؤيتك هنا!',
            'installation-title' => 'مرحبًا بك في التثبيت',
            'italian' => 'الإيطالية',
            'japanese' => 'اليابانية',
            'persian' => 'الفارسية',
            'polish' => 'البولندية',
            'portuguese' => 'البرتغالية البرازيلية',
            'russian' => 'الروسية',
            'sinhala' => 'السنهالية',
            'spanish' => 'الإسبانية',
            'title' => 'مثبت Bagisto',
            'turkish' => 'التركية',
            'ukrainian' => 'الأوكرانية',
            'webkul' => 'Webkul',
        ],
    ],
];
