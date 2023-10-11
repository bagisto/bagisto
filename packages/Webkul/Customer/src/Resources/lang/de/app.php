<?php

return [
    'validations' => [
        'captcha'=> [
            'required'=> 'Bitte wählen Sie CAPTCHA',
            'captcha' => 'Etwas ist schief gelaufen! Bitte versuche es erneut.',
        ],
    
        'vat-id'=> [
            'invalid-format'=> 'Ungültiges USt-Format',
        ],
    
    ],
];