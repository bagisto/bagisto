<?php

return [
    'description' => 'ادفع بأمان باستخدام بطاقة الائتمان أو بطاقة الخصم أو الخدمات المصرفية عبر الإنترنت أو UPI والمحافظ عبر PayU',
    'title'       => 'PayU',

    'redirect' => [
        'click-if-not-redirected' => 'انقر هنا للمتابعة',
        'please-wait'             => 'يرجى الانتظار بينما نقوم بتوجيهك إلى بوابة الدفع...',
        'redirect-message'        => 'إذا لم يتم إعادة توجيهك تلقائيًا، انقر على الزر أدناه.',
        'redirecting'             => 'إعادة التوجيه إلى PayU...',
        'redirecting-to-payment'  => 'إعادة التوجيه إلى دفع PayU',
        'secure-payment'          => 'بوابة دفع آمنة',
    ],

    'response' => [
        'cart-not-found'            => 'لم يتم العثور على السلة. يرجى المحاولة مرة أخرى.',
        'hash-mismatch'             => 'فشل التحقق من الدفع. عدم تطابق التجزئة.',
        'invalid-transaction'       => 'معاملة غير صالحة. يرجى المحاولة مرة أخرى.',
        'order-creation-failed'     => 'فشل إنشاء الطلب. يرجى الاتصال بالدعم.',
        'payment-already-processed' => 'تمت معالجة الدفع بالفعل.',
        'payment-cancelled'         => 'تم إلغاء الدفع. يمكنك المحاولة مرة أخرى.',
        'payment-failed'            => 'فشل الدفع. يرجى المحاولة مرة أخرى.',
        'payment-success'           => 'تم الدفع بنجاح!',
        'provide-credentials'       => 'يرجى تكوين مفتاح التاجر وSalt في لوحة الإدارة.',
    ],
];
