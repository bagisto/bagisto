<?php

return [
    'validations' => [
        'allowed-country' => [
            'not-allowed' => 'Dieser Shop akzeptiert keine Adressen im ausgewählten Land.',
        ],

        'captcha' => [
            'captcha' => 'Etwas ist schief gelaufen! Bitte versuche es erneut.',
            'required' => 'Bitte wählen Sie CAPTCHA',
        ],

        'vat-id' => [
            'invalid-format' => 'Ungültiges USt-Format',
        ],
    ],
];
