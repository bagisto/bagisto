<?php

return [
    'validations' => [
        'allowed-country' => [
            'not-allowed' => 'This store does not accept addresses in the selected country.',
        ],

        'captcha' => [
            'captcha' => 'Something went wrong! Please try again.',
            'required' => 'Please select CAPTCHA',
        ],

        'vat-id' => [
            'invalid-format' => 'Invalid Vat Format',
        ],
    ],
];
