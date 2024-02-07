<?php

return [
    'importers'  => [
        'customers' => [
            'title'      => 'مشتریان',

            'validation' => [
                'errors' => [
                    'duplicate-email'        => 'ایمیل: \'%s\' بیش از یک بار در فایل واردات پیدا شده است.',
                    'duplicate-phone'        => 'تلفن: \'%s\' بیش از یک بار در فایل واردات پیدا شده است.',
                    'invalid-customer-group' => 'گروه مشتری نامعتبر یا پشتیبانی نمی‌شود',
                    'email-not-found'        => 'ایمیل: \'%s\' در سیستم یافت نشد.',
                ],
            ],
        ],

        'products'  => [
            'title'      => 'محصولات',

            'validation' => [
                'errors' => [
                    'duplicate-url-key'         => 'کلید URL: \'%s\' قبلاً برای یک مورد با SKU: \'%s\' ایجاد شده است.',
                    'invalid-attribute-family'  => 'مقدار نامعتبر برای ستون خانواده ویژگی (خانواده ویژگی وجود ندارد؟)',
                    'invalid-type'              => 'نوع محصول نامعتبر یا پشتیبانی نمی‌شود',
                    'sku-not-found'             => 'محصول با SKU مشخص شده یافت نشد',
                    'super-attribute-not-found' => 'ویژگی فوق العاده با کد: \'%s\' یافت نشد یا به خانواده ویژگی ها تعلق ندارد: \'%s\'',
                ],
            ],
        ],

        'tax-rates' => [
            'title'      => 'نرخ‌های مالیاتی',

            'validation' => [
                'errors' => [
                    'duplicate-identifier' => 'شناسه: \'%s\' بیش از یک بار در فایل ورودی یافت شده است.',
                    'identifier-not-found' => 'شناسه: \'%s\' در سیستم یافت نشد.',
                ],
            ],
        ],
    ],

    'validation' => [
        'errors' => [
            'column-empty-headers' => 'تعداد ستون‌ها "%s" سربرگ‌های خالی دارند.',
            'column-name-invalid'  => 'نام‌های ستون نامعتبر هستند: "%s".',
            'column-not-found'     => 'ستون‌های مورد نیاز یافت نشدند: %s.',
            'column-numbers'       => 'تعداد ستون‌ها با تعداد ردیف‌ها در سربرگ مطابقت ندارد.',
            'invalid-attribute'    => 'سربرگ حاوی ویژگی(های) نامعتبر است: "%s".',
            'system'               => 'خطای غیرمنتظره سیستم رخ داده است.',
            'wrong-quotes'         => 'نقل قول‌های خمیده به جای نقل قول‌های تراشیده استفاده شده است.',
        ],
    ],
];
