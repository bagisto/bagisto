<?php

return [
    'admin' => [
        'system' => [
            'captcha' => [
                'validations' => [
                    'required' => 'Please select CAPTCHA',
                    'captcha'  => 'Something went wrong! Please try again.',
                ]
            ],
        ],
    ],

    'customers' => [
        'addresses' => [
            'invalid-format' => 'Invalid Vat Format',
        ],
    ],
];