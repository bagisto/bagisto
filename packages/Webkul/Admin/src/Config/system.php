<?php

return [
    [
        'key' => 'catalog',
        'name' => 'admin::app.admin.system.catalog',
        'sort' => 1
    ], [
        'key' => 'catalog.products',
        'name' => 'admin::app.admin.system.products',
        'sort' => 1,
    ], [
        'key' => 'catalog.products.review',
        'name' => 'admin::app.admin.system.review',
        'sort' => 1,
        'fields' => [
            [
                'name' => 'guest_review',
                'title' => 'admin::app.admin.system.allow-guest-review',
                'type' => 'boolean'
            ]
        ]
    ], [
        'key' => 'customer',
        'name' => 'admin::app.admin.system.customer',
        'sort' => 3,
    ], [
        'key' => 'customer.settings',
        'name' => 'admin::app.admin.system.settings',
        'sort' => 1,
    ], [
        'key' => 'customer.settings.address',
        'name' => 'admin::app.admin.system.address',
        'sort' => 1,
        'fields' => [
            [
                'name' => 'street_lines',
                'title' => 'admin::app.admin.system.street-lines',
                'type' => 'text',
                'validation' => 'between:1,4',
                'channel_based' => true
            ]
        ]
    ], [
        'key' => 'customer.settings.newsletter',
        'name' => 'admin::app.admin.system.newsletter',
        'sort' => 2,
        'fields' => [
            [
                'name' => 'subscription',
                'title' => 'admin::app.admin.system.newsletter-subscription',
                'type' => 'boolean'
            ]
        ],
    ], [
        'key' => 'customer.settings.email',
        'name' => 'admin::app.admin.system.email',
        'sort' => 2,
        'fields' => [
            [
                'name' => 'verification',
                'title' => 'admin::app.admin.system.email-verification',
                'type' => 'boolean'
            ]
        ],
    ], [
        'key' => 'general',
        'name' => 'admin::app.admin.system.general',
        'sort' => 4,
    ], [
        'key' => 'general.content',
        'name' => 'admin::app.admin.system.content',
        'sort' => 1,
    ], [
        'key' => 'general.content.footer',
        'name' => 'admin::app.admin.system.footer',
        'sort' => 1,
        'fields' => [
            [
                'name' => 'footer_content',
                'title' => 'admin::app.admin.system.footer-content',
                'type' => 'text',
                'channel_based' => true,
                'locale_based' => true
            ]
        ]
    ],
];