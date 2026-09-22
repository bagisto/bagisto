<?php

return [
    'description' => 'Безопасно платите кредитной/дебетовой картой через Stripe.',
    'title' => 'Stripe',

    'line-items' => [
        'shipping' => 'Доставка',
        'tax' => 'Налог',
    ],

    'response' => [
        'cart-changed' => 'Ваша корзина изменилась после начала оплаты, поэтому заказ не был оформлен. Свяжитесь с нами по поводу оплаты.',
        'cart-not-found' => 'Корзина не найдена или недействительна.',
        'cart-processed' => 'Эта корзина уже была обработана.',
        'invalid-session' => 'Сессия платежа недействительна.',
        'payment-cancelled' => 'Платеж был отменен.',
        'payment-failed' => 'Платеж не удался.',
        'payment-success' => 'Платеж успешно завершен.',
        'provide-credentials' => 'Пожалуйста, предоставьте действительные учетные данные Stripe.',
        'session-invalid' => 'Сессия платежа истекла или недействительна.',
        'session-not-found' => 'Сессия платежа не найдена.',
        'verification-failed' => 'Проверка платежа не удалась.',
    ],

    'webhook' => [
        'dispute-closed' => 'Спор Stripe :id закрыт с результатом: :status.',
        'dispute-opened' => 'Покупатель оспорил :amount этого платежа в Stripe (:id), причина: :reason.',
        'refund-recorded' => 'Записан возврат :amount, выполненный в Stripe; всего в Stripe возвращено :total.',
    ],
];
