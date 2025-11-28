<?php

return [
    'description' => 'Stripe を通じてクレジット/デビットカードで安全にお支払いください。',
    'title'       => 'Stripe',

    'response' => [
        'cart-not-found'      => 'カートが見つからないか無効です。',
        'cart-processed'      => 'このカートは既に処理されています。',
        'invalid-session'     => '支払いセッションが無効です。',
        'payment-cancelled'   => '支払いがキャンセルされました。',
        'payment-failed'      => '支払いに失敗しました。',
        'payment-success'     => '支払いが正常に完了しました。',
        'provide-credentials' => '有効な Stripe 認証情報を提供してください。',
        'session-invalid'     => '支払いセッションが期限切れまたは無効です。',
        'session-not-found'   => '支払いセッションが見つかりません。',
        'verification-failed' => '支払い検証に失敗しました。',
    ],
];
