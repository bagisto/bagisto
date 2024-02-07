<?php

return [
    'importers'  => [
        'customers' => [
            'title'      => 'Klanten',

            'validation' => [
                'errors' => [
                    'duplicate-email'        => 'E-mail: \'%s\' komt meer dan eens voor in het importbestand.',
                    'duplicate-phone'        => 'Telefoon: \'%s\' komt meer dan eens voor in het importbestand.',
                    'invalid-customer-group' => 'Klantgroep is ongeldig of wordt niet ondersteund',
                    'email-not-found'        => 'E-mail: \'%s\' niet gevonden in het systeem.',
                ],
            ],
        ],

        'products'  => [
            'title'      => 'Producten',

            'validation' => [
                'errors' => [
                    'duplicate-url-key'         => 'URL-sleutel: \'%s\' is al gegenereerd voor een item met de SKU: \'%s\'.',
                    'invalid-attribute-family'  => 'Ongeldige waarde voor kolom attribuutfamilie (attribuutfamilie bestaat niet?)',
                    'invalid-type'              => 'Producttype is ongeldig of wordt niet ondersteund',
                    'sku-not-found'             => 'Product met de opgegeven SKU niet gevonden',
                    'super-attribute-not-found' => 'Superattribuut met code: \'%s\' niet gevonden of behoort niet tot de attribuutfamilie: \'%s\'',
                ],
            ],
        ],

        'tax-rates' => [
            'title'      => 'Belastingtarieven',

            'validation' => [
                'errors' => [
                    'duplicate-identifier' => 'Identifier: \'%s\' komt meerdere keren voor in het importbestand.',
                    'identifier-not-found' => 'Identifier: \'%s\' niet gevonden in het systeem.',
                ],
            ],
        ],
    ],

    'validation' => [
        'errors' => [
            'column-empty-headers' => 'Kolomnummer "%s" heeft lege koppen.',
            'column-name-invalid'  => 'Ongeldige kolomnamen: "%s".',
            'column-not-found'     => 'Vereiste kolommen niet gevonden: %s.',
            'column-numbers'       => 'Aantal kolommen komt niet overeen met het aantal rijen in de koptekst.',
            'invalid-attribute'    => 'Koptekst bevat ongeldige attribuut(en): "%s".',
            'system'               => 'Er is een onverwachte systeemfout opgetreden.',
            'wrong-quotes'         => 'Golvende aanhalingstekens gebruikt in plaats van rechte aanhalingstekens.',
        ],
    ],
];
