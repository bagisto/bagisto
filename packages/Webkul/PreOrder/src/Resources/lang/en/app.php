<?php

return [
    'shop' => [
        'products' => [
            'percent-to-pay' => 'Pay :percent% as Preorder.',
            'nothing-to-pay' => 'Nothing to pay for Preorder.',
            'available-on' => '<span>Available On:</span> :date',
            'preorder' => 'Preorder',
            'complete-preorder-error' => 'Preorder payment not be completed.'
        ],

        'sales' => [
            'orders' => [
                'preorder-summary' => 'This order contains preorder items',
                'preorder-information' => 'Preorder Information',
                'preorder-payment-information' => 'Preorder Payment Information',
                'type' => 'Type : ',
                'status' => 'Status : ',
                'payment-order' => 'Payment Order',
                'reference-order' => 'Reference Order'
            ]
        ],
    ],

    'admin' => [
        'layouts' => [
            'preorder' => 'Preorder'
        ],

        'preorders' => [
            'title' => 'Preorders',
            'mass-notify-success' => 'Stock notification email sent succesfully.'
        ],

        'sales' => [
            'orders' => [
                'preorder-summary' => 'This order contains preorder items',
                'preorder-information' => 'Preorder Information',
                'preorder-payment-information' => 'Preorder Payment Information',
                'type' => 'Type : ',
                'status' => 'Status : ',
                'reference-order' => 'Reference Order : ',
                'payment-order' => 'Payment Order : '
            ]
        ],

        'system' => [
            'preorder' => 'Preorder',
            'settings' => 'Settings',
            'general' => 'General',
            'preorder-type' => 'Preorder Type',
            'partial-payment' => 'Partial Payment',
            'complete-payment' => 'Complete Payment',
            'preorder-percent' => 'Preorder Percent',
            'preorder-percent-info' => 'This value will be used if "Preorder Type" is selected as "Partial Payment".',
            'message' => 'Message',
            'enable-automatic-mail' => 'Enable Automatic Mail'
        ]
    ],

    'datagrid' => [
        'id' => 'Id',
        'order-id' => 'Order Id',
        'payment-order-id' => 'Payment Order Id',
        'product-name' => 'Product Name',
        'customer-name' => 'Customer Name',
        'customer-email' => 'Customer Email',
        'paid-amount' => 'Paid Amount',
        'remaining-amount' => 'Remaining Amount',
        'preorder-type' => 'Preorder Type',
        'status' => 'Status',
        'partial-payment' => 'Partial Payment',
        'complete-payment' => 'Complete Payment',
        'pending' => 'Pending',
        'processing' => 'Processing',
        'completed' => 'Completed',
        'email-sent' => 'Email Sent',
        'yes' => 'Yes',
        'no' => 'No',
        'order-type' => 'Order Type',
        'preorder' => 'Preorder',
        'normal-order' => 'Normal Order'
    ],

    'mail' => [
        'in-stock' => [
            'subject' => 'Product in stock notification',
            'dear' => 'Dear :name',
            'info' => 'Product :name has been arrived in stock now. <a style="color:#0041FF" href=":link">Click here</a> to complete preorder.'
        ]
    ]
];