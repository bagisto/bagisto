<?php

return [
    'configuration' => [
        'index' => [
            'general' => [
                'two_factor_auth' => [
                    'title'    => "双因素认证",
                    'info'     => "管理管理员用户的双因素认证设置。",

                    'settings' => [
                        'title'   => "设置",
                        'info'    => "管理管理员用户的双因素认证。",
                        'enabled' => "启用双因素认证",
                    ],
                ],
            ],
        ],
    ],

    'messages' => [
        'enabled_success'  => "双因素认证已成功启用。",
        'invalid_code'     => "无效的验证码。",
        'disabled_success' => "双因素认证已被禁用。",
        'verified_success' => "双因素认证已成功验证。",
    ],

    'setup' => [
        'title'        => "启用双因素认证",
        'scan_qr'      => "在您的 Google Authenticator 应用中扫描此二维码，然后输入下面的6位验证码。",
        'code_label'   => "验证码",
        'code_placeholder' => "输入6位验证码",
        'back'         => "返回",
        'verify_enable'=> "验证并启用",
    ],

    'verify' => [
        'title'                 => "验证双因素认证",
        'enter_code'            => "请输入您的认证器应用中的6位验证码以继续。",
        'code_label'            => "验证码",
        'code_placeholder'      => "输入6位验证码",
        'back'                  => "返回",
        'verify_code'           => "验证代码",
        'disabled_message'      => "双因素认证验证当前已被管理员禁用。",
    ],
];
