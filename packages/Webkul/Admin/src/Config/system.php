<?php

return [
    /**
     * General.
     */
    [
        'key'  => 'general',
        'name' => 'admin::app.configuration.index.general.general.title',
        'info' => 'admin::app.configuration.index.general.general.info',
        'sort' => 1,
    ], [
        'key'  => 'general.general',
        'name' => 'admin::app.configuration.index.general.general.title',
        'info' => 'admin::app.configuration.index.general.general.info',
        'icon' => 'settings/store.svg',
        'sort' => 1,
    ], [
        'key'    => 'general.general.locale_options',
        'name'   => 'admin::app.configuration.index.general.general.unit-options',
        'info'   => 'admin::app.configuration.index.general.general.unit-options-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'weight_unit',
                'title'         => 'admin::app.configuration.index.general.general.weight-unit',
                'type'          => 'select',
                'options'       => [
                    [
                        'title' => 'lbs',
                        'value' => 'lbs',
                    ], [
                        'title' => 'kgs',
                        'value' => 'kgs',
                    ],
                ],
                'channel_based' => true,
            ],
        ],
    ], [
        'key'  => 'general.content',
        'name' => 'admin::app.configuration.index.general.content.title',
        'info' => 'admin::app.configuration.index.general.content.info',
        'icon' => 'settings/store.svg',
        'sort' => 2,
    ], [
        'key'    => 'general.content.shop',
        'name'   => 'admin::app.configuration.index.general.content.settings-title',
        'info'   => 'admin::app.configuration.index.general.content.settings-title-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'compare_option',
                'title'         => 'admin::app.configuration.index.general.content.compare-options',
                'type'          => 'boolean',
                'locale_based'  => true,
                'channel_based' => true,
            ], [
                'name'          => 'wishlist_option',
                'title'         => 'admin::app.configuration.index.general.content.wishlist-options',
                'type'          => 'boolean',
                'locale_based'  => true,
                'channel_based' => true,
            ], [
                'name'          => 'image_search',
                'title'         => 'admin::app.configuration.index.general.content.image-search-option',
                'type'          => 'boolean',
                'locale_based'  => true,
                'channel_based' => true,
            ],
        ],
    ], [
        'key'    => 'general.content.custom_scripts',
        'name'   => 'admin::app.configuration.index.general.content.custom-scripts',
        'info'   => 'admin::app.configuration.index.general.content.custom-scripts-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'custom_css',
                'title'         => 'admin::app.configuration.index.general.content.custom-css',
                'type'          => 'textarea',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'          => 'custom_javascript',
                'title'         => 'admin::app.configuration.index.general.content.custom-javascript',
                'type'          => 'textarea',
                'channel_based' => true,
                'locale_based'  => false,
            ],
        ],
    ], [
        'key'  => 'general.design',
        'name' => 'admin::app.configuration.index.general.design.title',
        'info' => 'admin::app.configuration.index.general.design.info',
        'icon' => 'settings/theme.svg',
        'sort' => 3,
    ], [
        'key'    => 'general.design.admin_logo',
        'name'   => 'admin::app.configuration.index.general.design.admin-logo',
        'info'   => 'admin::app.configuration.index.general.design.admin-logo-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'logo_image',
                'title'         => 'admin::app.configuration.index.general.design.logo-image',
                'type'          => 'image',
                'channel_based' => true,
                'validation'    => 'mimes:bmp,jpeg,jpg,png,webp',
            ], [
                'name'          => 'favicon',
                'title'         => 'admin::app.configuration.index.general.design.favicon',
                'type'          => 'image',
                'channel_based' => true,
                'validation'    => 'mimes:bmp,jpeg,jpg,png,webp',
            ],
        ],
    ],

    /**
     * Catalog.
     */
    [
        'key'  => 'catalog',
        'name' => 'admin::app.configuration.index.catalog.title',
        'info' => 'admin::app.configuration.index.catalog.info',
        'sort' => 2,
    ], [
        'key'  => 'catalog.products',
        'name' => 'admin::app.configuration.index.catalog.products.title',
        'info' => 'admin::app.configuration.index.catalog.products.info',
        'icon' => 'settings/store-information.svg',
        'sort' => 2,
    ], [
        'key'    => 'catalog.products.guest_checkout',
        'name'   => 'admin::app.configuration.index.catalog.products.guest-checkout',
        'info'   => 'admin::app.configuration.index.catalog.products.guest-checkout-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'  => 'allow_guest_checkout',
                'title' => 'admin::app.configuration.index.catalog.products.allow-guest-checkout',
                'type'  => 'boolean',
            ],
        ],
    ], [
        'key'    => 'catalog.products.homepage',
        'name'   => 'admin::app.configuration.index.catalog.products.homepage',
        'info'   => 'admin::app.configuration.index.catalog.products.homepage-info',
        'sort'   => 2,
        'fields' => [
            [
                'name'       => 'no_of_new_product_homepage',
                'title'      => 'admin::app.configuration.index.catalog.products.allow-no-of-new-products',
                'type'       => 'number',
                'validation' => 'min:0',
            ],
            [
                'name'       => 'no_of_featured_product_homepage',
                'title'      => 'admin::app.configuration.index.catalog.products.allow-no-of-featured-products',
                'type'       => 'number',
                'validation' => 'min:0',
            ],
        ],
    ], [
        'key'    => 'catalog.products.product_view_page',
        'name'   => 'admin::app.configuration.index.catalog.products.product-view-page',
        'info'   => 'admin::app.configuration.index.catalog.products.product-view-page-info',
        'sort'   => 3,
        'fields' => [
            [
                'name'       => 'no_of_related_products',
                'title'      => 'admin::app.configuration.index.catalog.products.allow-no-of-related-products',
                'type'       => 'number',
                'validation' => 'min:0',
            ], [
                'name'       => 'no_of_up_sells_products',
                'title'      => 'admin::app.configuration.index.catalog.products.allow-no-of-up-sells-products',
                'type'       => 'number',
                'validation' => 'min:0',
            ],
        ],
    ], [
        'key'    => 'catalog.products.cart_view_page',
        'name'   => 'admin::app.configuration.index.catalog.products.cart-view-page',
        'info'   => 'admin::app.configuration.index.catalog.products.cart-view-page-info',
        'sort'   => 3,
        'fields' => [
            [
                'name'       => 'no_of_cross_sells_products',
                'title'      => 'admin::app.configuration.index.catalog.products.allow-no-of-cross-sells-products',
                'type'       => 'number',
                'validation' => 'min:0',
            ],
        ],
    ], [
        'key'    => 'catalog.products.storefront',
        'name'   => 'admin::app.configuration.index.catalog.products.storefront',
        'info'   => 'admin::app.configuration.index.catalog.products.storefront-info',
        'sort'   => 3,
        'fields' => [
            [
                'name'          => 'search_mode',
                'title'         => 'admin::app.configuration.index.catalog.products.search-mode',
                'type'          => 'select',
                'options'       => [
                    [
                        'title' => 'admin::app.configuration.index.catalog.products.flat',
                        'value' => 'flat',
                    ], [
                        'title' => 'admin::app.configuration.index.catalog.products.elastic',
                        'value' => 'elastic',
                    ],
                ],
            ], [
                'name'          => 'mode',
                'title'         => 'admin::app.configuration.index.catalog.products.default-list-mode',
                'type'          => 'select',
                'options'       => [
                    [
                        'title' => 'admin::app.configuration.index.catalog.products.grid',
                        'value' => 'grid',
                    ], [
                        'title' => 'admin::app.configuration.index.catalog.products.list',
                        'value' => 'list',
                    ],
                ],
                'channel_based' => true,
            ], [
                'name'          => 'products_per_page',
                'title'         => 'admin::app.configuration.index.catalog.products.products-per-page',
                'type'          => 'text',
                'info'          => 'admin::app.configuration.index.catalog.products.comma-separated',
                'channel_based' => true,
            ], [
                'name'          => 'sort_by',
                'title'         => 'admin::app.configuration.index.catalog.products.sort-by',
                'type'          => 'select',
                'options'       => [
                    [
                        'title' => 'admin::app.configuration.index.catalog.products.from-a-z',
                        'value' => 'name-asc',
                    ], [
                        'title' => 'admin::app.configuration.index.catalog.products.from-z-a',
                        'value' => 'name-desc',
                    ], [
                        'title' => 'admin::app.configuration.index.catalog.products.latest-first',
                        'value' => 'created_at-desc',
                    ], [
                        'title' => 'admin::app.configuration.index.catalog.products.oldest-first',
                        'value' => 'created_at-asc',
                    ], [
                        'title' => 'admin::app.configuration.index.catalog.products.cheapest-first',
                        'value' => 'price-asc',
                    ], [
                        'title' => 'admin::app.configuration.index.catalog.products.expensive-first',
                        'value' => 'price-desc',
                    ],
                ],
                'channel_based' => true,
            ], [
                'name'  => 'buy_now_button_display',
                'title' => 'admin::app.configuration.index.catalog.products.buy-now-button-display',
                'type'  => 'boolean',
            ],
        ],
    ], [
        'key'    => 'catalog.products.cache_small_image',
        'name'   => 'admin::app.configuration.index.catalog.products.cache-small-image',
        'info'   => 'admin::app.configuration.index.catalog.products.cache-small-image-info',
        'sort'   => 4,
        'fields' => [
            [
                'name'  => 'width',
                'title' => 'admin::app.configuration.index.catalog.products.width',
                'type'  => 'text',
            ],
            [
                'name'  => 'height',
                'title' => 'admin::app.configuration.index.catalog.products.height',
                'type'  => 'text',
            ],
        ],
    ], [
        'key'    => 'catalog.products.cache_medium_image',
        'name'   => 'admin::app.configuration.index.catalog.products.cache-medium-image',
        'info'   => 'admin::app.configuration.index.catalog.products.cache-medium-image-info',
        'sort'   => 5,
        'fields' => [
            [
                'name'  => 'width',
                'title' => 'admin::app.configuration.index.catalog.products.width',
                'type'  => 'text',
            ],
            [
                'name'  => 'height',
                'title' => 'admin::app.configuration.index.catalog.products.height',
                'type'  => 'text',
            ],
        ],
    ], [
        'key'    => 'catalog.products.cache_large_image',
        'name'   => 'admin::app.configuration.index.catalog.products.cache-large-image',
        'info'   => 'admin::app.configuration.index.catalog.products.cache-large-image-info',
        'sort'   => 6,
        'fields' => [
            [
                'name'  => 'width',
                'title' => 'admin::app.configuration.index.catalog.products.width',
                'type'  => 'text',
            ],
            [
                'name'  => 'height',
                'title' => 'admin::app.configuration.index.catalog.products.height',
                'type'  => 'text',
            ],
        ],
    ], [
        'key'    => 'catalog.products.review',
        'name'   => 'admin::app.configuration.index.catalog.products.review',
        'info'   => 'admin::app.configuration.index.catalog.products.review-info',
        'sort'   => 7,
        'fields' => [
            [
                'name'  => 'guest_review',
                'title' => 'admin::app.configuration.index.catalog.products.allow-guest-review',
                'type'  => 'boolean',
            ],
        ],
    ], [
        'key'    => 'catalog.products.attribute',
        'name'   => 'admin::app.configuration.index.catalog.products.attribute',
        'info'   => 'admin::app.configuration.index.catalog.products.attribute-info',
        'sort'   => 8,
        'fields' => [
            [
                'name'  => 'image_attribute_upload_size',
                'title' => 'admin::app.configuration.index.catalog.products.image-upload-size',
                'type'  => 'text',
            ], [
                'name'  => 'file_attribute_upload_size',
                'title' => 'admin::app.configuration.index.catalog.products.file-upload-size',
                'type'  => 'text',
            ],
        ],
    ], [
        'key'    => 'catalog.products.social_share',
        'name'   => 'social_share::app.title',
        'info'   => 'social_share::app.title-info',
        'sort'   => 100,
        'fields' => [
            [
                'name'  => 'enabled',
                'title' => 'social_share::app.configurations.system.enable-social-share',
                'type'  => 'boolean',
            ], [
                'name'  => 'facebook',
                'title' => 'social_share::app.configurations.system.enable-share-facebook',
                'type'  => 'boolean',
            ], [
                'name'  => 'twitter',
                'title' => 'social_share::app.configurations.system.enable-share-twitter',
                'type'  => 'boolean',
            ], [
                'name'  => 'pinterest',
                'title' => 'social_share::app.configurations.system.enable-share-pinterest',
                'type'  => 'boolean',
            ], [
                'name'  => 'whatsapp',
                'title' => 'social_share::app.configurations.system.enable-share-whatsapp',
                'type'  => 'boolean',
                'info'  => 'What\'s App share link just will appear to mobile devices.'
            ], [
                'name'  => 'linkedin',
                'title' => 'social_share::app.configurations.system.enable-share-linkedin',
                'type'  => 'boolean',
            ], [
                'name'  => 'email',
                'title' => 'social_share::app.configurations.system.enable-share-email',
                'type'  => 'boolean',
            ], [
                'name'  => 'share_message',
                'title' => 'social_share::app.configurations.system.share-message',
                'type'  => 'text',
            ],
        ],
    ], [
        'key'  => 'catalog.inventory',
        'name' => 'admin::app.configuration.index.catalog.inventory.title',
        'info' => 'admin::app.configuration.index.catalog.inventory.info',
        'icon' => 'settings/store.svg',
        'sort' => 1,
    ], [
        'key'    => 'catalog.inventory.stock_options',
        'name'   => 'admin::app.configuration.index.catalog.inventory.stock-options',
        'info'   => 'admin::app.configuration.index.catalog.inventory.stock-options-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'back_orders',
                'title'         => 'admin::app.configuration.index.catalog.inventory.allow-back-orders',
                'type'          => 'boolean',
                'channel_based' => true,
            ],
        ],
    ], [
        'key'  => 'catalog.rich_snippets',
        'name' => 'admin::app.configuration.index.catalog.rich-snippets.title',
        'info' => 'admin::app.configuration.index.catalog.rich-snippets.info',
        'icon' => 'settings/settings.svg',
        'sort' => 3,
    ], [
        'key'    => 'catalog.rich_snippets.products',
        'name'   => 'admin::app.configuration.index.catalog.rich-snippets.products',
        'info'   => 'admin::app.configuration.index.catalog.rich-snippets.rich-snippet-product-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'  => 'enable',
                'title' => 'admin::app.configuration.index.catalog.rich-snippets.enable',
                'type'  => 'boolean',
            ], [
                'name'  => 'show_sku',
                'title' => 'admin::app.configuration.index.catalog.rich-snippets.show-sku',
                'type'  => 'boolean',
            ], [
                'name'  => 'show_weight',
                'title' => 'admin::app.configuration.index.catalog.rich-snippets.show-weight',
                'type'  => 'boolean',
            ], [
                'name'  => 'show_categories',
                'title' => 'admin::app.configuration.index.catalog.rich-snippets.show-categories',
                'type'  => 'boolean',
            ], [
                'name'  => 'show_images',
                'title' => 'admin::app.configuration.index.catalog.rich-snippets.show-images',
                'type'  => 'boolean',
            ], [
                'name'  => 'show_reviews',
                'title' => 'admin::app.configuration.index.catalog.rich-snippets.show-reviews',
                'type'  => 'boolean',
            ], [
                'name'  => 'show_ratings',
                'title' => 'admin::app.configuration.index.catalog.rich-snippets.show-ratings',
                'type'  => 'boolean',
            ], [
                'name'  => 'show_offers',
                'title' => 'admin::app.configuration.index.catalog.rich-snippets.show-offers',
                'type'  => 'boolean',
            ],
        ],
    ], [
        'key'    => 'catalog.rich_snippets.categories',
        'name'   => 'admin::app.configuration.index.catalog.rich-snippets.categories',
        'info'   => 'admin::app.configuration.index.catalog.rich-snippets.categories-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'  => 'enable',
                'title' => 'admin::app.configuration.index.catalog.rich-snippets.enable',
                'type'  => 'boolean',
            ], [
                'name'  => 'show_search_input_field',
                'title' => 'admin::app.configuration.index.catalog.rich-snippets.show-search-input-field',
                'type'  => 'boolean',
            ],
        ],
    ],

    /**
     * Customer.
     */
    [
        'key'  => 'customer',
        'name' => 'admin::app.configuration.index.customer.title',
        'info' => 'admin::app.configuration.index.customer.info',
        'sort' => 3,
    ], [
        'key'  => 'customer.address',
        'name' => 'admin::app.configuration.index.customer.address.title',
        'info' => 'admin::app.configuration.index.customer.address.info',
        'icon' => 'settings/address.svg',
        'sort' => 1,
    ], [
        'key'    => 'customer.address.requirements',
        'name'   => 'admin::app.configuration.index.customer.address.requirements',
        'info'   => 'admin::app.configuration.index.customer.address.requirements-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'country',
                'title'         => 'admin::app.configuration.index.customer.address.country',
                'type'          => 'boolean',
                'channel_based' => true,
                'default'       => '1',
            ],
            [
                'name'          => 'state',
                'title'         => 'admin::app.configuration.index.customer.address.state',
                'type'          => 'boolean',
                'channel_based' => true,
                'default'       => '1',
            ],
            [
                'name'          => 'postcode',
                'title'         => 'admin::app.configuration.index.customer.address.zip',
                'type'          => 'boolean',
                'channel_based' => true,
                'default'       => '1',
            ],
        ],
    ], [
        'key'    => 'customer.address.information',
        'name'   => 'admin::app.configuration.index.customer.address.information',
        'info'   => 'admin::app.configuration.index.customer.address.information-info',
        'sort'   => 2,
        'fields' => [
            [
                'name'          => 'street_lines',
                'title'         => 'admin::app.configuration.index.customer.address.street-lines',
                'type'          => 'text',
                'validation'    => 'between:1,4',
                'channel_based' => true,
            ],
        ],
    ], [
        'key'  => 'customer.captcha',
        'name' => 'customer::app.admin.system.captcha.title',
        'info' => 'customer::app.admin.system.captcha.info',
        'icon' => 'settings/captcha.svg',
        'sort' => 2,
    ], [
        'key'    => 'customer.captcha.credentials',
        'name'   => 'customer::app.admin.system.captcha.credentials',
        'info'   => 'customer::app.admin.system.captcha.credentials-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'site_key',
                'title'         => 'customer::app.admin.system.captcha.site-key',
                'type'          => 'text',
                'channel_based' => true,
            ], [
                'name'          => 'secret_key',
                'title'         => 'customer::app.admin.system.captcha.secret-key',
                'type'          => 'text',
                'channel_based' => true,
            ], [
                'name'          => 'status',
                'title'         => 'customer::app.admin.system.captcha.status',
                'type'          => 'boolean',
                'channel_based' => true,
            ],
        ],
    ], [
        'key'  => 'customer.settings',
        'name' => 'admin::app.configuration.index.customer.settings.title',
        'info' => 'admin::app.configuration.index.customer.settings.settings-info',
        'icon' => 'settings/settings.svg',
        'sort' => 3,
    ], [
        'key'    => 'customer.settings.wishlist',
        'name'   => 'admin::app.configuration.index.customer.settings.wishlist',
        'info'   => 'admin::app.configuration.index.customer.settings.wishlist-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'  => 'share',
                'title' => 'admin::app.configuration.index.customer.settings.wishlist-share',
                'type'  => 'boolean',
            ],
        ],
    ], [
        'key'    => 'customer.settings.newsletter',
        'name'   => 'admin::app.configuration.index.customer.settings.newsletter',
        'info'   => 'admin::app.configuration.index.customer.settings.newsletter-info',
        'sort'   => 2,
        'fields' => [
            [
                'name'  => 'subscription',
                'title' => 'admin::app.configuration.index.customer.settings.newsletter-subscription',
                'type'  => 'boolean',
            ],
        ],
    ], [
        'key'    => 'customer.settings.email',
        'name'   => 'admin::app.configuration.index.customer.settings.email',
        'info'   => 'admin::app.configuration.index.customer.settings.email-info',
        'sort'   => 3,
        'fields' => [
            [
                'name'  => 'verification',
                'title' => 'admin::app.configuration.index.customer.settings.email-verification',
                'type'  => 'boolean',
            ],
        ],
    ], [
        'key'    => 'customer.settings.social_login',
        'name'   => 'social_login::app.admin.system.social-login',
        'info'   => 'social_login::app.admin.system.social-login-info',
        'sort'   => 4,
        'fields' => [
            [
                'name'          => 'enable_facebook',
                'title'         => 'social_login::app.admin.system.enable-facebook',
                'type'          => 'boolean',
                'channel_based' => true,
            ], [
                'name'          => 'enable_twitter',
                'title'         => 'social_login::app.admin.system.enable-twitter',
                'type'          => 'boolean',
                'channel_based' => true,
            ], [
                'name'          => 'enable_google',
                'title'         => 'social_login::app.admin.system.enable-google',
                'type'          => 'boolean',
                'channel_based' => true,
            ], [
                'name'          => 'enable_linkedin',
                'title'         => 'social_login::app.admin.system.enable-linkedin',
                'type'          => 'boolean',
                'channel_based' => true,
            ], [
                'name'          => 'enable_github',
                'title'         => 'social_login::app.admin.system.enable-github',
                'type'          => 'boolean',
                'channel_based' => true,
            ]
        ],
    ],

    /**
     * Emails.
     */
    [
        'key'  => 'emails',
        'name' => 'admin::app.configuration.index.email.title',
        'info' => 'admin::app.configuration.index.email.info',
        'sort' => 4,
    ], [
        'key'  => 'emails.configure',
        'name' => 'admin::app.configuration.index.email.email-settings.title',
        'info' => 'admin::app.configuration.index.email.email-settings.info',
        'icon' => 'settings/email.svg',
        'sort' => 1,
    ], [
        'key'    => 'emails.configure.email_settings',
        'name'   => 'admin::app.configuration.index.email.email-settings.title',
        'info'   => 'admin::app.configuration.index.email.email-settings.info',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'sender_name',
                'title'         => 'admin::app.configuration.index.email.email-settings.email-sender-name',
                'type'          => 'text',
                'info'          => 'admin::app.configuration.index.email.email-settings.email-sender-name-tip',
                'validation'    => 'required|max:50',
                'channel_based' => true,
                'default_value' => config('mail.from.name'),
            ], [
                'name'          => 'shop_email_from',
                'title'         => 'admin::app.configuration.index.email.email-settings.shop-email-from',
                'type'          => 'text',
                'info'          => 'admin::app.configuration.index.email.email-settings.shop-email-from-tip',
                'validation'    => 'required|email',
                'channel_based' => true,
                'default_value' => config('mail.from.address'),
            ], [
                'name'          => 'admin_name',
                'title'         => 'admin::app.configuration.index.email.email-settings.admin-name',
                'type'          => 'text',
                'info'          => 'admin::app.configuration.index.email.email-settings.admin-name-tip',
                'validation'    => 'required|max:50',
                'channel_based' => true,
                'default_value' => config('mail.admin.name'),
            ], [
                'name'          => 'admin_email',
                'title'         => 'admin::app.configuration.index.email.email-settings.admin-email',
                'type'          => 'text',
                'info'          => 'admin::app.configuration.index.email.email-settings.admin-email-tip',
                'validation'    => 'required|email',
                'channel_based' => true,
                'default_value' => config('mail.admin.address'),
            ],
        ],
    ], [
        'key'  => 'emails.general',
        'name' => 'admin::app.configuration.index.email.notifications.title',
        'info' => 'admin::app.configuration.index.email.notifications.info',
        'icon' => 'settings/store.svg',
        'sort' => 1,
    ], [
        'key'    => 'emails.general.notifications',
        'name'   => 'admin::app.configuration.index.email.notifications.title',
        'info'   => 'admin::app.configuration.index.email.notifications.info',
        'sort'   => 1,
        'fields' => [
            [
                'name'  => 'emails.general.notifications.verification',
                'title' => 'admin::app.configuration.index.email.notifications.verification',
                'type'  => 'boolean',
            ], [
                'name'  => 'emails.general.notifications.registration',
                'title' => 'admin::app.configuration.index.email.notifications.registration',
                'type'  => 'boolean',
            ], [
                'name'  => 'emails.general.notifications.customer_registration_confirmation_mail_to_admin',
                'title' => 'admin::app.configuration.index.email.notifications.customer-registration-confirmation-mail-to-admin',
                'type'  => 'boolean',
            ], [
                'name'  => 'emails.general.notifications.customer',
                'title' => 'admin::app.configuration.index.email.notifications.customer',
                'type'  => 'boolean',
            ], [
                'name'  => 'emails.general.notifications.new_order',
                'title' => 'admin::app.configuration.index.email.notifications.new-order',
                'type'  => 'boolean',
            ], [
                'name'  => 'emails.general.notifications.new_admin',
                'title' => 'admin::app.configuration.index.email.notifications.new-admin',
                'type'  => 'boolean',
            ], [
                'name'  => 'emails.general.notifications.new_invoice',
                'title' => 'admin::app.configuration.index.email.notifications.new-invoice',
                'type'  => 'boolean',
            ], [
                'name'  => 'emails.general.notifications.new_refund',
                'title' => 'admin::app.configuration.index.email.notifications.new-refund',
                'type'  => 'boolean',
            ], [
                'name'  => 'emails.general.notifications.new_shipment',
                'title' => 'admin::app.configuration.index.email.notifications.new-shipment',
                'type'  => 'boolean',
            ], [
                'name'  => 'emails.general.notifications.new_inventory_source',
                'title' => 'admin::app.configuration.index.email.notifications.new-inventory-source',
                'type'  => 'boolean',
            ], [
                'name'  => 'emails.general.notifications.cancel_order',
                'title' => 'admin::app.configuration.index.email.notifications.cancel-order',
                'type'  => 'boolean',
            ],
        ],
    ],

    /**
     * Sales.
     */
    [
        'key'  => 'sales',
        'name' => 'admin::app.configuration.index.sales.title',
        'info' => 'admin::app.configuration.index.sales.info',
        'sort' => 5,
    ], [
       'key'  => 'sales.shipping',
       'name' => 'admin::app.configuration.index.sales.shipping.title',
       'info' => 'admin::app.configuration.index.sales.shipping.info',
       'icon' => 'settings/shipping.svg',
       'sort' => 1,
    ], [
        'key'    => 'sales.shipping.origin',
        'name'   => 'admin::app.configuration.index.sales.shipping.origin',
        'info'   => 'admin::app.configuration.index.sales.shipping.origin-info',
        'sort'   => 0,
        'fields' => [
            [
                'name'          => 'country',
                'title'         => 'admin::app.configuration.index.sales.shipping.country',
                'type'          => 'country',
                'validation'    => 'required',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'state',
                'title'         => 'admin::app.configuration.index.sales.shipping.state',
                'type'          => 'state',
                'validation'    => 'required',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'city',
                'title'         => 'admin::app.configuration.index.sales.shipping.city',
                'type'          => 'text',
                'validation'    => 'required',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'          => 'address1',
                'title'         => 'admin::app.configuration.index.sales.shipping.street-address',
                'type'          => 'text',
                'validation'    => 'required',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'          => 'zipcode',
                'title'         => 'admin::app.configuration.index.sales.shipping.zip',
                'type'          => 'text',
                'validation'    => 'required',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'          => 'store_name',
                'title'         => 'admin::app.configuration.index.sales.shipping.store-name',
                'type'          => 'text',
                'channel_based' => true,
            ], [
                'name'          => 'vat_number',
                'title'         => 'admin::app.configuration.index.sales.shipping.vat-number',
                'type'          => 'text',
                'channel_based' => true,
            ], [
                'name'          => 'contact',
                'title'         => 'admin::app.configuration.index.sales.shipping.contact-number',
                'type'          => 'text',
                'channel_based' => true,
            ], [
                'name'          => 'bank_details',
                'title'         => 'admin::app.configuration.index.sales.shipping.bank-details',
                'type'          => 'textarea',
                'channel_based' => true,
            ],
        ],
    ], [
        'key'  => 'sales.carriers',
        'name' => 'admin::app.configuration.index.sales.shipping-methods.page-title',
        'info' => 'admin::app.configuration.index.sales.shipping-methods.info',
        'icon' => 'settings/shipping-method.svg',
        'sort' => 2,
    ], [
        'key'    => 'sales.carriers.free',
        'name'   => 'admin::app.configuration.index.sales.shipping-methods.free-shipping',
        'info'   => 'admin::app.configuration.index.sales.shipping-methods.free-shipping-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'title',
                'title'         => 'admin::app.configuration.index.sales.shipping-methods.title',
                'type'          => 'depends',
                'depend'        => 'active:1',
                'validation'    => 'required_if:active,1',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'description',
                'title'         => 'admin::app.configuration.index.sales.shipping-methods.description',
                'type'          => 'textarea',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'active',
                'title'         => 'admin::app.configuration.index.sales.shipping-methods.status',
                'type'          => 'boolean',
                'validation'    => 'required',
                'channel_based' => true,
                'locale_based'  => false,
            ],
        ],
    ], [
        'key'    => 'sales.carriers.flat_rate',
        'name'   => 'admin::app.configuration.index.sales.shipping-methods.flat-rate-shipping',
        'info'   => 'admin::app.configuration.index.sales.shipping-methods.flat-rate-shipping-info',
        'sort'   => 2,
        'fields' => [
            [
                'name'          => 'title',
                'title'         => 'admin::app.configuration.index.sales.shipping-methods.title',
                'type'          => 'depends',
                'depend'        => 'active:1',
                'validation'    => 'required_if:active,1',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'description',
                'title'         => 'admin::app.configuration.index.sales.shipping-methods.description',
                'type'          => 'textarea',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'default_rate',
                'title'         => 'admin::app.configuration.index.sales.shipping-methods.rate',
                'type'          => 'depends',
                'depend'        => 'active:1',
                'validation'    => 'required_if:active,1',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'    => 'type',
                'title'   => 'admin::app.configuration.index.sales.shipping-methods.type',
                'type'    => 'select',
                'options' => [
                    [
                        'title' => 'Per Unit',
                        'value' => 'per_unit',
                    ], [
                        'title' => 'Per Order',
                        'value' => 'per_order',
                    ],
                ],
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'          => 'active',
                'title'         => 'admin::app.configuration.index.sales.shipping-methods.status',
                'type'          => 'boolean',
                'validation'    => 'required',
                'channel_based' => true,
                'locale_based'  => false,
            ],
        ],
    ], [
        'key'  => 'sales.paymentmethods',
        'name' => 'admin::app.configuration.index.sales.payment-methods.page-title',
        'info' => 'admin::app.configuration.index.sales.payment-methods.info',
        'icon' => 'settings/payment-method.svg',
        'sort' => 3,
    ], [
        'key'    => 'sales.paymentmethods.cashondelivery',
        'name'   => 'admin::app.configuration.index.sales.payment-methods.cash-on-delivery',
        'info'   => 'admin::app.configuration.index.sales.payment-methods.cash-on-delivery-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'title',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.title',
                'type'          => 'depends',
                'depend'        => 'active:1',
                'validation'    => 'required_if:active,1',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'description',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.description',
                'type'          => 'textarea',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'instructions',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.instructions',
                'type'          => 'textarea',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'generate_invoice',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.generate-invoice',
                'type'          => 'boolean',
                'default_value' => false,
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'          => 'invoice_status',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.set-invoice-status',
                'validation'    => 'required_if:generate_invoice,1',
                'type'          => 'select',
                'options'       => [
                    [
                        'title' => 'admin::app.configuration.index.sales.payment-methods.pending',
                        'value' => 'pending',
                    ], [
                        'title' => 'admin::app.configuration.index.sales.payment-methods.paid',
                        'value' => 'paid',
                    ],
                ],
                'info'          => 'admin::app.configuration.index.sales.payment-methods.set-order-status',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'          => 'order_status',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.set-order-status',
                'type'          => 'select',
                'options'       => [
                    [
                        'title' => 'admin::app.configuration.index.sales.payment-methods.pending',
                        'value' => 'pending',
                    ], [
                        'title' => 'admin::app.configuration.index.sales.payment-methods.pending-payment',
                        'value' => 'pending_payment',
                    ], [
                        'title' => 'admin::app.configuration.index.sales.payment-methods.processing',
                        'value' => 'processing',
                    ],
                ],
                'info'          => 'admin::app.configuration.index.sales.payment-methods.generate-invoice-applicable',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'          => 'active',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.status',
                'type'          => 'boolean',
                'validation'    => 'required',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'    => 'sort',
                'title'   => 'admin::app.configuration.index.sales.payment-methods.sort-order',
                'type'    => 'select',
                'options' => [
                    [
                        'title' => '1',
                        'value' => 1,
                    ], [
                        'title' => '2',
                        'value' => 2,
                    ], [
                        'title' => '3',
                        'value' => 3,
                    ], [
                        'title' => '4',
                        'value' => 4,
                    ],
                ],
            ],
        ],
    ], [
        'key'    => 'sales.paymentmethods.moneytransfer',
        'name'   => 'admin::app.configuration.index.sales.payment-methods.money-transfer',
        'info'   => 'admin::app.configuration.index.sales.payment-methods.money-transfer-info',
        'sort'   => 2,
        'fields' => [
            [
                'name'          => 'title',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.title',
                'type'          => 'depends',
                'depend'        => 'active:1',
                'validation'    => 'required_if:active,1',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'description',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.description',
                'type'          => 'textarea',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'generate_invoice',
                'title'         => 'Automatically generate the invoice after placing an order',
                'type'          => 'boolean',
                'default_value' => false,
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'    => 'invoice_status',
                'title'   => 'Invoice status after creating the invoice',
                'type'    => 'select',
                'options' => [
                    [
                        'title' => 'admin::app.configuration.index.sales.payment-methods.pending',
                        'value' => 'pending',
                    ], [
                        'title' => 'admin::app.configuration.index.sales.payment-methods.paid',
                        'value' => 'paid',
                    ],
                ],
                'info'          => 'admin::app.configuration.index.sales.payment-methods.generate-invoice-applicable',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'    => 'order_status',
                'title'   => 'Order status after creating the invoice',
                'type'    => 'select',
                'options' => [
                    [
                        'title' => 'admin::app.configuration.index.sales.payment-methods.pending',
                        'value' => 'pending',
                    ], [
                        'title' => 'admin::app.configuration.index.sales.payment-methods.pending-payment',
                        'value' => 'pending_payment',
                    ], [
                        'title' => 'admin::app.configuration.index.sales.payment-methods.processing',
                        'value' => 'processing',
                    ],
                ],
                'info'          => 'admin::app.configuration.index.sales.payment-methods.generate-invoice-applicable',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'          => 'mailing_address',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.mailing-address',
                'type'          => 'textarea',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'active',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.status',
                'type'          => 'boolean',
                'validation'    => 'required',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'    => 'sort',
                'title'   => 'admin::app.configuration.index.sales.payment-methods.sort-order',
                'type'    => 'select',
                'options' => [
                    [
                        'title' => '1',
                        'value' => 1,
                    ], [
                        'title' => '2',
                        'value' => 2,
                    ], [
                        'title' => '3',
                        'value' => 3,
                    ], [
                        'title' => '4',
                        'value' => 4,
                    ],
                ],
            ],
        ],
    ], [
        'key'  => 'sales.order_settings',
        'name' => 'admin::app.configuration.index.sales.order-settings.title',
        'info' => 'admin::app.configuration.index.sales.order-settings.info',
        'icon' => 'settings/order.svg',
        'sort' => 4,
    ], [
        'key'    => 'sales.order_settings.order_number',
        'name'   => 'admin::app.configuration.index.sales.order-settings.order-number',
        'info'   => 'admin::app.configuration.index.sales.order-settings.order-number-info',
        'sort'   => 0,
        'fields' => [
            [
                'name'          => 'order_number_prefix',
                'title'         => 'admin::app.configuration.index.sales.order-settings.order-number-prefix',
                'type'          => 'text',
                'validation'    => false,
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'order_number_length',
                'title'         => 'admin::app.configuration.index.sales.order-settings.order-number-length',
                'type'          => 'text',
                'validation'    => 'numeric',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'order_number_suffix',
                'title'         => 'admin::app.configuration.index.sales.order-settings.order-number-suffix',
                'type'          => 'text',
                'validation'    => false,
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'order_number_generator_class',
                'title'         => 'admin::app.configuration.index.sales.order-settings.order-number-generator-class',
                'type'          => 'text',
                'validation'    => false,
                'channel_based' => true,
                'locale_based'  => true,
            ],
        ],
    ], [
        'key'    => 'sales.order_settings.minimum_order',
        'name'   => 'admin::app.configuration.index.sales.order-settings.minimum-order',
        'info'   => 'admin::app.configuration.index.sales.order-settings.minimum-order-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'minimum_order_amount',
                'title'         => 'admin::app.configuration.index.sales.order-settings.minimum-order-amount',
                'type'          => 'number',
                'validation'    => 'regex:^-?\d+(\.\d+)?$',
                'channel_based' => true,
                'locale_based'  => true,
            ],
        ],
    ], [
        'key'  => 'sales.invoice_settings',
        'name' => 'admin::app.configuration.index.sales.invoice-settings.title',
        'info' => 'admin::app.configuration.index.sales.invoice-settings.info',
        'icon' => 'settings/invoice.svg',
        'sort' => 5,
    ], [
        'key'    => 'sales.invoice_settings.invoice_number',
        'name'   => 'admin::app.configuration.index.sales.invoice-settings.invoice-number',
        'info'   => 'admin::app.configuration.index.sales.invoice-settings.invoice-number-info',
        'sort'   => 0,
        'fields' => [
            [
                'name'          => 'invoice_number_prefix',
                'title'         => 'admin::app.configuration.index.sales.invoice-settings.invoice-number-prefix',
                'type'          => 'text',
                'validation'    => false,
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'invoice_number_length',
                'title'         => 'admin::app.configuration.index.sales.invoice-settings.invoice-number-length',
                'type'          => 'text',
                'validation'    => 'numeric',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'invoice_number_suffix',
                'title'         => 'admin::app.configuration.index.sales.invoice-settings.invoice-number-suffix',
                'type'          => 'text',
                'validation'    => false,
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'invoice_number_generator_class',
                'title'         => 'admin::app.configuration.index.sales.invoice-settings.invoice-number-generator-class',
                'type'          => 'text',
                'validation'    => false,
                'channel_based' => true,
                'locale_based'  => true,
            ],
        ],
    ], [
        'key'    => 'sales.invoice_settings.payment_terms',
        'name'   => 'admin::app.configuration.index.sales.invoice-settings.payment-terms',
        'info'   => 'admin::app.configuration.index.sales.invoice-settings.payment-terms-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'due_duration',
                'title'         => 'admin::app.configuration.index.sales.invoice-settings.due-duration',
                'type'          => 'text',
                'validation'    => 'numeric',
                'channel_based' => true,
            ],
        ],
    ], [
        'key'    => 'sales.invoice_settings.invoice_slip_design',
        'name'   => 'admin::app.configuration.index.sales.invoice-settings.invoice-slip-design',
        'info'   => 'admin::app.configuration.index.sales.invoice-settings.invoice-slip-design-info',
        'sort'   => 2,
        'fields' => [
            [
                'name'          => 'logo',
                'title'         => 'admin::app.configuration.index.sales.invoice-settings.logo',
                'type'          => 'image',
                'validation'    => 'mimes:bmp,jpeg,jpg,png,webp',
                'channel_based' => true,
            ],
        ],
    ], [
        'key'    => 'sales.invoice_settings.invoice_reminders',
        'name'   => 'admin::app.configuration.index.sales.invoice-settings.invoice-reminders',
        'info'   => 'admin::app.configuration.index.sales.invoice-settings.invoice-reminders-info',
        'sort'   => 2,
        'fields' => [
            [
                'name'          => 'reminders_limit',
                'title'         => 'admin::app.configuration.index.sales.invoice-settings.maximum-limit-of-reminders',
                'type'          => 'text',
                'validation'    => 'numeric',
                'channel_based' => true,
            ], [
                'name'    => 'interval_between_reminders',
                'title'   => 'admin::app.configuration.index.sales.invoice-settings.interval-between-reminders',
                'type'    => 'select',
                'options' => [
                    [
                        'title' => '1 day',
                        'value' => 'P1D',
                    ], [
                        'title' => '2 days',
                        'value' => 'P2D',
                    ], [
                        'title' => '3 days',
                        'value' => 'P3D',
                    ], [
                        'title' => '4 days',
                        'value' => 'P4D',
                    ], [
                        'title' => '5 days',
                        'value' => 'P4D',
                    ], [
                        'title' => '6 days',
                        'value' => 'P4D',
                    ], [
                        'title' => '7 days',
                        'value' => 'P4D',
                    ], [
                        'title' => '2 weeks',
                        'value' => 'P2W',
                    ], [
                        'title' => '3 weeks',
                        'value' => 'P3W',
                    ], [
                        'title' => '4 weeks',
                        'value' => 'P4W',
                    ],
                ],
            ],
        ],
    ], [
        'key'  => 'taxes',
        'name' => 'tax::app.admin.system.taxes.taxes',
        'info' => 'tax::app.admin.system.taxes.taxes',
        'sort' => 6,
    ], [
        'key'  => 'taxes.catalogue',
        'name' => 'tax::app.admin.system.taxes.catalogue',
        'info' => 'tax::app.admin.system.taxes.catalogue-info',
        'icon' => 'settings/tax.svg',
        'sort' => 1,
    ], [
        'key'    => 'taxes.catalogue.pricing',
        'name'   => 'tax::app.admin.system.taxes.pricing',
        'info'   => 'tax::app.admin.system.taxes.pricing-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'       => 'tax_inclusive',
                'title'      => 'tax::app.admin.system.taxes.tax-inclusive',
                'type'       => 'boolean',
                'validation' => 'required',
                'default'    => false,
            ],
        ],
    ], [
        'key'    => 'taxes.catalogue.default_location_calculation',
        'name'   => 'tax::app.admin.system.taxes.default-location-calculation',
        'info'   => 'tax::app.admin.system.taxes.default-location-calculation-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'    => 'country',
                'title'   => 'tax::app.admin.system.taxes.default-country',
                'type'    => 'country',
                'default' => '',
            ], [
                'name'    => 'state',
                'title'   => 'tax::app.admin.system.taxes.default-state',
                'type'    => 'state',
                'default' => '',
            ], [
                'name'    => 'post_code',
                'title'   => 'tax::app.admin.system.taxes.default-post-code',
                'type'    => 'text',
                'default' => '',
            ],
        ],
    ],
];
