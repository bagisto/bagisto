<?php

return [
    'products' => [
        'title'    => 'data_transfer::app.importers.products.title',
        'importer' => 'Webkul\DataTransfer\Helpers\Importers\Product\Importer',

        'sample_paths' => [
            'csv'  => 'data-transfer/samples/csv/products.csv',
            'xls'  => 'data-transfer/samples/xls/products.xls',
            'xlsx' => 'data-transfer/samples/xlsx/products.xlsx',
            'xml'  => 'data-transfer/samples/xml/products.xml',
        ],
    ],

    'customers' => [
        'title'    => 'data_transfer::app.importers.customers.title',
        'importer' => 'Webkul\DataTransfer\Helpers\Importers\Customer\Importer',

        'sample_paths' => [
            'csv'  => 'data-transfer/samples/csv/customers.csv',
            'xls'  => 'data-transfer/samples/xls/customers.xls',
            'xlsx' => 'data-transfer/samples/xlsx/customers.xlsx',
            'xml'  => 'data-transfer/samples/xml/customers.xml',
        ],
    ],

    'tax_rates' => [
        'title'    => 'data_transfer::app.importers.tax-rates.title',
        'importer' => 'Webkul\DataTransfer\Helpers\Importers\TaxRate\Importer',

        'sample_paths' => [
            'csv'  => 'data-transfer/samples/csv/tax-rates.csv',
            'xls'  => 'data-transfer/samples/xls/tax-rates.xls',
            'xlsx' => 'data-transfer/samples/xlsx/tax-rates.xlsx',
            'xml'  => 'data-transfer/samples/xml/tax-rates.xml',
        ],
    ],
];
