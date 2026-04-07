<?php

use Webkul\Customer\Models\Customer;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderAddress;
use Webkul\Sales\Models\OrderItem;
use Webkul\Sales\Models\OrderPayment;

use function Pest\Laravel\get;

/**
 * Create an order for a customer.
 */
function createCustomerOrder(Customer $customer): Order
{
    $order = Order::factory()->create([
        'customer_id' => $customer->id,
        'customer_email' => $customer->email,
        'customer_first_name' => $customer->first_name,
        'customer_last_name' => $customer->last_name,
        'status' => 'completed',
    ]);

    OrderPayment::factory()->create([
        'order_id' => $order->id,
        'method' => 'cashondelivery',
    ]);

    OrderItem::factory()->create([
        'order_id' => $order->id,
        'product_id' => null,
        'sku' => fake()->uuid(),
        'type' => 'simple',
        'name' => fake()->words(3, true),
    ]);

    OrderAddress::factory()->create([
        'order_id' => $order->id,
        'customer_id' => $customer->id,
        'address_type' => OrderAddress::ADDRESS_TYPE_BILLING,
    ]);

    OrderAddress::factory()->create([
        'order_id' => $order->id,
        'customer_id' => $customer->id,
        'address_type' => OrderAddress::ADDRESS_TYPE_SHIPPING,
    ]);

    return $order;
}

// ============================================================================
// Index
// ============================================================================

it('should return the customer orders page', function () {
    $this->loginAsCustomer();

    get(route('shop.customers.account.orders.index'))
        ->assertOk()
        ->assertSeeText(trans('shop::app.customers.account.orders.title'));
});

// ============================================================================
// View
// ============================================================================

it('should return the order view page', function () {
    $customer = Customer::factory()->create();
    $order = createCustomerOrder($customer);

    $this->loginAsCustomer($customer);

    get(route('shop.customers.account.orders.view', $order->id))
        ->assertOk();
});

// ============================================================================
// Cancel
// ============================================================================

it('should cancel a pending order', function () {
    $customer = Customer::factory()->create();

    $order = createCustomerOrder($customer);
    $order->update(['status' => 'pending']);

    $this->loginAsCustomer($customer);

    $this->post(route('shop.customers.account.orders.cancel', $order->id))
        ->assertRedirect();

    $this->assertDatabaseHas('orders', [
        'id' => $order->id,
        'status' => 'canceled',
    ]);
});

// ============================================================================
// Reorder
// ============================================================================

it('should reorder a completed order', function () {
    $customer = Customer::factory()->create();
    $product = $this->createSimpleProduct();

    $order = Order::factory()->create([
        'customer_id' => $customer->id,
        'customer_email' => $customer->email,
        'customer_first_name' => $customer->first_name,
        'customer_last_name' => $customer->last_name,
        'status' => 'completed',
    ]);

    OrderPayment::factory()->create(['order_id' => $order->id]);

    OrderItem::factory()->create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'sku' => $product->sku,
        'type' => 'simple',
        'name' => $product->name,
    ]);

    OrderAddress::factory()->create([
        'order_id' => $order->id,
        'customer_id' => $customer->id,
        'address_type' => OrderAddress::ADDRESS_TYPE_BILLING,
    ]);

    $this->loginAsCustomer($customer);

    get(route('shop.customers.account.orders.reorder', $order->id))
        ->assertRedirect();
});
