<?php

return [
    'description' => 'Безпечно платіть кредитною/дебетовою карткою через Stripe.',
    'title' => 'Stripe',

    'line-items' => [
        'shipping' => 'Доставка',
        'tax' => 'Податок',
    ],

    'response' => [
        'cart-changed' => 'Ваш кошик змінився після початку оплати, тому замовлення не створено. Зв\'яжіться з нами щодо оплати.',
        'cart-not-found' => 'Кошик не знайдено або недійсний.',
        'cart-processed' => 'Цей кошик вже оброблено.',
        'invalid-session' => 'Сесія платежу недійсна.',
        'payment-cancelled' => 'Платіж скасовано.',
        'payment-failed' => 'Платіж не вдався.',
        'payment-success' => 'Платіж успішно завершено.',
        'provide-credentials' => 'Будь ласка, надайте дійсні облікові дані Stripe.',
        'session-invalid' => 'Термін дії сесії платежу закінчився або недійсна.',
        'session-not-found' => 'Сесія платежу не знайдена.',
        'verification-failed' => 'Перевірка платежу не вдалася.',
    ],

    'webhook' => [
        'dispute-closed' => 'Спір Stripe :id закрито з результатом: :status.',
        'dispute-opened' => 'Покупець оскаржив :amount цього платежу в Stripe (:id), причина: :reason.',
        'refund-recorded' => 'Записано повернення :amount, виконане в Stripe; загалом у Stripe повернуто :total.',
    ],
];
