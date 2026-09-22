<?php

return [
    'description' => 'ادفع بأمان باستخدام بطاقة الائتمان/الخصم الخاصة بك عبر Stripe.',
    'title' => 'Stripe',

    'line-items' => [
        'shipping' => 'الشحن',
        'tax' => 'الضريبة',
    ],

    'response' => [
        'cart-changed' => 'تغيرت سلتك بعد بدء الدفع، لذلك لم يتم إنشاء أي طلب. يرجى التواصل معنا بشأن دفعتك.',
        'cart-not-found' => 'السلة غير موجودة أو غير صالحة.',
        'cart-processed' => 'تمت معالجة هذه السلة بالفعل.',
        'invalid-session' => 'جلسة الدفع غير صالحة.',
        'payment-cancelled' => 'تم إلغاء الدفع.',
        'payment-failed' => 'فشل الدفع.',
        'payment-success' => 'تم الدفع بنجاح.',
        'provide-credentials' => 'يرجى تقديم بيانات اعتماد Stripe صالحة.',
        'session-invalid' => 'انتهت صلاحية جلسة الدفع أو غير صالحة.',
        'session-not-found' => 'لم يتم العثور على جلسة الدفع.',
        'verification-failed' => 'فشل التحقق من الدفع.',
    ],

    'webhook' => [
        'dispute-closed' => 'أُغلق نزاع Stripe رقم :id بالنتيجة: :status.',
        'dispute-opened' => 'اعترض العميل على :amount من هذه الدفعة في Stripe (:id)، والسبب: :reason.',
        'refund-recorded' => 'تم تسجيل استرداد بقيمة :amount تم في Stripe؛ إجمالي المبلغ المسترد في Stripe هو :total.',
    ],
];
