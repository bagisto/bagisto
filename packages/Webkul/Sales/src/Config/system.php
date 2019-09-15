<?php

return [
    [
        'key' => 'sales.invoiceSettings',
        'name' => 'admin::app.admin.system.invoice-settings',
        'sort' => 3,
    ],[
        'key' => 'sales.invoiceSettings.invoice_number',
        'name' => 'admin::app.admin.system.invoiceNumber',
        'sort' => 0,
        'fields' => [
            [
                'name' => 'invoice_number_prefix',
                'title' => 'admin::app.admin.system.invoice number prefix',
                'type' => 'text',
                'validation' => false,
                'channel_based' => true,
                'locale_based' => true
            ],
            [
                'name' => 'invoice_number_length',
                'title' => 'admin::app.admin.system.invoice number length',
                'type' => 'text',
                'validation' => false,
                'channel_based' => true,
                'locale_based' => true
            ],
            [
                'name' => 'invoice_number_suffix',
                'title' => 'admin::app.admin.system.invoice number suffix',
                'type' => 'text',
                'validation' => false,
                'channel_based' => true,
                'locale_based' => true
            ],
        ]
    ]
];