<?php

return [
    'seeders' => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'Default',
            ],

            'attribute-groups' => [
                'description'       => 'Description',
                'general'           => 'General',
                'inventories'       => 'Inventories',
                'meta-description'  => 'Meta Description',
                'price'             => 'Price',
                'settings'          => 'Settings',
                'shipping'          => 'Shipping',
            ],

            'attributes' => [
                'brand'                => 'Brand',
                'color'                => 'Color',
                'cost'                 => 'Cost',
                'description'          => 'Description',
                'featured'             => 'Featured',
                'guest-checkout'       => 'Guest Checkout',
                'height'               => 'Height',
                'length'               => 'Length',
                'manage-stock'         => 'Manage Stock',
                'meta-description'     => 'Meta Description',
                'meta-keywords'        => 'Meta Keywords',
                'meta-title'           => 'Meta Title',
                'name'                 => 'Name',
                'new'                  => 'New',
                'price'                => 'Price',
                'product-number'       => 'Product Number',
                'short-description'    => 'Short Description',
                'size'                 => 'Size',
                'sku'                  => 'SKU',
                'special-price'        => 'Special Price',
                'special-price-from'   => 'Special Price From',
                'special-price-to'     => 'Special Price To',
                'status'               => 'Status',
                'tax-category'         => 'Tax Category',
                'url-key'              => 'URL Key',
                'visible-individually' => 'Visible Individually',
                'weight'               => 'Weight',
                'width'                => 'Width',
            ],

            'attribute-options' => [
                'black'  => 'Black',
                'green'  => 'Green',
                'l'      => 'L',
                'm'      => 'M',
                'red'    => 'Red',
                's'      => 'S',
                'white'  => 'White',
                'xl'     => 'XL',
                'yellow' => 'Yellow',
            ],
        ],

        'category' => [
            'categories' => [
                'description' => 'Root Category Description',
                'name'        => 'Root',
            ],
        ],

        'cms' => [
            'pages' => [
                'about-us' => [
                    'content' => 'About Us Page Content',
                    'title'   => 'About Us',
                ],

                'contact-us' => [
                    'content' => 'Contact Us Page Content',
                    'title'   => 'Contact Us',
                ],

                'customer-service' => [
                    'content' => 'Customer Service Page Content',
                    'title'   => 'Customer Service',
                ],

                'payment-policy' => [
                    'content' => 'Payment Policy Page Content',
                    'title'   => 'Payment Policy',
                ],

                'privacy-policy' => [
                    'content' => 'Privacy Policy Page Content',
                    'title'   => 'Privacy Policy',
                ],

                'refund-policy' => [
                    'content' => 'Refund Policy Page Content',
                    'title'   => 'Refund Policy',
                ],

                'return-policy' => [
                    'content' => 'Return Policy Page Content',
                    'title'   => 'Return Policy',
                ],

                'shipping-policy' => [
                    'content' => 'Shipping Policy Page Content',
                    'title'   => 'Shipping Policy',
                ],

                'terms-conditions' => [
                    'content' => 'Terms & Conditions Page Content',
                    'title'   => 'Terms & Conditions',
                ],

                'terms-of-use' => [
                    'content' => 'Terms of Use Page Content',
                    'title'   => 'Terms of Use',
                ],

                'whats-new' => [
                    'content' => 'What\'s New page content',
                    'title'   => 'What\'s New',
                ],
            ],
        ],

        'core' => [
            'channels' => [
                'name'             => 'Default',
                'meta-title'       => 'Demo store',
                'meta-keywords'    => 'Demo store meta keyword',
                'meta-description' => 'Demo store meta description',
            ],

            'currencies' => [
                'AED' => 'United Arab Emirates Dirham',
                'ARS' => 'Argentine Peso',
                'AUD' => 'Australian Dollar',
                'BDT' => 'Bangladeshi Taka',
                'BRL' => 'Brazilian Real',
                'CAD' => 'Canadian Dollar',
                'CHF' => 'Swiss Franc',
                'CLP' => 'Chilean Peso',
                'CNY' => 'Chinese Yuan',
                'COP' => 'Colombian Peso',
                'CZK' => 'Czech Koruna',
                'DKK' => 'Danish Krone',
                'DZD' => 'Algerian Dinar',
                'EGP' => 'Egyptian Pound',
                'EUR' => 'Euro',
                'FJD' => 'Fijian Dollar',
                'GBP' => 'British Pound Sterling',
                'HKD' => 'Hong Kong Dollar',
                'HUF' => 'Hungarian Forint',
                'IDR' => 'Indonesian Rupiah',
                'ILS' => 'Israeli New Shekel',
                'INR' => 'Indian Rupee',
                'JOD' => 'Jordanian Dinar',
                'JPY' => 'Japanese Yen',
                'KRW' => 'South Korean Won',
                'KWD' => 'Kuwaiti Dinar',
                'KZT' => 'Kazakhstani Tenge',
                'LBP' => 'Lebanese Pound',
                'LKR' => 'Sri Lankan Rupee',
                'LYD' => 'Libyan Dinar',
                'MAD' => 'Moroccan Dirham',
                'MUR' => 'Mauritian Rupee',
                'MXN' => 'Mexican Peso',
                'MYR' => 'Malaysian Ringgit',
                'NGN' => 'Nigerian Naira',
                'NOK' => 'Norwegian Krone',
                'NPR' => 'Nepalese Rupee',
                'NZD' => 'New Zealand Dollar',
                'OMR' => 'Omani Rial',
                'PAB' => 'Panamanian Balboa',
                'PEN' => 'Peruvian Nuevo Sol',
                'PHP' => 'Philippine Peso',
                'PKR' => 'Pakistani Rupee',
                'PLN' => 'Polish Zloty',
                'PYG' => 'Paraguayan Guarani',
                'QAR' => 'Qatari Rial',
                'RON' => 'Romanian Leu',
                'RUB' => 'Russian Ruble',
                'SAR' => 'Saudi Riyal',
                'SEK' => 'Swedish Krona',
                'SGD' => 'Singapore Dollar',
                'THB' => 'Thai Baht',
                'TND' => 'Tunisian Dinar',
                'TRY' => 'Turkish Lira',
                'TWD' => 'New Taiwan Dollar',
                'UAH' => 'Ukrainian Hryvnia',
                'USD' => 'United States Dollar',
                'UZS' => 'Uzbekistani Som',
                'VEF' => 'Venezuelan Bolívar',
                'VND' => 'Vietnamese Dong',
                'XAF' => 'CFA Franc BEAC',
                'XOF' => 'CFA Franc BCEAO',
                'ZAR' => 'South African Rand',
                'ZMW' => 'Zambian Kwacha',
            ],

            'locales'    => [
                'ar'    => 'Arabic',
                'bn'    => 'Bengali',
                'de'    => 'German',
                'en'    => 'English',
                'es'    => 'Spanish',
                'fa'    => 'Persian',
                'fr'    => 'French',
                'he'    => 'Hebrew',
                'hi_IN' => 'Hindi',
                'it'    => 'Italian',
                'ja'    => 'Japanese',
                'nl'    => 'Dutch',
                'pl'    => 'Polish',
                'pt_BR' => 'Brazilian Portuguese',
                'ru'    => 'Russian',
                'sin'   => 'Sinhala',
                'tr'    => 'Turkish',
                'uk'    => 'Ukrainian',
                'zh_CN' => 'Chinese',
            ],
        ],

        'customer' => [
            'customer-groups' => [
                'general'   => 'General',
                'guest'     => 'Guest',
                'wholesale' => 'Wholesale',
            ],
        ],

        'inventory' => [
            'inventory-sources' => [
                'name' => 'Default',
            ],
        ],

        'shop' => [
            'theme-customizations' => [
                'all-products' => [
                    'name' => 'All Products',

                    'options' => [
                        'title' => 'All Products',
                    ],
                ],

                'bold-collections' => [
                    'content' => [
                        'btn-title'   => 'View Collections',
                        'description' => 'Introducing Our New Bold Collections! Elevate your style with daring designs and vibrant statements. Explore striking patterns and bold colors that redefine your wardrobe. Get ready to embrace the extraordinary!',
                        'title'       => 'Get Ready for our new Bold Collections!',
                    ],

                    'name' => 'Bold Collections',
                ],

                'categories-collections' => [
                    'name' => 'Categories Collections',
                ],

                'featured-collections' => [
                    'name' => 'Featured Collections',

                    'options' => [
                        'title' => 'Featured Products',
                    ],
                ],

                'footer-links' => [
                    'name' => 'Footer Links',

                    'options' => [
                        'about-us'         => 'About Us',
                        'contact-us'       => 'Contact Us',
                        'customer-service' => 'Customer Service',
                        'payment-policy'   => 'Payment Policy',
                        'privacy-policy'   => 'Privacy Policy',
                        'refund-policy'    => 'Refund Policy',
                        'return-policy'    => 'Return Policy',
                        'shipping-policy'  => 'Shipping Policy',
                        'terms-conditions' => 'Terms & Conditions',
                        'terms-of-use'     => 'Terms of Use',
                        'whats-new'        => 'What\'s New',
                    ],
                ],

                'game-container' => [
                    'content' => [
                        'sub-title-1' => 'Our Collections',
                        'sub-title-2' => 'Our Collections',
                        'title'       => 'The game with our new additions!',
                    ],

                    'name' => 'Game Container',
                ],

                'image-carousel' => [
                    'name' => 'Image Carousel',

                    'sliders' => [
                        'title' => 'Get Ready For New Collection',
                    ],
                ],

                'new-products' => [
                    'name' => 'New Products',

                    'options' => [
                        'title' => 'New Products',
                    ],
                ],

                'offer-information' => [
                    'content' => [
                        'title' => 'Get UPTO 40% OFF on your 1st order SHOP NOW',
                    ],

                    'name' => 'Offer Information',
                ],

                'services-content' => [
                    'description' => [
                        'emi-available-info'   => 'No cost EMI available on all major credit cards',
                        'free-shipping-info'   => 'Enjoy free shipping on all orders',
                        'product-replace-info' => 'Easy Product Replacement Available!',
                        'time-support-info'    => 'Dedicated 24/7 support via chat and email',
                    ],

                    'name' => 'Services Content',

                    'title' => [
                        'emi-available'   => 'Emi Available',
                        'free-shipping'   => 'Free Shipping',
                        'product-replace' => 'Product Replace',
                        'time-support'    => '24/7 Support',
                    ],
                ],

                'top-collections' => [
                    'content' => [
                        'sub-title-1' => 'Our Collections',
                        'sub-title-2' => 'Our Collections',
                        'sub-title-3' => 'Our Collections',
                        'sub-title-4' => 'Our Collections',
                        'sub-title-5' => 'Our Collections',
                        'sub-title-6' => 'Our Collections',
                        'title'       => 'The game with our new additions!',
                    ],

                    'name' => 'Top Collections',
                ],
            ],
        ],

        'user' => [
            'roles' => [
                'description' => 'This role users will have all the access',
                'name'        => 'Administrator',
            ],

            'users' => [
                'name' => 'Example',
            ],
        ],
    ],

    'installer' => [
        'index' => [
            'create-administrator' => [
                'admin'            => 'Admin',
                'bagisto'          => 'Bagisto',
                'confirm-password' => 'Confirm Password',
                'email'            => 'Email',
                'email-address'    => 'admin@example.com',
                'password'         => 'Password',
                'title'            => 'Create Administrator',
            ],

            'environment-configuration' => [
                'algerian-dinar'              => 'Algerian Dinar (DZD)',
                'allowed-currencies'          => 'Allowed Currencies',
                'allowed-locales'             => 'Allowed Locales',
                'application-name'            => 'Application Name',
                'argentine-peso'              => 'Argentine Peso (ARS)',
                'australian-dollar'           => 'Australian Dollar (AUD)',
                'bagisto'                     => 'Bagisto',
                'bangladeshi-taka'            => 'Bangladeshi Taka (BDT)',
                'brazilian-real'              => 'Brazilian Real (BRL)',
                'british-pound-sterling'      => 'British Pound Sterling (GBP)',
                'canadian-dollar'             => 'Canadian Dollar (CAD)',
                'cfa-franc-bceao'             => 'CFA Franc BCEAO (XOF)',
                'cfa-franc-beac'              => 'CFA Franc BEAC (XAF)',
                'chilean-peso'                => 'Chilean Peso (CLP)',
                'chinese-yuan'                => 'Chinese Yuan (CNY)',
                'colombian-peso'              => 'Colombian Peso (COP)',
                'czech-koruna'                => 'Czech Koruna (CZK)',
                'danish-krone'                => 'Danish Krone (DKK)',
                'database-connection'         => 'Database Connection',
                'database-hostname'           => 'Database Hostname',
                'database-name'               => 'Database Name',
                'database-password'           => 'Database Password',
                'database-port'               => 'Database Port',
                'database-prefix'             => 'Database Prefix',
                'database-username'           => 'Database Username',
                'default-currency'            => 'Default Currency',
                'default-locale'              => 'Default Locale',
                'default-timezone'            => 'Default Timezone',
                'default-url'                 => 'Default URL',
                'default-url-link'            => 'https://localhost',
                'egyptian-pound'              => 'Egyptian Pound (EGP)',
                'euro'                        => 'Euro (EUR)',
                'fijian-dollar'               => 'Fijian Dollar (FJD)',
                'hong-kong-dollar'            => 'Hong Kong Dollar (HKD)',
                'hungarian-forint'            => 'Hungarian Forint (HUF)',
                'indian-rupee'                => 'Indian Rupee (INR)',
                'indonesian-rupiah'           => 'Indonesian Rupiah (IDR)',
                'israeli-new-shekel'          => 'Israeli New Shekel (ILS)',
                'japanese-yen'                => 'Japanese Yen (JPY)',
                'jordanian-dinar'             => 'Jordanian Dinar (JOD)',
                'kazakhstani-tenge'           => 'Kazakhstani Tenge (KZT)',
                'kuwaiti-dinar'               => 'Kuwaiti Dinar (KWD)',
                'lebanese-pound'              => 'Lebanese Pound (LBP)',
                'libyan-dinar'                => 'Libyan Dinar (LYD)',
                'malaysian-ringgit'           => 'Malaysian Ringgit (MYR)',
                'mauritian-rupee'             => 'Mauritian Rupee (MUR)',
                'mexican-peso'                => 'Mexican Peso (MXN)',
                'moroccan-dirham'             => 'Moroccan Dirham (MAD)',
                'mysql'                       => 'Mysql',
                'nepalese-rupee'              => 'Nepalese Rupee (NPR)',
                'new-taiwan-dollar'           => 'New Taiwan Dollar (TWD)',
                'new-zealand-dollar'          => 'New Zealand Dollar (NZD)',
                'nigerian-naira'              => 'Nigerian Naira (NGN)',
                'norwegian-krone'             => 'Norwegian Krone (NOK)',
                'omani-rial'                  => 'Omani Rial (OMR)',
                'pakistani-rupee'             => 'Pakistani Rupee (PKR)',
                'panamanian-balboa'           => 'Panamanian Balboa (PAB)',
                'paraguayan-guarani'          => 'Paraguayan Guarani (PYG)',
                'peruvian-nuevo-sol'          => 'Peruvian Nuevo Sol (PEN)',
                'pgsql'                       => 'pgSQL',
                'philippine-peso'             => 'Philippine Peso (PHP)',
                'polish-zloty'                => 'Polish Zloty (PLN)',
                'qatari-rial'                 => 'Qatari Rial (QAR)',
                'romanian-leu'                => 'Romanian Leu (RON)',
                'russian-ruble'               => 'Russian Ruble (RUB)',
                'saudi-riyal'                 => 'Saudi Riyal (SAR)',
                'select-timezone'             => 'Select Timezone',
                'singapore-dollar'            => 'Singapore Dollar (SGD)',
                'south-african-rand'          => 'South African Rand (ZAR)',
                'south-korean-won'            => 'South Korean Won (KRW)',
                'sqlsrv'                      => 'SQLSRV',
                'sri-lankan-rupee'            => 'Sri Lankan Rupee (LKR)',
                'swedish-krona'               => 'Swedish Krona (SEK)',
                'swiss-franc'                 => 'Swiss Franc (CHF)',
                'thai-baht'                   => 'Thai Baht (THB)',
                'title'                       => 'Store Configuration',
                'tunisian-dinar'              => 'Tunisian Dinar (TND)',
                'turkish-lira'                => 'Turkish Lira (TRY)',
                'ukrainian-hryvnia'           => 'Ukrainian Hryvnia (UAH)',
                'united-arab-emirates-dirham' => 'United Arab Emirates Dirham (AED)',
                'united-states-dollar'        => 'United States Dollar (USD)',
                'uzbekistani-som'             => 'Uzbekistani Som (UZS)',
                'venezuelan-bolívar'          => 'Venezuelan Bolívar (VEF)',
                'vietnamese-dong'             => 'Vietnamese Dong (VND)',
                'warning-message'             => 'Beware! The settings for your default system languages as well as the default currency are permanent and cannot be changed ever again.',
                'zambian-kwacha'              => 'Zambian Kwacha (ZMW)',
            ],

            'installation-processing' => [
                'bagisto'          => 'Installation Bagisto',
                'bagisto-info'     => 'Creating the database tables, this can take a few moments',
                'title'            => 'Installation',
            ],

            'installation-completed' => [
                'admin-panel'                => 'Admin Panel',
                'bagisto-forums'             => 'Bagisto Forum',
                'customer-panel'             => 'Customer Panel',
                'explore-bagisto-extensions' => 'Explore Bagisto Extension',
                'title'                      => 'Installation Completed',
                'title-info'                 => 'Bagisto is Successfully installed on your system.',
            ],

            'ready-for-installation' => [
                'create-databsae-table'   => 'Create the database table',
                'install'                 => 'Installation',
                'install-info'            => 'Bagisto For Installation',
                'install-info-button'     => 'Click the button below to',
                'populate-database-table' => 'Populate the database tables',
                'start-installation'      => 'Start Installation',
                'title'                   => 'Ready for Installation',
            ],

            'start' => [
                'locale'        => 'Locale',
                'main'          => 'Start',
                'select-locale' => 'Select Locale',
                'title'         => 'Your Bagisto install',
                'welcome-title' => 'Welcome to Bagisto 2.0.',
            ],

            'server-requirements' => [
                'calendar'    => 'Calendar',
                'ctype'       => 'cType',
                'curl'        => 'cURL',
                'dom'         => 'dom',
                'fileinfo'    => 'fileInfo',
                'filter'      => 'Filter',
                'gd'          => 'GD',
                'hash'        => 'Hash',
                'intl'        => 'intl',
                'json'        => 'JSON',
                'mbstring'    => 'mbstring',
                'openssl'     => 'openssl',
                'pcre'        => 'pcre',
                'pdo'         => 'pdo',
                'php'         => 'PHP',
                'php-version' => '8.1 or higher',
                'session'     => 'session',
                'title'       => 'System Requirements',
                'tokenizer'   => 'tokenizer',
                'xml'         => 'XML',
            ],

            'arabic'                   => 'Arabic',
            'back'                     => 'Back',
            'bagisto'                  => 'Bagisto',
            'bagisto-info'             => 'a Community Project by',
            'bagisto-logo'             => 'Bagisto Logo',
            'bengali'                  => 'Bengali',
            'chinese'                  => 'Chinese',
            'continue'                 => 'Continue',
            'dutch'                    => 'Dutch',
            'english'                  => 'English',
            'french'                   => 'French',
            'german'                   => 'German',
            'hebrew'                   => 'Hebrew',
            'hindi'                    => 'Hindi',
            'installation-description' => 'Bagisto installation typically involves several steps. Here\'s a general outline of the installation process for Bagisto:',
            'installation-info'        => 'We are happy to see you here!',
            'installation-title'       => 'Welcome to Installation',
            'italian'                  => 'Italian',
            'japanese'                 => 'Japanese',
            'persian'                  => 'Persian',
            'polish'                   => 'Polish',
            'portuguese'               => 'Brazilian Portuguese',
            'russian'                  => 'Russian',
            'sinhala'                  => 'Sinhala',
            'spanish'                  => 'Spanish',
            'title'                    => 'Bagisto Installer',
            'turkish'                  => 'Turkish',
            'ukrainian'                => 'Ukrainian',
            'webkul'                   => 'Webkul',
        ],
    ],
];
