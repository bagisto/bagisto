<?php

return [
    'admin' => [
        'system' => [
            'captcha' => [
                'validations' => [
                    'required' => 'Пожалуйста, выберите CAPTCHA',
                    'captcha'  => 'Произошла ошибка! Попробуйте еще раз.',
                ],
            ],
        ],
    ], 
                                        
    'customers' => [
        'addresses' => [
            'invalid-format' => 'Недопустимый формат НДС',
        ],
    ],    
];
