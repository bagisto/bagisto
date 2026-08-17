<?php

return [
    'importers' => [
        'customers' => [
            'title' => '顧客',

            'validation' => [
                'errors' => [
                    'duplicate-email' => 'メールアドレス：\'%s\'はインポートファイル内で複数回見つかりました。',
                    'duplicate-phone' => '電話番号：\'%s\'はインポートファイル内で複数回見つかりました。',
                    'email-not-found' => 'メールアドレス：\'%s\'はシステム内で見つかりませんでした。',
                    'invalid-customer-group' => '顧客グループが無効またはサポートされていません',
                ],
            ],
        ],

        'products' => [
            'title' => '製品',

            'validation' => [
                'errors' => [
                    'duplicate-url-key' => 'URLキー：\'%s\'はSKU：\'%s\'を持つアイテムにすでに生成されています。',
                    'image-not-file' => '画像: \'%s\' はウェブアドレスですが、このインポートはファイルから画像を取得する設定です。画像ソースに「ファイル内の画像リンク」を選ぶか、値をファイル名に置き換えてください。',
                    'image-not-found' => '画像: \'%s\' は、このインポートが画像を探す場所に見つかりませんでした。',
                    'image-not-url' => '画像: \'%s\' はウェブアドレスではありませんが、このインポートはファイル内のリンクから画像を取得する設定です。別の画像ソースを選ぶか、値を完全な https:// アドレスに置き換えてください。',
                    'invalid-attribute-family' => '属性ファミリー列の値が無効です（属性ファミリーが存在しませんか？）',
                    'invalid-type' => '製品タイプが無効またはサポートされていません',
                    'sku-not-found' => '指定されたSKUの製品が見つかりません',
                    'super-attribute-not-found' => 'コード%s\'でスーパー属性が見つかりませんでした、または属性ファミリーに属していません: \'%s\'',
                ],
            ],
        ],

        'tax-rates' => [
            'title' => '税率',

            'validation' => [
                'errors' => [
                    'duplicate-identifier' => '識別子: \'%s\' はインポートファイルに複数回見つかりました。',
                    'identifier-not-found' => '識別子: \'%s\' がシステムに見つかりませんでした。',
                ],
            ],
        ],
    ],

    'validation' => [
        'errors' => [
            'column-empty-headers' => '列番号 "%s" のヘッダーが空です。',
            'column-name-invalid' => '無効な列名： "%s"。',
            'column-not-found' => '必要な列が見つかりません： %s。',
            'column-numbers' => '列数がヘッダーの行数に対応していません。',
            'invalid-attribute' => 'ヘッダーに無効な属性が含まれています： "%s"。',
            'more-issues' => 'ほか :count 件の問題 — 完全な一覧はレポート全体をダウンロードしてください。',
            'more-rows' => '(他 :count 行)',
            'system' => '予期しないシステムエラーが発生しました。',
            'wrong-quotes' => 'まっすぐな引用符の代わりにカーリー引用符が使用されています。',
        ],
    ],
];
