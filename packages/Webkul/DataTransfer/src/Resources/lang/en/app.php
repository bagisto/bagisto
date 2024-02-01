<?php

return [
    'validation' => [
        'errors' => [
            'column-empty-headers' => 'Columns number "%s" have empty headers.',
            'column-name-invalid'  => 'Invalid column names: "%s".',
            'column-not-found'     => 'Required columns not found: %s.',
            'column-numbers'       => 'Number of columns does not correspond to the number of rows in the header.',
            'invalid-attribute'    => 'Header contains invalid attribute(s): "%s".',
            'system'               => 'An unexpected system error occurred.',
            'wrong-quotes'         => 'Curly quotes used instead of straight quotes.',

            'products' => [
                'duplicate-url-key'        => 'URL key: \'%s\' was already generated for an item with the SKU: \'%s\'.',
                'invalid-attribute-family' => 'Invalid value for attribute family column (attribute family doesn\'t exist?)',
                'invalid-type'             => 'Product type is invalid or not supported',
                'sku-not-found'            => 'Product with specified SKU not found',
            ],
        ],
    ],
];
