<?php

return [
    [
        'key'    => 'catalog.products.social_share',
        'name'   => 'Social Share',
        'sort'   => 100,
        'fields' => [
            [
                'name'  => 'enabled',
                'title' => 'SocialShare::app.config.system.enable-social-share',
                'type'  => 'boolean',
            ], [
                'name'  => 'facebook',
                'title' => 'SocialShare::app.config.system.enable-share-facebook',
                'type'  => 'boolean',
            ], [
                'name'  => 'twitter',
                'title' => 'SocialShare::app.config.system.enable-share-twitter',
                'type'  => 'boolean',
            ], [
                'name'  => 'pinterest',
                'title' => 'SocialShare::app.config.system.enable-share-pintrest',
                'type'  => 'boolean',
            ], [
                'name'  => 'whatsapp',
                'title' => 'SocialShare::app.config.system.enable-share-whatsapp',
                'type'  => 'boolean',
                'info'  => 'What\'s App share link just will appear to mobile devices.'
            ], [
                'name'  => 'linkedin',
                'title' => 'SocialShare::app.config.system.enable-share-linkedin',
                'type'  => 'boolean',
            ], [
                'name'  => 'email',
                'title' => 'SocialShare::app.config.system.enable-share-email',
                'type'  => 'boolean',
            ], [
                'name'  => 'share_message',
                'title' => 'SocialShare::app.config.system.share-message',
                'type'  => 'text',
            ],
        ],
    ]
];
