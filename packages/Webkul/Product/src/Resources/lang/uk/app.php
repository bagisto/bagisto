<?php

return [
    'checkout' => [
        'cart' => [
            'integrity' => [
                'qty-missing'   => 'Принаймні один товар повинен мати більше 1 кількості.',
            ],

            'inventory-warning'        => 'Запитана кількість недоступна, будь ласка, спробуйте пізніше.',
            'missing-links'            => 'Відсутні посилання для завантаження для цього товару.',
            'missing-options'          => 'Відсутні опції для цього товару.',
            'selected-products-simple' => 'Вибрані товари повинні бути простими.',
        ],
    ],

    'datagrid' => [
        'copy-of-slug'                  => 'копія-:value',
        'copy-of'                       => 'Копія :value',
        'variant-already-exist-message' => 'Варіант із тими самими опціями атрибута вже існує.',
    ],

    'response' => [
        'product-can-not-be-copied' => 'Продукти типу :type не можуть бути скопійовані',
    ],

    'sort-by'  => [
        'options' => [
            'cheapest-first'  => 'Найдешевші спочатку',
            'expensive-first' => 'Найдорожчі спочатку',
            'from-a-z'        => 'Від А до Я',
            'from-z-a'        => 'Від Я до А',
            'latest-first'    => 'Спочатку нові',
            'oldest-first'    => 'Спочатку старі',
        ],
    ],

    'type'     => [
        'abstract'     => [
            'offers' => 'Купуйте :qty за :price кожен і заощаджуйте :discount',
        ],

        'bundle'       => 'Пакет',
        'configurable' => 'Налагоджуваний',
        'downloadable' => 'Завантажуваний',
        'grouped'      => 'Групований',
        'simple'       => 'Простий',
        'virtual'      => 'Віртуальний',
    ],
];
