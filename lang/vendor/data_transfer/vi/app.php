<?php

return [
    importers => [
        customers => [
            title => 'Khách hàng',
            validation => [
                errors => [
                    'duplicate-email' => 'Email : \'%s\' được tìm thấy nhiều lần trong tệp nhập.',
                    'duplicate-phone' => 'Số điện thoại : \'%s\' được tìm thấy nhiều lần trong tệp nhập.',
                    'email-not-found' => 'Email: \'%s\' không tìm thấy trong hệ thống.',
                    'invalid-customer-group' => 'Nhóm khách hàng không hợp lệ hoặc không được hỗ trợ',
                ],
            ],
        ],
        products => [
            title => 'Các sản phẩm',
            validation => [
                errors => [
                    'duplicate-url-key' => 'Khóa URL: \'%s\' đã được tạo cho một mặt hàng có SKU: \'%s\'.',
                    'invalid-attribute-family' => 'Giá trị không hợp lệ cho cột họ thuộc tính (họ thuộc tính không tồn tại?)',
                    'invalid-type' => 'Loại sản phẩm không hợp lệ hoặc không được hỗ trợ',
                    'sku-not-found' => 'Không tìm thấy sản phẩm có SKU được chỉ định',
                    'super-attribute-not-found' => 'Siêu thuộc tính có mã: \'%s\' không tìm thấy hoặc không thuộc họ thuộc tính: \'%s\'',
                ],
            ],
        ],
        'tax-rates' => [
            title => 'Thuế suất',
            validation => [
                errors => [
                    'duplicate-identifier' => 'Mã định danh: \'%s\' được tìm thấy nhiều lần trong tệp nhập.',
                    'identifier-not-found' => 'Mã định danh: \'%s\' không tìm thấy trong hệ thống.',
                ],
            ],
        ],
    ],
    validation => [
        errors => [
            'column-empty-headers' => 'Số cột \"%s\" có tiêu đề trống.',
            'column-name-invalid' => 'Tên cột không hợp lệ: \"%s\".',
            'column-not-found' => 'Không tìm thấy cột bắt buộc: %s.',
            'column-numbers' => 'Số cột không tương ứng với số hàng trong tiêu đề.',
            'invalid-attribute' => 'Tiêu đề chứa (các) thuộc tính không hợp lệ: \"%s\".',
            system => 'Đã xảy ra lỗi hệ thống không mong muốn.',
            'wrong-quotes' => 'Dấu ngoặc kép được sử dụng thay cho dấu ngoặc kép thẳng.',
        ],
    ],
];
