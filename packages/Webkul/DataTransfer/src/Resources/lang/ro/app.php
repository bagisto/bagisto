<?php

return [
    'importers' => [
        'customers' => [
            'title' => 'Clienți',

            'validation' => [
                'errors' => [
                    'duplicate-email' => 'Email: \'%s\' a fost găsit de mai multe ori în fișierul de import.',
                    'duplicate-phone' => 'Telefon: \'%s\' a fost găsit de mai multe ori în fișierul de import.',
                    'email-not-found' => 'Email: \'%s\' nu a fost găsit în sistem.',
                    'invalid-customer-group' => 'Grupul de clienți este invalid sau nu este acceptat',
                ],
            ],
        ],

        'products' => [
            'title' => 'Produse',

            'validation' => [
                'errors' => [
                    'duplicate-url-key' => 'Cheia URL: \'%s\' a fost deja generată pentru un articol cu SKU: \'%s\'.',
                    'invalid-attribute-family' => 'Valoare invalidă pentru coloana familiei de atribute (familia de atribute nu există?)',
                    'invalid-type' => 'Tipul produsului este invalid sau nu este acceptat',
                    'sku-not-found' => 'Produsul cu SKU-ul specificat nu a fost găsit',
                    'super-attribute-not-found' => 'Super atributul cu codul: \'%s\' nu a fost găsit sau nu aparține familiei de atribute: \'%s\'',
                ],
            ],
        ],

        'tax-rates' => [
            'title' => 'Rate de impozitare',

            'validation' => [
                'errors' => [
                    'duplicate-identifier' => 'Identificator: \'%s\' a fost găsit de mai multe ori în fișierul de import.',
                    'identifier-not-found' => 'Identificator: \'%s\' nu a fost găsit în sistem.',
                ],
            ],
        ],
    ],

    'validation' => [
        'errors' => [
            'column-empty-headers' => 'Coloanele cu numărul "%s" au anteturi goale.',
            'column-name-invalid' => 'Nume de coloane invalide: "%s".',
            'column-not-found' => 'Coloane obligatorii negăsite: %s.',
            'column-numbers' => 'Numărul de coloane nu corespunde cu numărul de rânduri din antet.',
            'invalid-attribute' => 'Antetul conține atribute invalide: "%s".',
            'system' => 'A apărut o eroare neașteptată a sistemului.',
            'wrong-quotes' => 'Ghilimele ondulate folosite în loc de ghilimele drepte.',
        ],
    ],
];
