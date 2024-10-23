<?php

return [
    'checkout' => [
        'cart' => [
            'integrity' => [
                'qty-missing'   => '至少一个产品的数量应大于1。',
            ],

            'inventory-warning'        => '所请求的数量不可用，请稍后重试。',
            'missing-links'            => '此产品缺少可下载的链接。',
            'missing-options'          => '此产品缺少选项。',
            'selected-products-simple' => '所选产品应为简单类型。',
        ],
    ],

    'datagrid' => [
        'copy-of-slug'                  => '的副本-:value',
        'copy-of'                       => ':value的副本',
        'variant-already-exist-message' => '具有相同属性选项的变体已经存在。',
    ],

    'response' => [
        'product-can-not-be-copied' => '类型为:type的产品无法复制',
    ],

    'sort-by'  => [
        'options' => [
            'cheapest-first'  => '最便宜的先',
            'expensive-first' => '最贵的先',
            'from-a-z'        => '从A到Z',
            'from-z-a'        => '从Z到A',
            'latest-first'    => '最新的先',
            'oldest-first'    => '最旧的先',
        ],
    ],

    'type'     => [
        'abstract'     => [
            'offers' => '每个:qty以:price购买，每个节省:discount',
        ],

        'bundle'       => '捆绑销售',
        'configurable' => '可配置的',
        'downloadable' => '可下载的',
        'grouped'      => '分组的',
        'simple'       => '简单的',
        'virtual'      => '虚拟的',
    ],
];
