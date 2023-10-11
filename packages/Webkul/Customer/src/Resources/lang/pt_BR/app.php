<?php

return [
    'admin' => [
        'system' => [
            'captcha' => [
                'validations' => [
                    'required' => 'Selecione CAPTCHA',
                    'captcha'  => 'Algo deu errado! Por favor, tente novamente.',
                ],
            ],
        ],
    ],
                                    
    'customers' => [
        'addresses' => [
            'invalid-format' => 'Formato inválido de VAT',
        ],
    ], 
];
