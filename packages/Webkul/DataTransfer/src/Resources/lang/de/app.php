<?php

return [
    'importers' => [
        'customers' => [
            'title' => 'Kunden',

            'validation' => [
                'errors' => [
                    'duplicate-email' => 'E-Mail: \'%s\' wurde mehrmals in der Importdatei gefunden.',
                    'duplicate-phone' => 'Telefon: \'%s\' wurde mehrmals in der Importdatei gefunden.',
                    'email-not-found' => 'E-Mail: \'%s\' nicht im System gefunden.',
                    'invalid-customer-group' => 'Kundengruppe ist ungültig oder wird nicht unterstützt',
                ],
            ],
        ],

        'products' => [
            'title' => 'Produkte',

            'validation' => [
                'errors' => [
                    'duplicate-url-key' => 'URL-Schlüssel: \'%s\' wurde bereits für einen Artikel mit der SKU: \'%s\' generiert.',
                    'image-not-file' => 'Bild: \'%s\' ist eine Webadresse, dieser Import soll Bilder jedoch aus Dateien nehmen. Wählen Sie \'Bildlinks in der Datei\' als Bildquelle oder ersetzen Sie den Wert durch einen Dateinamen.',
                    'image-not-found' => 'Bild: \'%s\' wurde dort, wo dieser Import seine Bilder erwartet, nicht gefunden.',
                    'image-not-url' => 'Bild: \'%s\' ist keine Webadresse, dieser Import soll Bilder jedoch über Links in der Datei laden. Wählen Sie eine andere Bildquelle oder ersetzen Sie den Wert durch eine vollständige https://-Adresse.',
                    'invalid-attribute-family' => 'Ungültiger Wert für Attributfamilien-Spalte (Attributfamilie existiert nicht?)',
                    'invalid-type' => 'Produkttyp ist ungültig oder wird nicht unterstützt',
                    'sku-not-found' => 'Produkt mit angegebener SKU nicht gefunden',
                    'super-attribute-not-found' => 'Superattribut mit Code: \'%s\' nicht gefunden oder gehört nicht zur Attributfamilie: \'%s\'',
                ],
            ],
        ],

        'tax-rates' => [
            'title' => 'Steuersätze',

            'validation' => [
                'errors' => [
                    'duplicate-identifier' => 'Kennung: \'%s\' wurde mehrmals in der Importdatei gefunden.',
                    'identifier-not-found' => 'Kennung: \'%s\' wurde im System nicht gefunden.',
                ],
            ],
        ],
    ],

    'validation' => [
        'errors' => [
            'column-empty-headers' => 'Spaltennummer "%s" hat leere Überschriften.',
            'column-name-invalid' => 'Ungültige Spaltennamen: "%s".',
            'column-not-found' => 'Erforderliche Spalten nicht gefunden: %s.',
            'column-numbers' => 'Anzahl der Spalten entspricht nicht der Anzahl der Zeilen im Header.',
            'invalid-attribute' => 'Header enthält ungültige Attribute: "%s".',
            'more-issues' => 'und :count weitere(s) Problem(e) — laden Sie den vollständigen Bericht für die komplette Liste herunter.',
            'more-rows' => '(+:count weitere Zeilen)',
            'system' => 'Ein unerwarteter Systemfehler ist aufgetreten.',
            'wrong-quotes' => 'Gekrümmte Anführungszeichen anstelle von geraden Anführungszeichen verwendet.',
        ],
    ],
];
