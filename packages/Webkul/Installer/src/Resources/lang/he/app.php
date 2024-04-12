<?php

return [
    'seeders' => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'ברירת מחדל',
            ],

            'attribute-groups' => [
                'description'      => 'תיאור',
                'general'          => 'כללי',
                'inventories'      => 'מלאי',
                'meta-description' => 'תיאור מטא',
                'price'            => 'מחיר',
                'settings'         => 'הגדרות',
                'shipping'         => 'משלוח',
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
                'manage-stock'         => 'ניהול מלאי',
                'meta-description'     => 'תיאור מטא',
                'meta-keywords'        => 'מילות מפתח מטא',
                'meta-title'           => 'כותרת מטא',
                'name'                 => 'שם',
                'new'                  => 'חדש',
                'price'                => 'מחיר',
                'product-number'       => 'מספר מוצר',
                'short-description'    => 'תיאור קצר',
                'size'                 => 'גודל',
                'sku'                  => 'קוד מוצר',
                'special-price'        => 'מחיר מיוחד',
                'special-price-from'   => 'מחיר מיוחד מ',
                'special-price-to'     => 'מחיר מיוחד עד',
                'status'               => 'סטטוס',
                'tax-category'         => 'קטגוריית מס',
                'url-key'              => 'מפתח URL',
                'visible-individually' => 'נראה באופן יחידני',
                'weight'               => 'משקל',
                'width'                => 'רוחב',
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

                'contact-us' => [
                    'content' => 'תוכן עמוד צור קשר',
                    'title'   => 'צור קשר',
                ],

                'customer-service' => [
                    'content' => 'תוכן עמוד שירות לקוחות',
                    'title'   => 'שירות לקוחות',
                ],

                'payment-policy' => [
                    'content' => 'תוכן עמוד מדיניות תשלום',
                    'title'   => 'מדיניות תשלום',
                ],

                'privacy-policy' => [
                    'content' => 'תוכן עמוד מדיניות פרטיות',
                    'title'   => 'מדיניות פרטיות',
                ],

                'refund-policy' => [
                    'content' => 'תוכן עמוד מדיניות החזרים',
                    'title'   => 'מדיניות החזרים',
                ],

                'return-policy' => [
                    'content' => 'תוכן עמוד מדיניות החזרים',
                    'title'   => 'מדיניות החזרים',
                ],

                'shipping-policy' => [
                    'content' => 'תוכן עמוד מדיניות משלוח',
                    'title'   => 'מדיניות משלוח',
                ],

                'terms-conditions' => [
                    'content' => 'תוכן עמוד תנאים והגבלות',
                    'title'   => 'תנאים והגבלות',
                ],

                'terms-of-use' => [
                    'content' => 'תוכן עמוד תנאי השימוש',
                    'title'   => 'תנאי שימוש',
                ],

                'whats-new' => [
                    'content' => 'תוכן עמוד "מה חדש"',
                    'title'   => 'מה חדש',
                ],
            ],
        ],

        'core' => [
            'channels' => [
                'name'             => 'ברירת מחדל',
                'meta-title'       => 'חנות הדגמה',
                'meta-keywords'    => 'מילות מפתח לחנות הדגמה',
                'meta-description' => 'תיאור מטא לחנות הדגמה',
            ],

            'currencies' => [
                'AED' => 'דירהם',
                'AFN' => 'שקל ישראלי',
                'CNY' => 'יואן סיני',
                'EUR' => 'יורו',
                'GBP' => 'לירה שטרלינג',
                'INR' => 'רופי הודי',
                'IRR' => 'ריאל איראני',
                'JPY' => 'ין יפני',
                'RUB' => 'רובל רוסי',
                'SAR' => 'ריאל סעודית',
                'TRY' => 'לירה טורקית',
                'UAH' => 'הריבניה האוקראינית',
                'USD' => 'דולר אמריקאי',
            ],

            'locales' => [
                'ar'    => 'ערבית',
                'bn'    => 'בנגלי',
                'de'    => 'גרמנית',
                'en'    => 'אנגלית',
                'es'    => 'ספרדית',
                'fa'    => 'פרסית',
                'fr'    => 'צרפתית',
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
                'general'   => 'כללי',
                'guest'     => 'אורח',
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
                'all-products' => [
                    'name' => 'כל המוצרים',

                    'options' => [
                        'title' => 'כל המוצרים',
                    ],
                ],

                'bold-collections' => [
                    'content' => [
                        'btn-title'   => 'צפייה בהכל',
                        'description' => 'הצג את הקולקציות המסוכנות שלנו! הרמת מלבוש עם עיצובים אימים והצהרות חיוניות. גלה דפוסים מודגשים וצבעים מוסריים שמחדשים את הארון שלך. הכן לקבל את המדהים!',
                        'title'       => 'הכנס לקולקציות המסירות החדשות שלנו!',
                    ],

                    'name' => 'אוספי מסירות',
                ],

                'categories-collections' => [
                    'name' => 'אוספי קטגוריות',
                ],

                'featured-collections' => [
                    'name' => 'קולקציות מומלצות',

                    'options' => [
                        'title' => 'מוצרים מומלצים',
                    ],
                ],

                'footer-links' => [
                    'name' => 'קישורי תחתית',

                    'options' => [
                        'about-us'         => 'אודותינו',
                        'contact-us'       => 'צור קשר',
                        'customer-service' => 'שירות לקוחות',
                        'payment-policy'   => 'מדיניות תשלום',
                        'privacy-policy'   => 'מדיניות פרטיות',
                        'refund-policy'    => 'מדיניות החזרה',
                        'return-policy'    => 'מדיניות החזרה',
                        'shipping-policy'  => 'מדיניות משלוחים',
                        'terms-conditions' => 'תנאים והגבלות',
                        'terms-of-use'     => 'תנאי שימוש',
                        'whats-new'        => 'מה חדש',
                    ],
                ],

                'game-container' => [
                    'content' => [
                        'sub-title-1' => 'אוספינו',
                        'sub-title-2' => 'אוספינו',
                        'title'       => 'המשחק עם ההוספות החדשות שלנו!',
                    ],

                    'name' => 'מגירת משחק',
                ],

                'image-carousel' => [
                    'name' => 'מסלול תמונה',

                    'sliders' => [
                        'title' => 'הכנס לאוסף החדש',
                    ],
                ],

                'new-products' => [
                    'name' => 'מוצרים חדשים',

                    'options' => [
                        'title' => 'מוצרים חדשים',
                    ],
                ],

                'offer-information' => [
                    'content' => [
                        'title' => 'קבל עד 40% הנחה על הזמנה ראשונה - קנה עכשיו',
                    ],

                    'name' => 'מידע על הצעה',
                ],

                'services-content' => [
                    'description' => [
                        'emi-available-info'   => 'EMI ללא עלות זמין על כל כרטיסי האשראי המרכזיים',
                        'free-shipping-info'   => 'תהנו ממשלוח חינם על כל ההזמנות',
                        'product-replace-info' => 'החלפת מוצר קלה זמינה!',
                        'time-support-info'    => 'תמיכה ייעודית 24/7 באמצעות צ\'אט ואימייל',
                    ],

                    'name' => 'תוכן שירותים',

                    'title' => [
                        'free-shipping'   => 'משלוח חינם',
                        'product-replace' => 'החלפת מוצר',
                        'emi-available'   => 'EMI זמין',
                        'time-support'    => 'תמיכה 24/7',
                    ],
                ],

                'top-collections' => [
                    'content' => [
                        'sub-title-1' => 'אוספינו',
                        'sub-title-2' => 'אוספינו',
                        'sub-title-3' => 'אוספינו',
                        'sub-title-4' => 'אוספינו',
                        'sub-title-5' => 'אוספינו',
                        'sub-title-6' => 'אוספינו',
                        'title'       => 'המשחק עם ההוספות החדשות שלנו!',
                    ],

                    'name' => 'אוספי מובילים',
                ],
            ],
        ],

        'user' => [
            'roles' => [
                'description' => 'תפקיד זה מעניק למשתמשים גישה מלאה',
                'name'        => 'מנהל',
            ],

            'users' => [
                'name' => 'דוגמה',
            ],
        ],
    ],

    'installer' => [
        'index' => [
            'create-administrator' => [
                'admin'            => 'מנהל מערכת',
                'bagisto'          => 'Bagisto',
                'confirm-password' => 'אשר סיסמה',
                'email'            => 'כתובת דוא"ל',
                'email-address'    => 'admin@example.com',
                'password'         => 'סיסמה',
                'title'            => 'יצירת מנהל מערכת',
            ],

            'environment-configuration' => [
                'allowed-currencies'  => 'מטבעות מורשים',
                'allowed-locales'     => 'שפות מורשות',
                'application-name'    => 'שם האפליקציה',
                'bagisto'             => 'Bagisto',
                'chinese-yuan'        => 'יואן סיני (CNY)',
                'database-connection' => 'חיבור למסד הנתונים',
                'database-hostname'   => 'שם המארח של מסד הנתונים',
                'database-name'       => 'שם מסד הנתונים',
                'database-password'   => 'סיסמת מסד הנתונים',
                'database-port'       => 'פורט מסד הנתונים',
                'database-prefix'     => 'קידומת מסד הנתונים',
                'database-username'   => 'שם משתמש של מסד הנתונים',
                'default-currency'    => 'מטבע ברירת מחדל',
                'default-locale'      => 'אזור ברירת מחדל',
                'default-timezone'    => 'איזור זמן ברירת מחדל',
                'default-url'         => 'כתובת URL ברירת מחדל',
                'default-url-link'    => 'https://localhost',
                'dirham'              => 'דירהאם (AED)',
                'euro'                => 'יורו (EUR)',
                'iranian'             => 'ריאל איראני (IRR)',
                'israeli'             => 'שקל ישראלי (AFN)',
                'japanese-yen'        => 'ין יפני (JPY)',
                'mysql'               => 'Mysql',
                'pgsql'               => 'pgSQL',
                'pound'               => 'לירה שטרלינג (GBP)',
                'rupee'               => 'רופי הודי (INR)',
                'russian-ruble'       => 'רובל רוסי (RUB)',
                'saudi'               => 'ריאל סעודית (SAR)',
                'select-timezone'     => 'בחר אזור זמן',
                'sqlsrv'              => 'SQLSRV',
                'title'               => 'הגדרות הסביבה',
                'turkish-lira'        => 'לירה טורקית (TRY)',
                'ukrainian-hryvnia'   => 'הריבניה האוקראינית (UAH)',
                'usd'                 => 'דולר אמריקאי (USD)',
                'warning-message'     => 'אזהרה! הגדרות שפות המערכת המוגדרות כברירת מחדל והמטבע המוגדר כברירת מחדל הם קבועים ואינם יכולים להשתנות שוב.',
            ],

            'installation-processing' => [
                'bagisto'      => 'התקנת Bagisto',
                'bagisto-info' => 'יצירת טבלאות מסד הנתונים, זה עשוי לקחת מספר רגעים',
                'title'        => 'התקנה',
            ],

            'installation-completed' => [
                'admin-panel'                => 'פנל מנהל המערכת',
                'bagisto-forums'             => 'פורום Bagisto',
                'customer-panel'             => 'פנל לקוח',
                'explore-bagisto-extensions' => 'גלה הרחבות Bagisto',
                'title'                      => 'התקנה הושלמה',
                'title-info'                 => 'Bagisto הותקן בהצלחה במערכת שלך.',
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
                'pcre'        => 'PCRE',
                'pdo'         => 'PDO',
                'php'         => 'PHP',
                'php-version' => '8.1 או גבוהה יותר',
                'session'     => 'Session',
                'title'       => 'דרישות השרת',
                'tokenizer'   => 'מפרק מחרוזת',
                'xml'         => 'XML',
            ],

            'arabic'                   => 'ערבית',
            'back'                     => 'חזרה',
            'bagisto'                  => 'Bagisto',
            'bagisto-info'             => 'פרויקט קהילתי על ידי',
            'bagisto-logo'             => 'לוגו Bagisto',
            'bengali'                  => 'בנגלי',
            'chinese'                  => 'סיני',
            'continue'                 => 'המשך',
            'dutch'                    => 'הולנדי',
            'english'                  => 'אנגלית',
            'french'                   => 'צרפתי',
            'german'                   => 'גרמני',
            'hebrew'                   => 'עברית',
            'hindi'                    => 'הינדי',
            'installation-description' => 'התקנת Bagisto בדרך כלל כוללת מספר שלבים. הנה סקירה כללית של תהליך ההתקנה עבור  Bagisto:',
            'installation-info'        => 'אנו שמחים לראותך כאן!',
            'installation-title'       => 'ברוך הבא להתקנת Bagisto',
            'italian'                  => 'איטלקי',
            'japanese'                 => 'יפני',
            'persian'                  => 'פרסי',
            'polish'                   => 'פולני',
            'portuguese'               => 'פורטוגזי ברזילאי',
            'russian'                  => 'רוסי',
            'save-configuration'       => 'שמור הגדרות',
            'sinhala'                  => 'סינהלה',
            'skip'                     => 'דילוג',
            'spanish'                  => 'ספרדית',
            'title'                    => 'מתקין Bagisto',
            'turkish'                  => 'טורקי',
            'ukrainian'                => 'אוקראיני',
            'webkul'                   => 'Webkul',
        ],
    ],
];
