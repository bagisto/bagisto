<?php

return [
    'validations' => [
        'allowed-country' => [
            'not-allowed' => '本店铺不接受所选国家/地区的地址。',
        ],

        'captcha' => [
            'captcha' => '出问题了! 请再试一次.',
            'required' => '请选择验证码',
        ],

        'vat-id' => [
            'invalid-format' => '无效的增值税格式',
        ],
    ],
];
