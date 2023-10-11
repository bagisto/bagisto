<?php

return [
    'validations' => [
        'captcha'=> [
            'required'=> 'Proszę wybrać CAPTCHA',
            'captcha' => 'Wystąpił błąd! Spróbuj ponownie.',
        ],
    
        'vat-id'=> [
            'invalid-format'=> 'Nieprawidłowy format numeru VAT',
        ],
    
    ],    
];