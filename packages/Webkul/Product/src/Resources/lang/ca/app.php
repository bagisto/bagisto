<?php

return [
    'checkout' => [
        'cart' => [
            'integrity' => [
                'qty-missing'   => 'Al menys un producte ha de tenir més d\'1 quantitat.',
            ],

            'invalid-file-extension'   => 'Extension de fichier invalide trouvée.',
            'inventory-warning'        => 'La quantitat solicitada no està disponible, si us plau, intenti-ho de nou més tard.',
            'missing-links'            => 'Falten enllaços descarregables per a aquest producte.',
            'missing-options'          => 'Falten opcions per a aquest producte.',
            'selected-products-simple' => 'Els productes seleccionats han de ser del tipus de producte simple.',
        ],
    ],

    'datagrid' => [
        'copy-of-slug'                  => 'copia-de-:value',
        'copy-of'                       => 'Copia de :value',
        'variant-already-exist-message' => 'ja existeix una variant amb les mateixes opcions d\'atributs.',
    ],

    'response' => [
        'product-can-not-be-copied' => 'Els productes del tipus :type no es poden copiar',
    ],

    'sort-by'  => [
        'options' => [
            'cheapest-first'  => 'Més barats primer',
            'expensive-first' => 'Més cars primer',
            'from-a-z'        => 'De l\'A a la Z',
            'from-z-a'        => 'De la Z a la A',
            'latest-first'    => 'Més recents primer',
            'oldest-first'    => 'Més antics primer',
        ],
    ],

    'type'     => [
        'abstract'     => [
            'offers' => 'Compra :qty per :price cadascún i estalvia :discount',
        ],

        'bundle'       => 'Paquet',
        'booking'      => 'Reserva',
        'configurable' => 'Configurable',
        'downloadable' => 'Descarregable',
        'grouped'      => 'Agrupat',
        'simple'       => 'Simple',
        'virtual'      => 'Virtual',
    ],
];
