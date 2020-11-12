<?php

namespace Tests\Functional\Admin\Sales;

use Faker\Factory;
use FunctionalTester;
use Codeception\Util\Locator;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderItem;
use Webkul\Sales\Models\OrderAddress;
use Webkul\Sales\Models\OrderPayment;
use Webkul\Core\Helpers\Laravel5Helper;

class OrderCest
{
    public function testIndex(FunctionalTester $I): void
    {
        /* simple order no further association like address, shipping method, payment method, etc. */
        $order = $I->have(Order::class);

        /* login as admin */
        $I->loginAsAdmin();

        /* go to order view page */
        $I->amOnAdminRoute('admin.dashboard.index');
        $I->click(__('admin::app.layouts.sales'), '//*[contains(@class, "navbar-left")]');
        $I->seeCurrentRouteIs('admin.sales.orders.index');
        $I->click(__('admin::app.layouts.orders'), '//*[contains(@class, "aside-nav")]');

        /* now test index page */
        $I->seeCurrentRouteIs('admin.sales.orders.index');
        $I->see($order->id, '//script[@type="text/x-template"]');
        $I->see($order->sub_total, '//script[@type="text/x-template"]');
    }

    public function testCancelCashOnDeliveryOrder(FunctionalTester $I): void
    {
        /* generate cash on delivery order */
        $order = $this->generateCashOnDeliveryOrder($I);

        /* login as admin */
        $I->loginAsAdmin();

        /* go to order view page */
        $I->amOnPage(route('admin.sales.orders.view', $order->id));
        $I->seeCurrentRouteIs('admin.sales.orders.view');

        /* now test cancel order */
        $I->see('Cancel', Locator::href(route('admin.sales.orders.cancel', $order->id)));
        $I->click('Cancel', Locator::href(route('admin.sales.orders.cancel', $order->id)));
        $I->seeCurrentRouteIs('admin.sales.orders.view');
        $I->see(0.00, '#due-amount-on-cancelled');
    }

    private function generateCashOnDeliveryOrder(FunctionalTester $I)
    {
        $product = $I->haveProduct(Laravel5Helper::SIMPLE_PRODUCT, [
            'productAttributes' => [],
            'productInventory'  => [
                'qty' => 5,
            ],
            'attributeValues'   => [
                'status' => 1,
            ],
        ]);

        $order = $I->have(OrderItem::class, ['product_id' => $product->id])->order;

        $I->have(OrderAddress::class, $this->generateAddressData([
            'order_id'     => $order->id,
            'address_type' => OrderAddress::ADDRESS_TYPE_SHIPPING,
            'customer_id'  => $order->customer->id,
        ]));

        $I->have(OrderAddress::class, $this->generateAddressData([
            'order_id'     => $order->id,
            'address_type' => OrderAddress::ADDRESS_TYPE_BILLING,
            'customer_id'  => $order->customer->id,
        ]));

        $I->have(OrderPayment::class, [
            'method'       => 'cashondelivery',
            'method_title' => null,
            'order_id'     => $order->id,
        ]);

        return $order;
    }

    private function generateAddressData(array $additionalData): array
    {
        $faker = Factory::create();

        return array_merge([
            'city'         => $faker->city,
            'company_name' => $faker->company,
            'country'      => $faker->countryCode,
            'email'        => $faker->email,
            'first_name'   => $faker->firstName,
            'last_name'    => $faker->lastName,
            'phone'        => $faker->phoneNumber,
            'postcode'     => $faker->postcode,
            'state'        => $faker->state,
        ], $additionalData);
    }
}
