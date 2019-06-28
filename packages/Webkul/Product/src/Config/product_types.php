<?php

return [
    'simple' => [
        'key' => 'simple',
        'name' => 'Simple',
        'class' => 'Webkul\Product\Type\Simple',
        'sort' => 1

    ],
    'configurable' => [
        'key' => 'configurable',
        'name' => 'Configurable',
        'class' => 'Webkul\Product\Type\Configurable',
        'sort' => 2
    ],
    'virtual' => [
        'key' => 'virtual',
        'name' => 'Virtual',
        'class' => 'Webkul\Product\Type\Virtual',
        'sort' => 3
    ],
    'downloadable' => [
        'key' => 'downloadable',
        'name' => 'Downloadable',
        'class' => 'Webkul\Product\Type\Downloadable',
        'sort' => 4
    ]
];