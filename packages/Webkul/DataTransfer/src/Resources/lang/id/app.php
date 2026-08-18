<?php

return [
    'importers' => [
        'customers' => [
            'title' => 'Pelanggan',

            'validation' => [
                'errors' => [
                    'duplicate-email' => 'Email: \'%s\' ditemukan lebih dari satu kali dalam file impor.',
                    'duplicate-phone' => 'Nomor telepon: \'%s\' ditemukan lebih dari satu kali dalam file impor.',
                    'email-not-found' => 'Email: \'%s\' tidak ditemukan dalam sistem.',
                    'invalid-customer-group' => 'Grup pelanggan tidak valid atau tidak didukung.',
                ],
            ],
        ],

        'products' => [
            'title' => 'Produk',

            'validation' => [
                'errors' => [
                    'duplicate-url-key' => 'URL key: \'%s\' sudah digunakan oleh item dengan SKU: \'%s\'.',
                    'image-not-file' => 'Gambar: \'%s\' adalah alamat web, padahal impor ini disetel mengambil gambar dari berkas. Pilih \'Tautan gambar di dalam berkas\' sebagai sumber, atau ganti nilainya dengan nama berkas.',
                    'image-not-found' => 'Gambar: \'%s\' tidak ditemukan di tempat impor ini mengharapkan gambarnya.',
                    'image-not-url' => 'Gambar: \'%s\' bukan alamat web, padahal impor ini disetel mengambil gambar dari tautan di dalam berkas. Pilih sumber gambar lain, atau ganti nilainya dengan alamat https:// lengkap.',
                    'invalid-attribute-family' => 'Nilai pada kolom attribute family tidak valid (mungkin attribute family tidak ada?).',
                    'invalid-type' => 'Tipe produk tidak valid atau tidak didukung.',
                    'sku-not-found' => 'Produk dengan SKU yang disebutkan tidak ditemukan.',
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
            'column-name-invalid' => 'Nama kolom tidak valid: "%s".',
            'column-not-found' => 'Kolom yang dibutuhkan tidak ditemukan: %s.',
            'column-numbers' => 'Jumlah kolom tidak sesuai dengan jumlah baris pada header.',
            'invalid-attribute' => 'Header berisi atribut yang tidak valid: "%s".',
            'more-issues' => 'dan :count masalah lainnya — unduh laporan lengkap untuk daftar selengkapnya.',
            'more-rows' => '(+:count baris lagi)',
            'system' => 'Terjadi kesalahan sistem yang tidak terduga.',
            'wrong-quotes' => 'Tanda kutip melengkung digunakan, seharusnya tanda kutip lurus.',
        ],
    ],
];
