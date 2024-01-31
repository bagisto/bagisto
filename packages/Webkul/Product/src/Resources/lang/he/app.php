<?php

return [
    'checkout' => [
        'cart' => [
            'integrity' => [
                'qty-missing'   => 'לפחות מוצר אחד צריך לכלול יותר מכמות 1.',
            ],

            'inventory-warning' => 'הכמות המבוקשת אינה זמינה, יש לנסות שוב מאוחר יותר.',
            'missing-links'     => 'קישורים להורדה חסרים למוצר זה.',
            'missing-options'   => 'אפשרויות חסרות למוצר זה.',
        ],
    ],

    'datagrid' => [
        'copy-of-slug'                  => 'העתק של :value',
        'copy-of'                       => 'העתק של :value',
        'variant-already-exist-message' => 'כבר קיימת וריאנט עם אותן אפשרויות מאפיינים.',
    ],

    'response' => [
        'product-can-not-be-copied' => 'מוצרים מסוג :type אינם יכולים להיעתק',
    ],

    'sort-by'  => [
        'options' => [
            'cheapest-first'  => 'הזול ביותר קודם',
            'expensive-first' => 'היקר ביותר קודם',
            'from-a-z'        => 'מ A ל Z',
            'from-z-a'        => 'מ Z ל A',
            'latest-first'    => 'החדש ביותר קודם',
            'oldest-first'    => 'הוותיק ביותר קודם',
        ],
    ],

    'type'     => [
        'abstract'     => [
            'offers' => 'קנה :qty במחיר :price לכל יחידה וחסוך :discount',
        ],

        'bundle'       => 'ארגז',
        'configurable' => 'ניתן להגדיר',
        'downloadable' => 'ניתן להוריד',
        'grouped'      => 'מקובץ',
        'simple'       => 'פשוט',
        'virtual'      => 'וירטואלי',
    ],
];
