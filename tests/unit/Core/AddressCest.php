<?php


namespace Tests\Unit\Core;


use UnitTester;
use Webkul\Checkout\Models\Cart;
use Webkul\Checkout\Models\CartAddress;
use Webkul\Checkout\Models\CartShippingRate;
use Webkul\Customer\Models\Customer;
use Webkul\Customer\Models\CustomerAddress;
use Webkul\Sales\Models\Invoice;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderAddress;
use Webkul\Sales\Models\Shipment;
use Webkul\Shipping\Carriers\Free;

class AddressCest
{
    public function testCustomerAddressRelations(UnitTester $I): void
    {
        /** @var Customer $customer1 */
        $customer1 = $I->have(Customer::class);
        CustomerAddress::create(['customer_id' => $customer1->id]);

        /** @var Customer $customer2 */
        $customer2 = $I->have(Customer::class);
        $address1 = CustomerAddress::create(['customer_id' => $customer2->id]);

        $customer2->refresh();
        $I->assertCount(1, $customer2->addresses);
        $I->assertEquals($address1->id, $customer2->addresses[0]->id);
        $I->assertNull($customer2->default_address);

        $address2 = CustomerAddress::create(['customer_id' => $customer2->id, 'default_address' => true]);
        $customer2->refresh();
        $I->assertCount(2, $customer2->addresses);
        $I->assertEquals($address2->id, $customer2->default_address->id);
    }

    public function testCartAddressRelations(UnitTester $I): void
    {
        /** @var Cart $cart */
        $cart = $I->have(Cart::class);
        $address1 = CartAddress::create(['cart_id' => $cart->id]);
        $address2 = CartAddress::create([
            'cart_id'      => $cart->id,
            'address_type' => CartAddress::ADDRESS_TYPE_SHIPPING
        ]);
        $cart->refresh();

        $I->assertNotNull($address1->address_type);
        $I->assertEquals($address1->id, $cart->billing_address->id);
        $I->assertEquals($address2->id, $cart->shipping_address->id);

        /** @var CartShippingRate $freeShipping */
        $freeShipping = (new Free())->calculate();
        $freeShipping->cart_address_id = $address2->id;
        $freeShipping->saveOrFail();

        $freeShipping->refresh();
        $I->assertEquals($address2->id, $freeShipping->shipping_address->id);
    }

    public function testOrderAddressRelations(UnitTester $I): void
    {
        /** @var Order $order */
        $order = $I->have(Order::class);
        $address1 = OrderAddress::create(['order_id' => $order->id]);
        $address2 = OrderAddress::create([
            'order_id'     => $order->id,
            'address_type' => OrderAddress::ADDRESS_TYPE_SHIPPING
        ]);
        $order->refresh();

        $I->assertNotNull($address1->address_type);
        $I->assertEquals($address1->id, $order->billing_address->id);
        $I->assertEquals($address2->id, $order->shipping_address->id);

        /** @var Shipment $shipment */
        $shipment = Shipment::create(['order_address_id' => $address2->id]);
        $I->assertEquals($address2->id, $shipment->address->id);

        /** @var Invoice $invoice */
        $invoice = Invoice::create(['order_id' => $order->id, 'order_address_id' => $address1->id]);
        $I->assertEquals($address1->id, $invoice->address->id);
    }
}