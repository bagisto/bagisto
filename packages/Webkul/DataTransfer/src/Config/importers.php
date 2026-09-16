<?php

use Webkul\DataTransfer\Helpers\Importers\Customer\Importer as CustomerImporter;
use Webkul\DataTransfer\Helpers\Importers\Product\Importer as ProductImporter;
use Webkul\DataTransfer\Helpers\Importers\TaxRate\Importer as TaxRateImporter;

return [
    'products' => [
        'title' => 'data_transfer::app.importers.products.title',
        'importer' => ProductImporter::class,

        'sample_paths' => [
            'csv' => 'data-transfer/samples/csv/products.csv',
            'xls' => 'data-transfer/samples/xls/products.xls',
            'xlsx' => 'data-transfer/samples/xlsx/products.xlsx',
            'xml' => 'data-transfer/samples/xml/products.xml',
        ],

        'sample_images_zip_path' => 'data-transfer/samples/images/product-images.zip',
    ],

    'customers' => [
        'title' => 'data_transfer::app.importers.customers.title',
        'importer' => CustomerImporter::class,

        'sample_paths' => [
            'csv' => 'data-transfer/samples/csv/customers.csv',
            'xls' => 'data-transfer/samples/xls/customers.xls',
            'xlsx' => 'data-transfer/samples/xlsx/customers.xlsx',
            'xml' => 'data-transfer/samples/xml/customers.xml',
        ],
    ],

    'tax_rates' => [
        'title' => 'data_transfer::app.importers.tax-rates.title',
        'importer' => TaxRateImporter::class,

        'sample_paths' => [
            'csv' => 'data-transfer/samples/csv/tax-rates.csv',
            'xls' => 'data-transfer/samples/xls/tax-rates.xls',
            'xlsx' => 'data-transfer/samples/xlsx/tax-rates.xlsx',
            'xml' => 'data-transfer/samples/xml/tax-rates.xml',
        ],
    ],
];
