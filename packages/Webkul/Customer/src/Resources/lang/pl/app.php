<?php

return [
    'admin' => [
        'system' => [
            'captcha' => [
                'validations' => [
                    'required' => 'Proszę wybrać CAPTCHA',
                    'captcha'  => 'Wystąpił błąd! Spróbuj ponownie.',
                ],
            ],
        ],
    ],
                                
    'customers' => [
        'addresses' => [
                'invalid-format' => 'Nieprawidłowy format numeru VAT',
            ],
        ],     
];
