<?php

return [
    'validations' => [
        'allowed-country' => [
            'not-allowed' => 'Toko ini tidak menerima alamat di negara yang dipilih.',
        ],

        'captcha' => [
            'captcha' => 'Terjadi kesalahan! Silakan coba lagi.',
            'required' => 'Silakan pilih CAPTCHA.',
        ],

        'vat-id' => [
            'invalid-format' => 'Format NPWP tidak valid.',
        ],
    ],
];
