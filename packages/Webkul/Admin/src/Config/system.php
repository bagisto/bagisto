<?php

return [
    /**
     * General.
     */
    [
        'key'  => 'general',
        'name' => 'admin::app.configuration.index.general.title',
        'info' => 'admin::app.configuration.index.general.info',
        'sort' => 1,
    ], [
        'key'  => 'general.general',
        'name' => 'admin::app.configuration.index.general.general.title',
        'info' => 'admin::app.configuration.index.general.general.info',
        'icon' => 'settings/store.svg',
        'sort' => 1,
    ], [
        'key'    => 'general.general.locale_options',
        'name'   => 'admin::app.configuration.index.general.general.unit-options.title',
        'info'   => 'admin::app.configuration.index.general.general.unit-options.title-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'weight_unit',
                'title'         => 'admin::app.configuration.index.general.general.unit-options.weight-unit',
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
        'name'   => 'admin::app.configuration.index.general.content.settings.title',
        'info'   => 'admin::app.configuration.index.general.content.settings.title-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'compare_option',
                'title'         => 'admin::app.configuration.index.general.content.settings.compare-options',
                'type'          => 'boolean',
                'default'       => '1',
                'locale_based'  => true,
                'channel_based' => true,
            ], [
                'name'          => 'wishlist_option',
                'title'         => 'admin::app.configuration.index.general.content.settings.wishlist-options',
                'type'          => 'boolean',
                'default'       => '1',
                'locale_based'  => true,
                'channel_based' => true,
            ], [
                'name'          => 'image_search',
                'title'         => 'admin::app.configuration.index.general.content.settings.image-search-option',
                'type'          => 'boolean',
                'default'       => '1',
                'locale_based'  => true,
                'channel_based' => true,
            ],
        ],
    ], [
        'key'    => 'general.content.custom_scripts',
        'name'   => 'admin::app.configuration.index.general.content.custom-scripts.title',
        'info'   => 'admin::app.configuration.index.general.content.custom-scripts.title-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'custom_css',
                'title'         => 'admin::app.configuration.index.general.content.custom-scripts.custom-css',
                'type'          => 'textarea',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'          => 'custom_javascript',
                'title'         => 'admin::app.configuration.index.general.content.custom-scripts.custom-javascript',
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
        'name'   => 'admin::app.configuration.index.general.design.admin-logo.title',
        'info'   => 'admin::app.configuration.index.general.design.admin-logo.title-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'logo_image',
                'title'         => 'admin::app.configuration.index.general.design.admin-logo.logo-image',
                'type'          => 'image',
                'channel_based' => true,
                'validation'    => 'mimes:bmp,jpeg,jpg,png,webp',
            ], [
                'name'          => 'favicon',
                'title'         => 'admin::app.configuration.index.general.design.admin-logo.favicon',
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
        'key'  => 'catalog.inventory',
        'name' => 'admin::app.configuration.index.catalog.inventory.title',
        'info' => 'admin::app.configuration.index.catalog.inventory.info',
        'icon' => 'settings/store.svg',
        'sort' => 1,
    ], [
        'key'    => 'catalog.inventory.stock_options',
        'name'   => 'admin::app.configuration.index.catalog.inventory.stock-options.title',
        'info'   => 'admin::app.configuration.index.catalog.inventory.stock-options.title-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'back_orders',
                'title'         => 'admin::app.configuration.index.catalog.inventory.stock-options.allow-back-orders',
                'type'          => 'boolean',
                'channel_based' => true,
            ],
        ],
    ], [
        'key'  => 'catalog.products',
        'name' => 'admin::app.configuration.index.catalog.products.title',
        'info' => 'admin::app.configuration.index.catalog.products.info',
        'icon' => 'settings/store-information.svg',
        'sort' => 2,
    ], [
        'key'    => 'catalog.products.guest_checkout',
        'name'   => 'admin::app.configuration.index.catalog.products.guest-checkout.title',
        'info'   => 'admin::app.configuration.index.catalog.products.guest-checkout.title-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'  => 'allow_guest_checkout',
                'title' => 'admin::app.configuration.index.catalog.products.guest-checkout.allow-guest-checkout',
                'type'  => 'boolean',
            ],
        ],
    ], [
        'key'    => 'catalog.products.product_view_page',
        'name'   => 'admin::app.configuration.index.catalog.products.product-view-page.title',
        'info'   => 'admin::app.configuration.index.catalog.products.product-view-page.title-info',
        'sort'   => 3,
        'fields' => [
            [
                'name'       => 'no_of_related_products',
                'title'      => 'admin::app.configuration.index.catalog.products.product-view-page.allow-no-of-related-products',
                'type'       => 'number',
                'validation' => 'min:0',
            ], [
                'name'       => 'no_of_up_sells_products',
                'title'      => 'admin::app.configuration.index.catalog.products.product-view-page.allow-no-of-up-sells-products',
                'type'       => 'number',
                'validation' => 'min:0',
            ],
        ],
    ], [
        'key'    => 'catalog.products.cart_view_page',
        'name'   => 'admin::app.configuration.index.catalog.products.cart-view-page.title',
        'info'   => 'admin::app.configuration.index.catalog.products.cart-view-page.title-info',
        'sort'   => 3,
        'fields' => [
            [
                'name'       => 'no_of_cross_sells_products',
                'title'      => 'admin::app.configuration.index.catalog.products.cart-view-page.allow-no-of-cross-sells-products',
                'type'       => 'number',
                'validation' => 'min:0',
            ],
        ],
    ], [
        'key'    => 'catalog.products.storefront',
        'name'   => 'admin::app.configuration.index.catalog.products.storefront.title',
        'info'   => 'admin::app.configuration.index.catalog.products.storefront.title-info',
        'sort'   => 3,
        'fields' => [
            [
                'name'          => 'search_mode',
                'title'         => 'admin::app.configuration.index.catalog.products.storefront.search-mode',
                'type'          => 'select',
                'options'       => [
                    [
                        'title' => 'admin::app.configuration.index.catalog.products.storefront.database',
                        'value' => 'database',
                    ], [
                        'title' => 'admin::app.configuration.index.catalog.products.storefront.elastic',
                        'value' => 'elastic',
                    ],
                ],
            ], [
                'name'          => 'mode',
                'title'         => 'admin::app.configuration.index.catalog.products.storefront.default-list-mode',
                'type'          => 'select',
                'options'       => [
                    [
                        'title' => 'admin::app.configuration.index.catalog.products.storefront.grid',
                        'value' => 'grid',
                    ], [
                        'title' => 'admin::app.configuration.index.catalog.products.storefront.list',
                        'value' => 'list',
                    ],
                ],
                'channel_based' => true,
            ], [
                'name'          => 'products_per_page',
                'title'         => 'admin::app.configuration.index.catalog.products.storefront.products-per-page',
                'type'          => 'text',
                'info'          => 'admin::app.configuration.index.catalog.products.storefront.comma-separated',
                'channel_based' => true,
            ], [
                'name'          => 'sort_by',
                'title'         => 'admin::app.configuration.index.catalog.products.storefront.sort-by',
                'type'          => 'select',
                'options'       => [
                    [
                        'title' => 'admin::app.configuration.index.catalog.products.storefront.from-a-z',
                        'value' => 'name-asc',
                    ], [
                        'title' => 'admin::app.configuration.index.catalog.products.storefront.from-z-a',
                        'value' => 'name-desc',
                    ], [
                        'title' => 'admin::app.configuration.index.catalog.products.storefront.latest-first',
                        'value' => 'created_at-desc',
                    ], [
                        'title' => 'admin::app.configuration.index.catalog.products.storefront.oldest-first',
                        'value' => 'created_at-asc',
                    ], [
                        'title' => 'admin::app.configuration.index.catalog.products.storefront.cheapest-first',
                        'value' => 'price-asc',
                    ], [
                        'title' => 'admin::app.configuration.index.catalog.products.storefront.expensive-first',
                        'value' => 'price-desc',
                    ],
                ],
                'channel_based' => true,
            ], [
                'name'  => 'buy_now_button_display',
                'title' => 'admin::app.configuration.index.catalog.products.storefront.buy-now-button-display',
                'type'  => 'boolean',
            ],
        ],
    ], [
        'key'    => 'catalog.products.cache_small_image',
        'name'   => 'admin::app.configuration.index.catalog.products.small-image.title',
        'info'   => 'admin::app.configuration.index.catalog.products.small-image.title-info',
        'sort'   => 4,
        'fields' => [
            [
                'name'  => 'width',
                'title' => 'admin::app.configuration.index.catalog.products.small-image.width',
                'type'  => 'text',
            ],
            [
                'name'  => 'height',
                'title' => 'admin::app.configuration.index.catalog.products.small-image.height',
                'type'  => 'text',
            ],
        ],
    ], [
        'key'    => 'catalog.products.cache_medium_image',
        'name'   => 'admin::app.configuration.index.catalog.products.medium-image.title',
        'info'   => 'admin::app.configuration.index.catalog.products.medium-image.title-info',
        'sort'   => 5,
        'fields' => [
            [
                'name'  => 'width',
                'title' => 'admin::app.configuration.index.catalog.products.medium-image.width',
                'type'  => 'text',
            ],
            [
                'name'  => 'height',
                'title' => 'admin::app.configuration.index.catalog.products.medium-image.height',
                'type'  => 'text',
            ],
        ],
    ], [
        'key'    => 'catalog.products.cache_large_image',
        'name'   => 'admin::app.configuration.index.catalog.products.large-image.title',
        'info'   => 'admin::app.configuration.index.catalog.products.large-image.title-info',
        'sort'   => 6,
        'fields' => [
            [
                'name'  => 'width',
                'title' => 'admin::app.configuration.index.catalog.products.large-image.width',
                'type'  => 'text',
            ],
            [
                'name'  => 'height',
                'title' => 'admin::app.configuration.index.catalog.products.large-image.height',
                'type'  => 'text',
            ],
        ],
    ], [
        'key'    => 'catalog.products.review',
        'name'   => 'admin::app.configuration.index.catalog.products.review.title',
        'info'   => 'admin::app.configuration.index.catalog.products.review.title-info',
        'sort'   => 7,
        'fields' => [
            [
                'name'  => 'guest_review',
                'title' => 'admin::app.configuration.index.catalog.products.review.allow-guest-review',
                'type'  => 'boolean',
            ],
        ],
    ], [
        'key'    => 'catalog.products.attribute',
        'name'   => 'admin::app.configuration.index.catalog.products.attribute.title',
        'info'   => 'admin::app.configuration.index.catalog.products.attribute.title-info',
        'sort'   => 8,
        'fields' => [
            [
                'name'  => 'image_attribute_upload_size',
                'title' => 'admin::app.configuration.index.catalog.products.attribute.image-upload-size',
                'type'  => 'text',
            ], [
                'name'  => 'file_attribute_upload_size',
                'title' => 'admin::app.configuration.index.catalog.products.attribute.file-upload-size',
                'type'  => 'text',
            ],
        ],
    ], [
        'key'    => 'catalog.products.social_share',
        'name'   => 'admin::app.configuration.index.catalog.products.social-share.title',
        'info'   => 'admin::app.configuration.index.catalog.products.social-share.title-info',
        'sort'   => 100,
        'fields' => [
            [
                'name'  => 'enabled',
                'title' => 'admin::app.configuration.index.catalog.products.social-share.enable-social-share',
                'type'  => 'boolean',
            ], [
                'name'  => 'facebook',
                'title' => 'admin::app.configuration.index.catalog.products.social-share.enable-share-facebook',
                'type'  => 'boolean',
            ], [
                'name'  => 'twitter',
                'title' => 'admin::app.configuration.index.catalog.products.social-share.enable-share-twitter',
                'type'  => 'boolean',
            ], [
                'name'  => 'pinterest',
                'title' => 'admin::app.configuration.index.catalog.products.social-share.enable-share-pinterest',
                'type'  => 'boolean',
            ], [
                'name'  => 'whatsapp',
                'title' => 'admin::app.configuration.index.catalog.products.social-share.enable-share-whatsapp',
                'type'  => 'boolean',
                'info'  => 'What\'s App share link just will appear to mobile devices.'
            ], [
                'name'  => 'linkedin',
                'title' => 'admin::app.configuration.index.catalog.products.social-share.enable-share-linkedin',
                'type'  => 'boolean',
            ], [
                'name'  => 'email',
                'title' => 'admin::app.configuration.index.catalog.products.social-share.enable-share-email',
                'type'  => 'boolean',
            ], [
                'name'  => 'share_message',
                'title' => 'admin::app.configuration.index.catalog.products.social-share.share-message',
                'type'  => 'text',
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
        'name'   => 'admin::app.configuration.index.catalog.rich-snippets.products.title',
        'info'   => 'admin::app.configuration.index.catalog.rich-snippets.products.title-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'  => 'enable',
                'title' => 'admin::app.configuration.index.catalog.rich-snippets.products.enable',
                'type'  => 'boolean',
            ], [
                'name'  => 'show_sku',
                'title' => 'admin::app.configuration.index.catalog.rich-snippets.products.show-sku',
                'type'  => 'boolean',
            ], [
                'name'  => 'show_weight',
                'title' => 'admin::app.configuration.index.catalog.rich-snippets.products.show-weight',
                'type'  => 'boolean',
            ], [
                'name'  => 'show_categories',
                'title' => 'admin::app.configuration.index.catalog.rich-snippets.products.show-categories',
                'type'  => 'boolean',
            ], [
                'name'  => 'show_images',
                'title' => 'admin::app.configuration.index.catalog.rich-snippets.products.show-images',
                'type'  => 'boolean',
            ], [
                'name'  => 'show_reviews',
                'title' => 'admin::app.configuration.index.catalog.rich-snippets.products.show-reviews',
                'type'  => 'boolean',
            ], [
                'name'  => 'show_ratings',
                'title' => 'admin::app.configuration.index.catalog.rich-snippets.products.show-ratings',
                'type'  => 'boolean',
            ], [
                'name'  => 'show_offers',
                'title' => 'admin::app.configuration.index.catalog.rich-snippets.products.show-offers',
                'type'  => 'boolean',
            ],
        ],
    ], [
        'key'    => 'catalog.rich_snippets.categories',
        'name'   => 'admin::app.configuration.index.catalog.rich-snippets.categories.title',
        'info'   => 'admin::app.configuration.index.catalog.rich-snippets.categories.title-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'  => 'enable',
                'title' => 'admin::app.configuration.index.catalog.rich-snippets.categories.enable',
                'type'  => 'boolean',
            ], [
                'name'  => 'show_search_input_field',
                'title' => 'admin::app.configuration.index.catalog.rich-snippets.categories.show-search-input-field',
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
        'name'   => 'admin::app.configuration.index.customer.address.requirements.title',
        'info'   => 'admin::app.configuration.index.customer.address.requirements.title-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'country',
                'title'         => 'admin::app.configuration.index.customer.address.requirements.country',
                'type'          => 'boolean',
                'channel_based' => true,
                'default'       => '1',
            ],
            [
                'name'          => 'state',
                'title'         => 'admin::app.configuration.index.customer.address.requirements.state',
                'type'          => 'boolean',
                'channel_based' => true,
                'default'       => '1',
            ],
            [
                'name'          => 'postcode',
                'title'         => 'admin::app.configuration.index.customer.address.requirements.zip',
                'type'          => 'boolean',
                'channel_based' => true,
                'default'       => '1',
            ],
        ],
    ], [
        'key'    => 'customer.address.information',
        'name'   => 'admin::app.configuration.index.customer.address.information.title',
        'info'   => 'admin::app.configuration.index.customer.address.information.title-info',
        'sort'   => 2,
        'fields' => [
            [
                'name'          => 'street_lines',
                'title'         => 'admin::app.configuration.index.customer.address.information.street-lines',
                'type'          => 'text',
                'validation'    => 'between:1,2',
                'channel_based' => true,
            ],
        ],
    ], [
        'key'  => 'customer.captcha',
        'name' => 'admin::app.configuration.index.customer.captcha.title',
        'info' => 'admin::app.configuration.index.customer.captcha.info',
        'icon' => 'settings/captcha.svg',
        'sort' => 2,
    ], [
        'key'    => 'customer.captcha.credentials',
        'name'   => 'admin::app.configuration.index.customer.captcha.credentials.title',
        'info'   => 'admin::app.configuration.index.customer.captcha.credentials.title-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'site_key',
                'title'         => 'admin::app.configuration.index.customer.captcha.credentials.site-key',
                'type'          => 'text',
                'channel_based' => true,
            ], [
                'name'          => 'secret_key',
                'title'         => 'admin::app.configuration.index.customer.captcha.credentials.secret-key',
                'type'          => 'text',
                'channel_based' => true,
            ], [
                'name'          => 'status',
                'title'         => 'admin::app.configuration.index.customer.captcha.credentials.status',
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
        'key'    => 'customer.settings.newsletter',
        'name'   => 'admin::app.configuration.index.customer.settings.newsletter.title',
        'info'   => 'admin::app.configuration.index.customer.settings.newsletter.title-info',
        'sort'   => 2,
        'fields' => [
            [
                'name'  => 'subscription',
                'title' => 'admin::app.configuration.index.customer.settings.newsletter.subscription',
                'type'  => 'boolean',
            ],
        ],
    ], [
        'key'    => 'customer.settings.email',
        'name'   => 'admin::app.configuration.index.customer.settings.email.title',
        'info'   => 'admin::app.configuration.index.customer.settings.email.title-info',
        'sort'   => 3,
        'fields' => [
            [
                'name'  => 'verification',
                'title' => 'admin::app.configuration.index.customer.settings.email.email-verification',
                'type'  => 'boolean',
            ],
        ],
    ], [
        'key'    => 'customer.settings.social_login',
        'name'   => 'admin::app.configuration.index.customer.settings.social-login.social-login',
        'info'   => 'admin::app.configuration.index.customer.settings.social-login.social-login-info',
        'sort'   => 4,
        'fields' => [
            [
                'name'          => 'enable_facebook',
                'title'         => 'admin::app.configuration.index.customer.settings.social-login.enable-facebook',
                'type'          => 'boolean',
                'channel_based' => true,
            ], [
                'name'          => 'enable_twitter',
                'title'         => 'admin::app.configuration.index.customer.settings.social-login.enable-twitter',
                'type'          => 'boolean',
                'channel_based' => true,
            ], [
                'name'          => 'enable_google',
                'title'         => 'admin::app.configuration.index.customer.settings.social-login.enable-google',
                'type'          => 'boolean',
                'channel_based' => true,
            ], [
                'name'          => 'enable_linkedin',
                'title'         => 'admin::app.configuration.index.customer.settings.social-login.enable-linkedin',
                'type'          => 'boolean',
                'channel_based' => true,
            ], [
                'name'          => 'enable_github',
                'title'         => 'admin::app.configuration.index.customer.settings.social-login.enable-github',
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
        'name'   => 'admin::app.configuration.index.sales.shipping.origin.title',
        'info'   => 'admin::app.configuration.index.sales.shipping.origin.title-info',
        'sort'   => 0,
        'fields' => [
            [
                'name'          => 'country',
                'title'         => 'admin::app.configuration.index.sales.shipping.origin.country',
                'type'          => 'country',
                'validation'    => 'required',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'state',
                'title'         => 'admin::app.configuration.index.sales.shipping.origin.state',
                'type'          => 'state',
                'validation'    => 'required',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'city',
                'title'         => 'admin::app.configuration.index.sales.shipping.origin.city',
                'type'          => 'text',
                'validation'    => 'required',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'          => 'address1',
                'title'         => 'admin::app.configuration.index.sales.shipping.origin.street-address',
                'type'          => 'text',
                'validation'    => 'required',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'          => 'zipcode',
                'title'         => 'admin::app.configuration.index.sales.shipping.origin.zip',
                'type'          => 'text',
                'validation'    => 'required',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'          => 'store_name',
                'title'         => 'admin::app.configuration.index.sales.shipping.origin.store-name',
                'type'          => 'text',
                'channel_based' => true,
            ], [
                'name'          => 'vat_number',
                'title'         => 'admin::app.configuration.index.sales.shipping.origin.vat-number',
                'type'          => 'text',
                'channel_based' => true,
            ], [
                'name'          => 'contact',
                'title'         => 'admin::app.configuration.index.sales.shipping.origin.contact-number',
                'type'          => 'text',
                'channel_based' => true,
            ], [
                'name'          => 'bank_details',
                'title'         => 'admin::app.configuration.index.sales.shipping.origin.bank-details',
                'type'          => 'textarea',
                'channel_based' => true,
            ],
        ],
    ], [
        'key'  => 'sales.carriers',
        'name' => 'admin::app.configuration.index.sales.shipping-methods.title',
        'info' => 'admin::app.configuration.index.sales.shipping-methods.info',
        'icon' => 'settings/shipping-method.svg',
        'sort' => 2,
    ], [
        'key'    => 'sales.carriers.free',
        'name'   => 'admin::app.configuration.index.sales.shipping-methods.free-shipping.page-title',
        'info'   => 'admin::app.configuration.index.sales.shipping-methods.free-shipping.title-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'title',
                'title'         => 'admin::app.configuration.index.sales.shipping-methods.free-shipping.title',
                'type'          => 'depends',
                'depend'        => 'active:1',
                'validation'    => 'required_if:active,1',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'description',
                'title'         => 'admin::app.configuration.index.sales.shipping-methods.free-shipping.description',
                'type'          => 'textarea',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'active',
                'title'         => 'admin::app.configuration.index.sales.shipping-methods.free-shipping.status',
                'type'          => 'boolean',
                'channel_based' => true,
                'locale_based'  => false,
            ],
        ],
    ], [
        'key'    => 'sales.carriers.flatrate',
        'name'   => 'admin::app.configuration.index.sales.shipping-methods.flat-rate-shipping.page-title',
        'info'   => 'admin::app.configuration.index.sales.shipping-methods.flat-rate-shipping.title-info',
        'sort'   => 2,
        'fields' => [
            [
                'name'          => 'title',
                'title'         => 'admin::app.configuration.index.sales.shipping-methods.flat-rate-shipping.title',
                'type'          => 'depends',
                'depend'        => 'active:1',
                'validation'    => 'required_if:active,1',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'description',
                'title'         => 'admin::app.configuration.index.sales.shipping-methods.flat-rate-shipping.description',
                'type'          => 'textarea',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'default_rate',
                'title'         => 'admin::app.configuration.index.sales.shipping-methods.flat-rate-shipping.rate',
                'type'          => 'depends',
                'depend'        => 'active:1',
                'validation'    => 'required_if:active,1',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'    => 'type',
                'title'   => 'admin::app.configuration.index.sales.shipping-methods.flat-rate-shipping.type',
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
                'title'         => 'admin::app.configuration.index.sales.shipping-methods.flat-rate-shipping.status',
                'type'          => 'boolean',
                'channel_based' => true,
                'locale_based'  => false,
            ],
        ],
    ], [
        'key'  => 'sales.payment_methods',
        'name' => 'admin::app.configuration.index.sales.payment-methods.page-title',
        'info' => 'admin::app.configuration.index.sales.payment-methods.info',
        'icon' => 'settings/payment-method.svg',
        'sort' => 3,
    ], [
        'key'    => 'sales.payment_methods.cashondelivery',
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
        'key'    => 'sales.payment_methods.moneytransfer',
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
                'title'         => 'admin::app.configuration.index.sales.payment-methods.pending',
                'type'          => 'boolean',
                'default_value' => false,
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'    => 'invoice_status',
                'title'   => 'admin::app.configuration.index.sales.payment-methods.pending',
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
                'title'   => 'admin::app.configuration.index.sales.payment-methods.pending',
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
        'key'    => 'sales.payment_methods.paypal_standard',
        'name'   => 'admin::app.configuration.index.sales.payment-methods.paypal-standard',
        'info'   => 'admin::app.configuration.index.sales.payment-methods.paypal-standard-info',
        'sort'   => 3,
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
            ],  [
                'name'          => 'business_account',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.business-account',
                'type'          => 'depends',
                'depend'        => 'active:1',
                'validation'    => 'required_if:active,1',
                'channel_based' => true,
                'locale_based'  => false,
            ],  [
                'name'          => 'active',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.status',
                'type'          => 'boolean',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'          => 'sandbox',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.sandbox',
                'type'          => 'boolean',
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
            ]
        ]
    ], [
        'key'    => 'sales.payment_methods.paypal_smart_button',
        'name'   => 'admin::app.configuration.index.sales.payment-methods.paypal-smart-button',
        'info'   => 'admin::app.configuration.index.sales.payment-methods.paypal-smart-button-info',
        'sort'   => 0,
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
                'name'          => 'client_id',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.client-id',
                'info'          => 'admin::app.configuration.index.sales.payment-methods.client-id-info',
                'type'          => 'depends',
                'depend'        => 'active:1',
                'validation'    => 'required_if:active,true',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'          => 'client_secret',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.client-secret',
                'info'          => 'admin::app.configuration.index.sales.payment-methods.client-secret-info',
                'type'          => 'depends',
                'depend'        => 'active:1',
                'validation'    => 'required_if:active,1',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'          => 'accepted_currencies',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.accepted-currencies',
                'info'          => 'admin::app.configuration.index.sales.payment-methods.accepted-currencies-info',
                'type'          => 'depends',
                'depend'        => 'active:1',
                'validation'    => 'required_if:active,1',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'          => 'active',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.status',
                'type'          => 'boolean',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'          => 'sandbox',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.sandbox',
                'type'          => 'boolean',
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
            ]
        ]
    ], [
        'key'  => 'sales.order_settings',
        'name' => 'admin::app.configuration.index.sales.order-settings.title',
        'info' => 'admin::app.configuration.index.sales.order-settings.info',
        'icon' => 'settings/order.svg',
        'sort' => 4,
    ], [
        'key'    => 'sales.order_settings.order_number',
        'name'   => 'admin::app.configuration.index.sales.order-settings.order-number.title',
        'info'   => 'admin::app.configuration.index.sales.order-settings.order-number.title-info',
        'sort'   => 0,
        'fields' => [
            [
                'name'          => 'order_number_prefix',
                'title'         => 'admin::app.configuration.index.sales.order-settings.order-number.prefix',
                'type'          => 'text',
                'validation'    => false,
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'order_number_length',
                'title'         => 'admin::app.configuration.index.sales.order-settings.order-number.length',
                'type'          => 'text',
                'validation'    => 'numeric',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'order_number_suffix',
                'title'         => 'admin::app.configuration.index.sales.order-settings.order-number.suffix',
                'type'          => 'text',
                'validation'    => false,
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'order_number_generator',
                'title'         => 'admin::app.configuration.index.sales.order-settings.order-number.generator',
                'type'          => 'text',
                'validation'    => false,
                'channel_based' => true,
                'locale_based'  => true,
            ],
        ],
    ], [
        'key'    => 'sales.order_settings.minimum_order',
        'name'   => 'admin::app.configuration.index.sales.order-settings.minimum-order.title',
        'info'   => 'admin::app.configuration.index.sales.order-settings.minimum-order.title-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'minimum_order_amount',
                'title'         => 'admin::app.configuration.index.sales.order-settings.minimum-order.minimum-order-amount',
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
        'name'   => 'admin::app.configuration.index.sales.invoice-settings.invoice-number.title',
        'info'   => 'admin::app.configuration.index.sales.invoice-settings.invoice-number.title-info',
        'sort'   => 0,
        'fields' => [
            [
                'name'          => 'invoice_number_prefix',
                'title'         => 'admin::app.configuration.index.sales.invoice-settings.invoice-number.prefix',
                'type'          => 'text',
                'validation'    => false,
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'invoice_number_length',
                'title'         => 'admin::app.configuration.index.sales.invoice-settings.invoice-number.length',
                'type'          => 'text',
                'validation'    => 'numeric',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'invoice_number_suffix',
                'title'         => 'admin::app.configuration.index.sales.invoice-settings.invoice-number.suffix',
                'type'          => 'text',
                'validation'    => false,
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'invoice_number_generator_class',
                'title'         => 'admin::app.configuration.index.sales.invoice-settings.invoice-number.generator',
                'type'          => 'text',
                'validation'    => false,
                'channel_based' => true,
                'locale_based'  => true,
            ],
        ],
    ], [
        'key'    => 'sales.invoice_settings.payment_terms',
        'name'   => 'admin::app.configuration.index.sales.invoice-settings.payment-terms.title',
        'info'   => 'admin::app.configuration.index.sales.invoice-settings.payment-terms.title-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'due_duration',
                'title'         => 'admin::app.configuration.index.sales.invoice-settings.payment-terms.due-duration',
                'type'          => 'text',
                'validation'    => 'numeric',
                'channel_based' => true,
            ],
        ],
    ], [
        'key'    => 'sales.invoice_settings.invoice_slip_design',
        'name'   => 'admin::app.configuration.index.sales.invoice-settings.invoice-slip-design.title',
        'info'   => 'admin::app.configuration.index.sales.invoice-settings.invoice-slip-design.title-info',
        'sort'   => 2,
        'fields' => [
            [
                'name'          => 'logo',
                'title'         => 'admin::app.configuration.index.sales.invoice-settings.invoice-slip-design.logo',
                'type'          => 'image',
                'validation'    => 'mimes:bmp,jpeg,jpg,png,webp',
                'channel_based' => true,
            ],
        ],
    ], [
        'key'    => 'sales.invoice_settings.invoice_reminders',
        'name'   => 'admin::app.configuration.index.sales.invoice-settings.invoice-reminders.title',
        'info'   => 'admin::app.configuration.index.sales.invoice-settings.invoice-reminders.title-info',
        'sort'   => 2,
        'fields' => [
            [
                'name'          => 'reminders_limit',
                'title'         => 'admin::app.configuration.index.sales.invoice-settings.invoice-reminders.maximum-limit-of-reminders',
                'type'          => 'text',
                'validation'    => 'numeric',
                'channel_based' => true,
            ], [
                'name'    => 'interval_between_reminders',
                'title'   => 'admin::app.configuration.index.sales.invoice-settings.invoice-reminders.interval-between-reminders',
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
        'name' => 'admin::app.configuration.index.taxes.title',
        'info' => 'admin::app.configuration.index.taxes.title',
        'sort' => 6,
    ], [
        'key'  => 'taxes.catalogue',
        'name' => 'admin::app.configuration.index.taxes.catalog.title',
        'info' => 'admin::app.configuration.index.taxes.catalog.title-info',
        'icon' => 'settings/tax.svg',
        'sort' => 1,
    ], [
        'key'    => 'taxes.catalogue.pricing',
        'name'   => 'admin::app.configuration.index.taxes.catalog.pricing.title',
        'info'   => 'admin::app.configuration.index.taxes.catalog.pricing.title-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'       => 'tax_inclusive',
                'title'      => 'admin::app.configuration.index.taxes.catalog.pricing.tax-inclusive',
                'type'       => 'boolean',
                'default'    => false,
            ],
        ],
    ], [
        'key'    => 'taxes.catalogue.default_location_calculation',
        'name'   => 'admin::app.configuration.index.taxes.catalog.default-location-calculation.title',
        'info'   => 'admin::app.configuration.index.taxes.catalog.default-location-calculation.title-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'    => 'country',
                'title'   => 'admin::app.configuration.index.taxes.catalog.default-location-calculation.default-country',
                'type'    => 'country',
                'default' => '',
            ], [
                'name'    => 'state',
                'title'   => 'admin::app.configuration.index.taxes.catalog.default-location-calculation.default-state',
                'type'    => 'state',
                'default' => '',
            ], [
                'name'    => 'post_code',
                'title'   => 'admin::app.configuration.index.taxes.catalog.default-location-calculation.default-post-code',
                'type'    => 'text',
                'default' => '',
            ],
        ],
    ],
];
