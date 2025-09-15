<?php

return [
    'configuration' => [
        'index' => [
            'general' => [
                'two_factor_auth' => [
                    'title'    => "Autentikasi Dua Faktor",
                    'info'     => "Kelola pengaturan autentikasi dua faktor untuk pengguna admin.",

                    'settings' => [
                        'title'   => "Pengaturan",
                        'info'    => "Kelola autentikasi dua faktor untuk pengguna admin.",
                        'enabled' => "Aktifkan autentikasi dua faktor",
                    ],
                ],
            ],
        ],
    ],

    'messages' => [
        'enabled_success'  => "Autentikasi dua faktor berhasil diaktifkan.",
        'invalid_code'     => "Kode verifikasi tidak valid.",
        'disabled_success' => "Autentikasi dua faktor telah dinonaktifkan.",
        'verified_success' => "Autentikasi dua faktor berhasil diverifikasi.",
    ],

    'setup' => [
        'title'        => "Aktifkan autentikasi dua faktor",
        'scan_qr'      => "Pindai kode QR ini di aplikasi Google Authenticator Anda, lalu masukkan kode 6 digit di bawah ini.",
        'code_label'   => "Kode Verifikasi",
        'code_placeholder' => "Masukkan kode 6 digit",
        'back'         => "Kembali",
        'verify_enable'=> "Verifikasi & Aktifkan",
    ],

    'verify' => [
        'title'                 => "Verifikasi autentikasi dua faktor",
        'enter_code'            => "Masukkan kode 6 digit dari aplikasi autentikator Anda untuk melanjutkan.",
        'code_label'            => "Kode Verifikasi",
        'code_placeholder'      => "Masukkan kode 6 digit",
        'back'                  => "Kembali",
        'verify_code'           => "Verifikasi Kode",
        'disabled_message'      => "Verifikasi autentikasi dua faktor saat ini dinonaktifkan oleh admin.",
    ],
];
