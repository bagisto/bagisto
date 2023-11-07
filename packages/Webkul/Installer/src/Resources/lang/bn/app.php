<?php

return [
    'seeders' => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'ডিফল্ট',
            ],

            'attribute-groups' => [
                'description'       => 'বর্ণনা',
                'general'           => 'সাধারণ',
                'inventories'       => 'মালামালের মৌলিক বিশেষত্ব',
                'meta-description'  => 'মেটা বর্ণনা',
                'price'             => 'মূল্য',
                'shipping'          => 'শিপিং',
                'settings'          => 'সেটিংস',
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
                'meta-title'           => 'মেটা শিরোনাম',
                'meta-keywords'        => 'মেটা কীওয়ার্ড',
                'meta-description'     => 'মেটা বর্ণনা',
                'manage-stock'         => 'স্টক পরিচালনা করুন',
                'new'                  => 'নতুন',
                'name'                 => 'নাম',
                'product-number'       => 'পণ্য নম্বর',
                'price'                => 'মূল্য',
                'sku'                  => 'SKU',
                'status'               => 'অবস্থা',
                'short-description'    => 'সংক্ষিপ্ত বর্ণনা',
                'special-price'        => 'বিশেষ মূল্য',
                'special-price-from'   => 'বিশেষ মূল্য থেকে',
                'special-price-to'     => 'বিশেষ মূল্য প্রায়',
                'size'                 => 'আকার',
                'tax-category'         => 'কর বিভাগ',
                'url-key'              => 'URL কী',
                'visible-individually' => 'একে একে দেখা যাবে',
                'width'                => 'প্রস্থ',
                'weight'               => 'ওজন',
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

                'refund-policy' => [
                    'content' => 'ফেরত নেওয়া নীতি পাতা সামগ্রী',
                    'title'   => 'ফেরত নেওয়া নীতি',
                ],

                'return-policy' => [
                    'content' => 'ফেরত নেওয়া নীতি পাতা সামগ্রী',
                    'title'   => 'ফেরত নেওয়া নীতি',
                ],

                'terms-conditions' => [
                    'content' => 'শর্তাবলী পাতা সামগ্রী',
                    'title'   => 'শর্তাবলী',
                ],

                'terms-of-use' => [
                    'content' => 'ব্যবহারের শর্ত পাতা সামগ্রী',
                    'title'   => 'ব্যবহারের শর্ত',
                ],

                'contact-us' => [
                    'content' => 'যোগাযোগ করুন পাতা সামগ্রী',
                    'title'   => 'যোগাযোগ করুন',
                ],

                'customer-service' => [
                    'content' => 'গ্রাহক সেবা পাতা সামগ্রী',
                    'title'   => 'গ্রাহক সেবা',
                ],

                'whats-new' => [
                    'content' => 'আমাদের নতুন জিনিস পাতা সামগ্রী',
                    'title'   => 'কি নতুন',
                ],

                'payment-policy' => [
                    'content' => 'পেমেন্ট নীতি পাতা সামগ্রী',
                    'title'   => 'পেমেন্ট নীতি',
                ],

                'shipping-policy' => [
                    'content' => 'পরিবহন নীতি পাতা সামগ্রী',
                    'title'   => 'পরিবহন নীতি',
                ],

                'privacy-policy' => [
                    'content' => 'গোপনীয়তা নীতি পাতা সামগ্রী',
                    'title'   => 'গোপনীয়তা নীতি',
                ],
            ],
        ],

        'core' => [
            'channels' => [
                'meta-title'       => 'ডেমো স্টোর',
                'meta-keywords'    => 'ডেমো স্টোর মেটা কীওয়ার্ড',
                'meta-description' => 'ডেমো স্টোর মেটা বর্ণনা',
                'name'             => 'ডিফল্ট',
            ],

            'currencies' => [
                'CNY' => 'চীনা ইউয়ান',
                'AED' => 'দিরহাম',
                'EUR' => 'ইউরো',
                'INR' => 'ভারতীয় টাকা',
                'IRR' => 'ইরানী রিয়াল',
                'ILS' => 'ইস্রায়েলি শেকেল',
                'JPY' => 'জাপানি ইয়েন',
                'GBP' => 'ব্রিটিশ পাউন্ড',
                'RUB' => 'রাশিয়ান রুবল',
                'SAR' => 'সৌদি রিয়াল',
                'TRY' => 'তুর্কি লিরা',
                'USD' => 'মার্কিন ডলার',
                'UAH' => 'ইউক্রেনিয়ান হৃব্র',
            ],

            'locales' => [
                'ar'    => 'আরবি',
                'bn'    => 'বাংলা',
                'de'    => 'জার্মান',
                'es'    => 'স্পেনী',
                'en'    => 'ইংরেজি',
                'fr'    => 'ফরাসি',
                'fa'    => 'ফারসি',
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
                'guest'     => 'অতিথি',
                'general'   => 'সাধারণ',
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
                'image-carousel' => [
                    'name'  => 'চিত্র ক্যারোসেল',

                    'sliders' => [
                        'title' => 'নতুন সংগ্রহের জন্য প্রস্তুত হোন',
                    ],
                ],

                'offer-information' => [
                    'name' => 'অফার তথ্য',

                    'content' => [
                        'title' => 'আপনার 1টি মান অর্ডারে 40% ছাড় পেতে SHOP NOW',
                    ],
                ],

                'categories-collections' => [
                    'name' => 'বিভাগ সংগ্রহ',
                ],

                'new-products' => [
                    'name' => 'নতুন পণ্য',

                    'options' => [
                        'title' => 'নতুন পণ্য',
                    ],
                ],

                'top-collections' => [
                    'name' => 'শীর্ষ সংগ্রহ',

                    'content' => [
                        'sub-title-1' => 'আমাদের সংগ্রহ',
                        'sub-title-2' => 'আমাদের সংগ্রহ',
                        'sub-title-3' => 'আমাদের সংগ্রহ',
                        'sub-title-4' => 'আমাদের সংগ্রহ',
                        'sub-title-5' => 'আমাদের সংগ্রহ',
                        'sub-title-6' => 'আমাদের সংগ্রহ',
                        'title'       => 'আমাদের নতুন যোগাযোগে খেলা!',
                    ],
                ],

                'bold-collections' => [
                    'name' => 'বোল্ড সংগ্রহ',

                    'content' => [
                        'btn-title'   => 'সব দেখুন',
                        'description' => 'আমাদের নতুন বোল্ড সংগ্রহ পরিচিত করার জন্য আপনার শৈলীকে ধৈর্যশীল ডিজাইন এবং জীবন্ত বক্তব্যগুলি দিয়ে আপনার গার্ডরোব পরিভ্রান্ত করুন। আকর্ষণীয় মোটীর্দে প্যাটার্ন এবং বোল্ড রঙের অনুসন্ধান করুন, যা আপনার সাড়াদে পরিভ্রান্ত করে। অসাধারণ দেখার জন্য প্রস্তুত হোন!',
                        'title'       => 'আমাদের নতুন বোল্ড সংগ্রহের জন্য প্রস্তুত হোন!',
                    ],
                ],

                'featured-collections' => [
                    'name' => 'নির্দেশিত সংগ্রহ',

                    'options' => [
                        'title' => 'নির্দেশিত পণ্য',
                    ],
                ],

                'game-container' => [
                    'name' => 'খেলা সংগ্রহ',

                    'content' => [
                        'sub-title-1' => 'আমাদের সংগ্রহ',
                        'sub-title-2' => 'আমাদের সংগ্রহ',
                        'title'       => 'আমাদের নতুন যোগাযোগে খেলা!',
                    ],
                ],

                'all-products' => [
                    'name' => 'সব পণ্য',

                    'options' => [
                        'title' => 'সমস্ত পণ্য',
                    ],
                ],

                'footer-links' => [
                    'name' => 'পাদচরণ লিঙ্কস',

                    'options' => [
                        'about-us'         => 'আমাদের সম্পর্কে',
                        'contact-us'       => 'যোগাযোগ করুন',
                        'customer-service' => 'গ্রাহক সেবা',
                        'privacy-policy'   => 'গোপনীয়তা নীতি',
                        'payment-policy'   => 'মূল্য নীতি',
                        'return-policy'    => 'প্রত্যাশ্য নীতি',
                        'refund-policy'    => 'প্রতিপূর্তি নীতি',
                        'shipping-policy'  => 'প্রেরণা নীতি',
                        'terms-of-use'     => 'ব্যবহারের শর্তাবলি',
                        'terms-conditions' => 'শর্ত এবং শর্তাদি',
                        'whats-new'        => 'নতুন কি আছে',
                    ],
                ],

                'services-content' => [
                    'name'  => 'সেবা সামগ্রী',
                    
                    'title' => [
                        'free-shipping'     => 'ফ্রি শিপিং',
                        'product-replace'   => 'পণ্য পরিবর্তন করুন',
                        'emi-available'     => 'ইএমআই উপলব্ধ',
                        'time-support'      => '২৪/৭ সমর্থন',
                    ],
                
                    'description' => [
                        'free-shipping-info'     => 'সমস্ত অর্ডারে ফ্রি শিপিং উপভোগ করুন',
                        'product-replace-info'   => 'সহজ পণ্য পরিবর্তন উপলব্ধ!',
                        'emi-available-info'     => 'সমস্ত প্রধান ক্রেডিট কার্ডে খরচ না করে ইএমআই উপলব্ধ',
                        'time-support-info'      => 'চ্যাট এবং ইমেল দ্বারা উপস্থিত সমর্থন প্রদান করা হয় ২৪/৭',
                    ],
                ],
            ],
        ],

        'user' => [
            'users' => [
                'name' => 'উদাহরণ',
            ],

            'roles' => [
                'description' => 'এই ভূমিকা ব্যবহারকারীদের সমস্ত অ্যাক্সেস থাকবে',
                'name'        => 'প্রশাসক',
            ],
        ],
    ],
];
