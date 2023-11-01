<?php

return [
    'seeders' => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'Default',
            ],

            'attribute-groups' => [
                'general'           => 'General',
                'description'       => 'Description',
                'meta-description'  => 'Meta Description',
                'price'             => 'Price',
                'shipping'          => 'Shipping',
                'settings'          => 'Settings',
                'inventories'       => 'Inventories',
            ],

            'attributes' => [
                'sku'                  => 'SKU',
                'name'                 => 'Name',
                'url-key'              => 'URL Key',
                'tax-category'         => 'Tax Category',
                'new'                  => 'New',
                'featured'             => 'Featured',
                'visible-individually' => 'Visible Individually',
                'status'               => 'Status',
                'short-description'    => 'Short Description',
                'description'          => 'Description',
                'price'                => 'Price',
                'cost'                 => 'Cost',
                'special-price'        => 'Special Price',
                'special-price-from'   => 'Special Price From',
                'special-price-to'     => 'Special Price To',
                'meta-title'           => 'Meta Title',
                'meta-keywords'        => 'Meta Keywords',
                'meta-description'     => 'Meta Description',
                'length'               => 'Length',
                'width'                => 'Width',
                'height'               => 'Height',
                'weight'               => 'Weight',
                'color'                => 'Color',
                'size'                 => 'Size',
                'brand'                => 'Brand',
                'guest-checkout'       => 'Guest Checkout',
                'product-number'       => 'Product Number',
                'manage-stock'         => 'Manage Stock',
            ],

            'attribute-options' => [
                'red'    => 'Red',
                'green'  => 'Green',
                'yellow' => 'Yellow',
                'black'  => 'Black',
                'white'  => 'White',
                's'      => 'S',
                'm'      => 'M',
                'l'      => 'L',
                'xl'     => 'XL',
            ],
        ],

        'category' => [
            'categories' => [
                'name'        => 'Root',
                'description' => 'Root Category Description',
            ],
        ],

        'cms' => [
            'pages' => [
                'about-us' => [
                    'title'   => 'About Us',
                    'content' => 'About Us Page Content',
                ],

                'refund-policy' => [
                    'title'   => 'Refund Policy',
                    'content' => 'Refund Policy Page Content',
                ],

                'return-policy' => [
                    'title'   => 'Return Policy',
                    'content' => 'Return Policy Page Content',
                ],

                'terms-conditions' => [
                    'title'   => 'Terms & Conditions',
                    'content' => 'Terms & Conditions Page Content',
                ],

                'terms-of-use' => [
                    'title'   => 'Terms of Use',
                    'content' => 'Terms of Use Page Content',
                ],

                'contact-us' => [
                    'title'   => 'Contact Us',
                    'content' => 'Contact Us Page Content',
                ],

                'customer-service' => [
                    'title'   => 'Customer Service',
                    'content' => 'Customer Service Page Content',
                ],

                'whats-new' => [
                    'title'   => 'What\'s New',
                    'content' => 'What\'s New page content',
                ],

                'payment-policy' => [
                    'title'   => 'Payment Policy',
                    'content' => 'Payment Policy Page Content',
                ],

                'shipping-policy' => [
                    'title'   => 'Shipping Policy',
                    'content' => 'Shipping Policy Page Content',
                ],

                'privacy-policy' => [
                    'title'   => 'Privacy Policy',
                    'content' => 'Privacy Policy Page Content',
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
                'pt_BR' => 'Brazilian Portuguese',
                'zh_CN' => 'Chinese',
                'nl'    => 'Dutch',
                'en'    => 'English',
                'fr'    => 'French',
                'de'    => 'German',
                'he'    => 'Hebrew',
                'hi_IN' => 'Hindi',
                'it'    => 'Italian',
                'ja'    => 'Japanese',
                'fa'    => 'Persian',
                'pl'    => 'Polish',
                'ru'    => 'Russian',
                'sin'   => 'Sinhala',
                'es'    => 'Spanish',
                'tr'    => 'Turkish',
                'uk'    => 'Ukrainian',
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
                        'title'       => 'The game with our new additions!',
                        'sub-title-1' => 'Our Collections',
                        'sub-title-2' => 'Our Collections',
                        'sub-title-3' => 'Our Collections',
                        'sub-title-4' => 'Our Collections',
                        'sub-title-5' => 'Our Collections',
                        'sub-title-6' => 'Our Collections',
                    ],
                ],

                'bold-collections' => [
                    'name' => 'Bold Collections',

                    'content' => [
                        'title'       => 'Get Ready for our new Bold Collections!',
                        'description' => 'Introducing Our New Bold Collections! Elevate your style with daring designs and vibrant statements. Explore striking patterns and bold colors that redefine your wardrobe. Get ready to embrace the extraordinary!',
                        'btn-title'   => 'View All',
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
                        'title'       => 'The game with our new additions!',
                        'sub-title-1' => 'Our Collections',
                        'sub-title-2' => 'Our Collections',
                    ],
                ],

                'all-products' => [
                    'name' => 'All Products',

                    'options' => [
                        'title' => 'All Products',
                    ],
                ],

                'bold-collections' => [
                    'name' => 'Bold Collections',

                    'content' => [
                        'title'       => 'Get Ready for our new Bold Collections!',
                        'description' => 'Introducing Our New Bold Collections! Elevate your style with daring designs and vibrant statements. Explore striking patterns and bold colors that redefine your wardrobe. Get ready to embrace the extraordinary!',
                        'btn-title'   => 'View All',
                    ],
                ],

                'footer-links' => [
                    'name' => 'Footer Links',

                    'options' => [
                        'about-us'         => 'About Us',
                        'contact-us'       => 'Contact Us',
                        'customer-service' => 'Customer Service',
                        'whats-new'        => 'What\'s New',
                        'terms-of-use'     => 'Terms of Use',
                        'terms-conditions' => 'Terms & Conditions',
                        'privacy-policy'   => 'Privacy Policy',
                        'payment-policy'   => 'Payment Policy',
                        'shipping-policy'  => 'Shipping Policy',
                        'return-policy'    => 'Return Policy',
                        'refund-policy'    => 'Refund Policy',
                    ],
                ],
            ],
        ],

        'user' => [
            'users' => [
                'name' => 'Example',
            ],

            'roles' => [
                'name'        => 'Administrator',
                'description' => 'This role users will have all the access',
            ],
        ],
    ],
];
