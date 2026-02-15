<?php

return [
    [
        'key'  => 'marketplace',
        'name' => 'marketplace::app.admin.system.marketplace',
        'info' => 'marketplace::app.admin.system.marketplace-info',
        'sort' => 5,
    ], [
        'key'  => 'marketplace.settings',
        'name' => 'marketplace::app.admin.system.settings',
        'info' => 'marketplace::app.admin.system.settings-info',
        'sort' => 1,
    ], [
        'key'  => 'marketplace.settings.general',
        'name' => 'marketplace::app.admin.system.general',
        'info' => 'marketplace::app.admin.system.general-info',
        'sort' => 1,
        'fields' => [
            [
                'name'          => 'status',
                'title'         => 'marketplace::app.admin.system.enable-marketplace',
                'type'          => 'boolean',
                'default'       => '1',
                'channel_based' => true,
            ], [
                'name'          => 'commission_percentage',
                'title'         => 'marketplace::app.admin.system.default-commission',
                'type'          => 'text',
                'default'       => '10',
                'validation'    => 'required|numeric|min:0|max:100',
                'channel_based' => true,
            ], [
                'name'          => 'seller_approval_required',
                'title'         => 'marketplace::app.admin.system.seller-approval-required',
                'type'          => 'boolean',
                'default'       => '1',
                'channel_based' => true,
            ], [
                'name'          => 'product_approval_required',
                'title'         => 'marketplace::app.admin.system.product-approval-required',
                'type'          => 'boolean',
                'default'       => '1',
                'channel_based' => true,
            ],
        ],
    ],
];
