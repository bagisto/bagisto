<?php

return [
    'description' => 'Stripe के माध्यम से अपने क्रेडिट/डेबिट कार्ड से सुरक्षित भुगतान करें।',
    'title' => 'Stripe',

    'line-items' => [
        'shipping' => 'शिपिंग',
        'tax' => 'कर',
    ],

    'response' => [
        'cart-changed' => 'भुगतान शुरू होने के बाद आपका कार्ट बदल गया, इसलिए कोई ऑर्डर नहीं बना। कृपया अपने भुगतान के बारे में हमसे संपर्क करें।',
        'cart-not-found' => 'कार्ट नहीं मिली या अमान्य है।',
        'cart-processed' => 'यह कार्ट पहले से प्रोसेस की गई है।',
        'invalid-session' => 'भुगतान सेशन अमान्य है।',
        'payment-cancelled' => 'भुगतान रद्द कर दिया गया।',
        'payment-failed' => 'भुगतान असफल।',
        'payment-success' => 'भुगतान सफलतापूर्वक पूरा हुआ।',
        'provide-credentials' => 'कृपया मान्य Stripe क्रेडेंशियल प्रदान करें।',
        'session-invalid' => 'भुगतान सेशन समाप्त हो गया या अमान्य है।',
        'session-not-found' => 'भुगतान सेशन नहीं मिला।',
        'verification-failed' => 'भुगतान सत्यापन असफल।',
    ],

    'webhook' => [
        'dispute-closed' => 'Stripe विवाद :id इस परिणाम के साथ बंद हुआ: :status।',
        'dispute-opened' => 'ग्राहक ने Stripe में इस भुगतान के :amount पर विवाद किया (:id), कारण: :reason।',
        'refund-recorded' => 'Stripe में किया गया :amount का रिफंड दर्ज किया गया; Stripe में कुल :total रिफंड हुआ है।',
    ],
];
