<?php

return [
    [
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
    ]
];
