<?php

return [
    'checkout' => [
        'cart' => [
            'integrity' => [
                'qty-missing'   => 'Przynajmniej jeden produkt powinien mieć więcej niż 1 sztukę.',
            ],

            'inventory-warning'        => 'Żądana ilość nie jest dostępna, spróbuj ponownie później.',
            'missing-links'            => 'Brakujące linki do pobrania dla tego produktu.',
            'missing-options'          => 'Brak opcji dla tego produktu.',
            'selected-products-simple' => 'Wybrane produkty muszą być typu prostego.',
        ],
    ],

    'datagrid' => [
        'copy-of-slug'                  => 'kopia-znaku-:value',
        'copy-of'                       => 'Kopia z :value',
        'variant-already-exist-message' => 'Wariant o tych samych opcjach atrybutu już istnieje.',
    ],

    'response' => [
        'product-can-not-be-copied' => 'Produkty typu :type nie mogą być kopiowane',
    ],

    'sort-by'  => [
        'options' => [
            'cheapest-first'  => 'Najtańsze najpierw',
            'expensive-first' => 'Najdroższe najpierw',
            'from-a-z'        => 'Od A do Z',
            'from-z-a'        => 'Od Z do A',
            'latest-first'    => 'Najnowsze najpierw',
            'oldest-first'    => 'Najstarsze najpierw',
        ],
    ],

    'type'     => [
        'abstract'     => [
            'offers' => 'Kup :qty za :price każdy i zaoszczędź :discount',
        ],

        'bundle'       => 'Pakiet',
        'configurable' => 'Konfigurowalny',
        'downloadable' => 'Do pobrania',
        'grouped'      => 'Grupowany',
        'simple'       => 'Prosty',
        'virtual'      => 'Wirtualny',
    ],
];
