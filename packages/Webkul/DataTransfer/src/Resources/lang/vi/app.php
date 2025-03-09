<?php

return [
    'importers' => [
        'customers' => [
            'title' => 'Khách hàng',

            'validation' => [
                'errors' => [
                    'duplicate-email'        => 'Email: \'%s\' được tìm thấy nhiều hơn một lần trong tệp nhập.',
                    'duplicate-phone'        => 'Số điện thoại: \'%s\' được tìm thấy nhiều hơn một lần trong tệp nhập.',
                    'email-not-found'        => 'Email: \'%s\' không tìm thấy trong hệ thống.',
                    'invalid-customer-group' => 'Nhóm khách hàng không hợp lệ hoặc không được hỗ trợ.',
                ],
            ],
        ],

        'products' => [
            'title' => 'Sản phẩm',

            'validation' => [
                'errors' => [
                    'duplicate-url-key'         => 'Khóa URL: \'%s\' đã được tạo cho một mục có SKU: \'%s\'.',
                    'invalid-attribute-family'  => 'Giá trị không hợp lệ cho cột nhóm thuộc tính (có thể nhóm thuộc tính không tồn tại?)',
                    'invalid-type'              => 'Loại sản phẩm không hợp lệ hoặc không được hỗ trợ.',
                    'sku-not-found'             => 'Không tìm thấy sản phẩm với SKU được chỉ định.',
                    'super-attribute-not-found' => 'Không tìm thấy thuộc tính siêu cấp với mã: \'%s\' hoặc thuộc tính này không thuộc nhóm thuộc tính: \'%s\'.',
                ],
            ],
        ],

        'tax-rates' => [
            'title' => 'Thuế suất',

            'validation' => [
                'errors' => [
                    'duplicate-identifier' => 'Mã định danh: \'%s\' được tìm thấy nhiều hơn một lần trong tệp nhập.',
                    'identifier-not-found' => 'Mã định danh: \'%s\' không tìm thấy trong hệ thống.',
                ],
            ],
        ],
    ],

    'validation' => [
        'errors' => [
            'column-empty-headers' => 'Cột số "%s" có tiêu đề trống.',
            'column-name-invalid'  => 'Tên cột không hợp lệ: "%s".',
            'column-not-found'     => 'Không tìm thấy các cột bắt buộc: %s.',
            'column-numbers'       => 'Số lượng cột không khớp với số lượng hàng trong tiêu đề.',
            'invalid-attribute'    => 'Tiêu đề chứa thuộc tính không hợp lệ: "%s".',
            'system'               => 'Đã xảy ra lỗi hệ thống không mong muốn.',
            'wrong-quotes'         => 'Dấu ngoặc nhọn được sử dụng thay vì dấu ngoặc thẳng.',
        ],
    ],
];
