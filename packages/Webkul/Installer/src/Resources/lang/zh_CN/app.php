<?php

return [
    'seeders' => [
        'attribute' => [
            'attribute-families' => [
                'default' => '默認',
            ],

            'attribute-groups' => [
                'description'       => '描述',
                'general'           => '通用',
                'inventories'       => '庫存',
                'meta-description'  => '元描述',
                'price'             => '價格',
                'shipping'          => '運送',
                'settings'          => '設定',
            ],

            'attributes' => [
                'brand'                => '品牌',
                'color'                => '顏色',
                'cost'                 => '成本',
                'description'          => '描述',
                'featured'             => '特色',
                'guest-checkout'       => '訪客結帳',
                'height'               => '高度',
                'length'               => '長度',
                'meta-title'           => '元標題',
                'meta-keywords'        => '元關鍵詞',
                'meta-description'     => '元描述',
                'manage-stock'         => '庫存管理',
                'new'                  => '新品',
                'name'                 => '名稱',
                'product-number'       => '產品號',
                'price'                => '價格',
                'sku'                  => 'SKU',
                'status'               => '狀態',
                'short-description'    => '簡短描述',
                'special-price'        => '特價',
                'special-price-from'   => '特價起始日期',
                'special-price-to'     => '特價結束日期',
                'size'                 => '尺寸',
                'tax-category'         => '稅收類別',
                'url-key'              => '網址關鍵字',
                'visible-individually' => '單獨顯示',
                'width'                => '寬度',
                'weight'               => '重量',
            ],

            'attribute-options' => [
                'black'  => '黑色',
                'green'  => '綠色',
                'l'      => 'L',
                'm'      => 'M',
                'red'    => '紅色',
                's'      => 'S',
                'white'  => '白色',
                'xl'     => 'XL',
                'yellow' => '黃色',
            ],
        ],

        'category' => [
            'categories' => [
                'description' => '根分類描述',
                'name'        => '根',
            ],
        ],

        'cms' => [
            'pages' => [
                'about-us' => [
                    'content' => '關於我們頁面內容',
                    'title'   => '關於我們',
                ],

                'refund-policy' => [
                    'content' => '退款政策頁面內容',
                    'title'   => '退款政策',
                ],

                'return-policy' => [
                    'content' => '退貨政策頁面內容',
                    'title'   => '退貨政策',
                ],

                'terms-conditions' => [
                    'content' => '條款和條件頁面內容',
                    'title'   => '條款和條件',
                ],

                'terms-of-use' => [
                    'content' => '使用條款頁面內容',
                    'title'   => '使用條款',
                ],

                'contact-us' => [
                    'content' => '聯繫我們頁面內容',
                    'title'   => '聯繫我們',
                ],

                'customer-service' => [
                    'content' => '客戶服務頁面內容',
                    'title'   => '客戶服務',
                ],

                'whats-new' => [
                    'content' => '最新消息頁面內容',
                    'title'   => '最新消息',
                ],

                'payment-policy' => [
                    'content' => '付款政策頁面內容',
                    'title'   => '付款政策',
                ],

                'shipping-policy' => [
                    'content' => '運送政策頁面內容',
                    'title'   => '運送政策',
                ],

                'privacy-policy' => [
                    'content' => '隱私政策頁面內容',
                    'title'   => '隱私政策',
                ],
            ],
        ],

        'core' => [
            'channels' => [
                'meta-title'       => '演示商店',
                'meta-keywords'    => '演示商店元關鍵詞',
                'meta-description' => '演示商店元描述',
                'name'             => '默認',
            ],

            'currencies' => [
                'CNY' => '人民幣',
                'AED' => '迪爾汗',
                'EUR' => '歐元',
                'INR' => '印度盧比',
                'IRR' => '伊朗里亞爾',
                'ILS' => '以色列謝克爾',
                'JPY' => '日元',
                'GBP' => '英鎊',
                'RUB' => '俄羅斯盧布',
                'SAR' => '沙特里亞爾',
                'TRY' => '土耳其里拉',
                'USD' => '美元',
                'UAH' => '烏克蘭格里夫納',
            ],

            'locales' => [
                'ar'    => '阿拉伯語',
                'bn'    => '孟加拉語',
                'de'    => '德語',
                'es'    => '西班牙語',
                'en'    => '英語',
                'fr'    => '法語',
                'fa'    => '波斯語',
                'he'    => '希伯來語',
                'hi_IN' => '印度區區',
                'it'    => '意大利語',
                'ja'    => '日語',
                'nl'    => '荷蘭語',
                'pl'    => '波蘭語',
                'pt_BR' => '巴西葡萄牙語',
                'ru'    => '俄語',
                'sin'   => '僧伽羅語',
                'tr'    => '土耳其語',
                'uk'    => '烏克蘭語',
                'zh_CN' => '簡體中文',
            ],
        ],

        'customer' => [
            'customer-groups' => [
                'guest'     => '訪客',
                'general'   => '普通',
                'wholesale' => '批發',
            ],
        ],

        'inventory' => [
            'inventory-sources' => [
                'name' => '默認',
            ],
        ],

        'shop' => [
            'theme-customizations' => [
                'image-carousel' => [
                    'name'  => 'Image Carousel',

                    'sliders' => [
                        'title' => 'Get Ready For New Collection',
                    ],
                ],

                'offer-information' => [
                    'name' => 'Offer Information',

                    'content' => [
                        'title' => 'Get UPTO 40% OFF on your 1st order SHOP NOW',
                    ],
                ],

                'categories-collections' => [
                    'name' => 'Categories Collections',
                ],

                'new-products' => [
                    'name' => 'New Products',

                    'options' => [
                        'title' => 'New Products',
                    ],
                ],

                'top-collections' => [
                    'name' => 'Top Collections',

                    'content' => [
                        'sub-title-1' => 'Our Collections',
                        'sub-title-2' => 'Our Collections',
                        'sub-title-3' => 'Our Collections',
                        'sub-title-4' => 'Our Collections',
                        'sub-title-5' => 'Our Collections',
                        'sub-title-6' => 'Our Collections',
                        'title'       => 'The game with our new additions!',
                    ],
                ],

                'bold-collections' => [
                    'name' => 'Bold Collections',

                    'content' => [
                        'btn-title'   => 'View All',
                        'description' => 'Introducing Our New Bold Collections! Elevate your style with daring designs and vibrant statements. Explore striking patterns and bold colors that redefine your wardrobe. Get ready to embrace the extraordinary!',
                        'title'       => 'Get Ready for our new Bold Collections!',
                    ],
                ],

                'featured-collections' => [
                    'name' => 'Featured Collections',

                    'options' => [
                        'title' => 'Featured Products',
                    ],
                ],

                'game-container' => [
                    'name' => 'Game Container',

                    'content' => [
                        'sub-title-1' => 'Our Collections',
                        'sub-title-2' => 'Our Collections',
                        'title'       => 'The game with our new additions!',
                    ],
                ],

                'all-products' => [
                    'name' => 'All Products',

                    'options' => [
                        'title' => 'All Products',
                    ],
                ],

                'footer-links' => [
                    'name' => 'Footer Links',

                    'options' => [
                        'about-us'         => 'About Us',
                        'contact-us'       => 'Contact Us',
                        'customer-service' => 'Customer Service',
                        'privacy-policy'   => 'Privacy Policy',
                        'payment-policy'   => 'Payment Policy',
                        'return-policy'    => 'Return Policy',
                        'refund-policy'    => 'Refund Policy',
                        'shipping-policy'  => 'Shipping Policy',
                        'terms-of-use'     => 'Terms of Use',
                        'terms-conditions' => 'Terms & Conditions',
                        'whats-new'        => 'What\'s New',
                    ],
                ],
            ],
        ],

        'user' => [
            'users' => [
                'name' => 'Example',
            ],

            'roles' => [
                'description' => 'This role users will have all the access',
                'name'        => 'Administrator',
            ],
        ],
    ],
];
