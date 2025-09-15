<?php

return [
    'configuration' => [
        'index' => [
            'general' => [
                'two_factor_auth' => [
                    'title'    => 'İki Faktörlü Kimlik Doğrulama',
                    'info'     => 'Yönetici kullanıcılar için iki faktörlü kimlik doğrulama ayarlarını yönetin.',

                    'settings' => [
                        'title'   => 'Ayarlar',
                        'info'    => 'Yönetici kullanıcılar için iki faktörlü kimlik doğrulamayı yönetin.',
                        'enabled' => 'İki Faktörlü Kimlik Doğrulamayı Etkinleştir',
                    ],
                ],
            ],
        ],
    ],

    'emails' => [
        'backup-codes' => [
            'greeting'            => 'Yönetici hesabınız için iki faktörlü kimlik doğrulamayı başarıyla etkinleştirdiniz.',
            'description'         => 'Güvenliğiniz için, doğrulayıcı uygulamanıza erişimi kaybederseniz kullanabileceğiniz yedek kodlar oluşturduk. Her kod yalnızca bir kez kullanılabilir.',
            'codes-title'         => 'Yedek Kodlarınız',
            'codes-subtitle'      => 'Bu kodları güvenli bir yerde saklayın — her kod yalnızca bir kez kullanılabilir.',
            'warning-title'       => 'Önemli Güvenlik Bildirimi',
            'warning-description' => 'Bu kodları güvende tutun ve kimseyle paylaşmayın. Kodları çevrimdışı güvenli bir yerde saklayın.',
        ],
    ],

    'messages' => [
        'enabled_success'  => 'İki faktörlü kimlik doğrulama başarıyla etkinleştirildi.',
        'invalid_code'     => 'Geçersiz doğrulama kodu.',
        'disabled_success' => 'İki faktörlü kimlik doğrulama devre dışı bırakıldı.',
        'verified_success' => 'İki faktörlü kimlik doğrulama başarıyla doğrulandı.',
        'email_failed'     => 'Yedek kodlar gönderilemedi',
    ],

    'setup' => [
        'title'            => 'İki Faktörlü Kimlik Doğrulamayı Etkinleştir',
        'scan_qr'          => 'Google Authenticator uygulamanızda bu QR kodunu tarayın, ardından aşağıya 6 haneli kodu girin.',
        'code_label'       => 'Doğrulama Kodu',
        'code_placeholder' => '6 haneli kodu girin',
        'back'             => 'Geri',
        'verify_enable'    => 'Doğrula & Etkinleştir',
    ],

    'verify' => [
        'title'                 => 'İki Faktörlü Kimlik Doğrulamayı Doğrula',
        'enter_code'            => 'Devam etmek için doğrulayıcı uygulamanızdan 6 haneli kodu girin.',
        'code_label'            => 'Doğrulama Kodu',
        'code_placeholder'      => '6 haneli kodu girin',
        'back'                  => 'Geri',
        'verify_code'           => 'Kodu Doğrula',
        'disabled_message'      => 'İki faktörlü kimlik doğrulama şu anda yönetici tarafından devre dışı bırakıldı.',
    ],
];
