<?php

return [
    'checkout' => [
        'cart' => [
            'integrity' => [
                'qty-missing'   => 'По крайней мере, у одного товара должно быть более 1 количество.',
            ],

            'inventory-warning'        => 'Запрошенное количество недоступно, пожалуйста, попробуйте позже.',
            'missing-links'            => 'Отсутствуют загружаемые ссылки для этого товара.',
            'missing-options'          => 'Отсутствуют опции для этого товара.',
            'selected-products-simple' => 'Выбранные товары должны быть простого типа.',
        ],
    ],

    'datagrid' => [
        'copy-of-slug'                  => 'копия-:value',
        'copy-of'                       => 'Копия :value',
        'variant-already-exist-message' => 'Вариант с теми же параметрами атрибута уже существует.',
    ],

    'response' => [
        'product-can-not-be-copied' => 'Продукты типа :type не могут быть скопированы',
    ],

    'sort-by'  => [
        'options' => [
            'cheapest-first'  => 'Сначала дешевые',
            'expensive-first' => 'Сначала дорогие',
            'from-a-z'        => 'От А до Z',
            'from-z-a'        => 'От Z до A',
            'latest-first'    => 'Сначала новые',
            'oldest-first'    => 'Сначала старые',
        ],
    ],

    'type'     => [
        'abstract'     => [
            'offers' => 'Покупайте :qty по :price за каждый и экономьте :discount',
        ],

        'bundle'       => 'Набор',
        'configurable' => 'Настроенный',
        'downloadable' => 'Загружаемый',
        'grouped'      => 'Группированный',
        'simple'       => 'Простой',
        'virtual'      => 'Виртуальный',
    ],
];
