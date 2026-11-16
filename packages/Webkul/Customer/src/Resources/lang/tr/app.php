<?php

return [
    'validations' => [
        'allowed-country' => [
            'not-allowed' => 'Bu mağaza seçilen ülkedeki adresleri kabul etmiyor.',
        ],

        'captcha' => [
            'captcha' => 'Bir şeyler yanlış gitti! Lütfen tekrar deneyin.',
            'required' => 'Lütfen CAPTCHA\'yı seçin',
        ],

        'vat-id' => [
            'invalid-format' => 'Geçersiz KDV Formatı',
        ],
    ],
];
