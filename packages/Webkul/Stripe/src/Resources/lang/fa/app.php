<?php

return [
    'description' => 'پرداخت امن با کارت اعتباری/نقدی خود از طریق Stripe.',
    'title' => 'Stripe',

    'line-items' => [
        'shipping' => 'ارسال',
        'tax' => 'مالیات',
    ],

    'response' => [
        'cart-changed' => 'سبد خرید شما پس از شروع پرداخت تغییر کرد، بنابراین سفارشی ثبت نشد. لطفاً درباره پرداخت خود با ما تماس بگیرید.',
        'cart-not-found' => 'سبد خرید یافت نشد یا نامعتبر است.',
        'cart-processed' => 'این سبد خرید قبلاً پردازش شده است.',
        'invalid-session' => 'جلسه پرداخت نامعتبر است.',
        'payment-cancelled' => 'پرداخت لغو شد.',
        'payment-failed' => 'پرداخت ناموفق.',
        'payment-success' => 'پرداخت با موفقیت تکمیل شد.',
        'provide-credentials' => 'لطفاً اعتبارنامه‌های معتبر Stripe ارائه دهید.',
        'session-invalid' => 'جلسه پرداخت منقضی شده یا نامعتبر است.',
        'session-not-found' => 'جلسه پرداخت یافت نشد.',
        'verification-failed' => 'تأیید پرداخت ناموفق بود.',
    ],

    'webhook' => [
        'dispute-closed' => 'اختلاف Stripe با شناسه :id با نتیجه :status بسته شد.',
        'dispute-opened' => 'مشتری به :amount از این پرداخت در Stripe اعتراض کرد (:id)، به دلیل: :reason.',
        'refund-recorded' => 'بازپرداخت :amount انجام‌شده در Stripe ثبت شد؛ در مجموع :total در Stripe بازپرداخت شده است.',
    ],
];
