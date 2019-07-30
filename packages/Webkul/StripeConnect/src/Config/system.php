<?php

return [
    [
        'key' => 'stripe',
        'name' => 'Stripe Connect',
        'sort' => 5
    ], [
        'key' => 'stripe.connect',
        'name' => 'Connect Account',
        'sort' => 1,
    ], [
        'key' => 'stripe.connect.details',
        'name' => 'Account Details',
        'sort' => 1,
        'fields' => [
            [
                'name' => 'stripefees',
                'title' => 'Stripe fee to be paid by customer or seller',
                'type' => 'select',
                'options' => [
                    [
                        'title' => 'Seller',
                        'value' => 'seller'
                    ], [
                        'title' => 'Customer',
                        'value' => 'customer'
                    ]
                ],
                'validation' => 'required'
            ], [
                'name' => 'statementdescriptor',
                'title' => 'Statement Descriptor',
                'type' => 'text'
            ]
        ]
    ]
];