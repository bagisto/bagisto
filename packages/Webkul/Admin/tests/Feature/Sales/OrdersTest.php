<?php

use Illuminate\Support\Facades\Mail;
use Webkul\Customer\Models\Customer;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderAddress;
use Webkul\Sales\Models\OrderItem;
use Webkul\Sales\Models\OrderPayment;
use Webkul\Shop\Mail\Order\CommentedNotification;

use function Pest\Laravel\get;
use function Pest\Laravel\postJson;

/**
 * Create a complete order with payment, items, and addresses.
 */
function createOrder(array $orderOverrides = []): Order
{
    $customer = Customer::factory()->create();

    $order = Order::factory()->create(array_merge([
        'customer_id' => $customer->id,
        'customer_email' => $customer->email,
        'customer_first_name' => $customer->first_name,
        'customer_last_name' => $customer->last_name,
        'status' => 'pending',
    ], $orderOverrides));

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

it('should return the orders index page', function () {
    $this->loginAsAdmin();

    get(route('admin.sales.orders.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.sales.orders.index.title'));
});

it('should deny guest access to the orders index page', function () {
    get(route('admin.sales.orders.index'))
        ->assertRedirect(route('admin.session.create'));
});

// ============================================================================
// View
// ============================================================================

it('should return the order view page', function () {
    $order = createOrder();

    $this->loginAsAdmin();

    get(route('admin.sales.orders.view', $order->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.sales.orders.view.title', ['order_id' => $order->increment_id]));
});

// ============================================================================
// Cancel
// ============================================================================

it('should cancel a pending order', function () {
    $order = createOrder(['status' => 'pending']);

    $this->loginAsAdmin();

    postJson(route('admin.sales.orders.cancel', $order->id))
        ->assertRedirect(route('admin.sales.orders.view', $order->id));

    $this->assertDatabaseHas('orders', [
        'id' => $order->id,
        'status' => 'canceled',
    ]);
});

it('should cancel an order and verify the session success message', function () {
    $order = createOrder(['status' => 'pending']);

    $this->loginAsAdmin();

    postJson(route('admin.sales.orders.cancel', $order->id))
        ->assertRedirect(route('admin.sales.orders.view', $order->id))
        ->assertSessionHas('success');

    $this->assertDatabaseHas('orders', [
        'id' => $order->id,
        'status' => 'canceled',
    ]);
});

// ============================================================================
// Comment
// ============================================================================

it('should add a comment to an order', function () {
    $order = createOrder();

    $this->loginAsAdmin();

    postJson(route('admin.sales.orders.comment', $order->id), [
        'comment' => $comment = fake()->sentence(),
    ])
        ->assertRedirect();

    $this->assertDatabaseHas('order_comments', [
        'order_id' => $order->id,
        'comment' => $comment,
    ]);
});

it('should add a comment and notify the customer', function () {
    Mail::fake();

    $order = createOrder();

    $this->loginAsAdmin();

    postJson(route('admin.sales.orders.comment', $order->id), [
        'comment' => fake()->sentence(),
        'customer_notified' => 1,
    ])
        ->assertRedirect();

    Mail::assertQueued(CommentedNotification::class);
});

it('should fail validation when comment is missing', function () {
    $order = createOrder();

    $this->loginAsAdmin();

    postJson(route('admin.sales.orders.comment', $order->id))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('comment');
});

// ============================================================================
// Search
// ============================================================================

it('should search orders by customer email', function () {
    $order = createOrder();

    $this->loginAsAdmin();

    get(route('admin.sales.orders.search', ['query' => $order->customer_email]))
        ->assertOk()
        ->assertJsonFragment(['customer_email' => $order->customer_email]);
});
