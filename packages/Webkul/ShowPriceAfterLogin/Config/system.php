<?php

return [
    [
        'key' => 'ShowPriceAfterLogin',
        'name' => 'ShowPriceAfterLogin::app.showpriceafterlogin.name',
        'sort' => 5
    ], [
        'key' => 'ShowPriceAfterLogin.settings',
        'name' => 'ShowPriceAfterLogin::app.showpriceafterlogin.settings',
        'sort' => 1,
    ], [
        'key' => 'ShowPriceAfterLogin.settings.settings',
        'name' => 'ShowPriceAfterLogin::app.showpriceafterlogin.settings',
        'sort' => 1,
        'fields' => [
            [
                'name' => 'enableordisable',
                'title' => 'ShowPriceAfterLogin::app.showpriceafterlogin.toggle',
                'type' => 'boolean',
                'channel_based' => true,
                'locale_based' => false
            ], [
                'name' => 'hide-shop-before-login',
                'title' => 'ShowPriceAfterLogin::app.showpriceafterlogin.hide-shop-before-login',
                'type' => 'boolean',
                'channel_based' => false,
                'locale_based' => false
            ], [
                'name' => 'selectfunction',
                'title' => 'ShowPriceAfterLogin::app.showpriceafterlogin.select-function',
                'type' => 'select',
                'options' => [
                    [
                        'name' => 'default',
                        'title' => 'ShowPriceAfterLogin::app.showpriceafterlogin.select-function',
                        'value' => ""
                    ], [
                        'name' => 'hide-buy-cart-guest',
                        'title' => 'ShowPriceAfterLogin::app.showpriceafterlogin.hide-buy-cart-guest',
                        'value' => "hide-buy-cart-guest"
                    ], [
                        'name' => 'hide-price-buy-cart',
                        'title' => 'ShowPriceAfterLogin::app.showpriceafterlogin.hide-price-buy-cart-guest',
                        'value' => "hide-price-buy-cart-guest"
                    ]
                ],
            ]
        ]
    ]
];