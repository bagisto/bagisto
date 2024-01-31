<?php

return [
    'checkout' => [
        'cart' => [
            'integrity' => [
                'qty-missing'   => 'Minstens één product moet meer dan 1 hoeveelheid hebben.',
            ],

            'inventory-warning' => 'De gevraagde hoeveelheid is niet beschikbaar, probeer het later opnieuw.',
            'missing-links'     => 'Downloadbare links ontbreken voor dit product.',
            'missing-options'   => 'Opties ontbreken voor dit product.',
        ],
    ],

    'datagrid' => [
        'copy-of-slug'                  => 'kopie-van-:value',
        'copy-of'                       => 'Kopie van :value',
        'variant-already-exist-message' => 'Variant met dezelfde attribuutopties bestaat al.',
    ],

    'response' => [
        'product-can-not-be-copied' => 'Producten van het type :type kunnen niet worden gekopieerd',
    ],

    'sort-by'  => [
        'options' => [
            'cheapest-first'  => 'Goedkoopste eerst',
            'expensive-first' => 'Duurste eerst',
            'from-a-z'        => 'Van A tot Z',
            'from-z-a'        => 'Van Z tot A',
            'latest-first'    => 'Nieuwste eerst',
            'oldest-first'    => 'Oudste eerst',
        ],
    ],

    'type'     => [
        'abstract'     => [
            'offers' => 'Koop :qty voor :price per stuk en bespaar :discount',
        ],

        'bundle'       => 'Bundel',
        'configurable' => 'Configureerbaar',
        'downloadable' => 'Downloadbaar',
        'grouped'      => 'Gegroepeerd',
        'simple'       => 'Eenvoudig',
        'virtual'      => 'Virtueel',
    ],
];
