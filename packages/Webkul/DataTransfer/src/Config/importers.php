<?php

return [
    'products' => [
        'title'       => 'data_transfer::app.importers.products.title',
        'importer'    => 'Webkul\DataTransfer\Helpers\Importers\Product\Importer',
        'sample_path' => 'data-transfer/samples/products.csv',
    ],

    'customers' => [
        'title'       => 'data_transfer::app.importers.customers.title',
        'importer'    => 'Webkul\DataTransfer\Helpers\Importers\Customer\Importer',
        'sample_path' => 'data-transfer/samples/customers.csv',
    ],

    'tax_rates' => [
        'title'       => 'data_transfer::app.importers.tax-rates.title',
        'importer'    => 'Webkul\DataTransfer\Helpers\Importers\TaxRate\Importer',
        'sample_path' => 'data-transfer/samples/tax-rates.csv',
    ],
];
