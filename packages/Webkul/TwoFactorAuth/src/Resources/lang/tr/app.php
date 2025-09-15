<?php

return [
    'configuration' => [
        'index' => [
            'general' => [
                'two_factor_auth' => [
                    'title'    => "İki Faktörlü Kimlik Doğrulama",
                    'info'     => "Yönetici kullanıcılar için iki faktörlü kimlik doğrulama ayarlarını yönetin.",

                    'settings' => [
                        'title'   => "Ayarlar",
                        'info'    => "Yönetici kullanıcılar için iki faktörlü kimlik doğrulamayı yönetin.",
                        'enabled' => "İki faktörlü kimlik doğrulamayı etkinleştir",
                    ],
                ],
            ],
        ],
    ],

    'messages' => [
        'enabled_success'  => "İki faktörlü kimlik doğrulama başarıyla etkinleştirildi.",
        'invalid_code'     => "Geçersiz doğrulama kodu.",
        'disabled_success' => "İki faktörlü kimlik doğrulama devre dışı bırakıldı.",
        'verified_success' => "İki faktörlü kimlik doğrulama başarıyla doğrulandı.",
    ],

    'setup' => [
        'title'        => "İki faktörlü kimlik doğrulamayı etkinleştir",
        'scan_qr'      => "Google Authenticator uygulamanızla bu QR kodunu tarayın ve ardından aşağıya 6 haneli kodu girin.",
        'code_label'   => "Doğrulama Kodu",
        'code_placeholder' => "6 haneli kodu girin",
        'back'         => "Geri",
        'verify_enable'=> "Doğrula ve Etkinleştir",
    ],

    'verify' => [
        'title'                 => "İki faktörlü kimlik doğrulamayı doğrula",
        'enter_code'            => "Devam etmek için doğrulayıcı uygulamanızdan 6 haneli kodu girin.",
        'code_label'            => "Doğrulama Kodu",
        'code_placeholder'      => "6 haneli kodu girin",
        'back'                  => "Geri",
        'verify_code'           => "Kodu Doğrula",
        'disabled_message'      => "İki faktörlü kimlik doğrulama şu anda yönetici tarafından devre dışı bırakılmıştır.",
    ],
];
