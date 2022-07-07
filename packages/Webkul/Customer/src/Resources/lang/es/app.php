<?php

return [
    'wishlist' => [
        'success' => 'Artículo agregado exitosamente a la lista de deseos',
        'failure' => 'El artículo no se puede agregar a la lista de deseos',
        'already' => 'El artículo ya está en tu lista de deseos',
        'removed' => 'Artículo eliminado correctamente de la lista de deseos',
        'remove-fail' => 'El artículo no se puede eliminar de la lista de deseos',
        'empty' => 'No tienes artículos en la lista de deseos',
        'select-options' => 'Necesita seleccionar opciones antes de agregar a la lista de deseos',
        'remove-all-success' => 'Se han eliminado todos los elementos de su lista de deseos',
    ],

    'product-removed'  => 'El producto ya no está disponible porque lo eliminó el administrador',

    'reviews' => [
        'empty' => 'Aún no has calificado ningún producto',
    ],

    'forget_password' => [
        'reset_link_sent' => 'Hemos enviado un correo electrónico con el enlace para restablecer la contraseña.',
        'email_not_exist' => "No podemos encontrar un usuario con esa dirección de correo electrónico",
    ],

    'admin' => [
        'system' => [
            'captcha' => [
                'title' => 'Captcha',
                'credentials' => 'Cartas credenciales',
                'site-key' => 'Clave del sitio',
                'secret-key' => 'Llave secreta',
                'status' => 'Estado',

                'validations' => [
                    'required' => 'Seleccione CAPTCHA',
                    'captcha' => '¡Algo salió mal! Inténtalo de nuevo.',
                ]
            ],
        ],
    ],
];
