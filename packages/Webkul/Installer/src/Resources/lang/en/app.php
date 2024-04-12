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
                'AED' => 'Dirham',
                'AFN' => 'Israeli Shekel',
                'CNY' => 'Chinese Yuan',
                'EUR' => 'EURO',
                'GBP' => 'Pound Sterling',
                'INR' => 'Indian Rupee',
                'IRR' => 'Iranian Rial',
                'JPY' => 'Japanese Yen',
                'RUB' => 'Russian Ruble',
                'SAR' => 'Saudi Riyal',
                'TRY' => 'Turkish Lira',
                'UAH' => 'Ukrainian Hryvnia',
                'USD' => 'US Dollar',
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
                        'btn-title'   => 'View All',
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
                'allowed-currencies'  => 'Allowed Currencies',
                'allowed-locales'     => 'Allowed Locales',
                'application-name'    => 'Application Name',
                'bagisto'             => 'Bagisto',
                'chinese-yuan'        => 'Chinese Yuan (CNY)',
                'database-connection' => 'Database Connection',
                'database-hostname'   => 'Database Hostname',
                'database-name'       => 'Database Name',
                'database-password'   => 'Database Password',
                'database-port'       => 'Database Port',
                'database-prefix'     => 'Database Prefix',
                'database-username'   => 'Database Username',
                'default-currency'    => 'Default Currency',
                'default-locale'      => 'Default Locale',
                'default-timezone'    => 'Default Timezone',
                'default-url'         => 'Default URL',
                'default-url-link'    => 'https://localhost',
                'dirham'              => 'Dirham (AED)',
                'euro'                => 'Euro (EUR)',
                'iranian'             => 'Iranian Rial (IRR)',
                'israeli'             => 'Israeli Shekel (AFN)',
                'japanese-yen'        => 'Japanese Yen (JPY)',
                'mysql'               => 'Mysql',
                'pgsql'               => 'pgSQL',
                'pound'               => 'Pound Sterling (GBP)',
                'rupee'               => 'Indian Rupee (INR)',
                'russian-ruble'       => 'Russian Ruble (RUB)',
                'saudi'               => 'Saudi Riyal (SAR)',
                'select-timezone'     => 'Select Timezone',
                'sqlsrv'              => 'SQLSRV',
                'title'               => 'Store Configuration',
                'turkish-lira'        => 'Turkish Lira (TRY)',
                'ukrainian-hryvnia'   => 'Ukrainian Hryvnia (UAH)',
                'usd'                 => 'US Dollar (USD)',
                'warning-message'     => 'Beware! The settings for your default system languages as well as the default currency are permanent and cannot be changed ever again.',
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
            'save-configuration'       => 'Save configuration',
            'sinhala'                  => 'Sinhala',
            'skip'                     => 'Skip',
            'spanish'                  => 'Spanish',
            'title'                    => 'Bagisto Installer',
            'turkish'                  => 'Turkish',
            'ukrainian'                => 'Ukrainian',
            'webkul'                   => 'Webkul',
        ],
    ],
];
