<?php

return [
    'admin' => [
        'system' => [
            'captcha' => [
                'validations' => [
                    'required' => '请选择验证码',
                    'captcha'  => '出问题了! 请再试一次.',
                ],
            ],
        ],
    ],
                                                        
    'customers' => [
        'addresses' => [
                'invalid-format' => '无效的增值税格式',
            ],
        ], 
];
