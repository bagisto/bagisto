<?php

return [
    'description' => 'Stripe を通じてクレジット/デビットカードで安全にお支払いください。',
    'title' => 'Stripe',

    'line-items' => [
        'shipping' => '配送料',
        'tax' => '税金',
    ],

    'response' => [
        'cart-changed' => '支払い開始後にカートが変更されたため、注文は作成されませんでした。お支払いについてお問い合わせください。',
        'cart-not-found' => 'カートが見つからないか無効です。',
        'cart-processed' => 'このカートは既に処理されています。',
        'invalid-session' => '支払いセッションが無効です。',
        'payment-cancelled' => '支払いがキャンセルされました。',
        'payment-failed' => '支払いに失敗しました。',
        'payment-success' => '支払いが正常に完了しました。',
        'provide-credentials' => '有効な Stripe 認証情報を提供してください。',
        'session-invalid' => '支払いセッションが期限切れまたは無効です。',
        'session-not-found' => '支払いセッションが見つかりません。',
        'verification-failed' => '支払い検証に失敗しました。',
    ],

    'webhook' => [
        'dispute-closed' => 'Stripe の不審請求の申し立て :id は次の結果で終了しました: :status。',
        'dispute-opened' => '顧客が Stripe でこの支払いの :amount に異議を申し立てました（:id）。理由: :reason。',
        'refund-recorded' => 'Stripe で行われた :amount の返金を記録しました。Stripe での返金額は合計 :total です。',
    ],
];
