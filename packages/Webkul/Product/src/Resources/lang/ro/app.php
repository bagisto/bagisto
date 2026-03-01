<?php

return [
    'checkout' => [
        'cart' => [
            'integrity' => [
                'qty-missing' => 'Cel puțin un produs trebuie să aibă o cantitate mai mare de 1.',
            ],

            'invalid-file-extension' => 'Extensie de fișier invalidă găsită.',
            'inventory-warning' => 'Cantitatea solicitată nu este disponibilă, vă rugăm să încercați mai târziu.',
            'missing-links' => 'Lipsesc linkurile descărcabile pentru acest produs.',
            'missing-options' => 'Lipsesc opțiunile pentru acest produs.',
            'selected-products-simple' => 'Produsele selectate trebuie să fie de tip produs simplu.',
        ],
    ],

    'datagrid' => [
        'copy-of-slug' => 'copie-din-:value',
        'copy-of' => 'Copie din :value',
        'variant-already-exist-message' => 'Varianta cu aceleași opțiuni de atribut există deja.',
    ],

    'response' => [
        'product-can-not-be-copied' => 'Produsele de tip :type nu pot fi copiate',
    ],

    'sort-by' => [
        'options' => [
            'cheapest-first' => 'Cel mai ieftin mai întâi',
            'expensive-first' => 'Cel mai scump mai întâi',
            'from-a-z' => 'De la A la Z',
            'from-z-a' => 'De la Z la A',
            'latest-first' => 'Cele mai noi mai întâi',
            'oldest-first' => 'Cele mai vechi mai întâi',
        ],
    ],

    'type' => [
        'abstract' => [
            'offers' => 'Cumpărați :qty la :price fiecare și economisiți :discount',
        ],

        'bundle' => 'Pachet',
        'booking' => 'Rezervare',
        'configurable' => 'Configurabil',
        'downloadable' => 'Descărcabil',
        'grouped' => 'Grupat',
        'simple' => 'Simplu',
        'virtual' => 'Virtual',
    ],
];
