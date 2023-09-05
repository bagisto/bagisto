<?php

return [
    [
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
];