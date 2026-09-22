<?php

return [
    'description' => 'Stripe හරහා ඔබේ ක්‍රෙඩිට්/ඩෙබිට් කාඩ්පතෙන් ආරක්ෂිතව ගෙවන්න.',
    'title' => 'Stripe',

    'line-items' => [
        'shipping' => 'නැව්ගත කිරීම',
        'tax' => 'බද්ද',
    ],

    'response' => [
        'cart-changed' => 'ගෙවීම ආරම්භ කළ පසු ඔබේ කරත්තය වෙනස් වූ බැවින් ඇණවුමක් ඉදිරිපත් නොකෙරිණි. කරුණාකර ඔබේ ගෙවීම ගැන අප අමතන්න.',
        'cart-not-found' => 'කරත්තය හමු නොවීම හෝ වලංගු නොවීම.',
        'cart-processed' => 'මෙම කරත්තය ඒ වනවිටම සකස් කර ඇත.',
        'invalid-session' => 'ගෙවීම් සැසිය වලංගු නොවේ.',
        'payment-cancelled' => 'ගෙවීම අවලංගු කර ඇත.',
        'payment-failed' => 'ගෙවීම අසාර්ථක විය.',
        'payment-success' => 'ගෙවීම සාර්ථකව සම්පූර්ණ කරන ලදී.',
        'provide-credentials' => 'කරුණාකර වලංගු Stripe අක්තපත්‍ර සපයන්න.',
        'session-invalid' => 'ගෙවීම් සැසිය කල්ඉකුත් හෝ වලංගු නොවේ.',
        'session-not-found' => 'ගෙවීම් සැසිය හමු නොවීය.',
        'verification-failed' => 'ගෙවීම් සත්‍යාපනය අසාර්ථක විය.',
    ],

    'webhook' => [
        'dispute-closed' => 'Stripe ආරවුල :id මෙම ප්‍රතිඵලය සමඟ වසා ඇත: :status.',
        'dispute-opened' => 'පාරිභෝගිකයා Stripe හි මෙම ගෙවීමේ :amount ට විරුද්ධ විය (:id), හේතුව: :reason.',
        'refund-recorded' => 'Stripe හි කළ :amount ආපසු ගෙවීම වාර්තා කරන ලදී; Stripe හි මුළු ආපසු ගෙවීම :total කි.',
    ],
];
