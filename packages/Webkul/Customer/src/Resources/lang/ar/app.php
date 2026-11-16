<?php

return [
    'validations' => [
        'allowed-country' => [
            'not-allowed' => 'هذا المتجر لا يقبل العناوين في الدولة المحددة.',
        ],

        'captcha' => [
            'captcha' => 'هناك خطأ ما! حاول مرة اخرى.',
            'required' => 'الرجاء اختيار CAPTCHA',
        ],

        'vat-id' => [
            'invalid-format' => 'تنسيق الضريبة المضافة غير صحيح',
        ],
    ],
];
