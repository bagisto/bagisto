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

                'services-content' => [
                    'name'  => 'Services Content',
                    
                    'title' => [
                        'free-shipping'     => 'Free Shipping',
                        'product-replace'   => 'Product Replace',
                        'emi-available'     => 'Emi Available',
                        'time-support'      => '24/7 Support',
                    ],

                    'description' => [
                        'free-shipping-info'     => 'Enjoy free shipping on all orders',
                        'product-replace-info'   => 'Easy Product Replacement Available!',
                        'emi-available-info'     => 'No cost EMI available on all major credit cards',
                        'time-support-info'      => 'Dedicated 24/7 support via chat and email',
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
            'admin'                         => 'Admin',
            'application-name'              => 'Application Name',
            'bagisto'                       => 'Bagisto',
            'bagisto-logo'                  => 'Bagisto Logo',
            'bagisto-info'                  => 'a Community Project by',
            'back'                          => 'Back',
            'bagisto-for-installation'      => 'Bagisto For Installation',
            'bagisto-for-installation-info' => 'Click the button below to',
            'calendar'                      => 'Calendar',
            'ctype'                         => 'cType',
            'curl'                          => 'cURL',
            'create-administrator'          => 'Create Administrator',
            'create-databsae-table'         => 'Create the database table',
            'continue'                      => 'Continue',
            'confirm-password'              => 'Confirm Password',
            'default-url'                   => 'Default URL',
            'default-url-link'              => 'https://localhost',
            'dom'                           => 'dom',
            'default-currency'              => 'Default Currency',
            'default-timezone'              => 'Default Timezone',
            'default-locale'                => 'Default Locale',
            'database-connection'           => 'Database Connection',
            'database-hostname'             => 'Database Hostname',
            'database-port'                 => 'Database Port',
            'database-name'                 => 'Database Name',
            'database-username'             => 'Database Username',
            'database-prefix'               => 'Database Prefix',
            'database-password'             => 'Database Password',
            'email'                         => 'Email',
            'email-address'                 => 'admin@example.com',
            'email-configuration'           => 'Email Configuration',
            'environment-configuration'     => 'Environment Configuration',
            'encryption'                    => 'Encryption',
            'fileinfo'                      => 'fileInfo',
            'filter'                        => 'Filter',
            'gd'                            => 'GD',
            'hash'                          => 'Hash',
            'intl'                          => 'intl',
            'installation'                  => 'Installation',
            'installation-bagisto'          => 'Installation Bagisto',
            'installation-bagisto-info'     => 'Creating the database tables, this can take a few moments',
            'installation-completed'        => 'Installation Completed',
            'installation-title'            => 'Welcome to Installation',
            'installation-info'             => 'We are happy to see you here!',
            'installation-description'      => 'Bagisto installation typically involves several steps. Here\'s a general outline of the installation process for Bagisto:',
            'json'                          => 'JSON',
            'mbstring'                      => 'mbstring',
            'openssl'                       => 'openssl',
            'outgoing-mail-server'          => 'Outgoing Mail Server',
            'outgoing-email'                => 'smpt.mailtrap.io',
            'password'                      => 'Password',
            'pcre'                          => 'pcre',
            'php'                           => 'PHP',
            'pdo'                           => 'pdo',
            'php-version'                   => '8.1 or higher',
            'populate-database-table'       => 'Populate the database tables',
            'ready-for-installation'        => 'Ready for Installation',
            'session'                       => 'session',
            'server-requirements'           => 'Server Requirements',
            'server-port'                   => 'Server Port',
            'server-port-code'              => '3306',
            'start-installation'            => 'Start Installation',
            'store-email'                   => 'Store Email Address',
            'stored-email-address'          => 'store@example.com',
            'tokenizer'                     => 'tokenizer',
            'title'                         => 'Bagisto Installer',
            'username'                      => 'Username',
            'webkul'                        => 'Webkul',
            'xml'                           => 'XML',
        ],
    ],
];
