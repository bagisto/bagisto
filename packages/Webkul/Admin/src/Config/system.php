<?php

return [
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
        'key'    => 'general.general.email_settings',
        'name'   => 'admin::app.admin.system.email-settings',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'sender_name',
                'title'         => 'admin::app.admin.system.email-sender-name',
                'type'          => 'text',
                'validation'    => 'required|max:50',
                'channel_based' => true,
            ],  [
                'name'          => 'shop_email_from',
                'title'         => 'admin::app.admin.system.shop-email-from',
                'type'          => 'text',
                'validation'    => 'required|email',
                'channel_based' => true,
            ],  [
                'name'          => 'admin_name',
                'title'         => 'admin::app.admin.system.admin-name',
                'type'          => 'text',
                'validation'    => 'required|max:50',
                'channel_based' => true,
            ],  [
                'name'          => 'admin_email',
                'title'         => 'admin::app.admin.system.admin-email',
                'type'          => 'text',
                'validation'    => 'required|email',
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
                'validation'    => 'mimes:jpeg,bmp,png,jpg',
            ],
        ],
    ], [
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
        'key'    => 'catalog.products.review',
        'name'   => 'admin::app.admin.system.review',
        'sort'   => 2,
        'fields' => [
            [
                'name'  => 'guest_review',
                'title' => 'admin::app.admin.system.allow-guest-review',
                'type'  => 'boolean',
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
        'key'  => 'social-login',
        'name' => 'admin::app.admin.system.sociallogin',
        'sort' => 3,
    ], [
        'key'  => 'social-login.settings',
        'name' => 'admin::app.admin.system.sociallogin',
        'sort' => 1,
    ], [
        'key'    => 'social-login.settings.sociallogins-enable-disable',
        'name'   => 'admin::app.admin.system.sociallogins-enable-disable',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'sociallogins_status',
                'title'         => 'admin::app.admin.system.sociallogins-status',
                'type'          => 'select',
                'options'       => [
                    [
                        'title' => 'Enable',
                        'value' => '1',
                    ], [
                        'title' => 'Disable',
                        'value' => '0',
                    ],
                ],
                'channel_based' => true,
            ],
        ],
         
    ],[
        'key'    => 'social-login.settings.sociallogins-facebook',
        'name'   => 'admin::app.admin.system.sociallogins-facebook',
        'sort'   => 2,
        'fields' => [
            [
                'name'          => 'sociallogins_facebook_status',
                'title'         => 'admin::app.admin.system.sociallogins-facebook-status',
                'type'          => 'select',
                'options'       => [
                    [
                        'title' => 'Enable',
                        'value' => '1',
                    ], [
                        'title' => 'Disable',
                        'value' => '0',
                    ],
                ],
                'channel_based' => true,
            ], [
                'name'          => 'facebook_appid',
                'title'         => 'admin::app.admin.system.sociallogins-facebook-appid',
                'type'          => 'text',
                'validation'    => 'required|max:100',
                'channel_based' => true,
            ],  [
                'name'          => 'facebook_appsecret',
                'title'         => 'admin::app.admin.system.sociallogins-facebook-appsecret',
                'type'          => 'text',
                'validation'    => 'required|max:100',
                'channel_based' => true,
            ],[
                'name'          => 'facebook_loginbutton',
                'title'         => 'admin::app.admin.system.sociallogins-facebook-appimage',
                'type'          => 'file',
                'validation'    => 'mimes:jpg,jpeg,gif,png',
                'channel_based' => true,
            ],
        ],

    ],[
        'key'    => 'social-login.settings.sociallogins-google',
        'name'   => 'admin::app.admin.system.sociallogins-google',
        'sort'   => 2,
        'fields' => [
            [
                'name'          => 'sociallogins_google_status',
                'title'         => 'admin::app.admin.system.sociallogins-google-status',
                'type'          => 'select',
                'options'       => [
                    [
                        'title' => 'Enable',
                        'value' => '1',
                    ], [
                        'title' => 'Disable',
                        'value' => '0',
                    ],
                ],
                'channel_based' => true,
            ], [
                'name'          => 'google_appid',
                'title'         => 'admin::app.admin.system.sociallogins-google-clientid',
                'type'          => 'text',
                'validation'    => 'required|max:100',
                'channel_based' => true,
            ],  [
                'name'          => 'google_appsecret',
                'title'         => 'admin::app.admin.system.sociallogins-google-clientsecret',
                'type'          => 'text',
                'validation'    => 'required|max:100',
                'channel_based' => true,
            ],[
                'name'          => 'google_loginbutton',
                'title'         => 'admin::app.admin.system.sociallogins-google-appimage',
                'type'          => 'file',
                'validation'    => 'mimes:jpg,jpeg,gif,png',
                'channel_based' => true,
            ],
        ],

    ], [
        'key'  => 'customer',
        'name' => 'admin::app.admin.system.customer',
        'sort' => 3,
    ], [
        'key'  => 'customer.settings',
        'name' => 'admin::app.admin.system.settings',
        'sort' => 1,
    ], [
        'key'    => 'customer.settings.address',
        'name'   => 'admin::app.admin.system.address',
        'sort'   => 1,
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
    ], [
        'key'  => 'emails',
        'name' => 'admin::app.admin.emails.email',
        'sort' => 1,
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