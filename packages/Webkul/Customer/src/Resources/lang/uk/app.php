<?php

return [
    'admin' => [
        'system' => [
            'captcha' => [
                'validations' => [
                    'required' => 'Будь ласка, оберіть CAPTCHA',
                    'captcha'  => 'Сталася помилка! Спробуйте ще раз.',
                ],
            ],
        ],
    ], 
                                                    
    'customers' => [
        'addresses' => [
            'invalid-format' => 'Невірний формат ПДВ',
        ],
    ],    
];
