<?php

return [
    'validations' => [
        'captcha'=> [
            'required'=> 'Selecteer CAPTCHA',
            'captcha' => 'Er is iets fout gegaan! Probeer het opnieuw.',
        ],
    
        'vat-id'=> [
            'invalid-format'=> 'Ongeldige BTW-indeling',
        ],
    
    ], 
];