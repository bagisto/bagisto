<?php

return [
    'validations' => [
        'allowed-country' => [
            'not-allowed' => 'Cette boutique n’accepte pas les adresses dans le pays sélectionné.',
        ],

        'captcha' => [
            'captcha' => 'Quelque chose s\'est mal passé ! Veuillez réessayer.',
            'required' => 'Veuillez sélectionner CAPTCHA',
        ],

        'vat-id' => [
            'invalid-format' => 'Format de TVA invalide',
        ],
    ],
];
