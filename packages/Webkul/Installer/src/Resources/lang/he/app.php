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

    'installer' => [
        'index' => [
            'server-requirements' => [
                'calendar'    => 'לוח שנה',
                'ctype'       => 'cType',
                'curl'        => 'cURL',
                'dom'         => 'DOM',
                'fileinfo'    => 'Fileinfo',
                'filter'      => 'מסנן',
                'gd'          => 'GD',
                'hash'        => 'גיבוב',
                'intl'        => 'Intl',
                'json'        => 'JSON',
                'mbstring'    => 'מחרוזת בת רב תווים',
                'openssl'     => 'OpenSSL',
                'php'         => 'PHP',
                'php-version' => '8.1 או גרסה גבוהה',
                'pcre'        => 'PCRE',
                'pdo'         => 'PDO',
                'session'     => 'Session',
                'title'       => 'דרישות השרת',
                'tokenizer'   => 'מפרק מחרוזת',
                'xml'         => 'XML',
            ],

            'environment-configuration' => [
                'application-name'    => 'שם היישום',
                'arabic'              => 'ערבית',
                'bagisto'             => 'Bagisto',
                'bengali'             => 'בנגאלי',
                'chinese-yuan'        => 'יואן סיני (CNY)',
                'chinese'             => 'סינית',
                'dirham'              => 'דירהם (AED)',
                'default-url'         => 'כתובת URL ברירת המחדל',
                'default-url-link'    => 'https://localhost',
                'default-currency'    => 'מטבע ברירת המחדל',
                'default-timezone'    => 'אזור זמן ברירת המחדל',
                'default-locale'      => 'הגדרות אזור ברירת המחדל',
                'dutch'               => 'הולנדית',
                'database-connection' => 'חיבור למסד הנתונים',
                'database-hostname'   => 'שם מארח מסד הנתונים',
                'database-port'       => 'יציאת מסד הנתונים',
                'database-name'       => 'שם מסד הנתונים',
                'database-username'   => 'שם משתמש למסד הנתונים',
                'database-prefix'     => 'קידומת למסד הנתונים',
                'database-password'   => 'סיסמת מסד הנתונים',
                'euro'                => 'יורו (EUR)',
                'english'             => 'אנגלית',
                'french'              => 'צרפתית',
                'hebrew'              => 'עברית',
                'hindi'               => 'הינדית',
                'iranian'             => 'ריאל איראני (IRR)',
                'israeli'             => 'שקל ישראלי (ILS)',
                'italian'             => 'איטלקית',
                'japanese-yen'        => 'ין יפני (JPY)',
                'japanese'            => 'יפנית',
                'mysql'               => 'MySQL',
                'pgsql'               => 'pgSQL',
                'pound'               => 'לירה שטרלינג (GBP)',
                'persian'             => 'פרסית',
                'polish'              => 'פולנית',
                'portuguese'          => 'פורטוגזית ברזילאית',
                'rupee'               => 'רופי הודית (INR)',
                'russian-ruble'       => 'רובל רוסי (RUB)',
                'russian'             => 'רוסית',
                'sqlsrv'              => 'SQLSRV',
                'saudi'               => 'ריאל סעודית (SAR)',
                'spanish'             => 'ספרדית',
                'sinhala'             => 'סינהלית',
                'title'               => 'הגדרות הסביבה',
                'turkish-lira'        => 'לירה טורקית (TRY)',
                'turkish'             => 'טורקית',
                'usd'                 => 'דולר אמריקאי (USD)',
                'ukrainian-hryvnia'   => 'הריבניה האוקראינית (UAH)',
                'ukrainian'           => 'אוקראינית',
            ],

            'ready-for-installation' => [
                'create-database-table'   => 'יצירת טבלת מסד הנתונים',
                'install'                 => 'התקנה',
                'install-info'            => 'Bagisto להתקנה',
                'install-info-button'     => 'לחץ על הלחצן למטה כדי להמשיך',
                'populate-database-table' => 'מילוי טבלאות מסד הנתונים',
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

            'email-configuration' => [
                'encryption'           => 'הצפנה',
                'enter-username'       => 'הזן שם משתמש',
                'enter-password'       => 'הזן סיסמה',
                'outgoing-mail-server' => 'שרת דואר יוצא',
                'outgoing-email'       => 'smpt.mailtrap.io',
                'password'             => 'סיסמה',
                'store-email'          => 'כתובת הדוא"ל של החנות',
                'enter-store-email'    => 'הזן כתובת דוא"ל של החנות',
                'server-port'          => 'פורט השרת',
                'server-port-code'     => '3306',
                'title'                => 'תצורת דוא"ל',
                'username'             => 'שם משתמש',
            ],

            'installation-completed' => [
                'admin-panel'                => 'פנל מנהל המערכת',
                'bagisto-forums'             => 'פורום Bagisto',
                'customer-panel'             => 'פנל לקוח',
                'explore-bagisto-extensions' => 'גלה הרחבות Bagisto',
                'title'                      => 'התקנה הושלמה',
                'title-info'                 => 'Bagisto הותקן בהצלחה במערכת שלך.',
            ],

            'bagisto-logo'             => 'לוגו Bagisto',
            'back'                     => 'חזור',
            'bagisto-info'             => 'פרויקט קהילתי על ידי',
            'bagisto'                  => 'Bagisto',
            'continue'                 => 'המשך',
            'installation-title'       => 'ברוך הבא להתקנה',
            'installation-info'        => 'אנחנו שמחים לראותך כאן!',
            'installation-description' => 'התקנת Bagisto כוללת בדרך כלל מספר שלבים. הנה מתקציב כללי של התהליך התקנת Bagisto:',
            'skip'                     => 'דלג',
            'save-configuration'       => 'שמור הגדרות',
            'title'                    => 'מתקין Bagisto',
            'webkul'                   => 'Webkul',
        ],
    ],
];
