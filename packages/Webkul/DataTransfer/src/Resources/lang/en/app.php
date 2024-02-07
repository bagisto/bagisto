<?php

return [
    'importers'  => [
        'customers' => [
            'title'      => 'Customers',

            'validation' => [
                'errors' => [
                    'duplicate-email'        => 'Email : \'%s\' is found more than once in the import file.',
                    'duplicate-phone'        => 'Phone : \'%s\' is found more than once in the import file.',
                    'invalid-customer-group' => 'Customer group is invalid or not supported',
                    'email-not-found'        => 'Email : \'%s\' not found in the system.',
                ],
            ],
        ],

        'products'  => [
            'title'      => 'Products',

            'validation' => [
                'errors' => [
                    'duplicate-url-key'         => 'URL key: \'%s\' was already generated for an item with the SKU: \'%s\'.',
                    'invalid-attribute-family'  => 'Invalid value for attribute family column (attribute family doesn\'t exist?)',
                    'invalid-type'              => 'Product type is invalid or not supported',
                    'sku-not-found'             => 'Product with specified SKU not found',
                    'super-attribute-not-found' => 'Super attribute with code: \'%s\' not found or does not belong to the attribute family: \'%s\'',
                ],
            ],
        ],

        'tax-rates' => [
            'title'      => 'Tax Rates',

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
            'column-name-invalid'  => 'Invalid column names: "%s".',
            'column-not-found'     => 'Required columns not found: %s.',
            'column-numbers'       => 'Number of columns does not correspond to the number of rows in the header.',
            'invalid-attribute'    => 'Header contains invalid attribute(s): "%s".',
            'system'               => 'An unexpected system error occurred.',
            'wrong-quotes'         => 'Curly quotes used instead of straight quotes.',
        ],
    ],
];
