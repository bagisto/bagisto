<?php

return [
    'admin' => [
        'system' => [
            'captcha' => [
                'validations' => [
                    'required' => 'Veuillez sélectionner CAPTCHA',
                    'captcha'  => 'Quelque chose s\'est mal passé ! Veuillez réessayer.',
                ],
            ],
        ],
    ],
        
    'customers' => [
        'addresses' => [
            'invalid-format' => 'Format de TVA invalide',
        ],
    ], 
];
