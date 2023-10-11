<?php

return [
    'validations' => [
        'captcha'=> [
            'required'=> 'Please select CAPTCHA',
            'captcha' => 'Something went wrong! Please try again.',
        ],
    
        'vat-id'=> [
            'invalid-format'=> 'Invalid Vat Format',
        ],
    
    ],
];