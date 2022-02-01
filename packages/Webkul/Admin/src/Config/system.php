<?php

return [
    /**
     * General.
     */
    [
        'key'  => 'general',
        'name' => 'admin::app.admin.system.general',
        'sort' => 1,
    ], [
        'key'  => 'general.general',
        'name' => 'admin::app.admin.system.general',
        'sort' => 1,
    ], [
        'key'    => 'general.general.locale_options',
        'name'   => 'admin::app.admin.system.locale-options',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'weight_unit',
                'title'         => 'admin::app.admin.system.weight-unit',
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
        'name' => 'admin::app.admin.system.content',
        'sort' => 2,
    ], [
        'key'    => 'general.content.footer',
        'name'   => 'admin::app.admin.system.footer',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'footer_content',
                'title'         => 'admin::app.admin.system.footer-content',
                'type'          => 'text',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'footer_toggle',
                'title'         => 'admin::app.admin.system.footer-toggle',
                'type'          => 'boolean',
                'locale_based'  => true,
                'channel_based' => true,
            ],
        ],
    ], [
        'key'    => 'general.content.custom_scripts',
        'name'   => 'admin::app.admin.system.custom-scripts',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'custom_css',
                'title'         => 'admin::app.admin.system.custom-css',
                'type'          => 'textarea',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'          => 'custom_javascript',
                'title'         => 'admin::app.admin.system.custom-javascript',
                'type'          => 'textarea',
                'channel_based' => true,
                'locale_based'  => false,
            ],
        ],
    ], [
        'key'  => 'general.design',
        'name' => 'admin::app.admin.system.design',
        'sort' => 3,
    ], [
        'key'    => 'general.design.admin_logo',
        'name'   => 'admin::app.admin.system.admin-logo',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'logo_image',
                'title'         => 'admin::app.admin.system.logo-image',
                'type'          => 'image',
                'channel_based' => true,
                'validation'    => 'mimes:bmp,jpeg,jpg,png,webp',
            ], [
                'name'          => 'favicon',
                'title'         => 'admin::app.admin.system.favicon',
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
        'name' => 'admin::app.admin.system.catalog',
        'sort' => 2,
    ], [
        'key'  => 'catalog.products',
        'name' => 'admin::app.admin.system.products',
        'sort' => 2,
    ], [
        'key'    => 'catalog.products.guest-checkout',
        'name'   => 'admin::app.admin.system.guest-checkout',
        'sort'   => 1,
        'fields' => [
            [
                'name'  => 'allow-guest-checkout',
                'title' => 'admin::app.admin.system.allow-guest-checkout',
                'type'  => 'boolean',
            ],
        ],
    ], [
        'key'    => 'catalog.products.homepage',
        'name'   => 'admin::app.admin.system.homepage',
        'sort'   => 2,
        'fields' => [
            [
                'name'       => 'no_of_new_product_homepage',
                'title'      => 'admin::app.admin.system.allow-no-of-new-product-homepage',
                'type'       => 'number',
                'validation' => 'min:0',
            ],
            [
                'name'       => 'no_of_featured_product_homepage',
                'title'      => 'admin::app.admin.system.allow-no-of-featured-product-homepage',
                'type'       => 'number',
                'validation' => 'min:0',
            ],
            [
                'name'  => 'out_of_stock_items',
                'title' => 'admin::app.admin.system.allow-out-of-stock-items',
                'type'  => 'boolean',
            ],
        ],
    ], [
        'key'    => 'catalog.products.storefront',
        'name'   => 'admin::app.admin.system.storefront',
        'sort'   => 3,
        'fields' => [
            [
                'name'          => 'mode',
                'title'         => 'admin::app.admin.system.default-list-mode',
                'type'          => 'select',
                'options'       => [
                    [
                        'title' => 'admin::app.admin.system.grid',
                        'value' => 'grid',
                    ], [
                        'title' => 'admin::app.admin.system.list',
                        'value' => 'list',
                    ],
                ],
                'channel_based' => true,
            ], [
                'name'          => 'products_per_page',
                'title'         => 'admin::app.admin.system.products-per-page',
                'type'          => 'text',
                'info'          => 'admin::app.admin.system.comma-seperated',
                'channel_based' => true,
            ], [
                'name'          => 'sort_by',
                'title'         => 'admin::app.admin.system.sort-by',
                'type'          => 'select',
                'options'       => [
                    [
                        'title' => 'admin::app.admin.system.from-z-a',
                        'value' => 'name-desc',
                    ], [
                        'title' => 'admin::app.admin.system.from-a-z',
                        'value' => 'name-asc',
                    ], [
                        'title' => 'admin::app.admin.system.newest-first',
                        'value' => 'created_at-desc',
                    ], [
                        'title' => 'admin::app.admin.system.oldest-first',
                        'value' => 'created_at-asc',
                    ], [
                        'title' => 'admin::app.admin.system.cheapest-first',
                        'value' => 'price-asc',
                    ], [
                        'title' => 'admin::app.admin.system.expensive-first',
                        'value' => 'price-desc',
                    ],
                ],
                'channel_based' => true,
            ], [
                'name'  => 'buy_now_button_display',
                'title' => 'admin::app.admin.system.buy-now-button-display',
                'type'  => 'boolean',
            ],
        ],
    ], [
        'key'    => 'catalog.products.cache-small-image',
        'name'   => 'admin::app.admin.system.cache-small-image',
        'sort'   => 4,
        'fields' => [
            [
                'name'  => 'width',
                'title' => 'admin::app.admin.system.width',
                'type'  => 'text',
            ],
            [
                'name'  => 'height',
                'title' => 'admin::app.admin.system.height',
                'type'  => 'text',
            ],
        ],
    ], [
        'key'    => 'catalog.products.cache-medium-image',
        'name'   => 'admin::app.admin.system.cache-medium-image',
        'sort'   => 5,
        'fields' => [
            [
                'name'  => 'width',
                'title' => 'admin::app.admin.system.width',
                'type'  => 'text',
            ],
            [
                'name'  => 'height',
                'title' => 'admin::app.admin.system.height',
                'type'  => 'text',
            ],
        ],
    ], [
        'key'    => 'catalog.products.cache-large-image',
        'name'   => 'admin::app.admin.system.cache-large-image',
        'sort'   => 6,
        'fields' => [
            [
                'name'  => 'width',
                'title' => 'admin::app.admin.system.width',
                'type'  => 'text',
            ],
            [
                'name'  => 'height',
                'title' => 'admin::app.admin.system.height',
                'type'  => 'text',
            ],
        ],
    ], [
        'key'    => 'catalog.products.review',
        'name'   => 'admin::app.admin.system.review',
        'sort'   => 7,
        'fields' => [
            [
                'name'  => 'guest_review',
                'title' => 'admin::app.admin.system.allow-guest-review',
                'type'  => 'boolean',
            ],
        ],
    ], [
        'key'    => 'catalog.products.attribute',
        'name'   => 'admin::app.admin.system.attribute',
        'sort'   => 8,
        'fields' => [
            [
                'name'  => 'image_attribute_upload_size',
                'title' => 'admin::app.admin.system.image-upload-size',
                'type'  => 'text',
            ], [
                'name'  => 'file_attribute_upload_size',
                'title' => 'admin::app.admin.system.file-upload-size',
                'type'  => 'text',
            ],
        ],
    ], [
        'key'  => 'catalog.inventory',
        'name' => 'admin::app.admin.system.inventory',
        'sort' => 1,
    ], [
        'key'    => 'catalog.inventory.stock_options',
        'name'   => 'admin::app.admin.system.stock-options',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'backorders',
                'title'         => 'admin::app.admin.system.allow-backorders',
                'type'          => 'boolean',
                'channel_based' => true,
            ],
        ],
    ], [
        'key'  => 'catalog.rich_snippets',
        'name' => 'admin::app.admin.system.rich-snippets',
        'sort' => 3,
    ], [
        'key'    => 'catalog.rich_snippets.products',
        'name'   => 'admin::app.admin.system.products',
        'sort'   => 1,
        'fields' => [
            [
                'name'  => 'enable',
                'title' => 'admin::app.admin.system.enable',
                'type'  => 'boolean',
            ], [
                'name'  => 'show_sku',
                'title' => 'admin::app.admin.system.show-sku',
                'type'  => 'boolean',
            ], [
                'name'  => 'show_weight',
                'title' => 'admin::app.admin.system.show-weight',
                'type'  => 'boolean',
            ], [
                'name'  => 'show_categories',
                'title' => 'admin::app.admin.system.show-categories',
                'type'  => 'boolean',
            ], [
                'name'  => 'show_images',
                'title' => 'admin::app.admin.system.show-images',
                'type'  => 'boolean',
            ], [
                'name'  => 'show_reviews',
                'title' => 'admin::app.admin.system.show-reviews',
                'type'  => 'boolean',
            ], [
                'name'  => 'show_ratings',
                'title' => 'admin::app.admin.system.show-ratings',
                'type'  => 'boolean',
            ], [
                'name'  => 'show_offers',
                'title' => 'admin::app.admin.system.show-offers',
                'type'  => 'boolean',
            ],
        ],
    ], [
        'key'    => 'catalog.rich_snippets.categories',
        'name'   => 'admin::app.admin.system.categories',
        'sort'   => 1,
        'fields' => [
            [
                'name'  => 'enable',
                'title' => 'admin::app.admin.system.enable',
                'type'  => 'boolean',
            ], [
                'name'  => 'show_search_input_field',
                'title' => 'admin::app.admin.system.show-search-input-field',
                'type'  => 'boolean',
            ],
        ],
    ],

    /**
     * Customer.
     */
    [
        'key'  => 'customer',
        'name' => 'admin::app.admin.system.customer',
        'sort' => 3,
    ], [
        'key'  => 'customer.address',
        'name' => 'admin::app.admin.system.address',
        'sort' => 1,
    ], [
        'key'    => 'customer.address.requirements',
        'name'   => 'admin::app.admin.system.requirements',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'country',
                'title'         => 'admin::app.admin.system.country',
                'type'          => 'boolean',
                'channel_based' => true,
                'default'       => '1',
            ],
            [
                'name'          => 'state',
                'title'         => 'admin::app.admin.system.state',
                'type'          => 'boolean',
                'channel_based' => true,
                'default'       => '1',
            ],
            [
                'name'          => 'postcode',
                'title'         => 'admin::app.admin.system.zip',
                'type'          => 'boolean',
                'channel_based' => true,
                'default'       => '1',
            ],
        ],
    ], [
        'key'    => 'customer.address.information',
        'name'   => 'Information',
        'sort'   => 2,
        'fields' => [
            [
                'name'          => 'street_lines',
                'title'         => 'admin::app.admin.system.street-lines',
                'type'          => 'text',
                'validation'    => 'between:1,4',
                'channel_based' => true,
            ],
        ],
    ], [
        'key'  => 'customer.settings',
        'name' => 'admin::app.admin.system.settings',
        'sort' => 3,
    ], [
        'key'    => 'customer.settings.wishlist',
        'name'   => 'admin::app.admin.system.wishlist',
        'sort'   => 1,
        'fields' => [
            [
                'name'  => 'share',
                'title' => 'admin::app.admin.system.wishlist-share',
                'type'  => 'boolean',
            ],
        ],
    ], [
        'key'    => 'customer.settings.newsletter',
        'name'   => 'admin::app.admin.system.newsletter',
        'sort'   => 2,
        'fields' => [
            [
                'name'  => 'subscription',
                'title' => 'admin::app.admin.system.newsletter-subscription',
                'type'  => 'boolean',
            ],
        ],
    ], [
        'key'    => 'customer.settings.email',
        'name'   => 'admin::app.admin.system.email',
        'sort'   => 3,
        'fields' => [
            [
                'name'  => 'verification',
                'title' => 'admin::app.admin.system.email-verification',
                'type'  => 'boolean',
            ],
        ],
    ],

    /**
     * Emails.
     */
    [
        'key'  => 'emails',
        'name' => 'admin::app.admin.emails.email',
        'sort' => 4,
    ], [
        'key'  => 'emails.configure',
        'name' => 'admin::app.admin.system.email-settings',
        'sort' => 1,
    ], [
        'key'    => 'emails.configure.email_settings',
        'name'   => 'admin::app.admin.system.email-settings',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'sender_name',
                'title'         => 'admin::app.admin.system.email-sender-name',
                'type'          => 'text',
                'info'          => 'admin::app.admin.system.email-sender-name-tip',
                'validation'    => 'required|max:50',
                'channel_based' => true,
                'default_value' => config('mail.from.name'),
            ], [
                'name'          => 'shop_email_from',
                'title'         => 'admin::app.admin.system.shop-email-from',
                'type'          => 'text',
                'info'          => 'admin::app.admin.system.shop-email-from-tip',
                'validation'    => 'required|email',
                'channel_based' => true,
                'default_value' => config('mail.from.address'),
            ], [
                'name'          => 'admin_name',
                'title'         => 'admin::app.admin.system.admin-name',
                'type'          => 'text',
                'info'          => 'admin::app.admin.system.admin-name-tip',
                'validation'    => 'required|max:50',
                'channel_based' => true,
                'default_value' => config('mail.admin.name'),
            ], [
                'name'          => 'admin_email',
                'title'         => 'admin::app.admin.system.admin-email',
                'type'          => 'text',
                'info'          => 'admin::app.admin.system.admin-email-tip',
                'validation'    => 'required|email',
                'channel_based' => true,
                'default_value' => config('mail.admin.address'),
            ],
        ],
    ], [
        'key'  => 'emails.general',
        'name' => 'admin::app.admin.emails.notification_label',
        'sort' => 1,
    ], [
        'key'    => 'emails.general.notifications',
        'name'   => 'admin::app.admin.emails.notification_label',
        'sort'   => 1,
        'fields' => [
            [
                'name'  => 'emails.general.notifications.verification',
                'title' => 'admin::app.admin.emails.notifications.verification',
                'type'  => 'boolean',
            ],
            [
                'name'  => 'emails.general.notifications.registration',
                'title' => 'admin::app.admin.emails.notifications.registration',
                'type'  => 'boolean',
            ],
            [
                'name'  => 'emails.general.notifications.customer-registration-confirmation-mail-to-admin',
                'title' => 'admin::app.admin.emails.notifications.customer-registration-confirmation-mail-to-admin',
                'type'  => 'boolean',
            ],
            [
                'name'  => 'emails.general.notifications.customer',
                'title' => 'admin::app.admin.emails.notifications.customer',
                'type'  => 'boolean',
            ],
            [
                'name'  => 'emails.general.notifications.new-order',
                'title' => 'admin::app.admin.emails.notifications.new-order',
                'type'  => 'boolean',
            ],
            [
                'name'  => 'emails.general.notifications.new-admin',
                'title' => 'admin::app.admin.emails.notifications.new-admin',
                'type'  => 'boolean',
            ],
            [
                'name'  => 'emails.general.notifications.new-invoice',
                'title' => 'admin::app.admin.emails.notifications.new-invoice',
                'type'  => 'boolean',
            ],
            [
                'name'  => 'emails.general.notifications.new-refund',
                'title' => 'admin::app.admin.emails.notifications.new-refund',
                'type'  => 'boolean',
            ],
            [
                'name'  => 'emails.general.notifications.new-shipment',
                'title' => 'admin::app.admin.emails.notifications.new-shipment',
                'type'  => 'boolean',
            ],
            [
                'name'  => 'emails.general.notifications.new-inventory-source',
                'title' => 'admin::app.admin.emails.notifications.new-inventory-source',
                'type'  => 'boolean',
            ],
            [
                'name'  => 'emails.general.notifications.cancel-order',
                'title' => 'admin::app.admin.emails.notifications.cancel-order',
                'type'  => 'boolean',
            ],
        ],
    ],
];
