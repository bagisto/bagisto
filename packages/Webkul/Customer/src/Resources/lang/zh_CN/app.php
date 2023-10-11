<?php

return [
    'validations' => [
        'captcha'=> [
            'required'=> '请选择验证码',
            'captcha' => '出问题了! 请再试一次.',
        ],
    
        'vat-id'=> [
            'invalid-format'=> '无效的增值税格式',
        ],
    
    ],
];