<?php

return [
    'checkout' => [
        'cart' => [
            'integrity' => [
                'qty-missing' => 'Ít nhất một sản phẩm phải có số lượng lớn hơn 1.',
            ],

            'inventory-warning' => 'Số lượng yêu cầu không có sẵn, vui lòng thử lại sau.',
            'missing-links'     => 'Liên kết tải xuống bị thiếu cho sản phẩm này.',
            'missing-options'   => 'Tùy chọn bị thiếu cho sản phẩm này.',
        ],
    ],

    'datagrid' => [
        'copy-of-slug'                  => 'bản sao của :value',
        'copy-of'                       => 'Bản sao của :value',
        'variant-already-exist-message' => 'Biến thể với cùng các tùy chọn thuộc tính đã tồn tại.',
    ],

    'response' => [
        'product-can-not-be-copied' => 'Các sản phẩm loại :type không thể được sao chép',
    ],

    'sort-by' => [
        'options' => [
            'cheapest-first'  => 'Giá thấp nhất trước',
            'expensive-first' => 'Giá cao nhất trước',
            'from-a-z'        => 'Từ A-Z',
            'from-z-a'        => 'Từ Z-A',
            'latest-first'    => 'Mới nhất trước',
            'oldest-first'    => 'Cũ nhất trước',
        ],
    ],

    'type' => [
        'abstract'     => [
            'offers' => 'Mua :qty với giá :price mỗi cái và tiết kiệm :discount',
        ],

        'bundle'       => 'Gói',
        'configurable' => 'Có thể cấu hình',
        'downloadable' => 'Có thể tải xuống',
        'grouped'      => 'Nhóm',
        'simple'       => 'Đơn giản',
        'virtual'      => 'Ảo',
    ],

];
