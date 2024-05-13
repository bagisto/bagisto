<?php

return [
    'importers' => [
        'customers' => [
            'title' => 'לקוחות',

            'validation' => [
                'errors' => [
                    'duplicate-email'        => 'האימייל: \'%s\' נמצא יותר מפעם אחת בקובץ היבוא.',
                    'duplicate-phone'        => 'הטלפון: \'%s\' נמצא יותר מפעם אחת בקובץ היבוא.',
                    'email-not-found'        => 'האימייל: \'%s\' לא נמצא במערכת.',
                    'invalid-customer-group' => 'קבוצת הלקוחות אינה תקפה או לא נתמכת',
                ],
            ],
        ],

        'products' => [
            'title' => 'מוצרים',

            'validation' => [
                'errors' => [
                    'duplicate-url-key'         => 'מפתח ה-URL: \'%s\' כבר נוצר עבור פריט עם ה-SKU: \'%s\'.',
                    'invalid-attribute-family'  => 'ערך לא תקף עבור עמודת משפחת המאפיינים (משפחת המאפיינים לא קיימת?)',
                    'invalid-type'              => 'סוג המוצר אינו תקף או לא נתמך',
                    'sku-not-found'             => 'מוצר עם ה-SKU המסוים לא נמצא',
                    'super-attribute-not-found' => 'מאפיין סופר עם הקוד: \'%s\' לא נמצא או אינו שייך למשפחת המאפיינים: \'%s\'',
                ],
            ],
        ],

        'tax-rates' => [
            'title' => 'שערי מס',

            'validation' => [
                'errors' => [
                    'duplicate-identifier' => 'המזהה: \'%s\' נמצא יותר מפעם אחת בקובץ היבוא.',
                    'identifier-not-found' => 'המזהה: \'%s\' לא נמצא במערכת.',
                ],
            ],
        ],
    ],

    'validation' => [
        'errors' => [
            'column-empty-headers' => 'מספר העמודות "%s" מכיל כותרות ריקות.',
            'column-name-invalid'  => 'שמות העמודות אינם חוקיים: "%s".',
            'column-not-found'     => 'לא נמצאו עמודות נדרשות: %s.',
            'column-numbers'       => 'מספר העמודות אינו תואם למספר השורות בכותרת.',
            'invalid-attribute'    => 'הכותרת מכילה מאפיין(ים) לא חוקי(ים): "%s".',
            'system'               => 'אירעה שגיאת מערכת בלתי צפויה.',
            'wrong-quotes'         => 'השתמשו בציטוטים מעוגלים במקום ציטוטים ישרים.',
        ],
    ],
];
