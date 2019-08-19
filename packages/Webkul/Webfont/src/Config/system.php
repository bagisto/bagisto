<?php

return [
    [
        'key' => 'general.design.webfont',
        'name' => 'webfont::app.webfont',
        'sort' => 1,
        'fields' => [
            [
                'name' => 'status',
                'title' => 'webfont::app.webfont-status',
                'type' => 'select',
                'options' => [
                    [
                        'title' => 'Active',
                        'value' => true
                    ], [
                        'title' => 'Inactive',
                        'value' => false
                    ]
                ],

                'channel_based' => false,
                'locale_based' => false
            ], [
                'name' => 'webfont',
                'title' => 'webfont::app.webfont-api',
                'type' => 'text',
                'validation' => 'required',
                'channel_based' => false,
                'locale_based' => false
            ], [
                'name' => 'enable_backend',
                'title' => 'webfont::app.webfont-backend',
                'type' => 'select',
                'channel_based' => false,
                'locale_based' => false,
                'options' => [
                    [
                        'title' => 'Active',
                        'value' => true
                    ], [
                        'title' => 'Inactive',
                        'value' => false
                    ]
                ]
            ], [
                'name' => 'enable_frontend',
                'title' => 'webfont::app.webfont-frontend',
                'type' => 'select',
                'channel_based' => false,
                'locale_based' => false,
                'options' => [
                    [
                        'title' => 'Active',
                        'value' => true
                    ], [
                        'title' => 'Inactive',
                        'value' => false
                    ]
                ]
            ]
        ]
    ]
];