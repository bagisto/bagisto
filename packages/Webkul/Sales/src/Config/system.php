<?php

return [
    [
        'key'  => 'sales.orderSettings',
        'name' => 'admin::app.admin.system.order-settings',
        'sort' => 3,
    ],[
        'key'    => 'sales.orderSettings.order_number',
        'name'   => 'admin::app.admin.system.orderNumber',
        'sort'   => 0,
        'fields' => [
            [
                'name'          => 'order_number_prefix',
                'title'         => 'admin::app.admin.system.order-number-prefix',
                'type'          => 'text',
                'validation'    => false,
                'channel_based' => true,
                'locale_based'  => true,
            ],
            [
                'name'          => 'order_number_length',
                'title'         => 'admin::app.admin.system.order-number-length',
                'type'          => 'text',
                'validation'    => 'numeric',
                'channel_based' => true,
                'locale_based'  => true,
            ],
            [
                'name'          => 'order_number_suffix',
                'title'         => 'admin::app.admin.system.order-number-suffix',
                'type'          => 'text',
                'validation'    => false,
                'channel_based' => true,
                'locale_based'  => true,
            ],
        ]
    ], [
        'key'    => 'sales.orderSettings.invoice_slip_design',
        'name'   => 'admin::app.admin.system.invoice-slip-design',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'logo',
                'title'         => 'admin::app.admin.system.logo',
                'type'          => 'image',
                'validation'    => 'mimes:jpeg,bmp,png,jpg',
                'channel_based' => true,
            ],
            [
                'name'          => 'address',
                'title'         => 'admin::app.admin.system.address',
                'type'          => 'textarea',
                'channel_based' => true,
            ]
        ]
    ]
];