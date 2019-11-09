<?php
return [
    'przelewy24_standard' => [
        'code' => 'przelewy24_standard',
        'title' => 'Przelewy24 Standard',
        'description' => 'shop::app.checkout.onepage.przelewy24',
        'class' => 'Webkul\Paypal\Payment\Standard',
        'sandbox' => true,
        'active' => true,
        'business_account' => 'test@webkul.com',
        'sort' => 3
    ]
];
