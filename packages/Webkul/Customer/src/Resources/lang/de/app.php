<?php

return [
    'admin' => [
        'system' => [
            'captcha' => [
                'validations' => [
                    'required' => 'Bitte wählen Sie CAPTCHA',
                    'captcha'  => 'Etwas ist schief gelaufen! Bitte versuche es erneut.',
                ],
            ],
        ],
    ],

    'customers' => [
        'addresses' => [
            'invalid-format' => 'Ungültiges USt-Format',
        ],
    ],
];
