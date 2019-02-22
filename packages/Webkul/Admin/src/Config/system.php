<?php

return [
    [
        'key' => 'catalog',
        'name' => 'Catalog',
        'sort' => 1
    ], [
        'key' => 'catalog.products',
        'name' => 'Products',
        'sort' => 1,
    ], [
        'key' => 'catalog.products.review',
        'name' => 'Review',
        'sort' => 1,
        'fields' => [
            [
                'name' => 'guest_review',
                'title' => 'Allow Guest Review',
                'type' => 'select',
                'options' => [
                    [
                        'title' => 'Yes',
                        'value' => true
                    ], [
                        'title' => 'No',
                        'value' => false
                    ]
                ],
            ]
        ]
    ],
];