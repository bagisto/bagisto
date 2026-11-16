<?php

return [
    'validations' => [
        'allowed-country' => [
            'not-allowed' => 'Этот магазин не принимает адреса в выбранной стране.',
        ],

        'captcha' => [
            'captcha' => 'Произошла ошибка! Попробуйте еще раз.',
            'required' => 'Пожалуйста, выберите CAPTCHA',
        ],

        'vat-id' => [
            'invalid-format' => 'Недопустимый формат НДС',
        ],
    ],
];
