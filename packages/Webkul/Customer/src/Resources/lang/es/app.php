<?php

return [
    'admin' => [
        'system' => [
            'captcha' => [
                'validations' => [
                    'required' => 'Seleccione CAPTCHA',
                    'captcha'  => '¡Algo salió mal! Inténtalo de nuevo.',
                ],
            ],
        ],
    ],

    'customers' => [
        'addresses' => [
            'invalid-format' => 'Formato de IVA inválido',
        ],
    ],
];
