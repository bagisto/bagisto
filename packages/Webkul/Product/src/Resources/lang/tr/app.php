<?php

return [
    'checkout' => [
        'cart' => [
            'integrity' => [
                'qty-missing'   => 'En az bir ürünün miktarı 1\'den fazla olmalıdır.',
            ],

            'inventory-warning'        => 'İstenen miktar mevcut değil, lütfen daha sonra tekrar deneyin.',
            'missing-links'            => 'Bu ürün için indirilebilir bağlantılar eksik.',
            'missing-options'          => 'Bu ürün için seçenekler eksik.',
            'selected-products-simple' => 'Seçilen ürünler basit ürün türünde olmalıdır.',
        ],
    ],

    'datagrid' => [
        'copy-of-slug'                  => ':value\'in kopyası',
        'copy-of'                       => ':value Kopyası',
        'variant-already-exist-message' => 'Aynı özellik seçeneklerine sahip varyant zaten mevcut.',
    ],

    'response' => [
        'product-can-not-be-copied' => ':type türündeki ürünler kopyalanamaz',
    ],

    'sort-by'  => [
        'options' => [
            'cheapest-first'  => 'En ucuz önce',
            'expensive-first' => 'En pahalı önce',
            'from-a-z'        => 'A\'dan Z\'ye',
            'from-z-a'        => 'Z\'den A\'ya',
            'latest-first'    => 'En yeni önce',
            'oldest-first'    => 'En eski önce',
        ],
    ],

    'type'     => [
        'abstract'     => [
            'offers' => ':qty adet al, her biri için :price öde ve :discount tasarruf et',
        ],

        'bundle'       => 'Paket',
        'configurable' => 'Yapılandırılabilir',
        'downloadable' => 'İndirilebilir',
        'grouped'      => 'Gruplandırılmış',
        'simple'       => 'Basit',
        'virtual'      => 'Sanal',
    ],
];
