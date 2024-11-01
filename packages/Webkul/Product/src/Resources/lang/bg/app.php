<?php

return [
    'checkout' => [
        'cart' => [
            'integrity' => [
                'qty-missing'   => 'Поне един от продуктите трябва да има повече от 1 брой.',
            ],

            'inventory-warning' => 'Заявеното количество не е налично. Моля, опитайте отново по-късно.',
            'missing-links'     => 'Липсват линковете за изтегляне за този продукт.',
            'missing-options'   => 'Липсват опции за този продукт.',
        ],
    ],

    'datagrid' => [
        'copy-of-slug'                  => 'copy-of-:value',
        'copy-of'                       => 'Копие на :value',
        'variant-already-exist-message' => 'Вече съществува вариант със същите опции за атрибут.',
    ],

    'response' => [
        'product-can-not-be-copied' => 'Продукти от тип :type не могат да бъдат копирани',
    ],

    'sort-by'  => [
        'options' => [
            'cheapest-first'  => 'Най-евтините',
            'expensive-first' => 'Най-скъпите',
            'from-a-z'        => 'От А-Я',
            'from-z-a'        => 'От Я-А',
            'latest-first'    => 'Най-новите',
            'oldest-first'    => 'Най-старите',
        ],
    ],

    'type'     => [
        'abstract'     => [
            'offers' => 'Купете :qty за :price всеки и спестете :discount',
        ],

        'bundle'       => 'Комплект',
        'configurable' => 'Конфигурируеми',
        'downloadable' => 'С възможност за изтегляне',
        'grouped'      => 'Групирани',
        'simple'       => 'Проби',
        'virtual'      => 'Виртуални',
    ],
];
