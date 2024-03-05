<?php

return [
    'seeders'   => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'ডিফল্ট',
            ],

            'attribute-groups'  => [
                'description'       => 'বর্ণনা',
                'general'           => 'সাধারণ',
                'inventories'       => 'মালামালের মৌলিক বিশেষত্ব',
                'meta-description'  => 'মেটা বর্ণনা',
                'price'             => 'মূল্য',
                'settings'          => 'সেটিংস',
                'shipping'          => 'শিপিং',
            ],

            'attributes'        => [
                'brand'                => 'ব্র্যান্ড',
                'color'                => 'রঙ',
                'cost'                 => 'মূল্য',
                'description'          => 'বর্ণনা',
                'featured'             => 'নির্বাচিত',
                'guest-checkout'       => 'অতিথি চেকআউট',
                'height'               => 'উচ্চতা',
                'length'               => 'দৈর্ঘ্য',
                'manage-stock'         => 'স্টক পরিচালনা করুন',
                'meta-description'     => 'মেটা বর্ণনা',
                'meta-keywords'        => 'মেটা কীওয়ার্ড',
                'meta-title'           => 'মেটা শিরোনাম',
                'name'                 => 'নাম',
                'new'                  => 'নতুন',
                'price'                => 'মূল্য',
                'product-number'       => 'পণ্য নম্বর',
                'short-description'    => 'সংক্ষিপ্ত বর্ণনা',
                'size'                 => 'আকার',
                'sku'                  => 'SKU',
                'special-price-from'   => 'বিশেষ মূল্য থেকে',
                'special-price-to'     => 'বিশেষ মূল্য প্রায়',
                'special-price'        => 'বিশেষ মূল্য',
                'status'               => 'অবস্থা',
                'tax-category'         => 'কর বিভাগ',
                'url-key'              => 'URL কী',
                'visible-individually' => 'একে একে দেখা যাবে',
                'weight'               => 'ওজন',
                'width'                => 'প্রস্থ',
            ],

            'attribute-options' => [
                'black'  => 'কালো',
                'green'  => 'সবুজ',
                'l'      => 'L',
                'm'      => 'M',
                'red'    => 'লাল',
                's'      => 'S',
                'white'  => 'সাদা',
                'xl'     => 'XL',
                'yellow' => 'হলুদ',
            ],
        ],

        'category'  => [
            'categories' => [
                'description' => 'মূল বর্গের বর্ণনা',
                'name'        => 'মূল',
            ],
        ],

        'cms'       => [
            'pages' => [
                'about-us'         => [
                    'content' => 'আমাদের সম্পর্কে পাতা সামগ্রী',
                    'title'   => 'আমাদের সম্পর্কে',
                ],

                'contact-us'       => [
                    'content' => 'যোগাযোগ করুন পাতা সামগ্রী',
                    'title'   => 'যোগাযোগ করুন',
                ],

                'customer-service' => [
                    'content' => 'গ্রাহক সেবা পাতা সামগ্রী',
                    'title'   => 'গ্রাহক সেবা',
                ],

                'payment-policy'   => [
                    'content' => 'পেমেন্ট নীতি পাতা সামগ্রী',
                    'title'   => 'পেমেন্ট নীতি',
                ],

                'privacy-policy'   => [
                    'content' => 'গোপনীয়তা নীতি পাতা সামগ্রী',
                    'title'   => 'গোপনীয়তা নীতি',
                ],

                'refund-policy'    => [
                    'content' => 'ফেরত নেওয়া নীতি পাতা সামগ্রী',
                    'title'   => 'ফেরত নেওয়া নীতি',
                ],

                'return-policy'    => [
                    'content' => 'ফেরত নেওয়া নীতি পাতা সামগ্রী',
                    'title'   => 'ফেরত নেওয়া নীতি',
                ],

                'shipping-policy'  => [
                    'content' => 'পরিবহন নীতি পাতা সামগ্রী',
                    'title'   => 'পরিবহন নীতি',
                ],

                'terms-conditions' => [
                    'content' => 'শর্তাবলী পাতা সামগ্রী',
                    'title'   => 'শর্তাবলী',
                ],

                'terms-of-use'     => [
                    'content' => 'ব্যবহারের শর্ত পাতা সামগ্রী',
                    'title'   => 'ব্যবহারের শর্ত',
                ],

                'whats-new'        => [
                    'content' => 'আমাদের নতুন জিনিস পাতা সামগ্রী',
                    'title'   => 'কি নতুন',
                ],
            ],
        ],

        'core'      => [
            'channels'   => [
                'meta-description' => 'ডেমো স্টোর মেটা বর্ণনা',
                'meta-keywords'    => 'ডেমো স্টোর মেটা কীওয়ার্ড',
                'meta-title'       => 'ডেমো স্টোর',
                'name'             => 'ডিফল্ট',
            ],

            'currencies' => [
                'AED' => 'দিরহাম',
                'AFN' => 'ইস্রায়েলি শেকেল',
                'CNY' => 'চীনা ইউয়ান',
                'EUR' => 'ইউরো',
                'GBP' => 'ব্রিটিশ পাউন্ড',
                'INR' => 'ভারতীয় টাকা',
                'IRR' => 'ইরানী রিয়াল',
                'JPY' => 'জাপানি ইয়েন',
                'RUB' => 'রাশিয়ান রুবল',
                'SAR' => 'সৌদি রিয়াল',
                'TRY' => 'তুর্কি লিরা',
                'UAH' => 'ইউক্রেনিয়ান হৃব্র',
                'USD' => 'মার্কিন ডলার',
            ],

            'locales'    => [
                'ar'    => 'আরবি',
                'bn'    => 'বাংলা',
                'de'    => 'জার্মান',
                'en'    => 'ইংরেজি',
                'es'    => 'স্পেনী',
                'fa'    => 'ফারসি',
                'fr'    => 'ফরাসি',
                'he'    => 'হিব্রু',
                'hi_IN' => 'হিন্দি',
                'it'    => 'ইতালীয়',
                'ja'    => 'জাপানি',
                'nl'    => 'ডাচ',
                'pl'    => 'পোলিশ',
                'pt_BR' => 'ব্রাজিলিয়ান পর্তুগিজ',
                'ru'    => 'রাশিয়ান',
                'sin'   => 'সিংহলা',
                'tr'    => 'তুর্কি',
                'uk'    => 'ইউক্রেনীয়',
                'zh_CN' => 'চীনা',
            ],
        ],

        'customer'  => [
            'customer-groups' => [
                'general'   => 'সাধারণ',
                'guest'     => 'অতিথি',
                'wholesale' => 'থোক',
            ],
        ],

        'inventory' => [
            'inventory-sources' => [
                'name' => 'ডিফল্ট',
            ],
        ],

        'shop'      => [
            'theme-customizations' => [
                'all-products'           => [
                    'name'    => 'সব পণ্য',

                    'options' => [
                        'title' => 'সমস্ত পণ্য',
                    ],
                ],

                'bold-collections'       => [
                    'content' => [
                        'btn-title'   => 'সব দেখুন',
                        'description' => 'আমাদের নতুন বোল্ড সংগ্রহ পরিচিত করার জন্য আপনার শৈলীকে ধৈর্যশীল ডিজাইন এবং জীবন্ত বক্তব্যগুলি দিয়ে আপনার গার্ডরোব পরিভ্রান্ত করুন। আকর্ষণীয় মোটীর্দে প্যাটার্ন এবং বোল্ড রঙের অনুসন্ধান করুন, যা আপনার সাড়াদে পরিভ্রান্ত করে। অসাধারণ দেখার জন্য প্রস্তুত হোন!',
                        'title'       => 'আমাদের নতুন বোল্ড সংগ্রহের জন্য প্রস্তুত হোন!',
                    ],

                    'name'    => 'বোল্ড সংগ্রহ',
                ],

                'categories-collections' => [
                    'name' => 'বিভাগ সংগ্রহ',
                ],

                'footer-links'           => [
                    'name'    => 'পাদচরণ লিঙ্কস',

                    'options' => [
                        'about-us'         => 'আমাদের সম্পর্কে',
                        'contact-us'       => 'যোগাযোগ করুন',
                        'customer-service' => 'গ্রাহক সেবা',
                        'payment-policy'   => 'মূল্য নীতি',
                        'privacy-policy'   => 'গোপনীয়তা নীতি',
                        'refund-policy'    => 'প্রতিপূর্তি নীতি',
                        'return-policy'    => 'প্রত্যাশ্য নীতি',
                        'shipping-policy'  => 'প্রেরণা নীতি',
                        'terms-conditions' => 'শর্ত এবং শর্তাদি',
                        'terms-of-use'     => 'ব্যবহারের শর্তাবলি',
                        'whats-new'        => 'নতুন কি আছে',
                    ],
                ],

                'featured-collections'   => [
                    'name'    => 'নির্দেশিত সংগ্রহ',

                    'options' => [
                        'title' => 'নির্দেশিত পণ্য',
                    ],
                ],

                'game-container'         => [
                    'content' => [
                        'sub-title-1' => 'আমাদের সংগ্রহ',
                        'sub-title-2' => 'আমাদের সংগ্রহ',
                        'title'       => 'আমাদের নতুন যোগাযোগে খেলা!',
                    ],

                    'name'    => 'খেলা সংগ্রহ',
                ],

                'image-carousel'         => [
                    'name'    => 'চিত্র ক্যারোসেল',

                    'sliders' => [
                        'title' => 'নতুন সংগ্রহের জন্য প্রস্তুত হোন',
                    ],
                ],

                'new-products'           => [
                    'name'    => 'নতুন পণ্য',

                    'options' => [
                        'title' => 'নতুন পণ্য',
                    ],
                ],

                'offer-information'      => [
                    'content' => [
                        'title' => 'আপনার 1টি মান অর্ডারে 40% ছাড় পেতে SHOP NOW',
                    ],

                    'name' => 'অফার তথ্য',
                ],

                'services-content'       => [
                    'name'  => 'সেবা সামগ্রী',

                    'title' => [
                        'emi-available'   => 'ইএমআই উপলব্ধ',
                        'free-shipping'   => 'ফ্রি শিপিং',
                        'product-replace' => 'পণ্য পরিবর্তন করুন',
                        'time-support'    => '২৪/৭ সমর্থন',
                    ],

                    'description' => [
                        'free-shipping-info'   => 'সমস্ত অর্ডারে ফ্রি শিপিং উপভোগ করুন',
                        'product-replace-info' => 'সহজ পণ্য পরিবর্তন উপলব্ধ!',
                        'emi-available-info'   => 'সমস্ত প্রধান ক্রেডিট কার্ডে খরচ না করে ইএমআই উপলব্ধ',
                        'time-support-info'    => 'চ্যাট এবং ইমেল দ্বারা উপস্থিত সমর্থন প্রদান করা হয় ২৪/৭',
                    ],
                ],

                'top-collections'        => [
                    'content' => [
                        'sub-title-1' => 'আমাদের সংগ্রহ',
                        'sub-title-2' => 'আমাদের সংগ্রহ',
                        'sub-title-3' => 'আমাদের সংগ্রহ',
                        'sub-title-4' => 'আমাদের সংগ্রহ',
                        'sub-title-5' => 'আমাদের সংগ্রহ',
                        'sub-title-6' => 'আমাদের সংগ্রহ',
                        'title'       => 'আমাদের নতুন যোগাযোগে খেলা!',
                    ],

                    'name'    => 'শীর্ষ সংগ্রহ',
                ],
            ],
        ],

        'user'      => [
            'roles' => [
                'description' => 'এই ভূমিকা ব্যবহারকারীদের সমস্ত অ্যাক্সেস থাকবে',
                'name'        => 'প্রশাসক',
            ],

            'users' => [
                'name' => 'উদাহরণ',
            ],
        ],
    ],

    'installer' => [
        'index' => [
            'create-administrator'      => [
                'admin'            => 'অ্যাডমিন',
                'bagisto'          => 'বাগিস্টো',
                'confirm-password' => 'পাসওয়ার্ড নিশ্চিত করুন',
                'email-address'    => 'admin@example.com',
                'email'            => 'ইমেল',
                'password'         => 'পাসওয়ার্ড',
                'title'            => 'প্রশাসক তৈরি করুন',
            ],

            'environment-configuration' => [
                'allowed-currencies'  => 'অনুমোদিত মুদ্রা',
                'allowed-locales'     => 'অনুমোদিত লোকেল',
                'application-name'    => 'অ্যাপ্লিকেশনের নাম',
                'bagisto'             => 'Bagisto',
                'chinese-yuan'        => 'চীনা ইউয়ান (CNY)',
                'database-connection' => 'ডাটাবেস সংযোগ',
                'database-hostname'   => 'ডাটাবেস হোস্টনাম',
                'database-name'       => 'ডাটাবেস নাম',
                'database-password'   => 'ডাটাবেস পাসওয়ার্ড',
                'database-port'       => 'ডাটাবেস পোর্ট',
                'database-prefix'     => 'ডাটাবেস প্রিফিক্স',
                'database-username'   => 'ডাটাবেস ইউজারনেম',
                'default-currency'    => 'ডিফল্ট মুদ্রা',
                'default-locale'      => 'ডিফল্ট লোকেল',
                'default-timezone'    => 'ডিফল্ট টাইমজোন',
                'default-url-link'    => 'https://localhost',
                'default-url'         => 'ডিফল্ট URL',
                'dirham'              => 'দিরহাম (AED)',
                'euro'                => 'ইউরো (EUR)',
                'iranian'             => 'ইরানী রিয়াল (IRR)',
                'israeli'             => 'ইস্রায়েলি শেকেল (AFN)',
                'japanese-yen'        => 'জাপানি ইয়েন (JPY)',
                'mysql'               => 'Mysql',
                'pgsql'               => 'pgSQL',
                'pound'               => 'পাউন্ড স্টার্লিং (GBP)',
                'rupee'               => 'ভারতীয় রুপি (INR)',
                'russian-ruble'       => 'রাশিয়ান রুবল (RUB)',
                'saudi'               => 'সৌদি রিয়াল (SAR)',
                'select-timezone'     => 'টাইমজোন নির্বাচন করুন',
                'sqlsrv'              => 'SQLSRV',
                'title'               => 'পরিবেশ কনফিগারেশন',
                'turkish-lira'        => 'তুর্কি লিরা (TRY)',
                'ukrainian-hryvnia'   => 'ইউক্রেনীয়ান হৃভনিয়া (UAH)',
                'usd'                 => 'মার্কিন ডলার (USD)',
                'warning-message'     => 'সাবধান! আপনার ডিফল্ট সিস্টেম ভাষা এবং ডিফল্ট মুদ্রা সেটিংস স্থায়ী এবং একবার আর পরিবর্তন করা যাবে না।',
            ],

            'installation-processing'   => [
                'bagisto-info'     => 'ডাটাবেস টেবিল তৈরি করা হচ্ছে, এটি কিছুটা সময় নিতে পারে',
                'bagisto'          => 'ইনস্টলেশন Bagisto',
                'title'            => 'ইনস্টলেশন',
            ],

            'installation-completed'    => [
                'admin-panel'                => 'অ্যাডমিন প্যানেল',
                'bagisto-forums'             => 'বাগিস্টো ফোরাম',
                'customer-panel'             => 'কাস্টমার প্যানেল',
                'explore-bagisto-extensions' => 'বাগিস্টো এক্সটেনশন অন্বেষণ করুন',
                'title-info'                 => 'বাগিস্টো সফলভাবে আপনার সিস্টেমে ইনস্টল করা হয়েছে।',
                'title'                      => 'ইনস্টলেশন সম্পন্ন',
            ],

            'ready-for-installation'    => [
                'create-databsae-table'   => 'ডাটাবেস টেবিল তৈরি করুন',
                'install-info-button'     => 'নীচের বাটনে ক্লিক করুন',
                'install-info'            => 'ইনস্টলেশনের জন্য Bagisto',
                'install'                 => 'ইনস্টলেশন',
                'populate-database-table' => 'ডাটাবেস টেবিল পূর্ণ করুন',
                'start-installation'      => 'ইনস্টলেশন শুরু করুন',
                'title'                   => 'ইনস্টলেশনের জন্য প্রস্তুত',
            ],

            'start'                     => [
                'locale'        => 'লোকেল',
                'main'          => 'শুরু',
                'select-locale' => 'লোকেল নির্বাচন করুন',
                'title'         => 'আপনার Bagisto ইনস্টলেশন',
                'welcome-title' => 'Bagisto 2.0-এ আপনাকে স্বাগতম।',
            ],

            'server-requirements'       => [
                'calendar'    => 'ক্যালেন্ডার',
                'ctype'       => 'cType',
                'curl'        => 'cURL',
                'dom'         => 'ডম',
                'fileinfo'    => 'ফাইলইনফো',
                'filter'      => 'ফিল্টার',
                'gd'          => 'GD',
                'hash'        => 'হ্যাশ',
                'intl'        => 'intl',
                'json'        => 'JSON',
                'mbstring'    => 'এমবি স্ট্রিং',
                'openssl'     => 'ওপেনএসএসএল',
                'pcre'        => 'pcre',
                'pdo'         => 'pdo',
                'php-version' => '8.1 বা তার উচ্চতর সংস্করণ',
                'php'         => 'PHP',
                'session'     => 'সেশন',
                'title'       => 'সার্ভারের প্রয়োজনীয়তা',
                'tokenizer'   => 'টোকেনাইজার',
                'xml'         => 'XML',
            ],

            'arabic'                    => 'আরবি',
            'back'                      => 'পিছনে',
            'bagisto-info'              => 'একটি সম্প্রদায়িক প্রকল্প দ্বারা',
            'bagisto-logo'              => 'বাগিস্তো লোগো',
            'bagisto'                   => 'বাগিস্তো',
            'bengali'                   => 'বাংলা',
            'chinese'                   => 'চীনা',
            'continue'                  => 'চালিয়ে যান',
            'dutch'                     => 'ডাচ',
            'english'                   => 'ইংরেজি',
            'french'                    => 'ফরাসি',
            'german'                    => 'জার্মান',
            'hebrew'                    => 'হিব্রু',
            'hindi'                     => 'হিন্দি',
            'installation-description'  => 'বাগিস্তো ইনস্টলেশন সাধারণত একাধিক পদক্ষেপ শামিল করে। বাগিস্তোর ইনস্টলেশন প্রক্রিয়ার জন্য এটি সাধারণ বর্ণনা:',
            'installation-info'         => 'আমরা আপনাকে এখানে দেখা দেখার খুশি!',
            'installation-title'        => 'ইনস্টলেশনে আপনাকে স্বাগতম',
            'italian'                   => 'ইটালিয়ান',
            'japanese'                  => 'জাপানি',
            'persian'                   => 'পার্সি',
            'polish'                    => 'পোলিশ',
            'portuguese'                => 'পর্তুগিজ',
            'russian'                   => 'রুশ',
            'save-configuration'        => 'কনফিগারেশন সংরক্ষণ করুন',
            'sinhala'                   => 'সিংহলি',
            'skip'                      => 'পার্থক্য',
            'spanish'                   => 'স্পেনীয়',
            'title'                     => 'বাগিস্তো ইনস্টলার',
            'turkish'                   => 'তুর্কি',
            'ukrainian'                 => 'ইউক্রেনীয়',
            'webkul'                    => 'ওয়েবকুল',
        ],
    ],
];
