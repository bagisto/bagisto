<?php

return [
    'configuration' => [
        'index' => [
            'general' => [
                'two_factor_auth' => [
                    'title'    => 'Autentikasi Dua Faktor',
                    'info'     => 'Kelola pengaturan autentikasi dua faktor untuk pengguna admin.',

                    'settings' => [
                        'title'   => 'Pengaturan',
                        'info'    => 'Kelola autentikasi dua faktor untuk pengguna admin.',
                        'enabled' => 'Aktifkan Autentikasi Dua Faktor',
                    ],
                ],
            ],
        ],
    ],

    'emails' => [
        'backup-codes' => [
            'greeting'            => 'Anda telah berhasil mengaktifkan autentikasi dua faktor untuk akun admin Anda.',
            'description'         => 'Untuk keamanan Anda, kami telah membuat kode cadangan yang dapat Anda gunakan jika kehilangan akses ke aplikasi autentikator Anda. Setiap kode hanya bisa digunakan sekali.',
            'codes-title'         => 'Kode Cadangan Anda',
            'codes-subtitle'      => 'Simpan kode ini di tempat yang aman — masing-masing hanya dapat digunakan sekali.',
            'warning-title'       => 'Pemberitahuan Keamanan Penting',
            'warning-description' => 'Jaga kode ini tetap aman dan jangan dibagikan kepada siapa pun. Simpan secara offline di tempat yang aman.',
        ],
    ],

    'messages' => [
        'enabled_success'  => 'Autentikasi dua faktor berhasil diaktifkan.',
        'invalid_code'     => 'Kode verifikasi tidak valid.',
        'disabled_success' => 'Autentikasi dua faktor telah dinonaktifkan.',
        'verified_success' => 'Autentikasi dua faktor berhasil diverifikasi.',
        'email_failed'     => 'Gagal mengirim kode cadangan',
    ],

    'setup' => [
        'title'            => 'Aktifkan Autentikasi Dua Faktor',
        'scan_qr'          => 'Pindai kode QR ini di aplikasi Google Authenticator Anda, lalu masukkan kode 6 digit di bawah ini.',
        'code_label'       => 'Kode Verifikasi',
        'code_placeholder' => 'Masukkan kode 6 digit',
        'back'             => 'Kembali',
        'verify_enable'    => 'Verifikasi & Aktifkan',
    ],

    'verify' => [
        'title'                 => 'Verifikasi Autentikasi Dua Faktor',
        'enter_code'            => 'Masukkan kode 6 digit dari aplikasi autentikator Anda untuk melanjutkan.',
        'code_label'            => 'Kode Verifikasi',
        'code_placeholder'      => 'Masukkan kode 6 digit',
        'back'                  => 'Kembali',
        'verify_code'           => 'Verifikasi Kode',
        'disabled_message'      => 'Verifikasi autentikasi dua faktor saat ini dinonaktifkan oleh admin.',
    ],
];
