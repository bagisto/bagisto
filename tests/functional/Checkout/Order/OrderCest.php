<?php

namespace Tests\Functional\Checkout\Cart;

use Faker\Factory;
use FunctionalTester;
use Cart;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderAddress;
use Webkul\Sales\Models\OrderPayment;
use Webkul\Checkout\Models\CartAddress;
use Webkul\Checkout\Models\CartPayment;
use Webkul\Customer\Models\Customer;
use Webkul\Core\Models\Channel;

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

        $I->seeRecord(CartAddress::class, array_merge($addressData, [
            'address_type' => 'shipping',
            'cart_id'      => $mocks['cart']->id,
        ]));

        $I->seeRecord(CartAddress::class, array_merge($addressData, [
            'address_type' => 'billing',
            'cart_id'      => $mocks['cart']->id,
        ]));

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

        $I->seeRecord(CartPayment::class, [
            'method'       => 'cashondelivery',
            'method_title' => null,
            'cart_id'      => $mocks['cart']->id,
        ]);

        // simulate click on the 'place order' button at the last step:
        $I->sendAjaxPostRequest(route('shop.checkout.save-order'),
            ['_token' => csrf_token()]
        );

        $I->seeResponseCodeIsSuccessful();

        $order = $I->grabRecord(Order::class, [
            'status'               => 'pending',
            'channel_name'         => 'Default',
            'is_guest'             => 0,
            'shipping_method'      => 'free_free',
            'shipping_title'       => 'Free Shipping - Free Shipping',
            'shipping_description' => 'Free Shipping',
            'customer_type'        => Customer::class,
            'channel_id'           => 1,
            'channel_type'         => Channel::class,
            'cart_id'              => $mocks['cart']->id,
            'customer_id'          => $customer->id,
            'total_item_count'     => count($mocks['cartItems']),
            'total_qty_ordered'    => $mocks['totalQtyOrdered'],
        ]);

        $I->seeRecord(OrderAddress::class, array_merge($addressData, [
            'order_id'     => $order->id,
            'address_type' => 'shipping',
        ]));

        $I->seeRecord(OrderAddress::class, array_merge($addressData, [
            'order_id'     => $order->id,
            'address_type' => 'billing',
        ]));

        $I->seeRecord(OrderPayment::class, [
            'method'       => 'cashondelivery',
            'method_title' => null,
            'order_id'     => $order->id,
        ]);
    }
}