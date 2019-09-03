<?php

return [
    [
        'key' => 'showpriceafterlogin',
        'name' => 'showpriceafterlogin::app.showpriceafterlogin.name',
        'sort' => 5
    ], [
        'key' => 'showpriceafterlogin.settings',
        'name' => 'showpriceafterlogin::app.showpriceafterlogin.settings',
        'sort' => 1,
    ], [
        'key' => 'showpriceafterlogin.settings.settings',
        'name' => 'showpriceafterlogin::app.showpriceafterlogin.settings',
        'sort' => 1,
        'fields' => [
            [
                'name' => 'enableordisable',
                'title' => 'showpriceafterlogin::app.showpriceafterlogin.toggle',
                'type' => 'boolean',
                'channel_based' => true,
                'locale_based' => false
            ], [
                'name' => 'hide-shop-before-login',
                'title' => 'showpriceafterlogin::app.showpriceafterlogin.hide-shop-before-login',
                'type' => 'boolean',
                'channel_based' => false,
                'locale_based' => false
            ], [
                'name' => 'selectfunction',
                'title' => 'showpriceafterlogin::app.showpriceafterlogin.select-function',
                'type' => 'select',
                'options' => [
                    [
                        'name' => 'hide-buy-cart-guest',
                        'title' => 'showpriceafterlogin::app.showpriceafterlogin.hide-buy-cart-guest',
                        'value' => "hide-buy-cart-guest"
                    ], [
                        'name' => 'hide-price-buy-cart',
                        'title' => 'showpriceafterlogin::app.showpriceafterlogin.hide-price-buy-cart-guest',
                        'value' => "hide-price-buy-cart-guest"
                    ]
                ],
            ]
        ]
    ]
];