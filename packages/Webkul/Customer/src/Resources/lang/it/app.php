<?php

return [
    'validations' => [
        'allowed-country' => [
            'not-allowed' => 'Questo negozio non accetta indirizzi nel paese selezionato.',
        ],

        'captcha' => [
            'captcha' => 'Qualcosa è andato storto! Per favore riprova.',
            'required' => 'Seleziona CAPTCHA',
        ],

        'vat-id' => [
            'invalid-format' => 'Formato IVA non valido',
        ],
    ],
];
