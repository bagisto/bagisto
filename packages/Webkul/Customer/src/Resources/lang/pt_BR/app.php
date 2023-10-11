<?php

return [
    'validations' => [
        'captcha'=> [
            'required'=> 'Selecione CAPTCHA',
            'captcha' => 'Algo deu errado! Por favor, tente novamente.',
        ],
    
        'vat-id'=> [
            'invalid-format'=> 'Formato inválido de VAT',
        ],
    
    ],  
];