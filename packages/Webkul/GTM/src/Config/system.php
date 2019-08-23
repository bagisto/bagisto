<?php

return [
    [
        'key' => 'general.gtm',
        'name' => 'gtm::app.gtm',
        'sort' => 3,
    ], [
        'key' => 'general.gtm.values',
        'name' => 'gtm::app.values',
        'sort' => 1,
        'fields' => [
            [
                'name' => 'status',
                'title' => 'gtm::app.gtm-status',
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
                'name' => 'container_id',
                'title' => 'gtm::app.container-id',
                'type' => 'text',
                'validation' => 'required',
                'channel_based' => false,
                'locale_based' => false
            ]
        ]
    ]
];