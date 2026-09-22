<?php

return [
    'description' => '通过 Stripe 安全地使用信用卡/借记卡付款。',
    'title' => 'Stripe',

    'line-items' => [
        'shipping' => '运费',
        'tax' => '税费',
    ],

    'response' => [
        'cart-changed' => '付款开始后您的购物车发生了变化，因此未能下单。请就您的付款联系我们。',
        'cart-not-found' => '购物车未找到或无效。',
        'cart-processed' => '此购物车已被处理。',
        'invalid-session' => '付款会话无效。',
        'payment-cancelled' => '付款已取消。',
        'payment-failed' => '付款失败。',
        'payment-success' => '付款成功完成。',
        'provide-credentials' => '请提供有效的 Stripe 凭据。',
        'session-invalid' => '付款会话已过期或无效。',
        'session-not-found' => '未找到付款会话。',
        'verification-failed' => '付款验证失败。',
    ],

    'webhook' => [
        'dispute-closed' => 'Stripe 争议 :id 已结束，结果：:status。',
        'dispute-opened' => '客户在 Stripe 中对此付款的 :amount 提出争议（:id），原因：:reason。',
        'refund-recorded' => '已记录在 Stripe 中进行的 :amount 退款；Stripe 中累计退款 :total。',
    ],
];
