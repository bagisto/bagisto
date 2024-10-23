<?php

return [
    'checkout' => [
        'cart' => [
            'integrity' => [
                'qty-missing'   => 'कम से कम एक उत्पाद में 1 से अधिक मात्रा होनी चाहिए।',
            ],

            'inventory-warning'        => 'अनुरोध की गई मात्रा उपलब्ध नहीं है, कृपया बाद में पुन: प्रयास करें।',
            'missing-links'            => 'इस उत्पाद के लिए डाउनलोडेबल लिंक्स अनुपस्थित हैं।',
            'missing-options'          => 'इस उत्पाद के लिए विकल्प अनुपस्थित हैं।',
            'selected-products-simple' => 'चयनित उत्पादों को साधारित प्रकार के होना चाहिए।',
        ],
    ],

    'datagrid' => [
        'copy-of-slug'                  => ':value की प्रतिलिपि',
        'copy-of'                       => ':value की प्रतिलिपि',
        'variant-already-exist-message' => 'एक ही विशेषता विकल्पों के साथ एक वैरिएंट पहले से मौजूद है।',
    ],

    'response' => [
        'product-can-not-be-copied' => ':type प्रकार के उत्पादों की प्रतिलिपि नहीं बना सकती',
    ],

    'sort-by'  => [
        'options' => [
            'cheapest-first'  => 'सबसे सस्ता पहले',
            'expensive-first' => 'सबसे महंगा पहले',
            'from-a-z'        => 'A से Z तक',
            'from-z-a'        => 'Z से A तक',
            'latest-first'    => 'नवीनतम पहले',
            'oldest-first'    => 'सबसे पुराना पहले',
        ],
    ],

    'type'     => [
        'abstract'     => [
            'offers' => 'हर एक के लिए :qty खरीदें, प्रति इकाई :price और :discount बचाएं',
        ],

        'bundle'       => 'बंडल',
        'configurable' => 'कॉन्फ़िगरेबल',
        'downloadable' => 'डाउनलोडेबल',
        'grouped'      => 'समूहित',
        'simple'       => 'साधारित',
        'virtual'      => 'आभासी',
    ],
];
