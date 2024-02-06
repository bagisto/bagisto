<?php

return [
    'importers'  => [
        'products'  => [
            'title'      => 'Productos',

            'validation' => [
                'errors' => [
                    'duplicate-url-key'        => 'La clave de URL: \'%s\' ya fue generada para un artículo con el SKU: \'%s\'.',
                    'invalid-attribute-family' => 'Valor no válido para la columna de familia de atributos (¿la familia de atributos no existe?)',
                    'invalid-type'             => 'El tipo de producto es inválido o no es compatible',
                    'sku-not-found'            => 'No se encontró el producto con el SKU especificado',
                ],
            ],
        ],

        'customers' => [
            'title'      => 'Clientes',

            'validation' => [
                'errors' => [
                    'duplicate-email'        => 'El correo electrónico: \'%s\' se encuentra más de una vez en el archivo de importación.',
                    'duplicate-phone'        => 'El teléfono: \'%s\' se encuentra más de una vez en el archivo de importación.',
                    'invalid-customer-group' => 'El grupo de clientes no es válido o no está soportado',
                    'email-not-found'        => 'El correo electrónico: \'%s\' no se encontró en el sistema.',
                ],
            ],
        ],
    ],

    'validation' => [
        'errors' => [
            'column-empty-headers' => 'El número de columnas "%s" tiene encabezados vacíos.',
            'column-name-invalid'  => 'Nombres de columnas no válidos: "%s".',
            'column-not-found'     => 'No se encontraron las columnas requeridas: %s.',
            'column-numbers'       => 'El número de columnas no corresponde al número de filas en el encabezado.',
            'invalid-attribute'    => 'El encabezado contiene atributo(s) no válido(s): "%s".',
            'system'               => 'Se ha producido un error del sistema inesperado.',
            'wrong-quotes'         => 'Se usaron comillas curvas en lugar de comillas rectas.',
        ],
    ],
];
