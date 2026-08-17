<?php

return [
    'importers' => [
        'customers' => [
            'title' => 'Customers',

            'validation' => [
                'errors' => [
                    'duplicate-email' => 'Email : \'%s\' is found more than once in the import file.',
                    'duplicate-phone' => 'Phone : \'%s\' is found more than once in the import file.',
                    'email-not-found' => 'Email : \'%s\' not found in the system.',
                    'invalid-customer-group' => 'Customer group is invalid or not supported',
                ],
            ],
        ],

        'products' => [
            'title' => 'Products',

            'validation' => [
                'errors' => [
                    'duplicate-url-key' => 'URL key: \'%s\' was already generated for an item with the SKU: \'%s\'.',
                    'image-not-file' => 'Image: \'%s\' is a web address, but this import is set to take images from files. Choose \'Image links in the file\' as the image source, or replace the value with a file name.',
                    'image-not-found' => 'Image: \'%s\' was not found where this import expects its images.',
                    'image-not-url' => 'Image: \'%s\' is not a web address, but this import is set to fetch images from links in the file. Choose a different image source, or replace the value with a full https:// address.',
                    'invalid-attribute-family' => 'Invalid value for attribute family column (attribute family doesn\'t exist?)',
                    'invalid-type' => 'Product type is invalid or not supported',
                    'sku-not-found' => 'Product with specified SKU not found',
                    'super-attribute-not-found' => 'Super attribute with code: \'%s\' not found or does not belong to the attribute family: \'%s\'',
                ],
            ],
        ],

        'tax-rates' => [
            'title' => 'Tax Rates',

            'validation' => [
                'errors' => [
                    'duplicate-identifier' => 'Identifier : \'%s\' is found more than once in the import file.',
                    'identifier-not-found' => 'Identifier : \'%s\' not found in the system.',
                ],
            ],
        ],
    ],

    'validation' => [
        'errors' => [
            'column-empty-headers' => 'Columns number "%s" have empty headers.',
            'column-name-invalid' => 'Invalid column names: "%s".',
            'column-not-found' => 'Required columns not found: %s.',
            'column-numbers' => 'Number of columns does not correspond to the number of rows in the header.',
            'invalid-attribute' => 'Header contains invalid attribute(s): "%s".',
            'more-issues' => 'and :count more issue(s) — download the full report for the complete list.',
            'more-rows' => '(+:count more rows)',
            'system' => 'An unexpected system error occurred.',
            'wrong-quotes' => 'Curly quotes used instead of straight quotes.',
        ],
    ],
];
