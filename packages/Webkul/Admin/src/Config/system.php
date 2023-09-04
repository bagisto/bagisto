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
        'icon' => 'store-information.svg',
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
        'icon' => 'store-information.svg',
        'sort' => 2,
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
        'icon' => 'theme-setting.png',
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
        'icon' => 'group.png',
        'sort' => 2,
    ], [
        'key'    => 'catalog.products.guest-checkout',
        'name'   => 'admin::app.configuration.index.catalog.products.guest-checkout',
        'info'   => 'admin::app.configuration.index.catalog.products.guest-checkout-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'  => 'allow-guest-checkout',
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
        'key'    => 'catalog.products.cache-small-image',
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
        'key'    => 'catalog.products.cache-medium-image',
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
        'key'    => 'catalog.products.cache-large-image',
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
        'key'  => 'catalog.inventory',
        'name' => 'admin::app.configuration.index.catalog.inventory.title',
        'info' => 'admin::app.configuration.index.catalog.inventory.info',
        'icon' => 'store-information.svg',
        'sort' => 1,
    ], [
        'key'    => 'catalog.inventory.stock_options',
        'name'   => 'admin::app.configuration.index.catalog.inventory.stock-options',
        'info'   => 'admin::app.configuration.index.catalog.inventory.stock-options-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'backorders',
                'title'         => 'admin::app.configuration.index.catalog.inventory.allow-back-orders',
                'type'          => 'boolean',
                'channel_based' => true,
            ],
        ],
    ], [
        'key'  => 'catalog.rich_snippets',
        'name' => 'admin::app.configuration.index.catalog.rich-snippets.title',
        'info' => 'admin::app.configuration.index.catalog.rich-snippets.info',
        'icon' => 'settings.png',
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
        'icon' => 'address-setting.png',
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
        'key'  => 'customer.settings',
        'name' => 'admin::app.configuration.index.customer.settings.title',
        'info' => 'admin::app.configuration.index.customer.settings.settings-info',
        'icon' => 'settings.png',
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
        'icon' => 'email.png',
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
        'icon' => 'store-information.svg',
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
            ],
            [
                'name'  => 'emails.general.notifications.registration',
                'title' => 'admin::app.configuration.index.email.notifications.registration',
                'type'  => 'boolean',
            ],
            [
                'name'  => 'emails.general.notifications.customer-registration-confirmation-mail-to-admin',
                'title' => 'admin::app.configuration.index.email.notifications.customer-registration-confirmation-mail-to-admin',
                'type'  => 'boolean',
            ],
            [
                'name'  => 'emails.general.notifications.customer',
                'title' => 'admin::app.configuration.index.email.notifications.customer',
                'type'  => 'boolean',
            ],
            [
                'name'  => 'emails.general.notifications.new-order',
                'title' => 'admin::app.configuration.index.email.notifications.new-order',
                'type'  => 'boolean',
            ],
            [
                'name'  => 'emails.general.notifications.new-admin',
                'title' => 'admin::app.configuration.index.email.notifications.new-admin',
                'type'  => 'boolean',
            ],
            [
                'name'  => 'emails.general.notifications.new-invoice',
                'title' => 'admin::app.configuration.index.email.notifications.new-invoice',
                'type'  => 'boolean',
            ],
            [
                'name'  => 'emails.general.notifications.new-refund',
                'title' => 'admin::app.configuration.index.email.notifications.new-refund',
                'type'  => 'boolean',
            ],
            [
                'name'  => 'emails.general.notifications.new-shipment',
                'title' => 'admin::app.configuration.index.email.notifications.new-shipment',
                'type'  => 'boolean',
            ],
            [
                'name'  => 'emails.general.notifications.new-inventory-source',
                'title' => 'admin::app.configuration.index.email.notifications.new-inventory-source',
                'type'  => 'boolean',
            ],
            [
                'name'  => 'emails.general.notifications.cancel-order',
                'title' => 'admin::app.configuration.index.email.notifications.cancel-order',
                'type'  => 'boolean',
            ],
        ],
    ],
];
