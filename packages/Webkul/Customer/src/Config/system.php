<?php

return [
    [
        'key'  => 'customer.captcha',
        'name' => 'customer::app.admin.system.captcha.title',
        'info' => 'customer::app.admin.system.captcha.info',
        'icon' => 'captcha.png',
        'sort' => 2,
    ], [
        'key'    => 'customer.captcha.credentials',
        'name'   => 'customer::app.admin.system.captcha.credentials',
        'info'   => 'customer::app.admin.system.captcha.credentials-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'site_key',
                'title'         => 'customer::app.admin.system.captcha.site-key',
                'type'          => 'text',
                'channel_based' => true,
            ], [
                'name'          => 'secret_key',
                'title'         => 'customer::app.admin.system.captcha.secret-key',
                'type'          => 'text',
                'channel_based' => true,
            ], [
                'name'          => 'status',
                'title'         => 'customer::app.admin.system.captcha.status',
                'type'          => 'boolean',
                'channel_based' => true,
            ],
        ],
    ],
];
