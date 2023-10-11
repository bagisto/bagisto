<?php

return [
    'validations' => [
        'captcha'=> [
            'required'=> 'Veuillez sélectionner CAPTCHA',
            'captcha' => 'Quelque chose s\'est mal passé ! Veuillez réessayer.',
        ],
    
        'vat-id'=> [
            'invalid-format'=> 'Format de TVA invalide',
        ],
    
    ], 
];