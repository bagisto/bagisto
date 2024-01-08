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
                'AFN' => 'שקל ישראלי',
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

                'services-content' => [
                    'name'  => 'תוכן שירותים',

                    'title' => [
                        'free-shipping'   => 'משלוח חינם',
                        'product-replace' => 'החלפת מוצר',
                        'emi-available'   => 'EMI זמין',
                        'time-support'    => 'תמיכה 24/7',
                    ],

                    'description' => [
                        'free-shipping-info'   => 'תהנו ממשלוח חינם על כל ההזמנות',
                        'product-replace-info' => 'החלפת מוצר קלה זמינה!',
                        'emi-available-info'   => 'EMI ללא עלות זמין על כל כרטיסי האשראי המרכזיים',
                        'time-support-info'    => 'תמיכה ייעודית 24/7 באמצעות צ\'אט ואימייל',
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

    'installer' => [
        'index' => [
            'start' => [
                'locale'        => 'אזור',
                'main'          => 'הַתְחָלָה',
                'select-locale' => 'בחר אזור',
                'title'         => 'התקנת Bagisto שלך',
                'welcome-title' => 'ברוך הבא ל-Bagisto 2.0.',
            ],

            'server-requirements' => [
                'calendar'    => 'לוח שנה',
                'ctype'       => 'cType',
                'curl'        => 'cURL',
                'dom'         => 'DOM',
                'fileinfo'    => 'FileInfo',
                'filter'      => 'מסנן',
                'gd'          => 'GD',
                'hash'        => 'גיבוב',
                'intl'        => 'Intl',
                'json'        => 'JSON',
                'mbstring'    => 'מחרוזת בת רב תווים',
                'openssl'     => 'OpenSSL',
                'php'         => 'PHP',
                'php-version' => '8.1 או גבוהה יותר',
                'pcre'        => 'PCRE',
                'pdo'         => 'PDO',
                'session'     => 'Session',
                'title'       => 'דרישות השרת',
                'tokenizer'   => 'מפרק מחרוזת',
                'xml'         => 'XML',
            ],

            'environment-configuration' => [
                'allowed-locales'     => 'שפות מורשות',
                'allowed-currencies'  => 'מטבעות מורשים',
                'application-name'    => 'שם האפליקציה',
                'bagisto'             => 'Bagisto',
                'chinese-yuan'        => 'יואן סיני (CNY)',
                'dirham'              => 'דירהאם (AED)',
                'default-url'         => 'כתובת URL ברירת מחדל',
                'default-url-link'    => 'https://localhost',
                'default-currency'    => 'מטבע ברירת מחדל',
                'default-timezone'    => 'איזור זמן ברירת מחדל',
                'default-locale'      => 'אזור ברירת מחדל',
                'database-connection' => 'חיבור למסד הנתונים',
                'database-hostname'   => 'שם המארח של מסד הנתונים',
                'database-port'       => 'פורט מסד הנתונים',
                'database-name'       => 'שם מסד הנתונים',
                'database-username'   => 'שם משתמש של מסד הנתונים',
                'database-prefix'     => 'קידומת מסד הנתונים',
                'database-password'   => 'סיסמת מסד הנתונים',
                'euro'                => 'יורו (EUR)',
                'iranian'             => 'ריאל איראני (IRR)',
                'israeli'             => 'שקל ישראלי (AFN)',
                'japanese-yen'        => 'ין יפני (JPY)',
                'mysql'               => 'Mysql',
                'pgsql'               => 'pgSQL',
                'pound'               => 'לירה שטרלינג (GBP)',
                'rupee'               => 'רופי הודי (INR)',
                'russian-ruble'       => 'רובל רוסי (RUB)',
                'sqlsrv'              => 'SQLSRV',
                'saudi'               => 'ריאל סעודית (SAR)',
                'title'               => 'הגדרות הסביבה',
                'turkish-lira'        => 'לירה טורקית (TRY)',
                'usd'                 => 'דולר אמריקאי (USD)',
                'ukrainian-hryvnia'   => 'הריבניה האוקראינית (UAH)',
                'warning-message'     => 'אזהרה! הגדרות שפות המערכת המוגדרות כברירת מחדל והמטבע המוגדר כברירת מחדל הם קבועים ואינם יכולים להשתנות שוב.',
            ],

            'ready-for-installation' => [
                'create-database-table'   => 'יצירת טבלת מסד הנתונים',
                'install'                 => 'התקנה',
                'install-info'            => 'Bagisto להתקנה',
                'install-info-button'     => 'לחץ על הכפתור למטה כדי',
                'populate-database-table' => 'מילוי הטבלאות במסד הנתונים',
                'start-installation'      => 'התחל התקנה',
                'title'                   => 'מוכן להתקנה',
            ],

            'installation-processing' => [
                'bagisto'          => 'התקנת Bagisto',
                'bagisto-info'     => 'יצירת טבלאות מסד הנתונים, זה עשוי לקחת מספר רגעים',
                'title'            => 'התקנה',
            ],

            'create-administrator' => [
                'admin'            => 'מנהל מערכת',
                'bagisto'          => 'Bagisto',
                'confirm-password' => 'אשר סיסמה',
                'email'            => 'כתובת דוא"ל',
                'email-address'    => 'admin@example.com',
                'password'         => 'סיסמה',
                'title'            => 'יצירת מנהל מערכת',
            ],

            'installation-completed' => [
                'admin-panel'                => 'פנל מנהל המערכת',
                'bagisto-forums'             => 'פורום Bagisto',
                'customer-panel'             => 'פנל לקוח',
                'explore-bagisto-extensions' => 'גלה הרחבות Bagisto',
                'title'                      => 'התקנה הושלמה',
                'title-info'                 => 'Bagisto הותקן בהצלחה במערכת שלך.',
            ],

            'arabic'                   => 'ערבית',
            'bengali'                  => 'בנגלי',
            'bagisto-logo'             => 'לוגו Bagisto',
            'back'                     => 'חזרה',
            'bagisto-info'             => 'פרויקט קהילתי על ידי',
            'bagisto'                  => 'Bagisto',
            'chinese'                  => 'סיני',
            'continue'                 => 'המשך',
            'dutch'                    => 'הולנדי',
            'english'                  => 'אנגלית',
            'french'                   => 'צרפתי',
            'german'                   => 'גרמני',
            'hebrew'                   => 'עברית',
            'hindi'                    => 'הינדי',
            'installation-title'       => 'ברוך הבא להתקנת Bagisto',
            'installation-info'        => 'אנו שמחים לראותך כאן!',
            'installation-description' => 'התקנת Bagisto בדרך כלל כוללת מספר שלבים. הנה סקירה כללית של תהליך ההתקנה עבור Bagisto:',
            'italian'                  => 'איטלקי',
            'japanese'                 => 'יפני',
            'persian'                  => 'פרסי',
            'polish'                   => 'פולני',
            'portuguese'               => 'פורטוגזי ברזילאי',
            'russian'                  => 'רוסי',
            'spanish'                  => 'ספרדית',
            'sinhala'                  => 'סינהלה',
            'skip'                     => 'דילוג',
            'save-configuration'       => 'שמור הגדרות',
            'title'                    => 'מתקין Bagisto',
            'turkish'                  => 'טורקי',
            'ukrainian'                => 'אוקראיני',
            'webkul'                   => 'Webkul',
        ],
    ],
];
