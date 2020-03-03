<?php

namespace Tests\Functional\Checkout\Cart;

use Faker\Factory;
use FunctionalTester;
use Cart;
use Webkul\Sales\Models\OrderAddress;
use Webkul\Checkout\Models\CartAddress;

/**
 * Class OrderCest
 *
 * @package Tests\Functional\Checkout\Cart
 */
class OrderCest
{

    /**
     * @param \FunctionalTester $I
     */
    public function testCheckoutAsCustomer(FunctionalTester $I)
    {
        $customer = $I->loginAsCustomer();

        $faker = Factory::create();

        $addressData = [
            'city'         => $faker->city,
            'company_name' => $faker->company,
            'country'      => $faker->countryCode,
            'email'        => $faker->email,
            'first_name'   => $faker->firstName,
            'last_name'    => $faker->lastName,
            'phone'        => $faker->phoneNumber,
            'postcode'     => $faker->postcode,
            'state'        => $faker->state,
        ];

        $mocks = $I->prepareCart([
            'customer' => $customer,
        ]);

        // assert that checkout can be reached and generate csrf token.
        $I->amOnRoute('shop.checkout.onepage.index');

        // simulate the entering of the address(es):
        $I->sendAjaxPostRequest(route('shop.checkout.save-address'), [
            '_token'   => csrf_token(),
            'billing'  => array_merge($addressData, [
                'address1'         => ['900 Nobel Parkway'],
                'save_as_address'  => true,
                'use_for_shipping' => true,
            ]),
            'shipping' => array_merge($addressData, [
                'address1'         => ['900 Nobel Parkway'],
                'save_as_address'  => true,
                'use_for_shipping' => true,
            ]),
        ]);

        $I->seeResponseCodeIsSuccessful();

        $I->seeRecord(CartAddress::class, $addressData);

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

        $I->seeRecord(OrderAddress::class, $addressData);
    }
}