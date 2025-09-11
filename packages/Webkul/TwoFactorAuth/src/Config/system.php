<?php

return [  
       [
        'key'  => 'general.two_factor_auth',
        'name' => 'two_factor_auth::app.configuration.index.general.two_factor_auth.title',
        'info' => 'two_factor_auth::app.configuration.index.general.two_factor_auth.info',
        'icon' => 'settings/store.svg',
        'sort' => 5,
    ], [
        'key'    => 'general.two_factor_auth.settings',
        'name'   => 'two_factor_auth::app.configuration.index.general.two_factor_auth.settings.title',
        'info'   => 'two_factor_auth::app.configuration.index.general.two_factor_auth.settings.info',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'enabled',
                'title'         => 'two_factor_auth::app.configuration.index.general.two_factor_auth.settings.enabled',
                'type'          => 'boolean',
                'channel_based' => true,
                'locale_based'  => true,
            ],
        ],
    ],
];
