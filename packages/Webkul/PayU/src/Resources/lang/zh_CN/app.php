<?php

return [
    'description' => '通过PayU使用信用卡、借记卡、网上银行、UPI和钱包安全支付',
    'title'       => 'PayU',

    'redirect' => [
        'click-if-not-redirected' => '点击此处继续',
        'please-wait'             => '请稍候，我们正在将您重定向到支付网关...',
        'redirect-message'        => '如果您没有自动重定向，请点击下面的按钮。',
        'redirecting'             => '正在重定向到PayU...',
        'redirecting-to-payment'  => '正在重定向到PayU支付',
        'secure-payment'          => '安全支付网关',
    ],

    'response' => [
        'cart-not-found'            => '未找到购物车。请重试。',
        'hash-mismatch'             => '支付验证失败。哈希不匹配。',
        'invalid-transaction'       => '无效交易。请重试。',
        'order-creation-failed'     => '创建订单失败。请联系支持。',
        'payment-already-processed' => '支付已处理。',
        'payment-cancelled'         => '支付已取消。您可以重试。',
        'payment-failed'            => '支付失败。请重试。',
        'payment-success'           => '支付成功完成！',
        'provide-credentials'       => '请在管理面板中配置PayU商户密钥和Salt。',
    ],
];
