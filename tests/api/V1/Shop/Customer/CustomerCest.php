<?php

namespace Tests\API\V1\Shop\Customer;

use ApiTester;
use Tests\API\V1\BaseCest;
use Webkul\Sales\Models\OrderAddress;
use Webkul\Sales\Models\OrderItem;
use Webkul\Sales\Models\OrderPayment;

class CustomerCest extends BaseCest
{
    protected function generateCashOnDeliveryOrder(ApiTester $I)
    {
        $product = $product = $I->haveSimpleProduct();

        $order = $I->have(OrderItem::class, ['product_id' => $product->id])->order;

        $I->have(OrderAddress::class, $this->generateAddressData($I, [
            'order_id'     => $order->id,
            'address_type' => OrderAddress::ADDRESS_TYPE_SHIPPING,
            'customer_id'  => $order->customer->id,
        ]));

        $I->have(OrderAddress::class, $this->generateAddressData($I, [
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

    protected function generateAddressData(ApiTester $I, array $additionalData): array
    {
        $faker = $I->fake();

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
