<?php

return [
    'simple'       => [
        'key'   => 'simple',
        'name'  => 'product::app.type.simple',
        'class' => 'Webkul\Product\Type\Simple',
        'sort'  => 1,
    ],

    'booking' => [
        'key'   => 'booking',
        'name'  => 'product::app.type.booking',
        'class' => 'Webkul\Product\Type\Booking',
        'sort'  => 2,
    ],

    'configurable' => [
        'key'   => 'configurable',
        'name'  => 'product::app.type.configurable',
        'class' => 'Webkul\Product\Type\Configurable',
        'sort'  => 3,
    ],

    'virtual'      => [
        'key'   => 'virtual',
        'name'  => 'product::app.type.virtual',
        'class' => 'Webkul\Product\Type\Virtual',
        'sort'  => 4,
    ],

    'grouped'      => [
        'key'   => 'grouped',
        'name'  => 'product::app.type.grouped',
        'class' => 'Webkul\Product\Type\Grouped',
        'sort'  => 5,
    ],

    'downloadable' => [
        'key'   => 'downloadable',
        'name'  => 'product::app.type.downloadable',
        'class' => 'Webkul\Product\Type\Downloadable',
        'sort'  => 6,
    ],

    'bundle'       => [
        'key'   => 'bundle',
        'name'  => 'product::app.type.bundle',
        'class' => 'Webkul\Product\Type\Bundle',
        'sort'  => 7,
    ],
];
