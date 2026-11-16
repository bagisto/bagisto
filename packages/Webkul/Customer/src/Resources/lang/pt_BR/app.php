<?php

return [
    'validations' => [
        'allowed-country' => [
            'not-allowed' => 'Esta loja não aceita endereços no país selecionado.',
        ],

        'captcha' => [
            'captcha' => 'Algo deu errado! Por favor, tente novamente.',
            'required' => 'Selecione CAPTCHA',
        ],

        'vat-id' => [
            'invalid-format' => 'Formato inválido de VAT',
        ],
    ],
];
