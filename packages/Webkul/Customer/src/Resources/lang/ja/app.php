<?php

return [
    'validations' => [
        'allowed-country' => [
            'not-allowed' => 'このストアは選択された国の住所を受け付けていません。',
        ],

        'captcha' => [
            'captcha' => '何か問題が発生しました！もう一度試してみてください。',
            'required' => 'CAPTCHA を選択してください',
        ],

        'vat-id' => [
            'invalid-format' => '無効な消費税形式',
        ],
    ],
];
