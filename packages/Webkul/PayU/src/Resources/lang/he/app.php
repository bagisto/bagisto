<?php

return [
    'description' => 'שלם בצורה מאובטחת באמצעות כרטיס אשראי, כרטיס חיוב, בנקאות מקוונת, UPI וארנקים דרך PayU',
    'title' => 'PayU',

    'redirect' => [
        'click-if-not-redirected' => 'לחץ כאן כדי להמשיך',
        'please-wait' => 'אנא המתן בזמן שאנו מעבירים אותך לשער התשלום...',
        'redirect-message' => 'אם לא הועברת אוטומטית, לחץ על הכפתור למטה.',
        'redirecting' => 'מעביר ל-PayU...',
        'redirecting-to-payment' => 'מעביר לתשלום PayU',
        'secure-payment' => 'שער תשלום מאובטח',
    ],

    'response' => [
        'cart-not-found' => 'העגלה לא נמצאה. אנא נסה שוב.',
        'hash-mismatch' => 'אימות התשלום נכשל. אי התאמה של Hash.',
        'invalid-transaction' => 'עסקה לא חוקית. אנא נסה שוב.',
        'order-creation-failed' => 'יצירת ההזמנה נכשלה. אנא צור קשר עם התמיכה.',
        'payment-already-processed' => 'התשלום כבר עובד.',
        'payment-cancelled' => 'התשלום בוטל. אתה יכול לנסות שוב.',
        'payment-failed' => 'התשלום נכשל. אנא נסה שוב.',
        'payment-success' => 'התשלום הושלם בהצלחה!',
        'provide-credentials' => 'אנא הגדר את מפתח הסוחר ו-Salt של PayU בפאנל הניהול.',
    ],
];
