<?php

return [
    'validations' => [
        'allowed-country' => [
            'not-allowed' => 'Цей магазин не приймає адреси у вибраній країні.',
        ],

        'captcha' => [
            'captcha' => 'Сталася помилка! Спробуйте ще раз.',
            'required' => 'Будь ласка, оберіть CAPTCHA',
        ],

        'vat-id' => [
            'invalid-format' => 'Невірний формат ПДВ',
        ],
    ],
];
