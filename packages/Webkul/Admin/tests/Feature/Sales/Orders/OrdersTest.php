<?php

use Illuminate\Support\Facades\Mail;
use Webkul\Checkout\Models\Cart;
use Webkul\Product\Models\ProductOrderedInventory;
use Webkul\Sales\Models\Order;
use Webkul\Shop\Mail\Order\CommentedNotification;

use function Pest\Laravel\get;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

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

it('should list an order in the orders datagrid by its increment id', function () {
    $order = $this->createOrder();

    $this->loginAsAdmin();

    getJson(route('admin.sales.orders.index', [
        'filters' => ['increment_id' => [$order->increment_id]],
    ]), ['X-Requested-With' => 'XMLHttpRequest'])
        ->assertOk()
        ->assertJsonPath('meta.total', 1)
        ->assertJsonPath('records.0.id', $order->id)
        ->assertJsonPath('records.0.customer_email', $order->customer_email);
});

// ============================================================================
// View
// ============================================================================

it('should return the order view page', function () {
    $order = $this->createOrder();

    $this->loginAsAdmin();

    get(route('admin.sales.orders.view', $order->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.sales.orders.view.title', ['order_id' => $order->increment_id]))
        ->assertSeeText($order->customer_email);
});

it('should return not found for an order that does not exist', function () {
    $this->loginAsAdmin();

    get(route('admin.sales.orders.view', Order::query()->max('id') + 1))
        ->assertNotFound();
});

// ============================================================================
// Cancel
// ============================================================================

it('should cancel a pending order, mark its items canceled and release the ordered stock', function () {
    $product = $this->createSimpleProduct();

    $order = $this->createOrder(items: [['product' => $product, 'qty_ordered' => 2]]);

    ProductOrderedInventory::query()->create([
        'product_id' => $product->id,
        'channel_id' => $order->channel_id,
        'qty' => 2,
    ]);

    $this->loginAsAdmin();

    postJson(route('admin.sales.orders.cancel', $order->id))
        ->assertRedirect(route('admin.sales.orders.view', $order->id))
        ->assertSessionHas('success', trans('admin::app.sales.orders.view.cancel-success'));

    $this->assertDatabaseHas('orders', [
        'id' => $order->id,
        'status' => Order::STATUS_CANCELED,
    ]);

    $this->assertDatabaseHas('order_items', [
        'order_id' => $order->id,
        'qty_canceled' => 2,
    ]);

    $this->assertDatabaseHas('product_ordered_inventories', [
        'product_id' => $product->id,
        'channel_id' => $order->channel_id,
        'qty' => 0,
    ]);
});

it('should refuse to cancel an order whose items are already invoiced', function () {
    $order = $this->createOrder(items: [['product' => $this->createSimpleProduct(), 'qty_ordered' => 2]]);

    $this->invoiceOrder($order);

    $this->loginAsAdmin();

    postJson(route('admin.sales.orders.cancel', $order->id))
        ->assertRedirect(route('admin.sales.orders.view', $order->id))
        ->assertSessionHas('error', trans('admin::app.sales.orders.view.cancel-error'));

    $this->assertDatabaseHas('orders', [
        'id' => $order->id,
        'status' => Order::STATUS_PROCESSING,
    ]);

    $this->assertDatabaseHas('order_items', [
        'order_id' => $order->id,
        'qty_canceled' => 0,
    ]);
});

// ============================================================================
// Comment
// ============================================================================

it('should add a comment to an order', function () {
    $order = $this->createOrder();

    $this->loginAsAdmin();

    postJson(route('admin.sales.orders.comment', $order->id), [
        'comment' => $comment = fake()->sentence(),
    ])
        ->assertRedirect(route('admin.sales.orders.view', $order->id))
        ->assertSessionHas('success', trans('admin::app.sales.orders.view.comment-success'));

    $this->assertDatabaseHas('order_comments', [
        'order_id' => $order->id,
        'comment' => $comment,
        'customer_notified' => 0,
    ]);
});

it('should add a comment and notify the customer', function () {
    Mail::fake();

    $order = $this->createOrder();

    $this->loginAsAdmin();

    postJson(route('admin.sales.orders.comment', $order->id), [
        'comment' => $comment = fake()->sentence(),
        'customer_notified' => 1,
    ])
        ->assertRedirect(route('admin.sales.orders.view', $order->id));

    $this->assertDatabaseHas('order_comments', [
        'order_id' => $order->id,
        'comment' => $comment,
        'customer_notified' => 1,
    ]);

    Mail::assertQueued(CommentedNotification::class, fn (CommentedNotification $mail) => $mail->hasTo($order->customer_email));
});

it('should fail validation when comment is missing', function () {
    $order = $this->createOrder();

    $this->loginAsAdmin();

    postJson(route('admin.sales.orders.comment', $order->id))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('comment');

    $this->assertDatabaseMissing('order_comments', ['order_id' => $order->id]);
});

// ============================================================================
// Search
// ============================================================================

it('should search orders by customer email regardless of case', function () {
    $order = $this->createOrder();

    $this->loginAsAdmin();

    get(route('admin.sales.orders.search', ['query' => strtoupper($order->customer_email)]))
        ->assertOk()
        ->assertJsonFragment(['customer_email' => $order->customer_email]);
});

it('should search orders by their increment id', function () {
    $order = $this->createOrder();

    $this->loginAsAdmin();

    get(route('admin.sales.orders.search', ['query' => $order->increment_id]))
        ->assertOk()
        ->assertJsonPath('data.0.id', $order->id);
});

// ============================================================================
// Reorder
// ============================================================================

it('should build an inactive cart holding the items of the order for its customer', function () {
    $product = $this->createSimpleProduct();

    $order = $this->createOrder(items: [['product' => $product, 'qty_ordered' => 2]]);

    $this->loginAsAdmin();

    $response = get(route('admin.sales.orders.reorder', $order->id));

    $cart = Cart::query()->where('customer_id', $order->customer_id)->latest('id')->firstOrFail();

    $response->assertRedirect(route('admin.sales.orders.create', $cart->id));

    expect($cart->is_active)->toBeFalsy();

    $this->assertDatabaseHas('cart_items', [
        'cart_id' => $cart->id,
        'product_id' => $product->id,
        'quantity' => 2,
    ]);
});

it('should refuse to reorder a guest order', function () {
    $order = $this->createGuestOrder();

    $this->loginAsAdmin();

    get(route('admin.sales.orders.reorder', $order->id))
        ->assertRedirect(route('admin.sales.orders.view', $order->id))
        ->assertSessionHas('error', trans('admin::app.sales.orders.view.reorder-customer-missing'));

    $this->assertDatabaseMissing('cart', ['customer_email' => $order->customer_email]);
});
