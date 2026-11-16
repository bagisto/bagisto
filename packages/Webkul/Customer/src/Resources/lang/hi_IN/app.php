<?php

return [
    'validations' => [
        'allowed-country' => [
            'not-allowed' => 'यह स्टोर चयनित देश के पते स्वीकार नहीं करता।',
        ],

        'captcha' => [
            'captcha' => 'कुछ गलती हुई! कृपया पुन: प्रयास करें।',
            'required' => 'कृपया CAPTCHA का चयन करें',
        ],

        'vat-id' => [
            'invalid-format' => 'अमान्य वीएट प्रारूप',
        ],
    ],
];
