<?php

return [
    /**
     * General.
     */
    [
        'key'  => 'general',
        'name' => 'admin::app.configuration.general',
        'info' => 'admin::app.configuration.general-info',
        'sort' => 1,
    ], [
        'key'  => 'general.general',
        'name' => 'admin::app.configuration.general',
        'info' => 'admin::app.configuration.general-info',
        'icon' => 'store-information.svg',
        'sort' => 1,
    ], [
        'key'    => 'general.general.locale_options',
        'name'   => 'admin::app.configuration.locale-options',
        'info'   => 'admin::app.configuration.locale-options-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'weight_unit',
                'title'         => 'admin::app.configuration.weight-unit',
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
        'name' => 'admin::app.configuration.content',
        'info' => 'admin::app.configuration.content-info',
        'icon' => 'store-information.svg',
        'sort' => 2,
    ], [
        'key'    => 'general.content.custom_scripts',
        'name'   => 'admin::app.configuration.custom-scripts',
        'info'   => 'admin::app.configuration.custom-scripts-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'custom_css',
                'title'         => 'admin::app.configuration.custom-css',
                'type'          => 'textarea',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'          => 'custom_javascript',
                'title'         => 'admin::app.configuration.custom-javascript',
                'type'          => 'textarea',
                'channel_based' => true,
                'locale_based'  => false,
            ],
        ],
    ], [
        'key'  => 'general.design',
        'name' => 'admin::app.configuration.design',
        'info' => 'admin::app.configuration.design-info',
        'icon' => 'theme-setting.png',
        'sort' => 3,
    ], [
        'key'    => 'general.design.admin_logo',
        'name'   => 'admin::app.configuration.admin-logo',
        'info'   => 'admin::app.configuration.admin-logo-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'logo_image',
                'title'         => 'admin::app.configuration.logo-image',
                'type'          => 'image',
                'channel_based' => true,
                'validation'    => 'mimes:bmp,jpeg,jpg,png,webp',
            ], [
                'name'          => 'favicon',
                'title'         => 'admin::app.configuration.favicon',
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
        'name' => 'admin::app.configuration.catalog',
        'info' => 'admin::app.configuration.catalog',
        'sort' => 2,
    ], [
        'key'  => 'catalog.products',
        'name' => 'admin::app.configuration.products',
        'info' => 'admin::app.configuration.products-info',
        'icon' => 'group.png',
        'sort' => 2,
    ], [
        'key'    => 'catalog.products.guest-checkout',
        'name'   => 'admin::app.configuration.guest-checkout',
        'info'   => 'admin::app.configuration.guest-checkout-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'  => 'allow-guest-checkout',
                'title' => 'admin::app.configuration.allow-guest-checkout',
                'type'  => 'boolean',
            ],
        ],
    ], [
        'key'    => 'catalog.products.homepage',
        'name'   => 'admin::app.configuration.homepage',
        'info'   => 'admin::app.configuration.homepage-info',
        'sort'   => 2,
        'fields' => [
            [
                'name'       => 'no_of_new_product_homepage',
                'title'      => 'admin::app.configuration.allow-no-of-new-product-homepage',
                'type'       => 'number',
                'validation' => 'min:0',
            ],
            [
                'name'       => 'no_of_featured_product_homepage',
                'title'      => 'admin::app.configuration.allow-no-of-featured-product-homepage',
                'type'       => 'number',
                'validation' => 'min:0',
            ],
        ],
    ], [
        'key'    => 'catalog.products.product_view_page',
        'name'   => 'admin::app.configuration.product-view-page',
        'info'   => 'admin::app.configuration.product-view-page-info',
        'sort'   => 3,
        'fields' => [
            [
                'name'       => 'no_of_related_products',
                'title'      => 'admin::app.configuration.allow-no-of-related-products',
                'type'       => 'number',
                'validation' => 'min:0',
            ], [
                'name'       => 'no_of_up_sells_products',
                'title'      => 'admin::app.configuration.allow-no-of-up-sells-products',
                'type'       => 'number',
                'validation' => 'min:0',
            ],
        ],
    ], [
        'key'    => 'catalog.products.cart_view_page',
        'name'   => 'admin::app.configuration.cart-view-page',
        'info'   => 'admin::app.configuration.cart-view-page-info',
        'sort'   => 3,
        'fields' => [
            [
                'name'       => 'no_of_cross_sells_products',
                'title'      => 'admin::app.configuration.allow-no-of-cross-sells-products',
                'type'       => 'number',
                'validation' => 'min:0',
            ],
        ],
    ], [
        'key'    => 'catalog.products.storefront',
        'name'   => 'admin::app.configuration.storefront',
        'info'   => 'admin::app.configuration.storefront-info',
        'sort'   => 3,
        'fields' => [
            [
                'name'          => 'search_mode',
                'title'         => 'admin::app.configuration.search-mode',
                'type'          => 'select',
                'options'       => [
                    [
                        'title' => 'admin::app.configuration.flat',
                        'value' => 'flat',
                    ], [
                        'title' => 'admin::app.configuration.elastic',
                        'value' => 'elastic',
                    ],
                ],
            ], [
                'name'          => 'mode',
                'title'         => 'admin::app.configuration.default-list-mode',
                'type'          => 'select',
                'options'       => [
                    [
                        'title' => 'admin::app.configuration.grid',
                        'value' => 'grid',
                    ], [
                        'title' => 'admin::app.configuration.list',
                        'value' => 'list',
                    ],
                ],
                'channel_based' => true,
            ], [
                'name'          => 'products_per_page',
                'title'         => 'admin::app.configuration.products-per-page',
                'type'          => 'text',
                'info'          => 'admin::app.configuration.comma-separated',
                'channel_based' => true,
            ], [
                'name'          => 'sort_by',
                'title'         => 'admin::app.configuration.sort-by',
                'type'          => 'select',
                'options'       => [
                    [
                        'title' => 'admin::app.configuration.from-a-z',
                        'value' => 'name-asc',
                    ], [
                        'title' => 'admin::app.configuration.from-z-a',
                        'value' => 'name-desc',
                    ], [
                        'title' => 'admin::app.configuration.latest-first',
                        'value' => 'created_at-desc',
                    ], [
                        'title' => 'admin::app.configuration.oldest-first',
                        'value' => 'created_at-asc',
                    ], [
                        'title' => 'admin::app.configuration.cheapest-first',
                        'value' => 'price-asc',
                    ], [
                        'title' => 'admin::app.configuration.expensive-first',
                        'value' => 'price-desc',
                    ],
                ],
                'channel_based' => true,
            ], [
                'name'  => 'buy_now_button_display',
                'title' => 'admin::app.configuration.buy-now-button-display',
                'type'  => 'boolean',
            ],
        ],
    ], [
        'key'    => 'catalog.products.cache-small-image',
        'name'   => 'admin::app.configuration.cache-small-image',
        'info'   => 'admin::app.configuration.cache-small-image-info',
        'sort'   => 4,
        'fields' => [
            [
                'name'  => 'width',
                'title' => 'admin::app.configuration.width',
                'type'  => 'text',
            ],
            [
                'name'  => 'height',
                'title' => 'admin::app.configuration.height',
                'type'  => 'text',
            ],
        ],
    ], [
        'key'    => 'catalog.products.cache-medium-image',
        'name'   => 'admin::app.configuration.cache-medium-image',
        'info'   => 'admin::app.configuration.cache-medium-image-info',
        'sort'   => 5,
        'fields' => [
            [
                'name'  => 'width',
                'title' => 'admin::app.configuration.width',
                'type'  => 'text',
            ],
            [
                'name'  => 'height',
                'title' => 'admin::app.configuration.height',
                'type'  => 'text',
            ],
        ],
    ], [
        'key'    => 'catalog.products.cache-large-image',
        'name'   => 'admin::app.configuration.cache-large-image',
        'info'   => 'admin::app.configuration.cache-large-image-info',
        'sort'   => 6,
        'fields' => [
            [
                'name'  => 'width',
                'title' => 'admin::app.configuration.width',
                'type'  => 'text',
            ],
            [
                'name'  => 'height',
                'title' => 'admin::app.configuration.height',
                'type'  => 'text',
            ],
        ],
    ], [
        'key'    => 'catalog.products.review',
        'name'   => 'admin::app.configuration.review',
        'info'   => 'admin::app.configuration.review-info',
        'sort'   => 7,
        'fields' => [
            [
                'name'  => 'guest_review',
                'title' => 'admin::app.configuration.allow-guest-review',
                'type'  => 'boolean',
            ],
        ],
    ], [
        'key'    => 'catalog.products.attribute',
        'name'   => 'admin::app.configuration.attribute',
        'info'   => 'admin::app.configuration.attribute-info',
        'sort'   => 8,
        'fields' => [
            [
                'name'  => 'image_attribute_upload_size',
                'title' => 'admin::app.configuration.image-upload-size',
                'type'  => 'text',
            ], [
                'name'  => 'file_attribute_upload_size',
                'title' => 'admin::app.configuration.file-upload-size',
                'type'  => 'text',
            ],
        ],
    ], [
        'key'  => 'catalog.inventory',
        'name' => 'admin::app.configuration.inventory',
        'info' => 'admin::app.configuration.inventory-info',
        'icon' => 'store-information.svg',
        'sort' => 1,
    ], [
        'key'    => 'catalog.inventory.stock_options',
        'name'   => 'admin::app.configuration.stock-options',
        'info'   => 'admin::app.configuration.stock-options-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'backorders',
                'title'         => 'admin::app.configuration.allow-backorders',
                'type'          => 'boolean',
                'channel_based' => true,
            ],
        ],
    ], [
        'key'  => 'catalog.rich_snippets',
        'name' => 'admin::app.configuration.rich-snippets',
        'info' => 'admin::app.configuration.rich-snippets-info',
        'icon' => 'settings.png',
        'sort' => 3,
    ], [
        'key'    => 'catalog.rich_snippets.products',
        'name'   => 'admin::app.configuration.products',
        'info'   => 'admin::app.configuration.rich-snippet-product-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'  => 'enable',
                'title' => 'admin::app.configuration.enable',
                'type'  => 'boolean',
            ], [
                'name'  => 'show_sku',
                'title' => 'admin::app.configuration.show-sku',
                'type'  => 'boolean',
            ], [
                'name'  => 'show_weight',
                'title' => 'admin::app.configuration.show-weight',
                'type'  => 'boolean',
            ], [
                'name'  => 'show_categories',
                'title' => 'admin::app.configuration.show-categories',
                'type'  => 'boolean',
            ], [
                'name'  => 'show_images',
                'title' => 'admin::app.configuration.show-images',
                'type'  => 'boolean',
            ], [
                'name'  => 'show_reviews',
                'title' => 'admin::app.configuration.show-reviews',
                'type'  => 'boolean',
            ], [
                'name'  => 'show_ratings',
                'title' => 'admin::app.configuration.show-ratings',
                'type'  => 'boolean',
            ], [
                'name'  => 'show_offers',
                'title' => 'admin::app.configuration.show-offers',
                'type'  => 'boolean',
            ],
        ],
    ], [
        'key'    => 'catalog.rich_snippets.categories',
        'name'   => 'admin::app.configuration.categories',
        'info'   => 'admin::app.configuration.categories-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'  => 'enable',
                'title' => 'admin::app.configuration.enable',
                'type'  => 'boolean',
            ], [
                'name'  => 'show_search_input_field',
                'title' => 'admin::app.configuration.show-search-input-field',
                'type'  => 'boolean',
            ],
        ],
    ],

    /**
     * Customer.
     */
    [
        'key'  => 'customer',
        'name' => 'admin::app.configuration.customer',
        'info' => 'admin::app.configuration.customer',
        'sort' => 3,
    ], [
        'key'  => 'customer.address',
        'name' => 'admin::app.configuration.address',
        'info' => 'admin::app.configuration.address-info',
        'icon' => 'address-setting.png',
        'sort' => 1,
    ], [
        'key'    => 'customer.address.requirements',
        'name'   => 'admin::app.configuration.requirements',
        'info'   => 'admin::app.configuration.requirements-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'country',
                'title'         => 'admin::app.configuration.country',
                'type'          => 'boolean',
                'channel_based' => true,
                'default'       => '1',
            ],
            [
                'name'          => 'state',
                'title'         => 'admin::app.configuration.state',
                'type'          => 'boolean',
                'channel_based' => true,
                'default'       => '1',
            ],
            [
                'name'          => 'postcode',
                'title'         => 'admin::app.configuration.zip',
                'type'          => 'boolean',
                'channel_based' => true,
                'default'       => '1',
            ],
        ],
    ], [
        'key'    => 'customer.address.information',
        'name'   => 'admin::app.configuration.information',
        'info'   => 'admin::app.configuration.information-info',
        'sort'   => 2,
        'fields' => [
            [
                'name'          => 'street_lines',
                'title'         => 'admin::app.configuration.street-lines',
                'type'          => 'text',
                'validation'    => 'between:1,4',
                'channel_based' => true,
            ],
        ],
    ], [
        'key'  => 'customer.settings',
        'name' => 'admin::app.configuration.settings',
        'info' => 'admin::app.configuration.settings-info',
        'icon' => 'settings.png',
        'sort' => 3,
    ], [
        'key'    => 'customer.settings.wishlist',
        'name'   => 'admin::app.configuration.wishlist',
        'info'   => 'admin::app.configuration.wishlist-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'  => 'share',
                'title' => 'admin::app.configuration.wishlist-share',
                'type'  => 'boolean',
            ],
        ],
    ], [
        'key'    => 'customer.settings.newsletter',
        'name'   => 'admin::app.configuration.newsletter',
        'info'   => 'admin::app.configuration.newsletter-info',
        'sort'   => 2,
        'fields' => [
            [
                'name'  => 'subscription',
                'title' => 'admin::app.configuration.newsletter-subscription',
                'type'  => 'boolean',
            ],
        ],
    ], [
        'key'    => 'customer.settings.email',
        'name'   => 'admin::app.configuration.email',
        'info'   => 'admin::app.configuration.email-info',
        'sort'   => 3,
        'fields' => [
            [
                'name'  => 'verification',
                'title' => 'admin::app.configuration.email-verification',
                'type'  => 'boolean',
            ],
        ],
    ],

    /**
     * Emails.
     */
    [
        'key'  => 'emails',
        'name' => 'admin::app.configuration.emails.email',
        'info' => 'admin::app.configuration.emails.email',
        'sort' => 4,
    ], [
        'key'  => 'emails.configure',
        'name' => 'admin::app.configuration.email-settings',
        'info' => 'admin::app.configuration.email-settings-info',
        'icon' => 'email.png',
        'sort' => 1,
    ], [
        'key'    => 'emails.configure.email_settings',
        'name'   => 'admin::app.configuration.email-settings',
        'info'   => 'admin::app.configuration.email-settings-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'sender_name',
                'title'         => 'admin::app.configuration.email-sender-name',
                'type'          => 'text',
                'info'          => 'admin::app.configuration.email-sender-name-tip',
                'validation'    => 'required|max:50',
                'channel_based' => true,
                'default_value' => config('mail.from.name'),
            ], [
                'name'          => 'shop_email_from',
                'title'         => 'admin::app.configuration.shop-email-from',
                'type'          => 'text',
                'info'          => 'admin::app.configuration.shop-email-from-tip',
                'validation'    => 'required|email',
                'channel_based' => true,
                'default_value' => config('mail.from.address'),
            ], [
                'name'          => 'admin_name',
                'title'         => 'admin::app.configuration.admin-name',
                'type'          => 'text',
                'info'          => 'admin::app.configuration.admin-name-tip',
                'validation'    => 'required|max:50',
                'channel_based' => true,
                'default_value' => config('mail.admin.name'),
            ], [
                'name'          => 'admin_email',
                'title'         => 'admin::app.configuration.admin-email',
                'type'          => 'text',
                'info'          => 'admin::app.configuration.admin-email-tip',
                'validation'    => 'required|email',
                'channel_based' => true,
                'default_value' => config('mail.admin.address'),
            ],
        ],
    ], [
        'key'  => 'emails.general',
        'name' => 'admin::app.configuration.emails.notification_label',
        'info' => 'admin::app.configuration.emails.notification_label-info',
        'icon' => 'store-information.svg',
        'sort' => 1,
    ], [
        'key'    => 'emails.general.notifications',
        'name'   => 'admin::app.configuration.emails.notification_label',
        'info'   => 'admin::app.configuration.emails.notification_label-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'  => 'emails.general.notifications.verification',
                'title' => 'admin::app.configuration.emails.notifications.verification',
                'type'  => 'boolean',
            ],
            [
                'name'  => 'emails.general.notifications.registration',
                'title' => 'admin::app.configuration.emails.notifications.registration',
                'type'  => 'boolean',
            ],
            [
                'name'  => 'emails.general.notifications.customer-registration-confirmation-mail-to-admin',
                'title' => 'admin::app.configuration.emails.notifications.customer-registration-confirmation-mail-to-admin',
                'type'  => 'boolean',
            ],
            [
                'name'  => 'emails.general.notifications.customer',
                'title' => 'admin::app.configuration.emails.notifications.customer',
                'type'  => 'boolean',
            ],
            [
                'name'  => 'emails.general.notifications.new-order',
                'title' => 'admin::app.configuration.emails.notifications.new-order',
                'type'  => 'boolean',
            ],
            [
                'name'  => 'emails.general.notifications.new-admin',
                'title' => 'admin::app.configuration.emails.notifications.new-admin',
                'type'  => 'boolean',
            ],
            [
                'name'  => 'emails.general.notifications.new-invoice',
                'title' => 'admin::app.configuration.emails.notifications.new-invoice',
                'type'  => 'boolean',
            ],
            [
                'name'  => 'emails.general.notifications.new-refund',
                'title' => 'admin::app.configuration.emails.notifications.new-refund',
                'type'  => 'boolean',
            ],
            [
                'name'  => 'emails.general.notifications.new-shipment',
                'title' => 'admin::app.configuration.emails.notifications.new-shipment',
                'type'  => 'boolean',
            ],
            [
                'name'  => 'emails.general.notifications.new-inventory-source',
                'title' => 'admin::app.configuration.emails.notifications.new-inventory-source',
                'type'  => 'boolean',
            ],
            [
                'name'  => 'emails.general.notifications.cancel-order',
                'title' => 'admin::app.configuration.emails.notifications.cancel-order',
                'type'  => 'boolean',
            ],
        ],
    ],
];
