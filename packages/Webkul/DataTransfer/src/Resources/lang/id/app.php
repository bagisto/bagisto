<?php

return [
    'importers' => [
        'customers' => [
            'title' => 'Pelanggan',

            'validation' => [
                'errors' => [
                    'duplicate-email'        => 'Email: \'%s\' ditemukan lebih dari satu kali dalam file impor.',
                    'duplicate-phone'        => 'Nomor telepon: \'%s\' ditemukan lebih dari satu kali dalam file impor.',
                    'email-not-found'        => 'Email: \'%s\' tidak ditemukan dalam sistem.',
                    'invalid-customer-group' => 'Grup pelanggan tidak valid atau tidak didukung.',
                ],
            ],
        ],

        'products' => [
            'title' => 'Produk',

            'validation' => [
                'errors' => [
                    'duplicate-url-key'         => 'URL key: \'%s\' sudah digunakan oleh item dengan SKU: \'%s\'.',
                    'invalid-attribute-family'  => 'Nilai pada kolom attribute family tidak valid (mungkin attribute family tidak ada?).',
                    'invalid-type'              => 'Tipe produk tidak valid atau tidak didukung.',
                    'sku-not-found'             => 'Produk dengan SKU yang disebutkan tidak ditemukan.',
                    'super-attribute-not-found' => 'Super attribute dengan kode: \'%s\' tidak ditemukan atau tidak termasuk dalam attribute family: \'%s\'.',
                ],
            ],
        ],

        'tax-rates' => [
            'title' => 'Tarif Pajak',

            'validation' => [
                'errors' => [
                    'duplicate-identifier' => 'Identifier: \'%s\' ditemukan lebih dari satu kali dalam file impor.',
                    'identifier-not-found' => 'Identifier: \'%s\' tidak ditemukan dalam sistem.',
                ],
            ],
        ],
    ],

    'validation' => [
        'errors' => [
            'column-empty-headers' => 'Kolom nomor "%s" memiliki header yang kosong.',
            'column-name-invalid'  => 'Nama kolom tidak valid: "%s".',
            'column-not-found'     => 'Kolom yang dibutuhkan tidak ditemukan: %s.',
            'column-numbers'       => 'Jumlah kolom tidak sesuai dengan jumlah baris pada header.',
            'invalid-attribute'    => 'Header berisi atribut yang tidak valid: "%s".',
            'system'               => 'Terjadi kesalahan sistem yang tidak terduga.',
            'wrong-quotes'         => 'Tanda kutip melengkung digunakan, seharusnya tanda kutip lurus.',
        ],
    ],
];
