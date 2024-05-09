<?php

return [
    'importers' => [
        'customers' => [
            'title' => 'ग्राहक',

            'validation' => [
                'errors' => [
                    'duplicate-email'        => 'ईमेल: \'%s\' आयात फ़ाइल में एक से अधिक बार पाया गया है।',
                    'duplicate-phone'        => 'फ़ोन: \'%s\' आयात फ़ाइल में एक से अधिक बार पाया गया है।',
                    'email-not-found'        => 'ईमेल: \'%s\' सिस्टम में नहीं मिला।',
                    'invalid-customer-group' => 'ग्राहक समूह अमान्य या समर्थित नहीं है',
                ],
            ],
        ],

        'products' => [
            'title' => 'उत्पाद',

            'validation' => [
                'errors' => [
                    'duplicate-url-key'         => 'URL कुंजी: \'%s\' पहले से ही एक आइटम के लिए उत्पन्न की गई थी, SKU: \'%s\'.',
                    'invalid-attribute-family'  => 'विशेषता परिवार स्तंभ के लिए अमान्य मान (विशेषता परिवार मौजूद नहीं है?)',
                    'invalid-type'              => 'उत्पाद का प्रकार अमान्य या समर्थित नहीं है',
                    'sku-not-found'             => 'निर्दिष्ट SKU के साथ उत्पाद नहीं मिला',
                    'super-attribute-not-found' => 'सुपर गुणधर्म कोड के साथ: \'%s\' नहीं मिला या यह गुणधर्म परिवार का हिस्सा नहीं है: \'%s\'',
                ],
            ],
        ],

        'tax-rates' => [
            'title' => 'कर दरें',

            'validation' => [
                'errors' => [
                    'duplicate-identifier' => 'पहचानकर्ता: \'%s\' आयात फ़ाइल में एक से अधिक बार पाया गया है।',
                    'identifier-not-found' => 'पहचानकर्ता: \'%s\' सिस्टम में नहीं मिला।',
                ],
            ],
        ],
    ],

    'validation' => [
        'errors' => [
            'column-empty-headers' => 'कॉलम संख्या "%s" खाली हैडर्स हैं।',
            'column-name-invalid'  => 'अमान्य कॉलम नाम: "%s"।',
            'column-not-found'     => 'आवश्यक कॉलम नहीं मिले: %s।',
            'column-numbers'       => 'कॉलम की संख्या हेडर में पंक्तियों की संख्या के समान नहीं है।',
            'invalid-attribute'    => 'हेडर में अमान्य विशेषता(ए) हैं: "%s"।',
            'system'               => 'एक अप्रत्याशित सिस्टम त्रुटि हुई है।',
            'wrong-quotes'         => 'सीधे कोट के बजाय मेंढ़ी कोट का उपयोग किया गया है।',
        ],
    ],
];
