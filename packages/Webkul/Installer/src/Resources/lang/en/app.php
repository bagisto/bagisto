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
                'AFN' => 'Israeli Shekel',
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

                'services-content' => [
                    'name'  => 'Services Content',

                    'title' => [
                        'free-shipping'   => 'Free Shipping',
                        'product-replace' => 'Product Replace',
                        'emi-available'   => 'Emi Available',
                        'time-support'    => '24/7 Support',
                    ],

                    'description' => [
                        'free-shipping-info'   => 'Enjoy free shipping on all orders',
                        'product-replace-info' => 'Easy Product Replacement Available!',
                        'emi-available-info'   => 'No cost EMI available on all major credit cards',
                        'time-support-info'    => 'Dedicated 24/7 support via chat and email',
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
                'php'         => 'PHP',
                'php-version' => '8.1 or higher',
                'pcre'        => 'pcre',
                'pdo'         => 'pdo',
                'session'     => 'session',
                'title'       => 'System Requirements',
                'tokenizer'   => 'tokenizer',
                'xml'         => 'XML',
            ],

            'environment-configuration' => [
                'allowed-locales'     => 'Allowed Locales',
                'allowed-currencies'  => 'Allowed Currencies',
                'application-name'    => 'Application Name',
                'bagisto'             => 'Bagisto',
                'chinese-yuan'        => 'Chinese Yuan (CNY)',
                'dirham'              => 'Dirham (AED)',
                'default-url'         => 'Default URL',
                'default-url-link'    => 'https://localhost',
                'default-currency'    => 'Default Currency',
                'default-timezone'    => 'Default Timezone',
                'default-locale'      => 'Default Locale',
                'database-connection' => 'Database Connection',
                'database-hostname'   => 'Database Hostname',
                'database-port'       => 'Database Port',
                'database-name'       => 'Database Name',
                'database-username'   => 'Database Username',
                'database-prefix'     => 'Database Prefix',
                'database-password'   => 'Database Password',
                'euro'                => 'Euro (EUR)',
                'iranian'             => 'Iranian Rial (IRR)',
                'israeli'             => 'Israeli Shekel (AFN)',
                'japanese-yen'        => 'Japanese Yen (JPY)',
                'mysql'               => 'Mysql',
                'pgsql'               => 'pgSQL',
                'pound'               => 'Pound Sterling (GBP)',
                'rupee'               => 'Indian Rupee (INR)',
                'russian-ruble'       => 'Russian Ruble (RUB)',
                'sqlsrv'              => 'SQLSRV',
                'saudi'               => 'Saudi Riyal (SAR)',
                'title'               => 'Store Configuration',
                'turkish-lira'        => 'Turkish Lira (TRY)',
                'usd'                 => 'US Dollar (USD)',
                'ukrainian-hryvnia'   => 'Ukrainian Hryvnia (UAH)',
                'warning-message'     => 'Beware! The settings for your default system languages as well as the default currency are permanent and cannot be changed ever again.',
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

            'installation-completed' => [
                'admin-panel'                => 'Admin Panel',
                'bagisto-forums'             => 'Bagisto Forum',
                'customer-panel'             => 'Customer Panel',
                'explore-bagisto-extensions' => 'Explore Bagisto Extension',
                'title'                      => 'Installation Completed',
                'title-info'                 => 'Bagisto is Successfully installed on your system.',
            ],

            'arabic'                   => 'Arabic',
            'bengali'                  => 'Bengali',
            'bagisto-logo'             => 'Bagisto Logo',
            'back'                     => 'Back',
            'bagisto-info'             => 'a Community Project by',
            'bagisto'                  => 'Bagisto',
            'chinese'                  => 'Chinese',
            'continue'                 => 'Continue',
            'dutch'                    => 'Dutch',
            'english'                  => 'English',
            'french'                   => 'French',
            'german'                   => 'German',
            'hebrew'                   => 'Hebrew',
            'hindi'                    => 'Hindi',
            'installation-title'       => 'Welcome to Installation',
            'installation-info'        => 'We are happy to see you here!',
            'installation-description' => 'Bagisto installation typically involves several steps. Here\'s a general outline of the installation process for Bagisto:',
            'italian'                  => 'Italian',
            'japanese'                 => 'Japanese',
            'persian'                  => 'Persian',
            'polish'                   => 'Polish',
            'portuguese'               => 'Brazilian Portuguese',
            'russian'                  => 'Russian',
            'spanish'                  => 'Spanish',
            'sinhala'                  => 'Sinhala',
            'skip'                     => 'Skip',
            'save-configuration'       => 'Save configuration',
            'title'                    => 'Bagisto Installer',
            'turkish'                  => 'Turkish',
            'ukrainian'                => 'Ukrainian',
            'webkul'                   => 'Webkul',
        ],
    ],
];
