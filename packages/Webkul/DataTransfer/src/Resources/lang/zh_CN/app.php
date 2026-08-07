<?php

return [
    'importers' => [
        'customers' => [
            'title' => '顾客',

            'validation' => [
                'errors' => [
                    'duplicate-email' => '电子邮件: \'%s\' 在导入文件中出现了多次。',
                    'duplicate-phone' => '电话: \'%s\' 在导入文件中出现了多次。',
                    'email-not-found' => '电子邮件: \'%s\' 在系统中未找到。',
                    'invalid-customer-group' => '客户组无效或不受支持。',
                ],
            ],
        ],

        'products' => [
            'title' => '产品',

            'validation' => [
                'errors' => [
                    'duplicate-url-key' => 'URL 键: \'%s\' 已经为 SKU: \'%s\' 生成。',
                    'image-not-file' => '图片：\'%s\' 是网址，但此导入设置为从文件中获取图片。请将图片来源选为“文件中的图片链接”，或将该值替换为文件名。',
                    'image-not-found' => '图片：\'%s\' 未在此导入期望存放图片的位置找到。',
                    'image-not-url' => '图片：\'%s\' 不是网址，但此导入设置为从文件中的链接获取图片。请选择其他图片来源，或将该值替换为完整的 https:// 地址。',
                    'invalid-attribute-family' => '属性家族列中的值无效或不受支持。',
                    'invalid-type' => '产品类型无效或不受支持。',
                    'sku-not-found' => '未找到具有指定 SKU 的产品。',
                    'super-attribute-not-found' => '未找到代码为\'%s\'的超级属性或不属于属性组: \'%s\'',
                ],
            ],
        ],

        'tax-rates' => [
            'title' => '税率',

            'validation' => [
                'errors' => [
                    'duplicate-identifier' => '标识符: \'%s\' 在导入文件中找到多次。',
                    'identifier-not-found' => '标识符: \'%s\' 在系统中未找到。',
                ],
            ],
        ],
    ],

    'validation' => [
        'errors' => [
            'column-empty-headers' => '列数 "%s" 的标题为空。',
            'column-name-invalid' => '列名无效: "%s".',
            'column-not-found' => '未找到所需的列: %s.',
            'column-numbers' => '列数与标题行中的行数不匹配。',
            'invalid-attribute' => '标题包含无效的属性(s): "%s".',
            'more-issues' => '以及另外 :count 个问题 — 请下载完整报告查看全部内容。',
            'more-rows' => '（另有 :count 行）',
            'system' => '发生了意外的系统错误。',
            'wrong-quotes' => '使用了曲线引号而不是直角引号。',
        ],
    ],
];
