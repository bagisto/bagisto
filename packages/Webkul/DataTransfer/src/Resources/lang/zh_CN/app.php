<?php

return [
    'importers'  => [
        'products'  => [
            'title'      => '产品',

            'validation' => [
                'errors' => [
                    'duplicate-url-key'        => 'URL 键: \'%s\' 已经为 SKU: \'%s\' 生成。',
                    'invalid-attribute-family' => '属性家族列中的值无效或不受支持。',
                    'invalid-type'             => '产品类型无效或不受支持。',
                    'sku-not-found'            => '未找到具有指定 SKU 的产品。',
                ],
            ],
        ],

        'customers' => [
            'title'      => '顾客',

            'validation' => [
                'errors' => [
                    'duplicate-email'        => '电子邮件: \'%s\' 在导入文件中出现了多次。',
                    'duplicate-phone'        => '电话: \'%s\' 在导入文件中出现了多次。',
                    'invalid-customer-group' => '客户组无效或不受支持。',
                    'email-not-found'        => '电子邮件: \'%s\' 在系统中未找到。',
                ],
            ],
        ],
    ],

    'validation' => [
        'errors' => [
            'column-empty-headers' => '列数 "%s" 的标题为空。',
            'column-name-invalid'  => '列名无效: "%s".',
            'column-not-found'     => '未找到所需的列: %s.',
            'column-numbers'       => '列数与标题行中的行数不匹配。',
            'invalid-attribute'    => '标题包含无效的属性(s): "%s".',
            'system'               => '发生了意外的系统错误。',
            'wrong-quotes'         => '使用了曲线引号而不是直角引号。',
        ],
    ],
];
