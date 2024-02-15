<?php

return [
    'importers'  => [
        'customers' => [
            'title'      => 'Khách hàng',

            'validation' => [
                'errors' => [
                    'duplicate-email'        => 'Email : \'%s\' được tìm thấy nhiều hơn một lần trong tệp nhập.',
                    'duplicate-phone'        => 'Số điện thoại : \'%s\' được tìm thấy nhiều hơn một lần trong tệp nhập.',
                    'invalid-customer-group' => 'Nhóm khách hàng không hợp lệ hoặc không được hỗ trợ',
                    'email-not-found'        => 'Email : \'%s\' không được tìm thấy trong hệ thống.',
                ],
            ],
        ],

        'products'  => [
            'title'      => 'Sản phẩm',

            'validation' => [
                'errors' => [
                    'duplicate-url-key'         => 'Khóa URL: \'%s\' đã được tạo trước đó cho một mục có SKU: \'%s\'.',
                    'invalid-attribute-family'  => 'Giá trị không hợp lệ cho cột gia đình thuộc tính (gia đình thuộc tính không tồn tại?)',
                    'invalid-type'              => 'Loại sản phẩm không hợp lệ hoặc không được hỗ trợ',
                    'sku-not-found'             => 'Không tìm thấy sản phẩm với SKU được chỉ định',
                    'super-attribute-not-found' => 'Thuộc tính siêu với mã: \'%s\' không được tìm thấy hoặc không thuộc về gia đình thuộc tính: \'%s\'',
                ],
            ],
        ],

        'tax-rates' => [
            'title'      => 'Thuế',

            'validation' => [
                'errors' => [
                    'duplicate-identifier' => 'Bộ nhận dạng : \'%s\' được tìm thấy nhiều hơn một lần trong tệp nhập.',
                    'identifier-not-found' => 'Bộ nhận dạng : \'%s\' không được tìm thấy trong hệ thống.',
                ],
            ],
        ],
    ],

    'validation' => [
        'errors' => [
            'column-empty-headers' => 'Các cột số "%s" có tiêu đề trống.',
            'column-name-invalid'  => 'Tên cột không hợp lệ: "%s".',
            'column-not-found'     => 'Không tìm thấy các cột được yêu cầu: %s.',
            'column-numbers'       => 'Số lượng cột không tương ứng với số hàng trong tiêu đề.',
            'invalid-attribute'    => 'Tiêu đề chứa thuộc tính không hợp lệ: "%s".',
            'system'               => 'Đã xảy ra một lỗi hệ thống không mong muốn.',
            'wrong-quotes'         => 'Dấu ngoặc kép được sử dụng thay vì dấu ngoặc thẳng.',
        ],
    ],
];
