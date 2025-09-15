<?php

return [
    'configuration' => [
        'index' => [
            'general' => [
                'two_factor_auth' => [
                    'title'    => "二要素認証",
                    'info'     => "管理者ユーザー向けの二要素認証の設定を管理します。",

                    'settings' => [
                        'title'   => "設定",
                        'info'    => "管理者ユーザー向けの二要素認証を管理します。",
                        'enabled' => "二要素認証を有効にする",
                    ],
                ],
            ],
        ],
    ],

    'emails' => [
        'backup-codes' => [
            'greeting'            => "管理者アカウントの二要素認証を正常に有効化しました。",
            'description'         => "セキュリティのため、認証アプリにアクセスできなくなった場合に使用できるバックアップコードを生成しました。各コードは一度だけ使用可能です。",
            'codes-title'         => "バックアップコード",
            'codes-subtitle'      => "これらのコードを安全な場所に保管してください — 各コードは一度だけ使用可能です。",
            'warning-title'       => "重要なセキュリティ通知",
            'warning-description' => "これらのコードを安全に保管し、他人と共有しないでください。オフラインで安全な場所に保管してください。",
        ],
    ],

    'messages' => [
        'enabled_success'  => "二要素認証が正常に有効化されました。",
        'invalid_code'     => "無効な確認コードです。",
        'disabled_success' => "二要素認証が無効化されました。",
        'verified_success' => "二要素認証が正常に確認されました。",
        'email_failed'     => "バックアップコードの送信に失敗しました",
    ],

    'setup' => [
        'title'            => "二要素認証を有効にする",
        'scan_qr'          => "このQRコードをGoogle Authenticatorアプリでスキャンし、以下に6桁のコードを入力してください。",
        'code_label'       => "確認コード",
        'code_placeholder' => "6桁のコードを入力",
        'back'             => "戻る",
        'verify_enable'    => "確認して有効化",
    ],

    'verify' => [
        'title'                 => "二要素認証を確認",
        'enter_code'            => "続行するには、認証アプリから6桁のコードを入力してください。",
        'code_label'            => "確認コード",
        'code_placeholder'      => "6桁のコードを入力",
        'back'                  => "戻る",
        'verify_code'           => "コードを確認",
        'disabled_message'      => "二要素認証の確認は現在管理者によって無効化されています。",
    ],
];
