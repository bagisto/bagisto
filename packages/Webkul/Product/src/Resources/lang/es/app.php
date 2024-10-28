<?php

return [
    'checkout' => [
        'cart' => [
            'integrity' => [
                'qty-missing'   => 'Al menos un producto debe tener más de 1 cantidad.',
            ],

            'inventory-warning'        => 'La cantidad solicitada no está disponible, por favor, inténtelo de nuevo más tarde.',
            'missing-links'            => 'Faltan enlaces descargables para este producto.',
            'missing-options'          => 'Faltan opciones para este producto.',
            'selected-products-simple' => 'Los productos seleccionados deben ser del tipo de producto simple.',
        ],
    ],

    'datagrid' => [
        'copy-of-slug'                  => 'copia-de-:value',
        'copy-of'                       => 'Copia de :value',
        'variant-already-exist-message' => 'Ya existe una variante con las mismas opciones de atributos.',
    ],

    'response' => [
        'product-can-not-be-copied' => 'Los productos del tipo :type no se pueden copiar',
    ],

    'sort-by'  => [
        'options' => [
            'cheapest-first'  => 'Más baratos primero',
            'expensive-first' => 'Más caros primero',
            'from-a-z'        => 'De la A a la Z',
            'from-z-a'        => 'De la Z a la A',
            'latest-first'    => 'Más recientes primero',
            'oldest-first'    => 'Más antiguos primero',
        ],
    ],

    'type'     => [
        'abstract'     => [
            'offers' => 'Compra :qty por :price cada uno y ahorra :discount',
        ],

        'bundle'       => 'Paquete',
        'configurable' => 'Configurable',
        'downloadable' => 'Descargable',
        'grouped'      => 'Agrupado',
        'simple'       => 'Simple',
        'virtual'      => 'Virtual',
    ],
];
