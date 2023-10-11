<?php

return [
    'validations' => [
        'captcha'=> [
            'required'=> 'Пожалуйста, выберите CAPTCHA',
            'captcha' => 'Произошла ошибка! Попробуйте еще раз.',
        ],
    
        'vat-id'=> [
            'invalid-format'=> 'Недопустимый формат НДС',
        ],
    
    ],     
];