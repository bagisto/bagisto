<?php

return [
    'checkout' => [
        'cart' => [
            'integrity' => [
                'qty-missing'   => 'Almeno un prodotto deve avere una quantità superiore a 1.',
            ],

            'inventory-warning' => 'La quantità richiesta non è disponibile, si prega di riprovare più tardi.',
            'missing-links'     => 'Link scaricabili mancanti per questo prodotto.',
            'missing-options'   => 'Mancano le opzioni per questo prodotto.',
        ],
    ],

    'datagrid' => [
        'copy-of-slug'                  => 'copia-di-:value',
        'copy-of'                       => 'Copia di :value',
        'variant-already-exist-message' => 'Esiste già una variante con le stesse opzioni di attributo.',
    ],

    'response' => [
        'product-can-not-be-copied' => 'I prodotti di tipo :type non possono essere copiati',
    ],

    'sort-by'  => [
        'options' => [
            'cheapest-first'  => 'Meno cari prima',
            'expensive-first' => 'Più cari prima',
            'from-a-z'        => 'Da A a Z',
            'from-z-a'        => 'Da Z a A',
            'latest-first'    => 'I più recenti prima',
            'oldest-first'    => 'I più vecchi prima',
        ],
    ],

    'type'     => [
        'abstract'     => [
            'offers' => 'Acquista :qty a :price ciascuno e risparmia :discount',
        ],

        'bundle'       => 'Pacchetto',
        'configurable' => 'Configurabile',
        'downloadable' => 'Scaricabile',
        'grouped'      => 'Raggruppato',
        'simple'       => 'Semplice',
        'virtual'      => 'Virtuale',
    ],
];
