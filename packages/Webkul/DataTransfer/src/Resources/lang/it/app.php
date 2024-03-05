<?php

return [
    'importers'  => [
        'customers' => [
            'title'      => 'Clienti',

            'validation' => [
                'errors' => [
                    'duplicate-email'        => 'L\'email: \'%s\' è stata trovata più di una volta nel file di importazione.',
                    'duplicate-phone'        => 'Il telefono: \'%s\' è stato trovato più di una volta nel file di importazione.',
                    'invalid-customer-group' => 'Il gruppo di clienti non è valido o non è supportato',
                    'email-not-found'        => 'L\'email: \'%s\' non è stata trovata nel sistema.',
                ],
            ],
        ],

        'products'  => [
            'title'      => 'Prodotti',

            'validation' => [
                'errors' => [
                    'duplicate-url-key'         => 'La chiave URL: \'%s\' è già stata generata per un elemento con lo SKU: \'%s\'.',
                    'invalid-attribute-family'  => 'Valore non valido per la colonna della famiglia di attributi (la famiglia di attributi non esiste?)',
                    'invalid-type'              => 'Il tipo di prodotto non è valido o non è supportato',
                    'sku-not-found'             => 'Prodotto con lo SKU specificato non trovato',
                    'super-attribute-not-found' => 'Super attributo con codice: \'%s\' non trovato o non appartiene alla famiglia di attributi: \'%s\'',
                ],
            ],
        ],

        'tax-rates' => [
            'title'      => 'Aliquote fiscali',

            'validation' => [
                'errors' => [
                    'duplicate-identifier' => 'Identificatore: \'%s\' trovato più di una volta nel file di importazione.',
                    'identifier-not-found' => 'Identificatore: \'%s\' non trovato nel sistema.',
                ],
            ],
        ],
    ],

    'validation' => [
        'errors' => [
            'column-empty-headers' => 'Il numero di colonne "%s" ha intestazioni vuote.',
            'column-name-invalid'  => 'Nomi delle colonne non validi: "%s".',
            'column-not-found'     => 'Colonnes richieste non trovate: %s.',
            'column-numbers'       => 'Il numero di colonne non corrisponde al numero di righe nell\'intestazione.',
            'invalid-attribute'    => 'L\'intestazione contiene attributi non validi: "%s".',
            'system'               => 'Si è verificato un errore di sistema imprevisto.',
            'wrong-quotes'         => 'Sono state utilizzate virgolette curve invece di virgolette dritte.',
        ],
    ],
];
