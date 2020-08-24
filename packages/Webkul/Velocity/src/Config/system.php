<?php

return [
    [
        'key'  => 'velocity',
        'name' => 'velocity::app.admin.system.velocity.extension_name',
        'sort' => 2,
    ], [
        'key'  => 'velocity.configuration',
        'name' => 'velocity::app.admin.system.velocity.settings',
        'sort' => 1,
    ], [
        'key'   => 'velocity.configuration.general',
        'name'  => 'velocity::app.admin.system.velocity.general',
        'sort'  => 1,
        'fields' => [
            [
                'name'    => 'status',
                'title'   => 'velocity::app.admin.system.general.status',
                'type'    => 'select',
                'options' => [
                    [
                        'title' => 'velocity::app.admin.system.general.active',
                        'value' => true,
                    ], [
                        'title' => 'velocity::app.admin.system.general.inactive',
                        'value' => false,
                    ]
                ]
            ]
        ]
    ],  [
        'key'    => 'velocity.configuration.category',
        'name'   => 'velocity::app.admin.system.velocity.category',
        'sort'   => 1,
        'fields' => [
            [
                'name'    => 'icon_status',
                'title'   => 'velocity::app.admin.system.category.icon-status',
                'type'    => 'select',
                'options' => [
                    [
                        'title' => 'velocity::app.admin.system.category.active',
                        'value' => true,
                    ], [
                        'title' => 'velocity::app.admin.system.category.inactive',
                        'value' => false,
                    ]
                ]
            ],  [
                'name'    => 'image_status',
                'title'   => 'velocity::app.admin.system.category.image-status',
                'type'    => 'select',
                'options' => [
                    [
                        'title' => 'velocity::app.admin.system.category.active',
                        'value' => true,
                    ], [
                        'title' => 'velocity::app.admin.system.category.inactive',
                        'value' => false,
                    ]
                ]
            ],  [
                'name'          => 'image_height',
                'title'         => 'velocity::app.admin.system.category.image-height',
                'type'          => 'depands',
                'depand'        => 'image_status:true',
                'validation'    => 'numeric|max:3',
                'channel_based' => false,
                'locale_based'  => false,
            ],  [
                'name'          => 'image_width',
                'title'         => 'velocity::app.admin.system.category.image-width',
                'type'          => 'depands',
                'depand'        => 'image_status:true',
                'validation'    => 'numeric|max:3',
                'channel_based' => false,
                'locale_based'  => false,
            ],  [
                'name'          => 'image_alignment',
                'title'         => 'velocity::app.admin.system.category.image-alignment',
                'channel_based' => false,
                'locale_based'  => false,
                'type'          => 'depands',
                'depand'        => 'image_status:true',
                'options'       => [
                    [
                        'title' => 'Right',
                        'value' => 'right',
                    ], [
                        'title' => 'Left',
                        'value' => 'left',
                    ]
                ]
            ],  [
                'name'    => 'tooltip_status',
                'title'   => 'velocity::app.admin.system.category.show-tooltip',
                'type'    => 'select',
                'options' => [
                    [
                        'title' => 'velocity::app.admin.system.category.active',
                        'value' => true,
                    ], [
                        'title' => 'velocity::app.admin.system.category.inactive',
                        'value' => false,
                    ]
                ]
            ],  [
                'name'          => 'sub_category',
                'title'         => 'velocity::app.admin.system.category.sub-category-show',
                'channel_based' => false,
                'locale_based'  => false,
                'type'          => 'select',
                'options'       => [
                    [
                        'title' => 'All',
                        'value' => 'all',
                    ], [
                        'title' => 'Custom',
                        'value' => 'custom',
                    ]
                ]
            ],  [
                'name'          => 'sub_category_num',
                'title'         => 'velocity::app.admin.system.category.num-sub-category',
                'channel_based' => false,
                'locale_based'  => false,
                'type'          => 'depands',
                'depand'        => 'sub_category:custom',
                'validation'    => 'numeric|max:2',
            ]
        ]
    ]
];