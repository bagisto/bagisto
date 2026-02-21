<?php

return [
    checkout => [
        cart => [
            integrity => [
                'qty-missing' => 'Ít nhất một sản phẩm phải có nhiều hơn 1 số lượng.',
            ],
            'invalid-file-extension' => 'Đã tìm thấy phần mở rộng tệp không hợp lệ.',
            'inventory-warning' => 'Số lượng yêu cầu không có sẵn, vui lòng thử lại sau.',
            'missing-links' => 'Thiếu liên kết có thể tải xuống cho sản phẩm này.',
            'missing-options' => 'Các tùy chọn bị thiếu cho sản phẩm này.',
            'selected-products-simple' => 'Sản phẩm được chọn phải thuộc loại sản phẩm đơn giản.',
        ],
    ],
    datagrid => [
        'copy-of-slug' => 'bản sao của:value',
        'copy-of' => 'Bản sao của :value',
        'variant-already-exist-message' => 'Biến thể có cùng tùy chọn thuộc tính đã tồn tại.',
    ],
    response => [
        'product-can-not-be-copied' => 'Không thể sao chép các sản phẩm thuộc loại :type',
    ],
    'sort-by' => [
        options => [
            'cheapest-first' => 'Rẻ nhất đầu tiên',
            'expensive-first' => 'Đắt đầu tiên',
            'from-a-z' => 'Từ A-Z',
            'from-z-a' => 'Từ Z-A',
            'latest-first' => 'Mới nhất đầu tiên',
            'oldest-first' => 'Cũ nhất đầu tiên',
        ],
    ],
    type => [
        abstract => [
            offers => 'Mua :qty với giá :price mỗi cái và tiết kiệm :discount',
        ],
        bundle => 'Bó',
        booking => 'Đặt chỗ',
        configurable => 'Có thể cấu hình',
        downloadable => 'Có thể tải xuống',
        grouped => 'Được nhóm',
        simple => 'Đơn giản',
        virtual => 'Ảo',
    ],
];
