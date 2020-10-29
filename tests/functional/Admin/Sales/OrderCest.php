<?php

namespace Tests\Functional\Admin\Sales;

use Faker\Factory;
use FunctionalTester;
use Codeception\Util\Locator;
use Webkul\Sales\Models\Order;
use Webkul\Core\Models\Channel;
use Webkul\Customer\Models\Customer;
use Webkul\Sales\Models\OrderAddress;
use Webkul\Sales\Models\OrderPayment;
use Webkul\Checkout\Models\CartAddress;
use Webkul\Checkout\Models\CartPayment;

class OrderCest
{
    public function testIndex(FunctionalTester $I): void
    {
        $order = $I->have(Order::class);

        $I->loginAsAdmin();
        $I->amOnAdminRoute('admin.dashboard.index');
        $I->click(__('admin::app.layouts.sales'), '//*[contains(@class, "navbar-left")]');
        $I->seeCurrentRouteIs('admin.sales.orders.index');
        $I->click(__('admin::app.layouts.orders'), '//*[contains(@class, "aside-nav")]');

        $I->seeCurrentRouteIs('admin.sales.orders.index');
        $I->see($order->id, '//script[@type="text/x-template"]');
        $I->see($order->sub_total, '//script[@type="text/x-template"]');
    }

    public function testCancelCashOnDeliveryOrder(FunctionalTester $I): void
    {
        /* place order for customer */
        $order = $this->placeCashOnDeliveryOrderForCustomer($I);

        /* go to admin page and login */
        $this->goToAdminLoginPageAndSignIn($I);

        /* go to order view page */
        $I->amOnPage(route('admin.sales.orders.view', $order->id));
        $I->seeCurrentRouteIs('admin.sales.orders.view');

        /* now cancel order test */
        $I->see('Cancel', Locator::href(route('admin.sales.orders.cancel', $order->id)));
        $I->click('Cancel', Locator::href(route('admin.sales.orders.cancel', $order->id)));
        $I->seeCurrentRouteIs('admin.sales.orders.view');
        $I->see(0.00, '#due-amount-on-cancelled');
    }

    private function goToAdminLoginPageAndSignIn(FunctionalTester $I): void
    {
        $I->amOnRoute('admin.session.create');
        $I->fillField('email', 'admin@example.com');
        $I->fillField('password', 'admin123');
        $I->click('Sign In');
        $I->seeCurrentRouteIs('admin.dashboard.index');
    }

    private function placeCashOnDeliveryOrderForCustomer(FunctionalTester $I): object
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

        $I->amOnRoute('shop.checkout.onepage.index');

        $I->seeCurrentRouteIs('shop.checkout.onepage.index');

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
            'address_type' => CartAddress::ADDRESS_TYPE_SHIPPING,
            'cart_id'      => $mocks['cart']->id,
            'customer_id'  => $mocks['customer']->id,
        ]));

        $I->seeRecord(CartAddress::class, array_merge($addressData, [
            'address_type' => CartAddress::ADDRESS_TYPE_BILLING,
            'cart_id'      => $mocks['cart']->id,
            'customer_id'  => $mocks['customer']->id,
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

        $I->sendAjaxPostRequest(route('shop.checkout.save-order'),
            ['_token' => csrf_token()]
        );

        $I->seeResponseCodeIsSuccessful();

        $order = $I->grabRecord(Order::class, [
            'status'               => 'pending',
            'channel_name'         => 'Default',
            'is_guest'             => 0,
            'customer_first_name'  => $customer->first_name,
            'customer_last_name'   => $customer->last_name,
            'customer_email'       => $customer->email,
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
            'address_type' => OrderAddress::ADDRESS_TYPE_SHIPPING,
            'customer_id'  => $mocks['customer']->id,
        ]));

        $I->seeRecord(OrderAddress::class, array_merge($addressData, [
            'order_id'     => $order->id,
            'address_type' => OrderAddress::ADDRESS_TYPE_BILLING,
            'customer_id'  => $mocks['customer']->id,
        ]));

        $I->seeRecord(OrderPayment::class, [
            'method'       => 'cashondelivery',
            'method_title' => null,
            'order_id'     => $order->id,
        ]);

        return $order;
    }
}
