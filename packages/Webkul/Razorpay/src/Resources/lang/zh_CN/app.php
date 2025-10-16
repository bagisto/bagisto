<?php

return [
    'configuration' => [
        'checkout-title'   => 'Razorpay 结账',
        'client-id'        => '客户端 ID',
        'client-secret'    => '客户端密钥',
        'description'      => '描述',
        'info'             => 'Razorpay 是一个金融科技平台，帮助企业接受、处理和支付款项。',
        'merchant_desc'    => '交易描述（将在付款表单上显示）',
        'merchant_name'    => '商户名称（将在付款表单上显示）',
        'name'             => 'Razorpay',
        'production-only'  => '仅适用于生产环境。',
        'sandbox-only'     => '仅适用于沙盒环境。',
        'status'           => '状态',
        'test-mode-id'     => '测试模式客户端 ID',
        'test-mode-secret' => '测试模式客户端密钥',
        'title'            => '标题',
    ],

    'response' => [
        'credentials-missing'  => '缺少 Razorpay 凭据！',
        'error-message'        => '加载支付网关时出错。请再试一次。',
        'razorpay-cancelled'   => 'Razorpay 付款已被取消。',
        'something-went-wrong' => '出现错误',
    ],
];