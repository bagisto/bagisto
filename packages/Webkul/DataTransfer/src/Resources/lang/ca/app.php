<?php

return [
    'importers' => [
        'customers' => [
            'title' => 'Clients',

            'validation' => [
                'errors' => [
                    'duplicate-email'        => 'El correu electrònic: \'%s\' està més d\'un cop a l\'archivo d\'importació.',
                    'duplicate-phone'        => 'El telèfon: \'%s\' està més d\'un cop a l\'archivo d\'importació.',
                    'email-not-found'        => 'El correu electrònic: \'%s\' no s\'ha trtobat al sistema.',
                    'invalid-customer-group' => 'El grup de clients no es vàlid o no està soportat',
                ],
            ],
        ],

        'products' => [
            'title' => 'Productos',

            'validation' => [
                'errors' => [
                    'duplicate-url-key'         => 'La clau de l\'URL: \'%s\' ja ha estat generada per a un article amb l\'SKU: \'%s\'.',
                    'invalid-attribute-family'  => 'Valor no vàlid per a la columna de família d\'atributs (¿la familia d\'atributs no existeix?)',
                    'invalid-type'              => 'El tipus de producte es invàlid o no es compatible',
                    'sku-not-found'             => 'No s\'ha trobat el producte amb l\'SKU especificat',
                    'super-attribute-not-found' => 'Superatribut amb còdi: \'%s\' no trobat o no pertany a la família d\'atributs: \'%s\'',
                ],
            ],
        ],

        'tax-rates' => [
            'title' => 'Taxes d\'impostos',

            'validation' => [
                'errors' => [
                    'duplicate-identifier' => 'L\'identificador: \'%s\' està més d\'un cop a l\'archivo d\'importació.',
                    'identifier-not-found' => 'L\'identificador: \'%s\' no s\'ha trobat al sistema.',
                ],
            ],
        ],
    ],

    'validation' => [
        'errors' => [
            'column-empty-headers' => 'El número de columnes "%s" te encapçalaments buits.',
            'column-name-invalid'  => 'Noms de columnes no vàlids: "%s".',
            'column-not-found'     => 'No s\'han trobat les columnes requerides: %s.',
            'column-numbers'       => 'El número de columnes no corresponen al número de files en l\'encapçalament.',
            'invalid-attribute'    => 'L\'encapçalament conté atribut(s) no vàido(s): "%s".',
            'system'               => 'S\'ha produït un error del sistema inesperat.',
            'wrong-quotes'         => 'S\'han fet servir cometes curves en comptes de cometes rectes.',
        ],
    ],
];
