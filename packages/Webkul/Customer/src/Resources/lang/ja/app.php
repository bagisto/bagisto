<?php

return [
    'admin' => [
        'system' => [
            'captcha' => [
                'validations' => [
                    'required' => 'CAPTCHA を選択してください',
                    'captcha'  => '何か問題が発生しました！もう一度試してみてください。',
                ],
            ],
        ],
    ], 
                        
    'customers' => [
        'addresses' => [
                'invalid-format' => '無効な消費税形式',
            ],
        ],    
];
