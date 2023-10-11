<?php

return [
    'validations' => [
        'captcha'=> [
            'required'=> 'Будь ласка, оберіть CAPTCHA',
            'captcha' => 'Сталася помилка! Спробуйте ще раз.',
        ],
    
        'vat-id'=> [
            'invalid-format'=> 'Невірний формат ПДВ',
        ],
    
    ],    
];  