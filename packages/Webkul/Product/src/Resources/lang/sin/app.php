<?php

return [
    'checkout' => [
        'cart' => [
            'integrity' => [
                'qty-missing'   => 'එක් ප්‍රවාහනය සිදු කිරීමට අහිතක් 1 කට වරක් විය හැකිය.',
            ],

            'inventory-warning' => 'වායුක්ත අවශ්‍ය වැදගත්කම් නැත, කරුණාකර පසුව නැවත උත්සහ කරන්න.',
            'missing-links'     => 'මෙම නිෂ්පාදනය සඳහා බාගත විය හැක්කේ නැත.',
            'missing-options'   => 'මෙම නිෂ්පාදනය සඳහා විකල්ප සඳහා නැත.',
        ],
    ],

    'datagrid' => [
        'copy-of-slug'                  => 'පිළිතුරු-ආකෘති-:value',
        'copy-of'                       => ':value යෙදුමක්',
        'variant-already-exist-message' => 'එවක් හෝද විකල්ප වර්ගයක් සහිත වැරටුම දැක්වෙයි.',
    ],

    'response' => [
        'product-can-not-be-copied' => ':type වර්ගයේ නිෂ්පාදනය කළ නොහැකිය',
    ],

    'sort-by'  => [
        'options' => [
            'cheapest-first'  => 'අඩුම පළමු',
            'expensive-first' => 'උපකාර පළමු',
            'from-a-z'        => 'A සහ Z දක්වා',
            'from-z-a'        => 'Z සහ A දක්වා',
            'latest-first'    => 'නවම් පළමු',
            'oldest-first'    => 'පැරණි පළමු',
        ],
    ],

    'type'     => [
        'abstract'     => [
            'offers' => ':qty සඳහා :price සහිත කිසිවක් වෙනුවෙයි සහ :discount සහිතයි',
        ],

        'bundle'       => 'සම්පූර්ණය',
        'configurable' => 'විකල්ප කළ හැක්කේ',
        'downloadable' => 'බාගත කළ හැක්කේ',
        'grouped'      => 'සමූහ කළ හැක්කේ',
        'simple'       => 'සරළු',
        'virtual'      => 'විදුහල්',
    ],
];
