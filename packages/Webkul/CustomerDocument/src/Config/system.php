<?php

return [
    [
        'key' => 'customer.settings.documents',
        'name' => 'customerdocument::app.admin.customers.documents',
        'sort' => 4,
        'fields' => [
            [
                'name' => 'size',
                'title' => 'customerdocument::app.admin.customers.size',
                'type' => 'text',
                'validation' => 'decimal:2'
            ], [
                'name' => 'allowed_extensions',
                'title' => 'customerdocument::app.admin.customers.allowed-types',
                'type' => 'text',
                'validation' => 'required'
            ]
        ],
    ]
];