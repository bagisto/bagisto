<?php

return [
    'seeders' => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'ডিফল্ট',
            ],

            'attribute-groups' => [
                'description'      => 'বর্ণনা',
                'general'          => 'সাধারণ',
                'inventories'      => 'মালামালের মৌলিক বিশেষত্ব',
                'meta-description' => 'মেটা বর্ণনা',
                'price'            => 'মূল্য',
                'settings'         => 'সেটিংস',
                'shipping'         => 'শিপিং',
            ],

            'attributes' => [
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
                'special-price'        => 'বিশেষ মূল্য',
                'special-price-from'   => 'বিশেষ মূল্য থেকে',
                'special-price-to'     => 'বিশেষ মূল্য প্রায়',
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

        'category' => [
            'categories' => [
                'description' => 'মূল বর্গের বর্ণনা',
                'name'        => 'মূল',
            ],
        ],

        'cms' => [
            'pages' => [
                'about-us' => [
                    'content' => 'আমাদের সম্পর্কে পাতা সামগ্রী',
                    'title'   => 'আমাদের সম্পর্কে',
                ],

                'contact-us' => [
                    'content' => 'যোগাযোগ করুন পাতা সামগ্রী',
                    'title'   => 'যোগাযোগ করুন',
                ],

                'customer-service' => [
                    'content' => 'গ্রাহক সেবা পাতা সামগ্রী',
                    'title'   => 'গ্রাহক সেবা',
                ],

                'payment-policy' => [
                    'content' => 'পেমেন্ট নীতি পাতা সামগ্রী',
                    'title'   => 'পেমেন্ট নীতি',
                ],

                'privacy-policy' => [
                    'content' => 'গোপনীয়তা নীতি পাতা সামগ্রী',
                    'title'   => 'গোপনীয়তা নীতি',
                ],

                'refund-policy'    => [
                    'content' => 'ফেরত নেওয়া নীতি পাতা সামগ্রী',
                    'title'   => 'ফেরত নেওয়া নীতি',
                ],

                'return-policy' => [
                    'content' => 'ফেরত নেওয়া নীতি পাতা সামগ্রী',
                    'title'   => 'ফেরত নেওয়া নীতি',
                ],

                'shipping-policy' => [
                    'content' => 'পরিবহন নীতি পাতা সামগ্রী',
                    'title'   => 'পরিবহন নীতি',
                ],

                'terms-conditions' => [
                    'content' => 'শর্তাবলী পাতা সামগ্রী',
                    'title'   => 'শর্তাবলী',
                ],

                'terms-of-use' => [
                    'content' => 'ব্যবহারের শর্ত পাতা সামগ্রী',
                    'title'   => 'ব্যবহারের শর্ত',
                ],

                'whats-new' => [
                    'content' => 'আমাদের নতুন জিনিস পাতা সামগ্রী',
                    'title'   => 'কি নতুন',
                ],
            ],
        ],

        'core' => [
            'channels' => [
                'name'             => 'ডিফল্ট',
                'meta-title'       => 'ডেমো স্টোর',
                'meta-keywords'    => 'ডেমো স্টোর মেটা কীওয়ার্ড',
                'meta-description' => 'ডেমো স্টোর মেটা বর্ণনা',
            ],

            'currencies' => [
                'AED' => 'সংযুক্ত আরব আমিরাত দিরহাম',
                'ARS' => 'আর্জেন্টিনা পেসো',
                'AUD' => 'অস্ট্রেলিয়ান ডলার',
                'BDT' => 'বাংলাদেশী টাকা',
                'BRL' => 'ব্রাজিলিয়ান রিয়েল',
                'CAD' => 'কানাডিয়ান ডলার',
                'CHF' => 'সুইস ফ্রাঙ্ক',
                'CLP' => 'চিলিয়ান পেসো',
                'CNY' => 'চীনা য়ুয়ান',
                'COP' => 'কলোম্বিয়ান পেসো',
                'CZK' => 'চেক কোরুনা',
                'DKK' => 'ড্যানিশ ক্রোন',
                'DZD' => 'আলজেরীয় দিনার',
                'EGP' => 'মিশরীয় পাউন্ড',
                'EUR' => 'ইউরো',
                'FJD' => 'ফিজিয়ান ডলার',
                'GBP' => 'ব্রিটিশ পাউন্ড স্টার্লিং',
                'HKD' => 'হংকং ডলার',
                'HUF' => 'হাঙ্গেরিয়ান ফোরিন্ট',
                'IDR' => 'ইন্দোনেশিয়ান রুপিয়া',
                'ILS' => 'ইস্রায়েলি নতুন শেকেল',
                'INR' => 'ভারতীয় রুপি',
                'JOD' => 'জর্ডানিয়ান দিনার',
                'JPY' => 'জাপানি ইয়েন',
                'KRW' => 'দক্ষিণ কোরিয়ান ওয়ন',
                'KWD' => 'কুয়েতি দিনার',
                'KZT' => 'কাজাখস্তানি টেঙ্গে',
                'LBP' => 'লেবানিজ পাউন্ড',
                'LKR' => 'শ্রীলঙ্কান রুপি',
                'LYD' => 'লিবিয়ান দিনার',
                'MAD' => 'মোরোক্কান দিরহাম',
                'MUR' => 'মরিশান রুপি',
                'MXN' => 'মেক্সিকান পেসো',
                'MYR' => 'মালয়েশিয়ান রিঙ্গিট',
                'NGN' => 'নাইজেরিয়ান নায়রা',
                'NOK' => 'নরওয়েজিয়ান ক্রোন',
                'NPR' => 'নেপালি রুপি',
                'NZD' => 'নিউজিল্যান্ড ডলার',
                'OMR' => 'ওমানি রিয়াল',
                'PAB' => 'পানামানিয়ান বালবোয়া',
                'PEN' => 'পেরুভিয়ান নুয়েভো সোল',
                'PHP' => 'ফিলিপাইন পেসো',
                'PKR' => 'পাকিস্তানি রুপি',
                'PLN' => 'পোলিশ জ্লোটি',
                'PYG' => 'প্যারাগুয়ান গুয়ারানি',
                'QAR' => 'কাতার রিয়াল',
                'RON' => 'রোমানিয়ান লেউ',
                'RUB' => 'রাশিয়ান রুবেল',
                'SAR' => 'সৌদি রিয়াল',
                'SEK' => 'সুইডিশ ক্রোনা',
                'SGD' => 'সিঙ্গাপুর ডলার',
                'THB' => 'থাই বাত',
                'TND' => 'তিউনেশিয়ান দিনার',
                'TRY' => 'তুর্কি লিরা',
                'TWD' => 'নতুন তাইওয়ান ডলার',
                'UAH' => 'ইউক্রেনীয় হৃভনিয়া',
                'USD' => 'মার্কিন যুক্তরাষ্ট্র ডলার',
                'UZS' => 'উজবেকিস্তানি সম',
                'VEF' => 'ভেনেজুয়েলান বলিভার',
                'VND' => 'ভিয়েতনামি ডঙ্গ',
                'XAF' => 'সিএফএ ফ্রাঙ্ক বিইএসইএস',
                'XOF' => 'সিএফএ ফ্রাঙ্ক বিসিইএও',
                'ZAR' => 'দক্ষিণ আফ্রিকান র্যান্ড',
                'ZMW' => 'জাম্বিয়ান কওয়াচা',
            ],

            'locales' => [
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

        'customer' => [
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

        'shop' => [
            'theme-customizations' => [
                'all-products' => [
                    'name' => 'সব পণ্য',

                    'options' => [
                        'title' => 'সমস্ত পণ্য',
                    ],
                ],

                'bold-collections' => [
                    'content' => [
                        'btn-title'   => 'সংগ্রহ দেখুন',
                        'description' => 'আমাদের নতুন বোল্ড সংগ্রহ পরিচিত করার জন্য আপনার শৈলীকে ধৈর্যশীল ডিজাইন এবং জীবন্ত বক্তব্যগুলি দিয়ে আপনার গার্ডরোব পরিভ্রান্ত করুন। আকর্ষণীয় মোটীর্দে প্যাটার্ন এবং বোল্ড রঙের অনুসন্ধান করুন, যা আপনার সাড়াদে পরিভ্রান্ত করে। অসাধারণ দেখার জন্য প্রস্তুত হোন!',
                        'title'       => 'আমাদের নতুন বোল্ড সংগ্রহের জন্য প্রস্তুত হোন!',
                    ],

                    'name' => 'বোল্ড সংগ্রহ',
                ],

                'categories-collections' => [
                    'name' => 'বিভাগ সংগ্রহ',
                ],

                'footer-links' => [
                    'name' => 'পাদচরণ লিঙ্কস',

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

                'featured-collections' => [
                    'name' => 'নির্দেশিত সংগ্রহ',

                    'options' => [
                        'title' => 'নির্দেশিত পণ্য',
                    ],
                ],

                'game-container' => [
                    'content' => [
                        'sub-title-1' => 'আমাদের সংগ্রহ',
                        'sub-title-2' => 'আমাদের সংগ্রহ',
                        'title'       => 'আমাদের নতুন যোগাযোগে খেলা!',
                    ],

                    'name' => 'খেলা সংগ্রহ',
                ],

                'image-carousel' => [
                    'name' => 'চিত্র ক্যারোসেল',

                    'sliders' => [
                        'title' => 'নতুন সংগ্রহের জন্য প্রস্তুত হোন',
                    ],
                ],

                'new-products' => [
                    'name' => 'নতুন পণ্য',

                    'options' => [
                        'title' => 'নতুন পণ্য',
                    ],
                ],

                'offer-information' => [
                    'content' => [
                        'title' => 'আপনার 1টি মান অর্ডারে 40% ছাড় পেতে SHOP NOW',
                    ],

                    'name' => 'অফার তথ্য',
                ],

                'services-content' => [
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

                'top-collections' => [
                    'content' => [
                        'sub-title-1' => 'আমাদের সংগ্রহ',
                        'sub-title-2' => 'আমাদের সংগ্রহ',
                        'sub-title-3' => 'আমাদের সংগ্রহ',
                        'sub-title-4' => 'আমাদের সংগ্রহ',
                        'sub-title-5' => 'আমাদের সংগ্রহ',
                        'sub-title-6' => 'আমাদের সংগ্রহ',
                        'title'       => 'আমাদের নতুন যোগাযোগে খেলা!',
                    ],

                    'name' => 'শীর্ষ সংগ্রহ',
                ],
            ],
        ],

        'user' => [
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
            'create-administrator' => [
                'admin'            => 'অ্যাডমিন',
                'bagisto'          => 'বাগিস্তো',
                'confirm-password' => 'পাসওয়ার্ড নিশ্চিত করুন',
                'email-address'    => 'ইমেল ঠিকানা',
                'email'            => 'ইমেল',
                'password'         => 'পাসওয়ার্ড',
                'title'            => 'প্রশাসক তৈরি করুন',
            ],

            'environment-configuration' => [
                'algerian-dinar'              => 'আলজেরিয়ান দিনার (DZD)',
                'allowed-currencies'          => 'অনুমোদিত মুদ্রা',
                'allowed-locales'             => 'অনুমোদিত লোকেল',
                'application-name'            => 'অ্যাপ্লিকেশন নাম',
                'argentine-peso'              => 'আর্জেন্টিনা পেসো (ARS)',
                'australian-dollar'           => 'অস্ট্রেলিয়ান ডলার (AUD)',
                'bagisto'                     => 'বাগিস্তো',
                'bangladeshi-taka'            => 'বাংলাদেশী টাকা (BDT)',
                'brazilian-real'              => 'ব্রাজিলিয়ান রিয়েল (BRL)',
                'british-pound-sterling'      => 'ব্রিটিশ পাউন্ড স্টার্লিং (GBP)',
                'canadian-dollar'             => 'কানাডিয়ান ডলার (CAD)',
                'cfa-franc-bceao'             => 'CFA ফ্রাঙ্ক BCEAO (XOF)',
                'cfa-franc-beac'              => 'CFA ফ্রাঙ্ক BEAC (XAF)',
                'chilean-peso'                => 'চিলিয়ান পেসো (CLP)',
                'chinese-yuan'                => 'চীনা য়ুয়ান (CNY)',
                'colombian-peso'              => 'কলম্বিয়ান পেসো (COP)',
                'czech-koruna'                => 'চেক কোরুনা (CZK)',
                'danish-krone'                => 'ড্যানিশ ক্রোন (DKK)',
                'database-connection'         => 'ডাটাবেস সংযোগ',
                'database-hostname'           => 'ডাটাবেস হোস্টনাম',
                'database-name'               => 'ডাটাবেস নাম',
                'database-password'           => 'ডাটাবেস পাসওয়ার্ড',
                'database-port'               => 'ডাটাবেস পোর্ট',
                'database-prefix'             => 'ডাটাবেস প্রিফিক্স',
                'database-username'           => 'ডাটাবেস ইউজারনেম',
                'default-currency'            => 'ডিফল্ট মুদ্রা',
                'default-locale'              => 'ডিফল্ট লোকেল',
                'default-timezone'            => 'ডিফল্ট টাইমজোন',
                'default-url'                 => 'ডিফল্ট URL',
                'default-url-link'            => 'https://localhost',
                'egyptian-pound'              => 'মিশরীয় পাউন্ড (EGP)',
                'euro'                        => 'ইউরো (EUR)',
                'fijian-dollar'               => 'ফিজিয়ান ডলার (FJD)',
                'hong-kong-dollar'            => 'হংকং ডলার (HKD)',
                'hungarian-forint'            => 'হাঙ্গেরিয়ান ফরিন্ট (HUF)',
                'indian-rupee'                => 'ভারতীয় টাকা (INR)',
                'indonesian-rupiah'           => 'ইন্দোনেশিয়ান রুপিয়া (IDR)',
                'israeli-new-shekel'          => 'ইস্রায়েলি নতুন শেকেল (ILS)',
                'japanese-yen'                => 'জাপানি ইয়েন (JPY)',
                'jordanian-dinar'             => 'জর্ডানিয়ান দিনার (JOD)',
                'kazakhstani-tenge'           => 'কাজাখস্তানি টেঙ্গে (KZT)',
                'kuwaiti-dinar'               => 'কুয়েতি দিনার (KWD)',
                'lebanese-pound'              => 'লেবানিজ পাউন্ড (LBP)',
                'libyan-dinar'                => 'লিবিয়ান দিনার (LYD)',
                'malaysian-ringgit'           => 'মালয়েশিয়ান রিঙ্গিট (MYR)',
                'mauritian-rupee'             => 'মরিশান রুপি (MUR)',
                'mexican-peso'                => 'মেক্সিকান পেসো (MXN)',
                'moroccan-dirham'             => 'মরোক্কান দিরহাম (MAD)',
                'mysql'                       => 'মাইসিকুয়েল',
                'nepalese-rupee'              => 'নেপালি রুপি (NPR)',
                'new-taiwan-dollar'           => 'নতুন তাইওয়ান ডলার (TWD)',
                'new-zealand-dollar'          => 'নিউজিল্যান্ড ডলার (NZD)',
                'nigerian-naira'              => 'নাইজেরিয়ান নায়রা (NGN)',
                'norwegian-krone'             => 'নরওয়েজিয়ান ক্রোন (NOK)',
                'omani-rial'                  => 'ওমানি রিয়াল (OMR)',
                'pakistani-rupee'             => 'পাকিস্তানি রুপি (PKR)',
                'panamanian-balboa'           => 'পানামানিয়ান বালবোয়া (PAB)',
                'paraguayan-guarani'          => 'প্যারাগুয়ান গুয়ারানি (PYG)',
                'peruvian-nuevo-sol'          => 'পেরুভিয়ান নুয়েভো সোল (PEN)',
                'pgsql'                       => 'পোস্টগ্রেএসকিউএল',
                'philippine-peso'             => 'ফিলিপাইন পেসো (PHP)',
                'polish-zloty'                => 'পোলিশ জ্লোটি (PLN)',
                'qatari-rial'                 => 'কাতারি রিয়াল (QAR)',
                'romanian-leu'                => 'রোমানিয়ান লেউ (RON)',
                'russian-ruble'               => 'রাশিয়ান রুবল (RUB)',
                'saudi-riyal'                 => 'সৌদি রিয়াল (SAR)',
                'select-timezone'             => 'টাইমজোন নির্বাচন করুন',
                'singapore-dollar'            => 'সিঙ্গাপুর ডলার (SGD)',
                'south-african-rand'          => 'দক্ষিণ আফ্রিকান র‌্যান্ড (ZAR)',
                'south-korean-won'            => 'দক্ষিণ কোরিয়ান ওয়ন (KRW)',
                'sqlsrv'                      => 'SQLSRV',
                'sri-lankan-rupee'            => 'শ্রীলঙ্কান রুপি (LKR)',
                'swedish-krona'               => 'সুইডিশ ক্রোনা (SEK)',
                'swiss-franc'                 => 'সুইস ফ্রাঙ্ক (CHF)',
                'thai-baht'                   => 'থাই বাত (THB)',
                'title'                       => 'স্টোর কনফিগারেশন',
                'tunisian-dinar'              => 'তিউনিসিয়ান দিনার (TND)',
                'turkish-lira'                => 'তুর্কি লিরা (TRY)',
                'ukrainian-hryvnia'           => 'ইউক্রেনীয় হৃবনিয়া (UAH)',
                'united-arab-emirates-dirham' => 'সংযুক্ত আরব আমিরাত দিরহাম (AED)',
                'united-states-dollar'        => 'মার্কিন যুক্তরাষ্ট্র ডলার (USD)',
                'uzbekistani-som'             => 'উজবেকিস্তানি সম (UZS)',
                'venezuelan-bolívar'          => 'ভেনেজুয়েলান বলিভার (VEF)',
                'vietnamese-dong'             => 'ভিয়েতনামি ডঙ্গ (VND)',
                'warning-message'             => 'সাবধান! আপনার ডিফল্ট সিস্টেম ভাষা এবং ডিফল্ট মুদ্রা সেটিংস স্থায়ী এবং আর পরিবর্তন করা যাবে না।',
                'zambian-kwacha'              => 'জাম্বিয়ান কোয়াচা (ZMW)',
            ],

            'installation-processing' => [
                'bagisto-info'     => 'ডাটাবেস টেবিল তৈরি করা হচ্ছে, এটি কিছুটা সময় নিতে পারে',
                'bagisto'          => 'ইনস্টলেশন বাগিস্তো',
                'title'            => 'ইনস্টলেশন',
            ],

            'installation-completed' => [
                'admin-panel'                => 'অ্যাডমিন প্যানেল',
                'bagisto-forums'             => 'বাগিস্তো ফোরাম',
                'customer-panel'             => 'কাস্টমার প্যানেল',
                'explore-bagisto-extensions' => 'বাগিস্তো এক্সটেনশন অন্বেষণ করুন',
                'title-info'                 => 'বাগিস্তো সফলভাবে আপনার সিস্টেমে ইনস্টল করা হয়েছে।',
                'title'                      => 'ইনস্টলেশন সম্পন্ন',
            ],

            'ready-for-installation' => [
                'create-databsae-table'   => 'ডাটাবেস টেবিল তৈরি করুন',
                'install-info-button'     => 'নীচের বাটনে ক্লিক করুন',
                'install-info'            => 'ইনস্টলেশনের জন্য Bagisto',
                'install'                 => 'ইনস্টলেশন',
                'populate-database-table' => 'ডাটাবেস টেবিল পূর্ণ করুন',
                'start-installation'      => 'ইনস্টলেশন শুরু করুন',
                'title'                   => 'ইনস্টলেশনের জন্য প্রস্তুত',
            ],

            'start' => [
                'locale'        => 'লোকেল',
                'main'          => 'শুরু',
                'select-locale' => 'লোকেল নির্বাচন করুন',
                'title'         => 'আপনার Bagisto ইনস্টলেশন',
                'welcome-title' => 'Bagisto 2.0-এ আপনাকে স্বাগতম।',
            ],

            'server-requirements' => [
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

            'arabic'                   => 'আরবি',
            'back'                     => 'পিছনে',
            'bagisto-info'             => 'একটি সম্প্রদায়িক প্রকল্প দ্বারা',
            'bagisto-logo'             => 'বাগিস্তো লোগো',
            'bagisto'                  => 'বাগিস্তো',
            'bengali'                  => 'বাংলা',
            'chinese'                  => 'চীনা',
            'continue'                 => 'চালিয়ে যান',
            'dutch'                    => 'ডাচ',
            'english'                  => 'ইংরেজি',
            'french'                   => 'ফরাসি',
            'german'                   => 'জার্মান',
            'hebrew'                   => 'হিব্রু',
            'hindi'                    => 'হিন্দি',
            'installation-description' => 'বাগিস্তো ইনস্টলেশন সাধারণত একাধিক পদক্ষেপ শামিল করে। বাগিস্তোর ইনস্টলেশন প্রক্রিয়ার জন্য এটি সাধারণ বর্ণনা:',
            'installation-info'        => 'আমরা আপনাকে এখানে দেখা দেখার খুশি!',
            'installation-title'       => 'ইনস্টলেশনে আপনাকে স্বাগতম',
            'italian'                  => 'ইটালিয়ান',
            'japanese'                 => 'জাপানি',
            'persian'                  => 'পার্সি',
            'polish'                   => 'পোলিশ',
            'portuguese'               => 'পর্তুগিজ',
            'russian'                  => 'রুশ',
            'sinhala'                  => 'সিংহলি',
            'spanish'                  => 'স্পেনীয়',
            'title'                    => 'বাগিস্তো ইনস্টলার',
            'turkish'                  => 'তুর্কি',
            'ukrainian'                => 'ইউক্রেনীয়',
            'webkul'                   => 'ওয়েবকুল',
        ],
    ],
];
