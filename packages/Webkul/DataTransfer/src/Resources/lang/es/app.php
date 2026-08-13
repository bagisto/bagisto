<?php

return [
    'importers' => [
        'customers' => [
            'title' => 'Clientes',

            'validation' => [
                'errors' => [
                    'duplicate-email' => 'El correo electrónico: \'%s\' se encuentra más de una vez en el archivo de importación.',
                    'duplicate-phone' => 'El teléfono: \'%s\' se encuentra más de una vez en el archivo de importación.',
                    'email-not-found' => 'El correo electrónico: \'%s\' no se encontró en el sistema.',
                    'invalid-customer-group' => 'El grupo de clientes no es válido o no está soportado',
                ],
            ],
        ],

        'products' => [
            'title' => 'Productos',

            'validation' => [
                'errors' => [
                    'duplicate-url-key' => 'La clave de URL: \'%s\' ya fue generada para un artículo con el SKU: \'%s\'.',
                    'image-not-file' => 'Imagen: \'%s\' es una dirección web, pero esta importación está configurada para tomar imágenes de archivos. Elija \'Enlaces de imagen en el archivo\' como origen, o sustituya el valor por un nombre de archivo.',
                    'image-not-found' => 'Imagen: \'%s\' no se encontró donde esta importación espera sus imágenes.',
                    'image-not-url' => 'Imagen: \'%s\' no es una dirección web, pero esta importación está configurada para obtener imágenes de enlaces del archivo. Elija otra fuente de imágenes o sustituya el valor por una dirección https:// completa.',
                    'invalid-attribute-family' => 'Valor no válido para la columna de familia de atributos (¿la familia de atributos no existe?)',
                    'invalid-type' => 'El tipo de producto es inválido o no es compatible',
                    'sku-not-found' => 'No se encontró el producto con el SKU especificado',
                    'super-attribute-not-found' => 'Superatributo con código: \'%s\' no encontrado o no pertenece a la familia de atributos: \'%s\'',
                ],
            ],
        ],

        'tax-rates' => [
            'title' => 'Tasas de impuestos',

            'validation' => [
                'errors' => [
                    'duplicate-identifier' => 'El identificador: \'%s\' se encuentra más de una vez en el archivo de importación.',
                    'identifier-not-found' => 'El identificador: \'%s\' no se encontró en el sistema.',
                ],
            ],
        ],
    ],

    'validation' => [
        'errors' => [
            'column-empty-headers' => 'El número de columnas "%s" tiene encabezados vacíos.',
            'column-name-invalid' => 'Nombres de columnas no válidos: "%s".',
            'column-not-found' => 'No se encontraron las columnas requeridas: %s.',
            'column-numbers' => 'El número de columnas no corresponde al número de filas en el encabezado.',
            'invalid-attribute' => 'El encabezado contiene atributo(s) no válido(s): "%s".',
            'more-issues' => 'y :count problema(s) más — descargue el informe completo para ver la lista íntegra.',
            'more-rows' => '(+:count filas más)',
            'system' => 'Se ha producido un error del sistema inesperado.',
            'wrong-quotes' => 'Se usaron comillas curvas en lugar de comillas rectas.',
        ],
    ],
];
