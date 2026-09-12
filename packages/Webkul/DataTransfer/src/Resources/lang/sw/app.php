<?php

return [
    'importers' => [
        'customers' => [
            'title' => 'Wateja',

            'validation' => [
                'errors' => [
                    'duplicate-email' => 'Barua pepe: \'%s\' imepatikana zaidi ya mara moja katika faili la ingizo.',
                    'duplicate-phone' => 'Namba ya simu: \'%s\' imepatikana zaidi ya mara moja katika faili la ingizo.',
                    'email-not-found' => 'Barua pepe: \'%s\' haipo kwenye mfumo.',
                    'invalid-customer-group' => 'Kundi la wateja ni batili au halitumiki',
                ],
            ],
        ],

        'products' => [
            'title' => 'Bidhaa',

            'validation' => [
                'errors' => [
                    'duplicate-url-key' => 'Ufunguo wa URL: \'%s\' tayari umetengenezwa kwa kipengele chenye SKU: \'%s\'.',
                    'image-not-file' => 'Picha: \'%s\' ni anwani ya tovuti, ila ingizo hili limewekwa kupata picha kutoka kwenye faili. Chagua \'Viungo vya picha ndani ya faili\' kama chanzo cha picha, au badilisha thamani hiyo kwa jina la faili.',
                    'image-not-found' => 'Picha: \'%s\' haikupatikana pale ingizo hili linapotarajia kupata picha zake.',
                    'image-not-url' => 'Picha: \'%s\' si anwani ya tovuti, ila ingizo hili limewekwa kupata picha kutoka kwenye viungo vilivyo ndani ya faili. Chagua chanzo tofauti cha picha, au badilisha thamani hiyo kwa anwani kamili ya https://.',
                    'invalid-attribute-family' => 'Thamani batili ya safu wima ya familia ya sifa (familia ya sifa haipo?)',
                    'invalid-type' => 'Aina ya bidhaa ni batili au haiatumiki',
                    'sku-not-found' => 'Bidhaa yenye SKU iliyotajwa haipatikani',
                    'super-attribute-not-found' => 'Sifa kuu yenye msimbo: \'%s\' haipatikani au si sehemu ya familia ya sifa: \'%s\'',
                ],
            ],
        ],

        'tax-rates' => [
            'title' => 'Viwango vya Kodi',

            'validation' => [
                'errors' => [
                    'duplicate-identifier' => 'Kitambulisho: \'%s\' kimepatikana zaidi ya mara moja katika faili la ingizo.',
                    'identifier-not-found' => 'Kitambulisho: \'%s\' hakipo kwenye mfumo.',
                ],
            ],
        ],
    ],

    'validation' => [
        'errors' => [
            'column-empty-headers' => 'Safu wima namba "%s" zina vichwa tupu.',
            'column-name-invalid' => 'Majina ya safu wima yasiyo sahihi: "%s".',
            'column-not-found' => 'Safu wima zinazohitajika hazipatikani: %s.',
            'column-numbers' => 'Idadi ya safu wima hailingani na idadi ya mistari iliyopo katika kichwa.',
            'invalid-attribute' => 'Kichwa kina sifa zisizo sahihi: "%s".',
            'more-issues' => 'na matatizo mengine :count — pakua ripoti kamili ili kuona orodha yote.',
            'more-rows' => '(+:count mistari zaidi)',
            'system' => 'Kosa lisilotarajiwa la mfumo limetokea.',
            'wrong-quotes' => 'Alama za nukuu za kunamisha zimetumika badala za nukuu nyofu.',
        ],
    ],
];
