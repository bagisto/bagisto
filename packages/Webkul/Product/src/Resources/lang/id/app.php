<?php

return [
    'checkout' => [
        'cart' => [
            'integrity' => [
                'qty-missing'   => 'Setidaknya satu produk harus memiliki jumlah lebih dari 1.',
            ],

            'invalid-file-extension'   => 'Ekstensi file tidak valid terdeteksi.',
            'inventory-warning'        => 'Jumlah yang diminta tidak tersedia, silakan coba lagi nanti.',
            'missing-links'            => 'Tautan unduhan untuk produk ini tidak tersedia.',
            'missing-options'          => 'Opsi untuk produk ini belum dipilih.',
            'selected-products-simple' => 'Produk yang dipilih harus bertipe produk sederhana (simple product).',
        ],
    ],

    'datagrid' => [
        'copy-of-slug'                  => 'salinan-dari-:value',
        'copy-of'                       => 'Salinan dari :value',
        'variant-already-exist-message' => 'Varian dengan kombinasi atribut yang sama sudah ada.',
    ],

    'response' => [
        'product-can-not-be-copied' => 'Produk dengan tipe :type tidak bisa disalin.',
    ],

    'sort-by'  => [
        'options' => [
            'cheapest-first'  => 'Termurah dulu',
            'expensive-first' => 'Termahal dulu',
            'from-a-z'        => 'Dari A ke Z',
            'from-z-a'        => 'Dari Z ke A',
            'latest-first'    => 'Terbaru dulu',
            'oldest-first'    => 'Terlama dulu',
        ],
    ],

    'type'     => [
        'abstract'     => [
            'offers' => 'Beli :qty seharga :price per item dan hemat :discount',
        ],

        'bundle'       => 'Bundle',
        'booking'      => 'Pemesanan',
        'configurable' => 'Konfigurabel',
        'downloadable' => 'Dapat Diunduh',
        'grouped'      => 'Terkelompok',
        'simple'       => 'Sederhana',
        'virtual'      => 'Virtual',
    ],
];
