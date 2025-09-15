<?php

return [
    'configuration' => [
        'index' => [
            'general' => [
                'two_factor_auth' => [
                    'title'    => '双因素认证',
                    'info'     => '管理管理员用户的双因素认证设置。',

                    'settings' => [
                        'title'   => '设置',
                        'info'    => '管理管理员用户的双因素认证。',
                        'enabled' => '启用双因素认证',
                    ],
                ],
            ],
        ],
    ],

    'emails' => [
        'backup-codes' => [
            'greeting'            => '您已成功为管理员账户启用双因素认证。',
            'description'         => '为了您的安全，我们生成了备用代码，如果您丢失访问认证器应用的权限，可使用这些代码。每个代码仅可使用一次。',
            'codes-title'         => '您的备用代码',
            'codes-subtitle'      => '请将这些代码保存在安全的地方 — 每个代码仅可使用一次。',
            'warning-title'       => '重要安全提示',
            'warning-description' => '请确保这些代码安全，勿与他人分享。离线保存在安全位置。',
        ],
    ],

    'messages' => [
        'enabled_success'  => '双因素认证已成功启用。',
        'invalid_code'     => '验证码无效。',
        'disabled_success' => '双因素认证已被禁用。',
        'verified_success' => '双因素认证已成功验证。',
        'email_failed'     => '发送备用代码失败',
    ],

    'setup' => [
        'title'            => '启用双因素认证',
        'scan_qr'          => '在您的 Google Authenticator 应用中扫描此二维码，然后输入下面的 6 位验证码。',
        'code_label'       => '验证码',
        'code_placeholder' => '输入 6 位验证码',
        'back'             => '返回',
        'verify_enable'    => '验证并启用',
    ],

    'verify' => [
        'title'                 => '验证双因素认证',
        'enter_code'            => '请输入认证器应用中的 6 位验证码以继续。',
        'code_label'            => '验证码',
        'code_placeholder'      => '输入 6 位验证码',
        'back'                  => '返回',
        'verify_code'           => '验证验证码',
        'disabled_message'      => '管理员当前已禁用双因素认证验证。',
    ],
];
