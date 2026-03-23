<?php

return [
    [
        'key' => 'catalog.products.omnibus',
        'name' => 'omnibus::app.admin.system.omnibus',
        'info' => 'omnibus::app.admin.system.omnibus-info',
        'sort' => 150,
        'fields' => [
            [
                'name' => 'is_enabled',
                'title' => 'omnibus::app.admin.system.is-enabled',
                'type' => 'boolean',
                'channel_based' => true,
                'locale_based' => false,
            ],
        ],
    ],
];
