<?php

return [
    'importers' => [
        'customers' => [
            'title' => 'Клієнти',

            'validation' => [
                'errors' => [
                    'duplicate-email'        => 'The email "%s" is duplicated in the import file.',
                    'duplicate-phone'        => 'Телефон : \'%s\' знайдено більше одного разу в файлі імпорту.',
                    'email-not-found'        => 'Email : \'%s\' не знайдено в системі.',
                    'invalid-customer-group' => 'Група клієнта недійсна або непідтримується',
                ],
            ],
        ],

        'products' => [
            'title' => 'Товари',

            'validation' => [
                'errors' => [
                    'duplicate-url-key'         => 'URL ключ: \'%s\' вже був згенерований для елемента з SKU: \'%s\'.',
                    'invalid-attribute-family'  => 'Недійсне значення для стовпця атрибут родини (атрибут родини не існує?)',
                    'invalid-type'              => 'Тип товару недійсний або непідтримується',
                    'sku-not-found'             => 'Товар зі вказаним SKU не знайдено',
                    'super-attribute-not-found' => 'Суператрибут із кодом \'%s\' не знайдений або не належить до сімейства атрибутів: \'%s\'',
                ],
            ],
        ],

        'tax-rates' => [
            'title' => 'Ставки податків',

            'validation' => [
                'errors' => [
                    'duplicate-identifier' => 'Ідентифікатор: \'%s\' знайдено більше одного разу в файлі імпорту.',
                    'identifier-not-found' => 'Ідентифікатор: \'%s\' не знайдено в системі.',
                ],
            ],
        ],
    ],

    'validation' => [
        'errors' => [
            'column-empty-headers' => 'Стовпці з номером "%s" мають порожні заголовки.',
            'column-name-invalid'  => 'Недійсні назви стовпців: "%s".',
            'column-not-found'     => 'Вимагаються не знайдені стовпці: %s.',
            'column-numbers'       => 'Кількість стовпців не відповідає кількості рядків у заголовку.',
            'invalid-attribute'    => 'Заголовок містить недійсні атрибути: "%s".',
            'system'               => 'Сталася несподівана помилка системи.',
            'wrong-quotes'         => 'Криві лапки використовуються замість прямих лапок.',
        ],
    ],
];
