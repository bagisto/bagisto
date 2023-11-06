<?php

return [
    'seeders' => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'ברירת מחדל',
            ],

            'attribute-groups' => [
                'description'       => 'תיאור',
                'general'           => 'כללי',
                'inventories'       => 'מלאי',
                'meta-description'  => 'תיאור מטא',
                'price'             => 'מחיר',
                'shipping'          => 'משלוח',
                'settings'          => 'הגדרות',
            ],

            'attributes' => [
                'brand'                => 'מותג',
                'color'                => 'צבע',
                'cost'                 => 'עלות',
                'description'          => 'תיאור',
                'featured'             => 'מומלץ',
                'guest-checkout'       => 'הזמנה כאורח',
                'height'               => 'גובה',
                'length'               => 'אורך',
                'meta-title'           => 'כותרת מטא',
                'meta-keywords'        => 'מילות מפתח מטא',
                'meta-description'     => 'תיאור מטא',
                'manage-stock'         => 'ניהול מלאי',
                'new'                  => 'חדש',
                'name'                 => 'שם',
                'product-number'       => 'מספר מוצר',
                'price'                => 'מחיר',
                'sku'                  => 'קוד מוצר',
                'status'               => 'סטטוס',
                'short-description'    => 'תיאור קצר',
                'special-price'        => 'מחיר מיוחד',
                'special-price-from'   => 'מחיר מיוחד מ',
                'special-price-to'     => 'מחיר מיוחד עד',
                'size'                 => 'גודל',
                'tax-category'         => 'קטגוריית מס',
                'url-key'              => 'מפתח URL',
                'visible-individually' => 'נראה באופן יחידני',
                'width'                => 'רוחב',
                'weight'               => 'משקל',
            ],

            'attribute-options' => [
                'black'  => 'שחור',
                'green'  => 'ירוק',
                'l'      => 'L',
                'm'      => 'M',
                'red'    => 'אדום',
                's'      => 'S',
                'white'  => 'לבן',
                'xl'     => 'XL',
                'yellow' => 'צהוב',
            ],
        ],

        'category' => [
            'categories' => [
                'description' => 'תיאור קטגוריה ראשית',
                'name'        => 'ראשית',
            ],
        ],

        'cms' => [
            'pages' => [
                'about-us' => [
                    'content' => 'תוכן עמוד אודות',
                    'title'   => 'אודות',
                ],

                'refund-policy' => [
                    'content' => 'תוכן עמוד מדיניות החזרים',
                    'title'   => 'מדיניות החזרים',
                ],

                'return-policy' => [
                    'content' => 'תוכן עמוד מדיניות החזרים',
                    'title'   => 'מדיניות החזרים',
                ],

                'terms-conditions' => [
                    'content' => 'תוכן עמוד תנאים והגבלות',
                    'title'   => 'תנאים והגבלות',
                ],

                'terms-of-use' => [
                    'content' => 'תוכן עמוד תנאי השימוש',
                    'title'   => 'תנאי שימוש',
                ],

                'contact-us' => [
                    'content' => 'תוכן עמוד צור קשר',
                    'title'   => 'צור קשר',
                ],

                'customer-service' => [
                    'content' => 'תוכן עמוד שירות לקוחות',
                    'title'   => 'שירות לקוחות',
                ],

                'whats-new' => [
                    'content' => 'תוכן עמוד "מה חדש"',
                    'title'   => 'מה חדש',
                ],

                'payment-policy' => [
                    'content' => 'תוכן עמוד מדיניות תשלום',
                    'title'   => 'מדיניות תשלום',
                ],

                'shipping-policy' => [
                    'content' => 'תוכן עמוד מדיניות משלוח',
                    'title'   => 'מדיניות משלוח',
                ],

                'privacy-policy' => [
                    'content' => 'תוכן עמוד מדיניות פרטיות',
                    'title'   => 'מדיניות פרטיות',
                ],
            ],
        ],

        'core' => [
            'channels' => [
                'meta-title'       => 'חנות הדגמה',
                'meta-keywords'    => 'מילות מפתח לחנות הדגמה',
                'meta-description' => 'תיאור מטא לחנות הדגמה',
                'name'             => 'ברירת מחדל',
            ],

            'currencies' => [
                'CNY' => 'יואן סיני',
                'AED' => 'דירהם',
                'EUR' => 'יורו',
                'INR' => 'רופי הודי',
                'IRR' => 'ריאל איראני',
                'ILS' => 'שקל ישראלי',
                'JPY' => 'ין יפני',
                'GBP' => 'לירה שטרלינג',
                'RUB' => 'רובל רוסי',
                'SAR' => 'ריאל סעודית',
                'TRY' => 'לירה טורקית',
                'USD' => 'דולר אמריקאי',
                'UAH' => 'הריבניה האוקראינית',
            ],

            'locales' => [
                'ar'    => 'ערבית',
                'bn'    => 'בנגלי',
                'de'    => 'גרמנית',
                'es'    => 'ספרדית',
                'en'    => 'אנגלית',
                'fr'    => 'צרפתית',
                'fa'    => 'פרסית',
                'he'    => 'עברית',
                'hi_IN' => 'הינדית',
                'it'    => 'איטלקית',
                'ja'    => 'יפנית',
                'nl'    => 'הולנדית',
                'pl'    => 'פולנית',
                'pt_BR' => 'פורטוגזית ברזילאית',
                'ru'    => 'רוסית',
                'sin'   => 'סינהלה',
                'tr'    => 'טורקית',
                'uk'    => 'אוקראינית',
                'zh_CN' => 'סינית',
            ],
        ],

        'customer' => [
            'customer-groups' => [
                'guest'     => 'אורח',
                'general'   => 'כללי',
                'wholesale' => 'סיטונאי',
            ],
        ],

        'inventory' => [
            'inventory-sources' => [
                'name' => 'ברירת מחדל',
            ],
        ],

        'shop' => [
            'theme-customizations' => [
                'image-carousel' => [
                    'name' => 'מסלול תמונה',

                    'sliders' => [
                        'title' => 'הכנס לאוסף החדש',
                    ],
                ],

                'offer-information' => [
                    'name' => 'מידע על הצעה',

                    'content' => [
                        'title' => 'קבל עד 40% הנחה על הזמנה ראשונה - קנה עכשיו',
                    ],
                ],

                'categories-collections' => [
                    'name' => 'אוספי קטגוריות',
                ],

                'new-products' => [
                    'name' => 'מוצרים חדשים',

                    'options' => [
                        'title' => 'מוצרים חדשים',
                    ],
                ],

                'top-collections' => [
                    'name' => 'אוספי מובילים',

                    'content' => [
                        'sub-title-1' => 'אוספינו',
                        'sub-title-2' => 'אוספינו',
                        'sub-title-3' => 'אוספינו',
                        'sub-title-4' => 'אוספינו',
                        'sub-title-5' => 'אוספינו',
                        'sub-title-6' => 'אוספינו',
                        'title'       => 'המשחק עם ההוספות החדשות שלנו!',
                    ],
                ],

                'bold-collections' => [
                    'name' => 'אוספי מסירות',

                    'content' => [
                        'btn-title'   => 'צפייה בהכל',
                        'description' => 'הצג את הקולקציות המסוכנות שלנו! הרמת מלבוש עם עיצובים אימים והצהרות חיוניות. גלה דפוסים מודגשים וצבעים מוסריים שמחדשים את הארון שלך. הכן לקבל את המדהים!',
                        'title'       => 'הכנס לקולקציות המסירות החדשות שלנו!',
                    ],
                ],

                'featured-collections' => [
                    'name' => 'קולקציות מומלצות',

                    'options' => [
                        'title' => 'מוצרים מומלצים',
                    ],
                ],

                'game-container' => [
                    'name' => 'מגירת משחק',

                    'content' => [
                        'sub-title-1' => 'אוספינו',
                        'sub-title-2' => 'אוספינו',
                        'title'       => 'המשחק עם ההוספות החדשות שלנו!',
                    ],
                ],

                'all-products' => [
                    'name' => 'כל המוצרים',

                    'options' => [
                        'title' => 'כל המוצרים',
                    ],
                ],

                'footer-links' => [
                    'name' => 'קישורי תחתית',

                    'options' => [
                        'about-us'         => 'אודותינו',
                        'contact-us'       => 'צור קשר',
                        'customer-service' => 'שירות לקוחות',
                        'privacy-policy'   => 'מדיניות פרטיות',
                        'payment-policy'   => 'מדיניות תשלום',
                        'return-policy'    => 'מדיניות החזרה',
                        'refund-policy'    => 'מדיניות החזרה',
                        'shipping-policy'  => 'מדיניות משלוחים',
                        'terms-of-use'     => 'תנאי שימוש',
                        'terms-conditions' => 'תנאים והגבלות',
                        'whats-new'        => 'מה חדש',
                    ],
                ],
            ],
        ],

        'user' => [
            'users' => [
                'name' => 'דוגמה',
            ],

            'roles' => [
                'description' => 'תפקיד זה מעניק למשתמשים גישה מלאה',
                'name'        => 'מנהל',
            ],
        ],
    ],
];
