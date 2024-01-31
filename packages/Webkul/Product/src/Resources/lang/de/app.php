<?php

return [
    'checkout' => [
        'cart' => [
            'integrity' => [
                'qty-missing'   => 'Mindestens ein Produkt sollte mehr als 1 Menge haben.',
            ],

            'inventory-warning' => 'Die angeforderte Menge ist nicht verfügbar, bitte versuchen Sie es später erneut.',
            'missing-links'     => 'Download-Links fehlen für dieses Produkt.',
            'missing-options'   => 'Optionen fehlen für dieses Produkt.',
        ],
    ],

    'datagrid' => [
        'copy-of-slug'                  => 'Kopie von :value',
        'copy-of'                       => 'Kopie von :value',
        'variant-already-exist-message' => 'Variante mit denselben Attributoptionen existiert bereits.',
    ],

    'response' => [
        'product-can-not-be-copied' => 'Produkte vom Typ :type können nicht kopiert werden',
    ],

    'sort-by'  => [
        'options' => [
            'cheapest-first'  => 'Günstigste zuerst',
            'expensive-first' => 'Teuerste zuerst',
            'from-a-z'        => 'Von A-Z',
            'from-z-a'        => 'Von Z-A',
            'latest-first'    => 'Neueste zuerst',
            'oldest-first'    => 'Älteste zuerst',
        ],
    ],

    'type'     => [
        'abstract'     => [
            'offers' => 'Kaufen Sie :qty für :price pro Stück und sparen Sie :discount',
        ],

        'bundle'       => 'Bündel',
        'configurable' => 'Konfigurierbar',
        'downloadable' => 'Downloadbar',
        'grouped'      => 'Gruppiert',
        'simple'       => 'Einfach',
        'virtual'      => 'Virtuell',
    ],
];
