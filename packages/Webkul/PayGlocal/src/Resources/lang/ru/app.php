<?php

return [
    'description' => 'Безопасная оплата кредитной или дебетовой картой через PayGlocal.',
    'title' => 'PayGlocal',

    'response' => [
        'cart-not-found' => 'Корзина не найдена или недействительна.',
        'order-creation-failed' => 'Платеж прошел успешно, но заказ не удалось создать. Обратитесь в службу поддержки.',
        'payment-cancelled' => 'Платеж был отменен.',
        'payment-failed' => 'Платеж не прошел. Повторите попытку.',
        'payment-pending' => 'Ваш платеж PayGlocal все еще ожидает. Пожалуйста, завершите его или подождите немного и проверьте еще раз.',
        'payment-success' => 'Платеж успешно завершен.',
        'provide-credentials' => 'Укажите действительные учетные данные PayGlocal.',
        'supported-currency-error' => 'Валюта :currency не поддерживается. Поддерживаемые валюты: :supportedCurrencies.',
        'transaction-not-found' => 'Для этого платежа не найдено ни одной транзакции PayGlocal.',
        'verification-failed' => 'Не удалось подтвердить платеж.',
    ],
];
