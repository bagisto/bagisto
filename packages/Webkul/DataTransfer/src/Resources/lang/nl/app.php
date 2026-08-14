<?php

return [
    'importers' => [
        'customers' => [
            'title' => 'Klanten',

            'validation' => [
                'errors' => [
                    'duplicate-email' => 'E-mail: \'%s\' komt meer dan eens voor in het importbestand.',
                    'duplicate-phone' => 'Telefoon: \'%s\' komt meer dan eens voor in het importbestand.',
                    'email-not-found' => 'E-mail: \'%s\' niet gevonden in het systeem.',
                    'invalid-customer-group' => 'Klantgroep is ongeldig of wordt niet ondersteund',
                ],
            ],
        ],

        'products' => [
            'title' => 'Producten',

            'validation' => [
                'errors' => [
                    'duplicate-url-key' => 'URL-sleutel: \'%s\' is al gegenereerd voor een item met de SKU: \'%s\'.',
                    'image-not-file' => 'Afbeelding: \'%s\' is een webadres, terwijl deze import is ingesteld om afbeeldingen uit bestanden te halen. Kies \'Afbeeldingslinks in het bestand\' als bron, of vervang de waarde door een bestandsnaam.',
                    'image-not-found' => 'Afbeelding: \'%s\' is niet gevonden op de plek waar deze import zijn afbeeldingen verwacht.',
                    'image-not-url' => 'Afbeelding: \'%s\' is geen webadres, terwijl deze import is ingesteld om afbeeldingen op te halen via links in het bestand. Kies een andere afbeeldingsbron of vervang de waarde door een volledig https://-adres.',
                    'invalid-attribute-family' => 'Ongeldige waarde voor kolom attribuutfamilie (attribuutfamilie bestaat niet?)',
                    'invalid-type' => 'Producttype is ongeldig of wordt niet ondersteund',
                    'sku-not-found' => 'Product met de opgegeven SKU niet gevonden',
                    'super-attribute-not-found' => 'Superattribuut met code: \'%s\' niet gevonden of behoort niet tot de attribuutfamilie: \'%s\'',
                ],
            ],
        ],

        'tax-rates' => [
            'title' => 'Belastingtarieven',

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
            'column-name-invalid' => 'Ongeldige kolomnamen: "%s".',
            'column-not-found' => 'Vereiste kolommen niet gevonden: %s.',
            'column-numbers' => 'Aantal kolommen komt niet overeen met het aantal rijen in de koptekst.',
            'invalid-attribute' => 'Koptekst bevat ongeldige attribuut(en): "%s".',
            'more-issues' => 'en :count ander(e) probleem/problemen — download het volledige rapport voor de complete lijst.',
            'more-rows' => '(+:count meer rijen)',
            'system' => 'Er is een onverwachte systeemfout opgetreden.',
            'wrong-quotes' => 'Golvende aanhalingstekens gebruikt in plaats van rechte aanhalingstekens.',
        ],
    ],
];
