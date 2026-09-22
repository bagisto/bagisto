<?php

return [
    'description' => 'পে সিকিউর উইথ ইউর ক্রেডিট/ডেবিট কার্ড ভায়া স্ট্রাইপ।',
    'title' => 'Stripe',

    'line-items' => [
        'shipping' => 'শিপিং',
        'tax' => 'কর',
    ],

    'response' => [
        'cart-changed' => 'পেমেন্ট শুরু হওয়ার পর আপনার কার্ট পরিবর্তিত হয়েছে, তাই কোনো অর্ডার দেওয়া হয়নি। আপনার পেমেন্ট সম্পর্কে আমাদের সাথে যোগাযোগ করুন।',
        'cart-not-found' => 'কার্ট পাওয়া যায়নি বা অবৈধ।',
        'cart-processed' => 'এই কার্টটি ইতিমধ্যে প্রসেস করা হয়েছে।',
        'invalid-session' => 'পেমেন্ট সেশন অবৈধ।',
        'payment-cancelled' => 'পেমেন্ট বাতিল করা হয়েছে।',
        'payment-failed' => 'পেমেন্ট ব্যর্থ।',
        'payment-success' => 'পেমেন্ট সফলভাবে সম্পন্ন হয়েছে।',
        'provide-credentials' => 'দয়া করে বৈধ Stripe ক্রেডেনশিয়াল প্রদান করুন।',
        'session-invalid' => 'পেমেন্ট সেশনের মেয়াদ শেষ বা অবৈধ।',
        'session-not-found' => 'পেমেন্ট সেশন পাওয়া যায়নি।',
        'verification-failed' => 'পেমেন্ট যাচাইকরণ ব্যর্থ।',
    ],

    'webhook' => [
        'dispute-closed' => 'Stripe বিরোধ :id বন্ধ হয়েছে, ফলাফল: :status।',
        'dispute-opened' => 'গ্রাহক Stripe-এ এই পেমেন্টের :amount নিয়ে বিরোধ করেছেন (:id), কারণ: :reason।',
        'refund-recorded' => 'Stripe-এ করা :amount রিফান্ড রেকর্ড করা হয়েছে; Stripe-এ মোট :total রিফান্ড হয়েছে।',
    ],
];
