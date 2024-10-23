<?php

return [
    'checkout' => [
        'cart' => [
            'integrity' => [
                'qty-missing'   => 'حداقل یک محصول باید بیش از 1 تعداد داشته باشد.',
            ],

            'inventory-warning'        => 'تعداد درخواستی در دسترس نیست، لطفاً بعداً دوباره تلاش کنید.',
            'missing-links'            => 'پیوندهای قابل دانلود برای این محصول وجود ندارند.',
            'missing-options'          => 'گزینه‌ها برای این محصول وجود ندارند.',
            'selected-products-simple' => 'محصولات انتخابی باید از نوع محصول ساده باشند.',
        ],
    ],

    'datagrid' => [
        'copy-of-slug'                  => 'کپی-از-:value',
        'copy-of'                       => 'کپی از :value',
        'variant-already-exist-message' => 'نسخه با همان گزینه‌های ویژگی از قبل وجود دارد.',
    ],

    'response' => [
        'product-can-not-be-copied' => 'محصولات از نوع :type قابل کپی نیستند',
    ],

    'sort-by'  => [
        'options' => [
            'cheapest-first'  => 'ارزان‌ترین اول',
            'expensive-first' => 'گران‌ترین اول',
            'from-a-z'        => 'از A تا Z',
            'from-z-a'        => 'از Z تا A',
            'latest-first'    => 'جدیدترین اول',
            'oldest-first'    => 'قدیمی‌ترین اول',
        ],
    ],

    'type'     => [
        'abstract'     => [
            'offers' => 'خرید :qty به قیمت :price هر کدام و صرفه‌جویی :discount',
        ],

        'bundle'       => 'بسته',
        'configurable' => 'پیکربندی‌پذیر',
        'downloadable' => 'قابل دانلود',
        'grouped'      => 'گروه‌بندی‌شده',
        'simple'       => 'ساده',
        'virtual'      => 'مجازی',
    ],
];
