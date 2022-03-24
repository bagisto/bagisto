<?php

return [
    [
        'key'    => 'customer.settings.social_login',
        'name'   => 'sociallogin::app.admin.system.social-login',
        'sort'   => 4,
        'fields' => [
            [
                'name'          => 'enable_facebook',
                'title'         => 'sociallogin::app.admin.system.enable-facebook',
                'type'          => 'boolean',
                'channel_based' => true,
            ], [
                'name'          => 'enable_twitter',
                'title'         => 'sociallogin::app.admin.system.enable-twitter',
                'type'          => 'boolean',
                'channel_based' => true,
            ], [
                'name'          => 'enable_google',
                'title'         => 'sociallogin::app.admin.system.enable-google',
                'type'          => 'boolean',
                'channel_based' => true,
            ], [
                'name'          => 'enable_linkedin',
                'title'         => 'sociallogin::app.admin.system.enable-linkedin',
                'type'          => 'boolean',
                'channel_based' => true,
            ], [
                'name'          => 'enable_github',
                'title'         => 'sociallogin::app.admin.system.enable-github',
                'type'          => 'boolean',
                'channel_based' => true,
            ]
        ],
    ],
];