<?php

return [
    'checkout' => [
        'cart' => [
            'integrity' => [
                'qty-missing'   => 'يجب أن يحتوي على الأقل منتج واحد على أكثر من كمية واحدة.',
            ],

            'inventory-warning'        => 'الكمية المطلوبة غير متوفرة، يرجى المحاولة مرة أخرى لاحقًا.',
            'missing-links'            => 'الروابط القابلة للتنزيل غير متوفرة لهذا المنتج.',
            'missing-options'          => 'الخيارات غير متوفرة لهذا المنتج.',
            'selected-products-simple' => 'يجب أن تكون المنتجات المحددة من نوع المنتج البسيط.',
        ],
    ],

    'datagrid' => [
        'copy-of-slug'                  => 'نسخة من :value',
        'copy-of'                       => 'نسخة من :value',
        'variant-already-exist-message' => 'التبويب بنفس خيارات السمات موجود بالفعل.',
    ],

    'response' => [
        'product-can-not-be-copied' => 'لا يمكن نسخ منتجات من نوع :type',
    ],

    'sort-by'  => [
        'options' => [
            'cheapest-first'  => 'الأرخص أولاً',
            'expensive-first' => 'الأغلى أولاً',
            'from-a-z'        => 'من الألف إلى الياء',
            'from-z-a'        => 'من الياء إلى الألف',
            'latest-first'    => 'الأحدث أولاً',
            'oldest-first'    => 'الأقدم أولاً',
        ],
    ],

    'type'     => [
        'abstract'     => [
            'offers' => 'اشترِ :qty بسعر :price لكل وحدة ووفر :discount',
        ],

        'bundle'       => 'حزمة',
        'configurable' => 'قابل للتكوين',
        'downloadable' => 'قابل للتنزيل',
        'grouped'      => 'مجمع',
        'simple'       => 'بسيط',
        'virtual'      => 'افتراضي',
    ],
];
