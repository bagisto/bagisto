<?php

return [
    'configuration' => [
        'index' => [
            'general' => [
                'two_factor_auth' => [
                    'title'    => "二段階認証",
                    'info'     => "管理者ユーザーの二段階認証設定を管理します。",

                    'settings' => [
                        'title'   => "設定",
                        'info'    => "管理者ユーザーの二段階認証を管理します。",
                        'enabled' => "二段階認証を有効にする",
                    ],
                ],
            ],
        ],
    ],

    'messages' => [
        'enabled_success'  => "二段階認証が正常に有効化されました。",
        'invalid_code'     => "無効な確認コードです。",
        'disabled_success' => "二段階認証が無効化されました。",
        'verified_success' => "二段階認証が正常に確認されました。",
    ],

    'setup' => [
        'title'        => "二段階認証を有効にする",
        'scan_qr'      => "Google AuthenticatorアプリでこのQRコードをスキャンし、以下に6桁のコードを入力してください。",
        'code_label'   => "確認コード",
        'code_placeholder' => "6桁のコードを入力してください",
        'back'         => "戻る",
        'verify_enable'=> "確認して有効化",
    ],

    'verify' => [
        'title'                 => "二段階認証を確認",
        'enter_code'            => "続行するには、Authenticatorアプリから6桁のコードを入力してください。",
        'code_label'            => "確認コード",
        'code_placeholder'      => "6桁のコードを入力してください",
        'back'                  => "戻る",
        'verify_code'           => "コードを確認",
        'disabled_message'      => "二段階認証の確認は現在、管理者によって無効化されています。",
    ],
];
