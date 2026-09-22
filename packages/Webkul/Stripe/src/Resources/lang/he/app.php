<?php

return [
    'description' => 'שלמו בבטח> = עם כרטיס האשראי/החיוב שלכם דרך Stripe.',
    'title' => 'Stripe',

    'line-items' => [
        'shipping' => 'משלוח',
        'tax' => 'מס',
    ],

    'response' => [
        'cart-changed' => 'העגלה שלך השתנתה לאחר תחילת התשלום, ולכן לא בוצעה הזמנה. אנא צרו איתנו קשר לגבי התשלום.',
        'cart-not-found' => 'עגלת הקניות לא נמצאה  לא תקפה.',
        'cart-processed' => 'עג הקניות הזו כבר עובדה.',
        'invalid-session' => 'הפעלת התשלום לא תקפה.',
        'payment-cancelled' => 'התשלום בוטל.',
        'payment-failed' => 'התשלום נכשל.',
        'payment-success' => 'התשלום הושלם בהצלחה.',
        'provide-credentials' => 'אנא ספקו פרטי אימות תקפים  Stripe.',
        'session-invalid' => 'הפע התשלום פגה או לא תקפה.',
        'session-not-found' => 'הפעלת התשלום לא נמצאה.',
        'verification-failed' => 'אימות התשלום נכשל.',
    ],

    'webhook' => [
        'dispute-closed' => 'המחלוקת :id ב-Stripe נסגרה עם התוצאה: :status.',
        'dispute-opened' => 'הלקוח חלק על :amount מתשלום זה ב-Stripe ‏(:id), מהסיבה: :reason.',
        'refund-recorded' => 'נרשם החזר של :amount שבוצע ב-Stripe; בסך הכול הוחזרו ב-Stripe ‏:total.',
    ],
];
