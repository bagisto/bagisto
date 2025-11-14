<?php

return [
    'description' => 'PayU経由でクレジットカード、デビットカード、ネットバンキング、UPI、ウォレットを使用して安全にお支払いください',
    'title'       => 'PayU Money',

    'redirect' => [
        'click-if-not-redirected' => '続行するにはここをクリックしてください',
        'please-wait'             => '支払いゲートウェイにリダイレクトしていますので、お待ちください...',
        'redirect-message'        => '自動的にリダイレクトされない場合は、下のボタンをクリックしてください。',
        'redirecting'             => 'PayUにリダイレクトしています...',
        'redirecting-to-payment'  => 'PayU支払いにリダイレクトしています',
        'secure-payment'          => '安全な支払いゲートウェイ',
    ],

    'response' => [
        'cart-not-found'            => 'カートが見つかりません。もう一度お試しください。',
        'hash-mismatch'             => '支払いの検証に失敗しました。ハッシュの不一致。',
        'invalid-transaction'       => '無効なトランザクションです。もう一度お試しください。',
        'order-creation-failed'     => '注文の作成に失敗しました。サポートにお問い合わせください。',
        'payment-already-processed' => '支払いはすでに処理されています。',
        'payment-cancelled'         => '支払いがキャンセルされました。もう一度お試しいただけます。',
        'payment-failed'            => '支払いに失敗しました。もう一度お試しください。',
        'payment-success'           => '支払いが正常に完了しました！',
        'provide-credentials'       => '管理パネルでPayUマーチャントキーとSaltを設定してください。',
    ],
];
