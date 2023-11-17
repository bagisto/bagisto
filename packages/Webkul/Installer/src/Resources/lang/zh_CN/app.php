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

                'services-content' => [
                    'name'  => '服务内容',

                    'title' => [
                        'free-shipping'     => '免费送货',
                        'product-replace'   => '产品更换',
                        'emi-available'     => 'EMI 可用',
                        'time-support'      => '24/7 支持',
                    ],

                    'description' => [
                        'free-shipping-info'     => '所有订单均可享受免费送货',
                        'product-replace-info'   => '可轻松更换产品！',
                        'emi-available-info'     => '所有主要信用卡均可免费使用 EMI',
                        'time-support-info'      => '专门的 24/7 支持，通过聊天和电子邮件提供',
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

    'installer' => [
        'index' => [
            'server-requirements' => [
                'calendar'    => '日历',
                'ctype'       => 'cType',
                'curl'        => 'cURL',
                'dom'         => 'DOM',
                'fileinfo'    => '文件信息',
                'filter'      => '过滤器',
                'gd'          => 'GD',
                'hash'        => '哈希',
                'intl'        => '国际化',
                'json'        => 'JSON',
                'mbstring'    => '多字节字符串',
                'openssl'     => 'OpenSSL',
                'php'         => 'PHP',
                'php-version' => '8.1 或更高版本',
                'pcre'        => 'PCRE',
                'pdo'         => 'PDO',
                'session'     => '会话',
                'title'       => '服务器要求',
                'tokenizer'   => '标记器',
                'xml'         => 'XML',
            ],

            'environment-configuration' => [
                'application-name'    => '应用程序名称',
                'arabic'              => '阿拉伯语',
                'bagisto'             => 'Bagisto',
                'bengali'             => '孟加拉语',
                'chinese-yuan'        => '人民币 (CNY)',
                'chinese'             => '中文',
                'dirham'              => '迪拉姆 (AED)',
                'default-url'         => '默认网址',
                'default-url-link'    => 'https://localhost',
                'default-currency'    => '默认货币',
                'default-timezone'    => '默认时区',
                'default-locale'      => '默认区域设置',
                'dutch'               => '荷兰语',
                'database-connection' => '数据库连接',
                'database-hostname'   => '数据库主机名',
                'database-port'       => '数据库端口',
                'database-name'       => '数据库名称',
                'database-username'   => '数据库用户名',
                'database-prefix'     => '数据库前缀',
                'database-password'   => '数据库密码',
                'euro'                => '欧元 (EUR)',
                'english'             => '英语',
                'french'              => '法语',
                'hebrew'              => '希伯来语',
                'hindi'               => '印地语',
                'iranian'             => '伊朗里亚尔 (IRR)',
                'israeli'             => '以色列谢克尔 (ILS)',
                'italian'             => '意大利语',
                'japanese-yen'        => '日元 (JPY)',
                'japanese'            => '日语',
                'mysql'               => 'Mysql',
                'pgsql'               => 'pgSQL',
                'pound'               => '英镑 (GBP)',
                'persian'             => '波斯语',
                'polish'              => '波兰语',
                'portuguese'          => '巴西葡萄牙语',
                'rupee'               => '印度卢比 (INR)',
                'russian-ruble'       => '俄罗斯卢布 (RUB)',
                'russian'             => '俄语',
                'sqlsrv'              => 'SQLSRV',
                'saudi'               => '沙特里亚尔 (SAR)',
                'spanish'             => '西班牙语',
                'sinhala'             => '僧伽罗语',
                'title'               => '环境配置',
                'turkish-lira'        => '土耳其里拉 (TRY)',
                'turkish'             => '土耳其语',
                'usd'                 => '美元 (USD)',
                'ukrainian-hryvnia'   => '乌克兰赫里夫纳 (UAH)',
                'ukrainian'           => '乌克兰语',
            ],

            'ready-for-installation' => [
                'create-database-table'   => '创建数据库表',
                'install'                 => '安装',
                'install-info'            => 'Bagisto 安装',
                'install-info-button'     => '点击下面的按钮',
                'populate-database-table' => '填充数据库表',
                'start-installation'      => '开始安装',
                'title'                   => '准备安装',
            ],

            'installation-processing' => [
                'bagisto'          => 'Bagisto 安装',
                'bagisto-info'     => '正在创建数据库表，这可能需要一些时间',
                'title'            => '安装',
            ],

            'create-administrator' => [
                'admin'            => '管理员',
                'bagisto'          => 'Bagisto',
                'confirm-password' => '确认密码',
                'email'            => '电子邮件',
                'email-address'    => 'admin@example.com',
                'password'         => '密码',
                'title'            => '创建管理员',
            ],

            'email-configuration' => [
                'encryption'           => '加密',
                'enter-username'       => '输入用户名',
                'enter-password'       => '输入密码',
                'outgoing-mail-server' => '出站邮件服务器',
                'outgoing-email'       => 'smpt.mailtrap.io',
                'password'             => '密码',
                'store-email'          => '商店电子邮件地址',
                'enter-store-email'    => '输入商店电子邮件地址',
                'server-port'          => '服务器端口',
                'server-port-code'     => '3306',
                'title'                => '电子邮件配置',
                'username'             => '用户名',
            ],

            'installation-completed' => [
                'admin-panel'                => '管理员面板',
                'bagisto-forums'             => 'Bagisto 论坛',
                'customer-panel'             => '客户面板',
                'explore-bagisto-extensions' => '探索 Bagisto 扩展',
                'title'                      => '安装已完成',
                'title-info'                 => 'Bagisto 已成功安装在您的系统上。',
            ],

            'bagisto-logo'             => 'Bagisto 徽标',
            'back'                     => '返回',
            'bagisto-info'             => '社区项目',
            'bagisto'                  => 'Bagisto',
            'continue'                 => '继续',
            'installation-title'       => '欢迎安装',
            'installation-info'        => '我们很高兴在这里见到您！',
            'installation-description' => 'Bagisto 安装通常涉及多个步骤。以下是 Bagisto 安装过程的一般概述：',
            'skip'                     => '跳过',
            'save-configuration'       => '保存配置',
            'title'                    => 'Bagisto 安装程序',
            'webkul'                   => 'Webkul',
        ],
    ],
];
