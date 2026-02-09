<?php

return [
    'seeders' => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'ברירת מחדל',
            ],

            'attribute-groups' => [
                'description' => 'תיאור',
                'general' => 'כללי',
                'inventories' => 'מלאי',
                'meta-description' => 'תיאור מטא',
                'price' => 'מחיר',
                'rma' => 'RMA',
                'settings' => 'הגדרות',
                'shipping' => 'משלוח',
            ],

            'attributes' => [
                'allow-rma' => 'אפשר RMA',
                'brand' => 'מותג',
                'color' => 'צבע',
                'cost' => 'עלות',
                'description' => 'תיאור',
                'featured' => 'מומלץ',
                'guest-checkout' => 'הזמנה כאורח',
                'height' => 'גובה',
                'length' => 'אורך',
                'manage-stock' => 'ניהול מלאי',
                'meta-description' => 'תיאור מטא',
                'meta-keywords' => 'מילות מפתח מטא',
                'meta-title' => 'כותרת מטא',
                'name' => 'שם',
                'new' => 'חדש',
                'price' => 'מחיר',
                'product-number' => 'מספר מוצר',
                'rma-rules' => 'כללי RMA',
                'short-description' => 'תיאור קצר',
                'size' => 'גודל',
                'sku' => 'קוד מוצר',
                'special-price' => 'מחיר מיוחד',
                'special-price-from' => 'מחיר מיוחד מ',
                'special-price-to' => 'מחיר מיוחד עד',
                'status' => 'סטטוס',
                'tax-category' => 'קטגוריית מס',
                'url-key' => 'מפתח URL',
                'visible-individually' => 'נראה באופן יחידני',
                'weight' => 'משקל',
                'width' => 'רוחב',
            ],

            'attribute-options' => [
                'black' => 'שחור',
                'green' => 'ירוק',
                'l' => 'L',
                'm' => 'M',
                'red' => 'אדום',
                's' => 'S',
                'white' => 'לבן',
                'xl' => 'XL',
                'yellow' => 'צהוב',
            ],
        ],

        'category' => [
            'categories' => [
                'description' => 'תיאור קטגוריה ראשית',
                'name' => 'ראשית',
            ],
        ],

        'cms' => [
            'pages' => [
                'about-us' => [
                    'content' => 'תוכן עמוד אודות',
                    'title' => 'אודות',
                ],

                'contact-us' => [
                    'content' => 'תוכן עמוד צור קשר',
                    'title' => 'צור קשר',
                ],

                'customer-service' => [
                    'content' => 'תוכן עמוד שירות לקוחות',
                    'title' => 'שירות לקוחות',
                ],

                'payment-policy' => [
                    'content' => 'תוכן עמוד מדיניות תשלום',
                    'title' => 'מדיניות תשלום',
                ],

                'privacy-policy' => [
                    'content' => 'תוכן עמוד מדיניות פרטיות',
                    'title' => 'מדיניות פרטיות',
                ],

                'refund-policy' => [
                    'content' => 'תוכן עמוד מדיניות החזרים',
                    'title' => 'מדיניות החזרים',
                ],

                'return-policy' => [
                    'content' => 'תוכן עמוד מדיניות החזרים',
                    'title' => 'מדיניות החזרים',
                ],

                'shipping-policy' => [
                    'content' => 'תוכן עמוד מדיניות משלוח',
                    'title' => 'מדיניות משלוח',
                ],

                'terms-conditions' => [
                    'content' => 'תוכן עמוד תנאים והגבלות',
                    'title' => 'תנאים והגבלות',
                ],

                'terms-of-use' => [
                    'content' => 'תוכן עמוד תנאי השימוש',
                    'title' => 'תנאי שימוש',
                ],

                'whats-new' => [
                    'content' => 'תוכן עמוד "מה חדש"',
                    'title' => 'מה חדש',
                ],
            ],
        ],

        'core' => [
            'channels' => [
                'meta-description' => 'תיאור מטא לחנות הדגמה',
                'meta-keywords' => 'מילות מפתח לחנות הדגמה',
                'meta-title' => 'חנות הדגמה',
                'name' => 'ברירת מחדל',
            ],

            'currencies' => [
                'AED' => 'דירהם של איחוד האמירויות',
                'ARS' => 'פזו ארגנטינאי',
                'AUD' => 'דולר אוסטרלי',
                'BDT' => 'טאקה בנגלדשי',
                'BHD' => 'דינר בחרייני',
                'BRL' => 'ריאל ברזילאי',
                'CAD' => 'דולר קנדי',
                'CHF' => 'פרנק שוויצרי',
                'CLP' => 'פזו צ׳יליאני',
                'CNY' => 'יואן סיני',
                'COP' => 'פזו קולומביאני',
                'CZK' => 'קורונה צ׳כית',
                'DKK' => 'כתר דני',
                'DZD' => 'דינר אלג׳ירי',
                'EGP' => 'לירה מצרית',
                'EUR' => 'יורו',
                'FJD' => 'דולר פיג׳יאני',
                'GBP' => 'לירה שטרלינג',
                'HKD' => 'דולר הונג קונגי',
                'HUF' => 'פורינט הונגרי',
                'IDR' => 'רופיה אינדונזית',
                'ILS' => 'שקל חדש',
                'INR' => 'רופי הודי',
                'JOD' => 'דינר ירדני',
                'JPY' => 'ין יפני',
                'KRW' => 'וון דרום קוריאני',
                'KWD' => 'דינר כוויתי',
                'KZT' => 'טנגה קזחסטני',
                'LBP' => 'לירה לבנונית',
                'LKR' => 'רופי סרי לנקי',
                'LYD' => 'דינר לובי',
                'MAD' => 'דירהם מרוקאי',
                'MUR' => 'רופי מאוריציני',
                'MXN' => 'פזו מקסיקני',
                'MYR' => 'רינגיט מלזי',
                'NGN' => 'נאירה ניגרי',
                'NOK' => 'כתר נורווגי',
                'NPR' => 'רופי נפאלי',
                'NZD' => 'דולר ניו זילנדי',
                'OMR' => 'ריאל עומאני',
                'PAB' => 'בלבואה פנמי',
                'PEN' => 'סול פרואני',
                'PHP' => 'פזו פיליפיני',
                'PKR' => 'רופי פקיסטני',
                'PLN' => 'זלוטי פולני',
                'PYG' => 'גוארני פרגוואי',
                'QAR' => 'ריאל קטארי',
                'RON' => 'לאו רומני',
                'RUB' => 'רובל רוסי',
                'SAR' => 'ריאל סעודי',
                'SEK' => 'כתר שוודי',
                'SGD' => 'דולר סינגפורי',
                'THB' => 'בהט תאילנדי',
                'TND' => 'דינר טוניסאי',
                'TRY' => 'לירה טורקית',
                'TWD' => 'דולר טייוואני חדש',
                'UAH' => 'הריבנה הוקראינית',
                'USD' => 'דולר אמריקאי',
                'UZS' => 'סום אוזבקי',
                'VEF' => 'בוליבר ונצואלי',
                'VND' => 'דונג וייטנאמי',
                'XAF' => 'פרנק CFA BEAC',
                'XOF' => 'פרנק CFA BCEAO',
                'ZAR' => 'ראנד דרום אפריקאי',
                'ZMW' => 'קוואצ׳ה זמבית',
            ],

            'locales' => [
                'ar' => 'ערבית',
                'bn' => 'בנגלי',
                'ca' => 'קטלאנית',
                'de' => 'גרמנית',
                'en' => 'אנגלית',
                'es' => 'ספרדית',
                'fa' => 'פרסית',
                'fr' => 'צרפתית',
                'he' => 'עברית',
                'hi_IN' => 'הינדית',
                'id' => 'אינדונזי',
                'it' => 'איטלקית',
                'ja' => 'יפנית',
                'nl' => 'הולנדית',
                'pl' => 'פולנית',
                'pt_BR' => 'פורטוגזית ברזילאית',
                'ru' => 'רוסית',
                'sin' => 'סינהלה',
                'tr' => 'טורקית',
                'uk' => 'אוקראינית',
                'zh_CN' => 'סינית',
            ],
        ],

        'customer' => [
            'customer-groups' => [
                'general' => 'כללי',
                'guest' => 'אורח',
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
                        'btn-title' => 'צפה באוספים',
                        'description' => 'הצג את הקולקציות המסוכנות שלנו! הרמת מלבוש עם עיצובים אימים והצהרות חיוניות. גלה דפוסים מודגשים וצבעים מוסריים שמחדשים את הארון שלך. הכן לקבל את המדהים!',
                        'title' => 'הכנס לקולקציות המסירות החדשות שלנו!',
                    ],

                    'name' => 'אוספי מסירות',
                ],

                'bold-collections-2' => [
                    'content' => [
                        'btn-title' => 'צפה בקולקציות',
                        'description' => 'הקולקציות הנועזות שלנו כאן כדי להגדיר מחדש את הארון שלך עם עיצובים ללא פחד וצבעים בולטים ותוססים. מדפוסים נועזים ועד גוונים עוצמתיים, זו ההזדמנות שלך לפרוץ מהרגיל ולדרוך אל המיוחד.',
                        'title' => 'שחרר את העוז שלך עם הקולקציה החדשה שלנו!',
                    ],

                    'name' => 'קולקציות נועזות',
                ],

                'booking-products' => [
                    'name' => 'מוצרי הזמנה',

                    'options' => [
                        'title' => 'הזמן כרטיסים',
                    ],
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
                        'about-us' => 'אודותינו',
                        'contact-us' => 'צור קשר',
                        'customer-service' => 'שירות לקוחות',
                        'payment-policy' => 'מדיניות תשלום',
                        'privacy-policy' => 'מדיניות פרטיות',
                        'refund-policy' => 'מדיניות החזרה',
                        'return-policy' => 'מדיניות החזרה',
                        'shipping-policy' => 'מדיניות משלוחים',
                        'terms-conditions' => 'תנאים והגבלות',
                        'terms-of-use' => 'תנאי שימוש',
                        'whats-new' => 'מה חדש',
                    ],
                ],

                'game-container' => [
                    'content' => [
                        'sub-title-1' => 'אוספינו',
                        'sub-title-2' => 'אוספינו',
                        'title' => 'המשחק עם ההוספות החדשות שלנו!',
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
                        'emi-available-info' => 'EMI ללא עלות זמין על כל כרטיסי האשראי המרכזיים',
                        'free-shipping-info' => 'תהנו ממשלוח חינם על כל ההזמנות',
                        'product-replace-info' => 'החלפת מוצר קלה זמינה!',
                        'time-support-info' => 'תמיכה ייעודית 24/7 באמצעות צ\'אט ואימייל',
                    ],

                    'name' => 'תוכן שירותים',

                    'title' => [
                        'emi-available' => 'EMI זמין',
                        'free-shipping' => 'משלוח חינם',
                        'product-replace' => 'החלפת מוצר',
                        'time-support' => 'תמיכה 24/7',
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
                        'title' => 'המשחק עם ההוספות החדשות שלנו!',
                    ],

                    'name' => 'אוספי מובילים',
                ],
            ],
        ],

        'user' => [
            'roles' => [
                'description' => 'תפקיד זה מעניק למשתמשים גישה מלאה',
                'name' => 'מנהל',
            ],

            'users' => [
                'name' => 'דוגמה',
            ],
        ],

        'sample-categories' => [
            'category-translation' => [
                '2' => [
                    'description' => '<p>גברים</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'גברים',
                    'slug' => 'mens',
                    'url-path' => 'men',
                ],

                '3' => [
                    'description' => '<p>ילדים</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'ילדים',
                    'slug' => 'kids',
                    'url-path' => 'kids',
                ],

                '4' => [
                    'description' => '<p>נשים</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'נשים',
                    'slug' => 'womens',
                    'url-path' => 'woman',
                ],

                '5' => [
                    'description' => '<p>לבוש רשמי</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'לבוש רשמי',
                    'slug' => 'formal-wear-men',
                    'url-path' => 'men/formal-wear-men',
                ],

                '6' => [
                    'description' => '<p>לבוש קז\'\u05d5אל</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'לבוש קז\'\u05d5אל',
                    'slug' => 'casual-wear-men',
                    'url-path' => 'men/casual-wear-men',
                ],

                '7' => [
                    'description' => '<p>ביגוד ספורט</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'ביגוד ספורט',
                    'slug' => 'active-wear',
                    'url-path' => 'men/active-wear',
                ],

                '8' => [
                    'description' => '<p>הנעלה</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'הנעלה',
                    'slug' => 'footwear',
                    'url-path' => 'men/footwear',
                ],

                '9' => [
                    'description' => '<p>בגדי בנות</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'בגדי בנות',
                    'slug' => 'formal-wear-female',
                    'url-path' => 'woman/formal-wear-female',
                ],

                '10' => [
                    'description' => '<p>בגדי בנים</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'בגדי בנים',
                    'slug' => 'casual-wear-female',
                    'url-path' => 'woman/casual-wear-female',
                ],

                '11' => [
                    'description' => '<p>נעלי בנות</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'נעלי בנות',
                    'slug' => 'active-wear-female',
                    'url-path' => 'woman/active-wear-female',
                ],

                '12' => [
                    'description' => '<p>נעלי בנים</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'נעלי בנים',
                    'slug' => 'footwear-female',
                    'url-path' => 'woman/footwear-female',
                ],

                '13' => [
                    'description' => '<p>לבוש רשמי</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => 'Girls Clothing',
                    'name' => 'לבוש רשמי',
                    'slug' => 'girls-clothing',
                    'url-path' => 'kids/girls-clothing',
                ],

                '14' => [
                    'description' => '<p>לבוש קז\'\u05d5אל</p>',
                    'meta-description' => 'Boys Fashion',
                    'meta-keywords' => '',
                    'meta-title' => 'Boys Clothing',
                    'name' => 'לבוש קז\'\u05d5אל',
                    'slug' => 'boys-clothing',
                    'url-path' => 'kids/boys-clothing',
                ],

                '15' => [
                    'description' => '<p>ביגוד ספורט</p>',
                    'meta-description' => 'Girls Fashionable Footwear Collection',
                    'meta-keywords' => '',
                    'meta-title' => 'Girls Footwear',
                    'name' => 'ביגוד ספורט',
                    'slug' => 'girls-footwear',
                    'url-path' => 'kids/girls-footwear',
                ],

                '16' => [
                    'description' => '<p>הנעלה</p>',
                    'meta-description' => 'Boys Stylish Footwear Collection',
                    'meta-keywords' => '',
                    'meta-title' => 'Boys Footwear',
                    'name' => 'הנעלה',
                    'slug' => 'boys-footwear',
                    'url-path' => 'kids/boys-footwear',
                ],

                '17' => [
                    'description' => '<p>בריאות</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'בריאות',
                    'slug' => 'wellness',
                    'url-path' => 'wellness',
                ],

                '18' => [
                    'description' => '<p>הדרכת יוגה להורדה</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'הדרכת יוגה להורדה',
                    'slug' => 'downloadable-yoga-tutorial',
                    'url-path' => 'wellness/downloadable-yoga-tutorial',
                ],

                '19' => [
                    'description' => '<p>ספרים אלקטרוניים</p>',
                    'meta-description' => 'Books Collection',
                    'meta-keywords' => '',
                    'meta-title' => 'Books Collection',
                    'name' => 'ספרים אלקטרוניים',
                    'slug' => 'e-books',
                    'url-path' => 'wellness/e-books',
                ],

                '20' => [
                    'description' => '<p>כרטיס קולנוע</p>',
                    'meta-description' => 'Immerse yourself in the magic of 10 movies each month without extra charges. Valid nationwide with no blackout dates, this pass offers exclusive perks and concession discounts, making it a must-have for movie enthusiasts.',
                    'meta-keywords' => '',
                    'meta-title' => 'CineXperience Monthly Movie Pass',
                    'name' => 'כרטיס קולנוע',
                    'slug' => 'movie-pass',
                    'url-path' => 'wellness/movie-pass',
                ],

                '21' => [
                    'description' => '<p>הזמנות</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'הזמנות',
                    'slug' => 'bookings',
                    'url-path' => '',
                ],

                '22' => [
                    'description' => '<p>הזמנת תור</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'הזמנת תור',
                    'slug' => 'appointment-booking',
                    'url-path' => '',
                ],

                '23' => [
                    'description' => '<p>הזמנת אירוע</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'הזמנת אירוע',
                    'slug' => 'event-booking',
                    'url-path' => '',
                ],

                '24' => [
                    'description' => '<p>הזמנות אולם קהילתי</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'הזמנות אולם קהילתי',
                    'slug' => 'community-hall-bookings',
                    'url-path' => '',
                ],

                '25' => [
                    'description' => '<p>הזמנת שולחן</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'הזמנת שולחן',
                    'slug' => 'table-booking',
                    'url-path' => '',
                ],

                '26' => [
                    'description' => '<p>הזמנת השכרה</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'הזמנת השכרה',
                    'slug' => 'rental-booking',
                    'url-path' => '',
                ],

                '27' => [
                    'description' => '<p>אלקטרוניקה</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'אלקטרוניקה',
                    'slug' => 'electronics',
                    'url-path' => '',
                ],

                '28' => [
                    'description' => '<p>טלפונים ניידים ואביזרים</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'טלפונים ניידים ואביזרים',
                    'slug' => 'mobile-phones-accessories',
                    'url-path' => '',
                ],

                '29' => [
                    'description' => '<p>מחשבים ניידים וטאבלטים</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'מחשבים ניידים וטאבלטים',
                    'slug' => 'laptops-tablets',
                    'url-path' => '',
                ],

                '30' => [
                    'description' => '<p>מכשירי שמע</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'מכשירי שמע',
                    'slug' => 'audio-devices',
                    'url-path' => '',
                ],

                '31' => [
                    'description' => '<p>בית חכם ואוטומציה</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'בית חכם ואוטומציה',
                    'slug' => 'smart-home-automation',
                    'url-path' => '',
                ],

                '32' => [
                    'description' => '<p>בית</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'בית',
                    'slug' => 'household',
                    'url-path' => '',
                ],

                '33' => [
                    'description' => '<p>מכשירי מטבח</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'מכשירי מטבח',
                    'slug' => 'kitchen-appliances',
                    'url-path' => '',
                ],

                '34' => [
                    'description' => '<p>כלי בישול ואוכל</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'כלי בישול ואוכל',
                    'slug' => 'cookware-dining',
                    'url-path' => '',
                ],

                '35' => [
                    'description' => '<p>רהיטים ועיצוב</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'רהיטים ועיצוב',
                    'slug' => 'furniture-decor',
                    'url-path' => '',
                ],

                '36' => [
                    'description' => '<p>חומרי ניקיון</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'חומרי ניקיון',
                    'slug' => 'cleaning-supplies',
                    'url-path' => '',
                ],

                '37' => [
                    'description' => '<p>ספרים וציוד משרדי</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'ספרים וציוד משרדי',
                    'slug' => 'books-stationery',
                    'url-path' => '',
                ],

                '38' => [
                    'description' => '<p>ספרי בדיון ועיון</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'ספרי בדיון ועיון',
                    'slug' => 'fiction-non-fiction-books',
                    'url-path' => '',
                ],

                '39' => [
                    'description' => '<p>חינוכי ואקדמי</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'חינוכי ואקדמי',
                    'slug' => 'educational-academic',
                    'url-path' => '',
                ],

                '40' => [
                    'description' => '<p>ציוד משרדי</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'ציוד משרדי',
                    'slug' => 'office-supplies',
                    'url-path' => '',
                ],

                '41' => [
                    'description' => '<p>חומרי אומנות ויצירה</p>',
                    'meta-description' => '',
                    'meta-keywords' => '',
                    'meta-title' => '',
                    'name' => 'חומרי אומנות ויצירה',
                    'slug' => 'art-craft-materials',
                    'url-path' => '',
                ],
            ],
        ],
    ],

    'installer' => [
        'middleware' => [
            'already-installed' => 'האפליקציה כבר מותקנת.',
        ],

        'index' => [
            'create-administrator' => [
                'admin' => 'מנהל',
                'bagisto' => 'Bagisto',
                'confirm-password' => 'אשר סיסמה',
                'email' => 'אימייל',
                'email-address' => 'admin@example.com',
                'password' => 'סיסמה',
                'title' => 'צור מנהל',
            ],

            'environment-configuration' => [
                'algerian-dinar' => 'דינר אלגי (DZD)',
                'allowed-currencies' => 'מטבעות מותרים',
                'allowed-locales' => 'שפות מותרות',
                'application-name' => 'שם היישום',
                'argentine-peso' => 'פסו ארגנטינאי (ARS)',
                'australian-dollar' => 'דולר אוסטרלי (AUD)',
                'bagisto' => 'Bagisto',
                'bangladeshi-taka' => 'טאקה בנגלדשי (BDT)',
                'bahraini-dinar' => 'דינר בחרייני (BHD)',
                'brazilian-real' => 'ריאל ברזילאי (BRL)',
                'british-pound-sterling' => 'לירה שטרלינג בריטית (GBP)',
                'canadian-dollar' => 'דולר קנדי (CAD)',
                'cfa-franc-bceao' => 'פרנק CFA BCEAO (XOF)',
                'cfa-franc-beac' => 'פרנק CFA BEAC (XAF)',
                'chilean-peso' => 'פסו צ\'יליאני (CLP)',
                'chinese-yuan' => 'יואן סיני (CNY)',
                'colombian-peso' => 'פסו קולומביאני (COP)',
                'czech-koruna' => 'קורונה צ\'כית (CZK)',
                'danish-krone' => 'כתר דני (DKK)',
                'database-connection' => 'חיבור למסד נתונים',
                'database-hostname' => 'שם מארח מסד נתונים',
                'database-name' => 'שם מסד נתונים',
                'database-password' => 'סיסמת מסד נתונים',
                'database-port' => 'יציאת מסד נתונים',
                'database-prefix' => 'קידומת מסד נתונים',
                'database-prefix-help' => 'הקידומת צריכה להיות באורך 4 תווים ויכולה להכיל רק אותיות, מספרים ותווי קו תחתון.',
                'database-username' => 'שם משתמש מסד נתונים',
                'default-currency' => 'מטבע ברירת מחדל',
                'default-locale' => 'שפת ברירת מחדל',
                'default-timezone' => 'אזור זמן ברירת מחדל',
                'default-url' => 'כתובת URL ברירת מחדל',
                'default-url-link' => 'https://localhost',
                'egyptian-pound' => 'לירה מצרית (EGP)',
                'euro' => 'יורו (EUR)',
                'fijian-dollar' => 'דולר פיג\'י (FJD)',
                'hong-kong-dollar' => 'דולר הונג קונגי (HKD)',
                'hungarian-forint' => 'פורינט הונגרי (HUF)',
                'indian-rupee' => 'רופי הודי (INR)',
                'indonesian-rupiah' => 'רופיה אינדונזית (IDR)',
                'israeli-new-shekel' => 'ש"ח חדש ישראלי (ILS)',
                'japanese-yen' => 'ין יפני (JPY)',
                'jordanian-dinar' => 'דינר ירדני (JOD)',
                'kazakhstani-tenge' => 'טנגה קזחסטני (KZT)',
                'kuwaiti-dinar' => 'דינר כוויתי (KWD)',
                'lebanese-pound' => 'לירה לבנונית (LBP)',
                'libyan-dinar' => 'דינר לובי (LYD)',
                'malaysian-ringgit' => 'רינגיט מלזי (MYR)',
                'mauritian-rupee' => 'רופי מאוריציני (MUR)',
                'mexican-peso' => 'פסו מקסיקני (MXN)',
                'moroccan-dirham' => 'דירהם מרוקאי (MAD)',
                'mysql' => 'MySQL',
                'nepalese-rupee' => 'רופי נפאלי (NPR)',
                'new-taiwan-dollar' => 'דולר טייוואני חדש (TWD)',
                'new-zealand-dollar' => 'דולר ניו זילנדי (NZD)',
                'nigerian-naira' => 'נאירה ניגרית (NGN)',
                'norwegian-krone' => 'כתר נורבגי (NOK)',
                'omani-rial' => 'ריאל עומאני (OMR)',
                'pakistani-rupee' => 'רופי פקיסטני (PKR)',
                'panamanian-balboa' => 'בלבואה פנמה (PAB)',
                'paraguayan-guarani' => 'גוארני פרגוואי (PYG)',
                'peruvian-nuevo-sol' => 'סול פרואני חדש (PEN)',
                'pgsql' => 'pgSQL',
                'philippine-peso' => 'פסו פיליפיני (PHP)',
                'polish-zloty' => 'זלוטי פולני (PLN)',
                'qatari-rial' => 'ריאל קטארי (QAR)',
                'romanian-leu' => 'לאו רומני (RON)',
                'russian-ruble' => 'רובל רוסי (RUB)',
                'saudi-riyal' => 'ריאל סעודי (SAR)',
                'select-timezone' => 'בחר אזור זמן',
                'singapore-dollar' => 'דולר סינגפורי (SGD)',
                'south-african-rand' => 'ראנד דרום אפריקאי (ZAR)',
                'south-korean-won' => 'וון דרום קוריאני (KRW)',
                'sqlsrv' => 'SQLSRV',
                'sri-lankan-rupee' => 'רופי סרי לנקי (LKR)',
                'swedish-krona' => 'כתר שוודי (SEK)',
                'swiss-franc' => 'פרנק שוויצרי (CHF)',
                'thai-baht' => 'באת תאילנדי (THB)',
                'title' => 'תצורת החנות',
                'tunisian-dinar' => 'דינר טוניסאי (TND)',
                'turkish-lira' => 'לירה טורקית (TRY)',
                'ukrainian-hryvnia' => 'הריבניה האוקראינית (UAH)',
                'united-arab-emirates-dirham' => 'דירהם איחוד האמירויות הערביות (AED)',
                'united-states-dollar' => 'דולר אמריקאי (USD)',
                'uzbekistani-som' => 'סום אוזבקי (UZS)',
                'venezuelan-bolívar' => 'בוליבר ונצואלי (VEF)',
                'vietnamese-dong' => 'דונג וייטנאמי (VND)',
                'warning-message' => 'זהירות! ההגדרות של שפת המערכת והמטבע המוגדרות כברירת מחדל הן קבועות ואינן ניתנות לשינוי לאחר ההגדרה.',
                'zambian-kwacha' => 'קוואצ\'ה זמבית (ZMW)',
            ],

            'sample-products' => [
                'download-sample' => 'הורד דוגמה',
                'no' => 'לא',
                'sample-products' => 'מוצרי דוגמה',
                'title' => 'מוצרי דוגמה',
                'yes' => 'כן',
            ],

            'installation-processing' => [
                'bagisto' => 'התקנת Bagisto',
                'bagisto-info' => 'יצירת טבלאות מסד הנתונים, זה עשוי לקחת מספר רגעים',
                'title' => 'התקנה',
            ],

            'installation-completed' => [
                'admin-panel' => 'פנל מנהל המערכת',
                'bagisto-forums' => 'פורום Bagisto',
                'customer-panel' => 'פנל לקוח',
                'explore-bagisto-extensions' => 'גלה הרחבות Bagisto',
                'title' => 'התקנה הושלמה',
                'title-info' => 'Bagisto הותקן בהצלחה במערכת שלך.',
            ],

            'ready-for-installation' => [
                'create-database-table' => 'יצירת טבלת מסד הנתונים',
                'install' => 'התקנה',
                'install-info' => 'Bagisto להתקנה',
                'install-info-button' => 'לחץ על הכפתור למטה כדי',
                'populate-database-table' => 'מילוי הטבלאות במסד הנתונים',
                'start-installation' => 'התחל התקנה',
                'title' => 'מוכן להתקנה',
            ],

            'start' => [
                'locale' => 'אזור',
                'main' => 'הַתְחָלָה',
                'select-locale' => 'בחר אזור',
                'title' => 'התקנת Bagisto שלך',
                'welcome-title' => 'ברוך הבא ל-Bagisto',
            ],

            'server-requirements' => [
                'calendar' => 'לוח שנה',
                'ctype' => 'cType',
                'curl' => 'cURL',
                'dom' => 'DOM',
                'fileinfo' => 'FileInfo',
                'filter' => 'מסנן',
                'gd' => 'GD',
                'hash' => 'גיבוב',
                'intl' => 'Intl',
                'json' => 'JSON',
                'mbstring' => 'מחרוזת בת רב תווים',
                'openssl' => 'OpenSSL',
                'pcre' => 'PCRE',
                'pdo' => 'PDO',
                'php' => 'PHP',
                'php-version' => '8.1 או גבוהה יותר',
                'session' => 'Session',
                'title' => 'דרישות השרת',
                'tokenizer' => 'מפרק מחרוזת',
                'xml' => 'XML',
            ],

            'arabic' => 'ערבית',
            'back' => 'חזרה',
            'bagisto' => 'Bagisto',
            'bagisto-info' => 'פרויקט קהילתי על ידי',
            'bagisto-logo' => 'לוגו Bagisto',
            'bengali' => 'בנגלי',
            'catalan' => 'קטלאני',
            'chinese' => 'סיני',
            'continue' => 'המשך',
            'dutch' => 'הולנדי',
            'english' => 'אנגלית',
            'french' => 'צרפתי',
            'german' => 'גרמני',
            'hebrew' => 'עברית',
            'hindi' => 'הינדי',
            'indonesian' => 'אינדונזי',
            'installation-description' => 'התקנת Bagisto כוללת בדרך כלל מספר שלבים. הנה מתווה כללי של תהליך ההתקנה עבור Bagisto',
            'installation-info' => 'אנו שמחים לראותך כאן!',
            'installation-title' => 'ברוך הבא להתקנת Bagisto',
            'italian' => 'איטלקי',
            'japanese' => 'יפני',
            'persian' => 'פרסי',
            'polish' => 'פולני',
            'portuguese' => 'פורטוגזי ברזילאי',
            'russian' => 'רוסי',
            'sinhala' => 'סינהלה',
            'spanish' => 'ספרדית',
            'title' => 'מתקין Bagisto',
            'turkish' => 'טורקי',
            'ukrainian' => 'אוקראיני',
            'webkul' => 'Webkul',
        ],
    ],
];
