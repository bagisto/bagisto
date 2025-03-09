<?php

return [
    'checkout' => [
        'cart' => [
            'integrity' => [
                'qty-missing'   => 'Ít nhất một sản phẩm phải có số lượng lớn hơn 1.',
            ],

            'inventory-warning'        => 'Số lượng yêu cầu không có sẵn, vui lòng thử lại sau.',
            'missing-links'            => 'Liên kết tải xuống bị thiếu cho sản phẩm này.',
            'missing-options'          => 'Tùy chọn bị thiếu cho sản phẩm này.',
            'selected-products-simple' => 'Các sản phẩm được chọn phải thuộc loại sản phẩm đơn giản.',
        ],
    ],

    'datagrid' => [
        'copy-of-slug'                  => 'ban-sao-cua-:value',
        'copy-of'                       => 'Bản sao của :value',
        'variant-already-exist-message' => 'Biến thể với cùng tùy chọn thuộc tính đã tồn tại.',
    ],

    'response' => [
        'product-can-not-be-copied' => 'Sản phẩm loại :type không thể được sao chép.',
    ],

    'sort-by'  => [
        'options' => [
            'cheapest-first'  => 'Giá thấp - cao',
            'expensive-first' => 'Giá cao - thấp',
            'from-a-z'        => 'Từ A-Z',
            'from-z-a'        => 'Từ Z-A',
            'latest-first'    => 'Mới nhất',
            'oldest-first'    => 'Cũ nhất',
        ],
    ],

    'type'     => [
        'abstract'     => [
            'offers' => 'Mua :qty với giá :price mỗi sản phẩm và tiết kiệm :discount',
        ],

        'bundle'       => 'Gói sản phẩm',
        'configurable' => 'Cấu hình',
        'downloadable' => 'Có thể tải xuống',
        'grouped'      => 'Nhóm',
        'simple'       => 'Đơn giản',
        'virtual'      => 'Ảo',
    ],
];
