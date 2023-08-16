<?php

return [
    [
        'key'  => 'taxes',
        'name' => 'tax::app.admin.system.taxes.taxes',
        'info' => 'tax::app.admin.system.taxes.taxes',
        'sort' => 6,
    ],

    /**
     * Catalog.
     */
    [
        'key'  => 'taxes.catalogue',
        'name' => 'tax::app.admin.system.taxes.catalogue',
        'info' => 'tax::app.admin.system.taxes.catalogue-info',
        'icon' => 'tax.png',
        'sort' => 1,
    ], [
        'key'    => 'taxes.catalogue.pricing',
        'name'   => 'tax::app.admin.system.taxes.pricing',
        'info'   => 'tax::app.admin.system.taxes.pricing-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'       => 'tax_inclusive',
                'title'      => 'tax::app.admin.system.taxes.tax-inclusive',
                'type'       => 'boolean',
                'validation' => 'required',
                'default'    => false,
            ],
        ],
    ], [
        'key'    => 'taxes.catalogue.default-location-calculation',
        'name'   => 'tax::app.admin.system.taxes.default-location-calculation',
        'info'   => 'tax::app.admin.system.taxes.default-location-calculation-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'    => 'country',
                'title'   => 'tax::app.admin.system.taxes.default-country',
                'type'    => 'country',
                'default' => '',
            ],
            [
                'name'    => 'state',
                'title'   => 'tax::app.admin.system.taxes.default-state',
                'type'    => 'state',
                'default' => '',
            ],
            [
                'name'    => 'post_code',
                'title'   => 'tax::app.admin.system.taxes.default-post-code',
                'type'    => 'text',
                'default' => '',
            ],
        ],
    ],
];
