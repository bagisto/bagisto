<?php

return [
    [
        'key' => 'sales',
        'name' => 'Sales',
        'sort' => 1
    ], [
        'key' => 'sales.paymentmethods',
        'name' => 'Payment Methods',
        'sort' => 2,
    ], [
        'key' => 'sales.paymentmethods.cashondelivery',
        'name' => 'Cash On Delivery',
        'sort' => 1,
        'fields' => [
            [
                'name' => 'title',
                'title' => 'Title',
                'type' => 'text',
                'validation' => 'required',
                'channel_based' => false,
                'locale_based' => true
            ], [
                'name' => 'description',
                'title' => 'Description',
                'type' => 'textarea',
                'channel_based' => false,
                'locale_based' => true
            ],  [
                'name' => 'order_status',
                'title' => 'Order Status',
                'type' => 'select',
                'options' => [
                    [
                        'title' => 'Pending',
                        'value' => 'pending'
                    ], [
                        'title' => 'Approved',
                        'value' => 'Approved'
                    ], [
                        'title' => 'Pending Payment',
                        'value' => 'pending_payment'
                    ]
                ],
                'validation' => 'required'
            ], [
                'name' => 'active',
                'title' => 'Status',
                'type' => 'select',
                'options' => [
                    [
                        'title' => 'Active',
                        'value' => true
                    ], [
                        'title' => 'Inactive',
                        'value' => false
                    ]
                ],
                'validation' => 'required'
            ]
        ]
    ], [
        'key' => 'sales.paymentmethods.moneytransfer',
        'name' => 'Money Transfer',
        'sort' => 2,
        'fields' => [
            [
                'name' => 'title',
                'title' => 'Title',
                'type' => 'text',
                'validation' => 'required',
                'channel_based' => false,
                'locale_based' => true
            ], [
                'name' => 'description',
                'title' => 'Description',
                'type' => 'textarea',
                'channel_based' => false,
                'locale_based' => true
            ],  [
                'name' => 'order_status',
                'title' => 'Order Status',
                'type' => 'select',
                'options' => [
                    [
                        'title' => 'Pending',
                        'value' => 'pending'
                    ], [
                        'title' => 'Approved',
                        'value' => 'Approved'
                    ], [
                        'title' => 'Pending Payment',
                        'value' => 'pending_payment'
                    ]
                ],
                'validation' => 'required'
            ], [
                'name' => 'active',
                'title' => 'Status',
                'type' => 'select',
                'options' => [
                    [
                        'title' => 'Active',
                        'value' => true
                    ], [
                        'title' => 'Inactive',
                        'value' => false
                    ]
                ],
                'validation' => 'required'
            ]
        ]
    ], [
        'key' => 'sales.paymentmethods.paypal_standard',
        'name' => 'Paypal Standard',
        'sort' => 3,
        'fields' => [
            [
                'name' => 'title',
                'title' => 'Title',
                'type' => 'text',
                'validation' => 'required',
                'channel_based' => false,
                'locale_based' => true
            ], [
                'name' => 'description',
                'title' => 'Description',
                'type' => 'textarea',
                'channel_based' => false,
                'locale_based' => true
            ],  [
                'name' => 'order_status',
                'title' => 'Order Status',
                'type' => 'select',
                'options' => [
                    [
                        'title' => 'Pending',
                        'value' => 'pending'
                    ], [
                        'title' => 'Approved',
                        'value' => 'Approved'
                    ], [
                        'title' => 'Pending Payment',
                        'value' => 'pending_payment'
                    ]
                ],
                'validation' => 'required'
            ],  [
                'name' => 'business_account',
                'title' => 'Business Account',
                'type' => 'select',
                'type' => 'text',
                'validation' => 'required'
            ],  [
                'name' => 'active',
                'title' => 'Status',
                'type' => 'select',
                'options' => [
                    [
                        'title' => 'Active',
                        'value' => true
                    ], [
                        'title' => 'Inactive',
                        'value' => false
                    ]
                ],
                'validation' => 'required'
            ]
        ]
    ]
];