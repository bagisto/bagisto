<?php

use Webkul\Customer\Models\Customer;
use Webkul\Sales\Models\Invoice;
use Webkul\Sales\Models\InvoiceItem;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderAddress;
use Webkul\Sales\Models\OrderItem;
use Webkul\Sales\Models\OrderPayment;

use function Pest\Laravel\get;

/**
 * Create an order with invoice for dashboard stats.
 */
function createDashboardOrder(): Order
{
    $customer = Customer::factory()->create();

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

    $orderItem = OrderItem::factory()->create([
        'order_id' => $order->id,
        'product_id' => null,
        'sku' => fake()->uuid(),
        'type' => 'simple',
        'name' => fake()->words(3, true),
    ]);

    $invoice = Invoice::factory()->create([
        'order_id' => $order->id,
        'state' => 'paid',
    ]);

    InvoiceItem::factory()->create([
        'invoice_id' => $invoice->id,
        'order_item_id' => $orderItem->id,
        'name' => $orderItem->name,
        'sku' => $orderItem->sku,
        'qty' => 1,
        'price' => $orderItem->price,
        'base_price' => $orderItem->base_price,
        'total' => $orderItem->price,
        'base_total' => $orderItem->base_price,
        'product_id' => $orderItem->product_id,
        'product_type' => $orderItem->product_type,
    ]);

    return $order;
}

// ============================================================================
// Index
// ============================================================================

it('should return the dashboard index page', function () {
    $this->loginAsAdmin();

    get(route('admin.dashboard.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.dashboard.index.title'))
        ->assertSeeText(trans('admin::app.dashboard.index.overall-details'))
        ->assertSeeText(trans('admin::app.dashboard.index.total-sales'))
        ->assertSeeText(trans('admin::app.dashboard.index.today-sales'));
});

it('should deny guest access to the dashboard', function () {
    get(route('admin.dashboard.index'))
        ->assertRedirect(route('admin.session.create'));
});

// ============================================================================
// Stats
// ============================================================================

it('should return the overall dashboard stats', function () {
    createDashboardOrder();

    $this->loginAsAdmin();

    get(route('admin.dashboard.stats', ['type' => 'over-all']))
        ->assertOk()
        ->assertJsonStructure([
            'statistics' => ['total_customers', 'total_orders', 'total_sales'],
        ]);
});

it('should return the today dashboard stats', function () {
    createDashboardOrder();

    $this->loginAsAdmin();

    get(route('admin.dashboard.stats', ['type' => 'today']))
        ->assertOk()
        ->assertJsonStructure([
            'statistics' => ['total_customers', 'total_orders', 'total_sales'],
        ]);
});

it('should return the stock threshold products stats', function () {
    $this->createSimpleProduct();

    $this->loginAsAdmin();

    get(route('admin.dashboard.stats', ['type' => 'stock-threshold-products']))
        ->assertOk()
        ->assertJsonStructure(['statistics']);
});

it('should return the total sales stats', function () {
    createDashboardOrder();

    $this->loginAsAdmin();

    get(route('admin.dashboard.stats', ['type' => 'total-sales']))
        ->assertOk()
        ->assertJsonStructure([
            'statistics' => ['over_time'],
        ]);
});

it('should return the top selling products stats', function () {
    $product = $this->createSimpleProduct();
    $customer = Customer::factory()->create();

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
        'qty_ordered' => 5,
    ]);

    $this->loginAsAdmin();

    get(route('admin.dashboard.stats', ['type' => 'top-selling-products']))
        ->assertOk()
        ->assertJsonStructure(['statistics']);
});

it('should return the top customers stats', function () {
    createDashboardOrder();

    $this->loginAsAdmin();

    get(route('admin.dashboard.stats', ['type' => 'top-customers']))
        ->assertOk()
        ->assertJsonStructure(['statistics']);
});
