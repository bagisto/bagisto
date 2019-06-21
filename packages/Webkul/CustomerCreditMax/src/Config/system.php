<?php

return [
    [
        'key' => 'customer.settings.credit_max',
        'name' => 'customercreditmax::app.admin.system.credit-max',
        'sort' => 4,
        'fields' => [
            [
                'name' => 'status',
                'title' => 'customercreditmax::app.admin.system.use-credit-max',
                'type' => 'boolean',
                'channel_based' => true
            ], [
                'name' => 'amount',
                'title' => 'customercreditmax::app.admin.system.max-credit-amount',
                'type' => 'text',
                'validation' => 'decimal',
                'channel_based' => true
            ]
        ],
    ]
];