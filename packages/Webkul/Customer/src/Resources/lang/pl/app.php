<?php

return [
    'validations' => [
        'allowed-country' => [
            'not-allowed' => 'Ten sklep nie przyjmuje adresów w wybranym kraju.',
        ],

        'captcha' => [
            'captcha' => 'Wystąpił błąd! Spróbuj ponownie.',
            'required' => 'Proszę wybrać CAPTCHA',
        ],

        'vat-id' => [
            'invalid-format' => 'Nieprawidłowy format numeru VAT',
        ],
    ],
];
