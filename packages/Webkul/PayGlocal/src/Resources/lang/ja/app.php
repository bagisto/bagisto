<?php

return [
    'description' => 'PayGlocal でクレジットカードまたはデビットカードを使用して安全に支払います。',
    'title' => 'PayGlocal',

    'response' => [
        'cart-not-found' => 'カートが見つからないか無効です。',
        'order-creation-failed' => '支払いは成功しましたが、注文を作成できませんでした。サポートにお問い合わせください。',
        'payment-cancelled' => '支払いはキャンセルされました。',
        'payment-failed' => '支払いに失敗しました。もう一度お試しください。',
        'payment-pending' => 'PayGlocalの支払いはまだ保留中です。 完了するか、少し待ってもう一度確認してください。',
        'payment-success' => '支払いが正常に完了しました。',
        'provide-credentials' => '有効な PayGlocal の認証情報を入力してください。',
        'supported-currency-error' => '通貨 :currency はサポートされていません。サポートされている通貨: :supportedCurrencies.',
        'transaction-not-found' => 'この支払いに対する PayGlocal の取引が見つかりませんでした。',
        'verification-failed' => '支払いを検証できませんでした。',
    ],
];
