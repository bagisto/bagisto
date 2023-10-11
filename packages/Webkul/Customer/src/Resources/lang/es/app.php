<?php

return [
    'validations' => [
        'captcha'=> [
            'required'=> 'Seleccione CAPTCHA',
            'captcha' => '¡Algo salió mal! Inténtalo de nuevo.',
        ],
    
        'vat-id'=> [
            'invalid-format'=> 'Formato de IVA inválido',
        ],
    
    ],
];