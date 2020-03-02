<?php

namespace Tests\Functional\Checkout\Cart;

use FunctionalTester;
use Cart;
use Webkul\Sales\Models\OrderAddress;
use Webkul\Checkout\Models\CartAddress;

class OrderCest
{

    /**
     * @param \FunctionalTester $I
     */
    public function testCheckoutAsCustomer(FunctionalTester $I)
    {
        $customer = $I->loginAsCustomer();

        $mocks = $I->prepareCart([
            'customer' => $customer,
        ]);

        // assert that checkout can be reached and generate csrf token.
        $I->amOnRoute('shop.checkout.onepage.index');

        // simulate the entering of the address(es):
        $I->sendAjaxPostRequest(route('shop.checkout.save-address'), [
            '_token'   => csrf_token(),
            'billing'  => [
                'address1'         => ['900 Nobel Parkway'],
                'city'             => 'Quia et cillum rerum',
                'company_name'     => 'Davis and Best Plc',
                'country'          => 'TN',
                'email'            => 'kularynefo@mailinator.com',
                'first_name'       => 'Maggie',
                'last_name'        => 'Paul',
                'phone'            => '+1 (995) 347-2667',
                'postcode'         => '16239',
                'save_as_address'  => true,
                'state'            => 'Aperiam a eligendi a',
                'use_for_shipping' => true,
            ],
            'shipping' => [
                'address1'         => ['900 Nobel Parkway'],
                'city'             => 'Quia et cillum rerum',
                'company_name'     => 'Davis and Best Plc',
                'country'          => 'TN',
                'email'            => 'kularynefo@mailinator.com',
                'first_name'       => 'Maggie',
                'last_name'        => 'Paul',
                'phone'            => '+1 (995) 347-2667',
                'postcode'         => '16239',
                'save_as_address'  => true,
                'state'            => 'Aperiam a eligendi a',
                'use_for_shipping' => true,
            ],
        ]);

        $I->seeResponseCodeIsSuccessful();

        $I->seeRecord(CartAddress::class, [
            'city'         => 'Quia et cillum rerum',
            'company_name' => 'Davis and Best Plc',
            'country'      => 'TN',
            'email'        => 'kularynefo@mailinator.com',
            'first_name'   => 'Maggie',
            'last_name'    => 'Paul',
            'phone'        => '+1 (995) 347-2667',
            'postcode'     => '16239',
        ]);

        $I->sendAjaxPostRequest(route('shop.checkout.save-shipping'), [
            '_token'          => csrf_token(),
            'shipping_method' => 'free_free',
        ]);

        $I->seeResponseCodeIsSuccessful();

        $I->sendAjaxPostRequest(route('shop.checkout.save-payment'), [
            '_token'  => csrf_token(),
            'payment' => [
                'method' => 'cashondelivery',
            ],
        ]);

        $I->seeResponseCodeIsSuccessful();

        // simulate click on the 'place order' button at the last step:
        $I->sendAjaxPostRequest(route('shop.checkout.save-order'),
            ['_token' => csrf_token()]
        );

        $I->seeResponseCodeIsSuccessful();

        $I->seeRecord(OrderAddress::class, [
            'city'         => 'Quia et cillum rerum',
            'company_name' => 'Davis and Best Plc',
            'country'      => 'TN',
            'email'        => 'kularynefo@mailinator.com',
            'first_name'   => 'Maggie',
            'last_name'    => 'Paul',
            'phone'        => '+1 (995) 347-2667',
            'postcode'     => '16239',
        ]);
    }
}