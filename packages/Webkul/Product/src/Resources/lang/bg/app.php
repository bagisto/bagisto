<?php

return [
    'checkout' => [
        'cart' => [
            'integrity' => [
                'qty-missing'   => 'Най-малко един продукт трябва да има повече от 1 брой.',
            ],

            'inventory-warning' => 'Заявеното количество не е налично, моля опитайте отново по-късно.',
            'missing-links'     => 'Липсват връзки за изтегляне за този продукт.',
            'missing-options'   => 'Липсват опции за този продукт.',
        ],
    ],

    'datagrid' => [
        'copy-of-slug'                  => 'copy-of-:value',
        'copy-of'                       => 'Копирай от :value',
        'variant-already-exist-message' => 'Вариант със същите атрибутни опции вече съществува.',
    ],

    'response' => [
        'product-can-not-be-copied' => 'Продукти от тип :type не могат да бъдат копирани',
    ],

    'sort-by'  => [
        'options' => [
            'cheapest-first'  => 'Най-евтино',
            'expensive-first' => 'Най-скъпо',
            'from-a-z'        => 'От А-Я',
            'from-z-a'        => 'От Я-А',
            'latest-first'    => 'Най-ново',
            'oldest-first'    => 'Най-старо',
        ],
    ],

    'type'     => [
        'abstract'     => [
            'offers' => 'Купи :qty за :price от всеки и спести :discount',
        ],

        'bundle'       => 'Пакет',
        'configurable' => 'Конфигурируем',
        'downloadable' => 'Изтегляем',
        'grouped'      => 'Групиран',
        'simple'       => 'Обикновен',
        'virtual'      => 'Виртуален',
    ],
];
