<?php

return [
    'configuration' => [
        'index' => [
            'general' => [
                'two_factor_auth' => [
                    'title'    => "אימות דו-שלבי",
                    'info'     => "נהל את הגדרות האימות הדו-שלבי עבור משתמשי מנהל.",

                    'settings' => [
                        'title'   => "הגדרות",
                        'info'    => "נהל את האימות הדו-שלבי עבור משתמשי מנהל.",
                        'enabled' => "הפעל אימות דו-שלבי",
                    ],
                ],
            ],
        ],
    ],

    'messages' => [
        'enabled_success'  => "האימות הדו-שלבי הופעל בהצלחה.",
        'invalid_code'     => "קוד אימות לא חוקי.",
        'disabled_success' => "האימות הדו-שלבי הושבת.",
        'verified_success' => "האימות הדו-שלבי אומת בהצלחה.",
    ],

    'setup' => [
        'title'        => "הפעל אימות דו-שלבי",
        'scan_qr'      => "סרוק את קוד ה-QR הזה באפליקציית Google Authenticator שלך, ולאחר מכן הזן את הקוד בן 6 הספרות למטה.",
        'code_label'   => "קוד אימות",
        'code_placeholder' => "הזן את הקוד בן 6 הספרות",
        'back'         => "חזרה",
        'verify_enable'=> "אמת והפעל",
    ],

    'verify' => [
        'title'                 => "אמת את האימות הדו-שלבי",
        'enter_code'            => "הזן את הקוד בן 6 הספרות מהאפליקציה שלך כדי להמשיך.",
        'code_label'            => "קוד אימות",
        'code_placeholder'      => "הזן את הקוד בן 6 הספרות",
        'back'                  => "חזרה",
        'verify_code'           => "אמת קוד",
        'disabled_message'      => "אימות דו-שלבי כרגע מושבת על ידי המנהל.",
    ],
];
