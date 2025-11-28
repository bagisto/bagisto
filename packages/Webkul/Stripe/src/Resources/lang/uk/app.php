<?php

return [
    'description' => 'Безпечно платіть кредитною/дебетовою карткою через Stripe.',
    'title'       => 'Stripe',

    'response' => [
        'cart-not-found'      => 'Кошик не знайдено або недійсний.',
        'cart-processed'      => 'Цей кошик вже оброблено.',
        'invalid-session'     => 'Сесія платежу недійсна.',
        'payment-cancelled'   => 'Платіж скасовано.',
        'payment-failed'      => 'Платіж не вдався.',
        'payment-success'     => 'Платіж успішно завершено.',
        'provide-credentials' => 'Будь ласка, надайте дійсні облікові дані Stripe.',
        'session-invalid'     => 'Термін дії сесії платежу закінчився або недійсна.',
        'session-not-found'   => 'Сесія платежу не знайдена.',
        'verification-failed' => 'Перевірка платежу не вдалася.',
    ],
];
