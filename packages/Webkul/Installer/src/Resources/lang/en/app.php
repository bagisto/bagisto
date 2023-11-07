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
                'shipping'          => 'Shipping',
                'settings'          => 'Settings',
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
                'meta-title'           => 'Meta Title',
                'meta-keywords'        => 'Meta Keywords',
                'meta-description'     => 'Meta Description',
                'manage-stock'         => 'Manage Stock',
                'new'                  => 'New',
                'name'                 => 'Name',
                'product-number'       => 'Product Number',
                'price'                => 'Price',
                'sku'                  => 'SKU',
                'status'               => 'Status',
                'short-description'    => 'Short Description',
                'special-price'        => 'Special Price',
                'special-price-from'   => 'Special Price From',
                'special-price-to'     => 'Special Price To',
                'size'                 => 'Size',
                'tax-category'         => 'Tax Category',
                'url-key'              => 'URL Key',
                'visible-individually' => 'Visible Individually',
                'width'                => 'Width',
                'weight'               => 'Weight',
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

                'refund-policy' => [
                    'content' => 'Refund Policy Page Content',
                    'title'   => 'Refund Policy',
                ],

                'return-policy' => [
                    'content' => 'Return Policy Page Content',
                    'title'   => 'Return Policy',
                ],

                'terms-conditions' => [
                    'content' => 'Terms & Conditions Page Content',
                    'title'   => 'Terms & Conditions',
                ],

                'terms-of-use' => [
                    'content' => 'Terms of Use Page Content',
                    'title'   => 'Terms of Use',
                ],

                'contact-us' => [
                    'content' => 'Contact Us Page Content',
                    'title'   => 'Contact Us',
                ],

                'customer-service' => [
                    'content' => 'Customer Service Page Content',
                    'title'   => 'Customer Service',
                ],

                'whats-new' => [
                    'content' => 'What\'s New page content',
                    'title'   => 'What\'s New',
                ],

                'payment-policy' => [
                    'content' => 'Payment Policy Page Content',
                    'title'   => 'Payment Policy',
                ],

                'shipping-policy' => [
                    'content' => 'Shipping Policy Page Content',
                    'title'   => 'Shipping Policy',
                ],

                'privacy-policy' => [
                    'content' => 'Privacy Policy Page Content',
                    'title'   => 'Privacy Policy',
                ],
            ],
        ],

        'core' => [
            'channels' => [
                'meta-title'       => 'Demo store',
                'meta-keywords'    => 'Demo store meta keyword',
                'meta-description' => 'Demo store meta description',
                'name'             => 'Default',
            ],

            'currencies' => [
                'CNY' => 'Chinese Yuan',
                'AED' => 'Dirham',
                'EUR' => 'EURO',
                'INR' => 'Indian Rupee',
                'IRR' => 'Iranian Rial',
                'ILS' => 'Israeli Shekel',
                'JPY' => 'Japanese Yen',
                'GBP' => 'Pound Sterling',
                'RUB' => 'Russian Ruble',
                'SAR' => 'Saudi Riyal',
                'TRY' => 'Turkish Lira',
                'USD' => 'US Dollar',
                'UAH' => 'Ukrainian Hryvnia',
            ],

            'locales' => [
                'ar'    => 'Arabic',
                'bn'    => 'Bengali',
                'de'    => 'German',
                'es'    => 'Spanish',
                'en'    => 'English',
                'fr'    => 'French',
                'fa'    => 'Persian',
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
                'guest'     => 'Guest',
                'general'   => 'General',
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

    'installer' => [
        'index' => [
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
                'php'         => 'PHP',
                'php-version' => '8.1 or higher',
                'pcre'        => 'pcre',
                'pdo'         => 'pdo',
                'session'     => 'session',
                'title'       => 'Server Requirements',
                'tokenizer'   => 'tokenizer',
                'xml'         => 'XML',
            ],

            'environment-configuration' => [
                'application-name'    => 'Application Name',
                'arabic'              => 'Arabic',
                'bagisto'             => 'Bagisto',
                'bengali'             => 'Bengali',
                'chinese-yuan'        => 'Chinese Yuan (CNY)',
                'chinese'             => 'Chinese',
                'dirham'              => 'Dirham (AED)',
                'default-url'         => 'Default URL',
                'default-url-link'    => 'https://localhost',
                'default-currency'    => 'Default Currency',
                'default-timezone'    => 'Default Timezone',
                'default-locale'      => 'Default Locale',
                'dutch'               => 'Dutch',
                'database-connection' => 'Database Connection',
                'database-hostname'   => 'Database Hostname',
                'database-port'       => 'Database Port',
                'database-name'       => 'Database Name',
                'database-username'   => 'Database Username',
                'database-prefix'     => 'Database Prefix',
                'database-password'   => 'Database Password',
                'euro'                => 'Euro (EUR)',
                'english'             => 'English',
                'french'              => 'French',
                'hebrew'              => 'Hebrew',
                'hindi'               => 'Hindi',
                'iranian'             => 'Iranian Rial (IRR)',
                'israeli'             => 'Israeli Shekel (ILS)',
                'italian'             => 'Italian',
                'japanese-yen'        => 'Japanese Yen (JPY)',
                'japanese'            => 'Japanese',
                'mysql'               => 'Mysql',
                'pgsql'               => 'pgSQL',
                'pound'               => 'Pound Sterling (GBP)',
                'persian'             => 'Persian',
                'polish'              => 'Polish',
                'portuguese'          => 'Brazilian Portuguese',
                'rupee'               => 'Indian Rupee (INR)',
                'russian-ruble'       => 'Russian Ruble (RUB)',
                'russian'             => 'Russian',
                'sqlsrv'              => 'SQLSRV',
                'saudi'               => 'Saudi Riyal (SAR)',
                'spanish'             => 'Spanish',
                'sinhala'             => 'Sinhala',
                'title'               => 'Environment Configuration',
                'turkish-lira'        => 'Turkish Lira (TRY)',
                'turkish'             => 'Turkish',
                'usd'                 => 'US Dollar (USD)',
                'ukrainian-hryvnia'   => 'Ukrainian Hryvnia (UAH)',
                'ukrainian'           => 'Ukrainian',
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

            'installation-processing' => [
                'bagisto'          => 'Installation Bagisto',
                'bagisto-info'     => 'Creating the database tables, this can take a few moments',
                'title'            => 'Installation',
            ],

            'create-administrator' => [
                'admin'            => 'Admin',
                'bagisto'          => 'Bagisto',
                'confirm-password' => 'Confirm Password',
                'email'            => 'Email',
                'email-address'    => 'admin@example.com',
                'password'         => 'Password',
                'title'            => 'Create Administrator',
            ],

            'email-configuration' => [
                'encryption'           => 'Encryption',
                'enter-username'       => 'Enter Username',
                'enter-password'       => 'Enter Password',
                'outgoing-mail-server' => 'Outgoing Mail Server',
                'outgoing-email'       => 'smpt.mailtrap.io',
                'password'             => 'Password',
                'store-email'          => 'Store Email Address',
                'enter-store-email'    => 'Enter Store Email Address',
                'server-port'          => 'Server Port',
                'server-port-code'     => '3306',
                'title'                => 'Email Configuration',
                'username'             => 'Username',

            ],

            'installation-completed' => [
                'admin-panel'                => 'Admin Panel',
                'bagisto-forums'             => 'Bagisto Forum',
                'customer-panel'             => 'Customer Panel',
                'explore-bagisto-extensions' => 'Explore Bagisto Extension',
                'title'                      => 'Installation Completed',
                'title-info'                 => 'Bagisto is Successfully installed on your system.',
            ],

            'bagisto-logo'             => 'Bagisto Logo',
            'back'                     => 'Back',
            'bagisto-info'             => 'a Community Project by',
            'bagisto'                  => 'Bagisto',
            'continue'                 => 'Continue',
            'installation-title'       => 'Welcome to Installation',
            'installation-info'        => 'We are happy to see you here!',
            'installation-description' => 'Bagisto installation typically involves several steps. Here\'s a general outline of the installation process for Bagisto:',
            'skip'                     => 'Skip',
            'save-configuration'       => 'Save configuration',
            'title'                    => 'Bagisto Installer',
            'webkul'                   => 'Webkul',
        ],
    ],
];
