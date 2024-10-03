<?php

return [
    'importers' => [
        'customers' => [
            'title' => 'Клиенти',

            'validation' => [
                'errors' => [
                    'duplicate-email'        => 'Имейлът \'%s\' се среща повече от веднъж в импортния файл.',
                    'duplicate-phone'        => 'Телефонът \'%s\' се среща повече от веднъж в импортния файл.',
                    'email-not-found'        => 'Имейлът \'%s\' не е намерен в системата.',
                    'invalid-customer-group' => 'Клиентската група е невалидна или не се поддържа',
                ],
            ],
        ],

        'products' => [
            'title' => 'Продукти',

            'validation' => [
                'errors' => [
                    'duplicate-url-key'         => 'URL ключът \'%s\' вече е генериран за продукт със SKU номер : \'%s\'.',
                    'invalid-attribute-family'  => 'Невалидна стойност за колона семейство на атрибут (семейството на атрибута не съществува?)',
                    'invalid-type'              => 'Типът от продукти е невалиден или не се поддържа',
                    'sku-not-found'             => 'Продукт със зададен SKU номер не е намерен',
                    'super-attribute-not-found' => 'Супер атрибут с код \'%s\' не е намерен или не принадлежи към семейството на атрибута: \'%s\'',
                ],
            ],
        ],

        'tax-rates' => [
            'title' => 'Данъци',

            'validation' => [
                'errors' => [
                    'duplicate-identifier' => 'Идентификатор \'%s\' се среща повече от веднъж в импортния файл.',
                    'identifier-not-found' => 'Идентификатор \'%s\' не е намерен в системата',
                ],
            ],
        ],
    ],

    'validation' => [
        'errors' => [
            'column-empty-headers' => 'Колони номер "%s" имат празни заглавия.',
            'column-name-invalid'  => 'Невалидни имена на колони: "%s".',
            'column-not-found'     => 'Не са намерени задължителни колони: %s.',
            'column-numbers'       => 'Броят на колоните не съответства на броя на редовете в "header".',
            'invalid-attribute'    => '"Header" съдържа невалиден(и) атрибут(и): "%s".',
            'system'               => 'Възникна неочаквана системна грешка.',
            'wrong-quotes'         => 'Използвани са къдрави кавички вместо прави кавички.',
        ],
    ],
];