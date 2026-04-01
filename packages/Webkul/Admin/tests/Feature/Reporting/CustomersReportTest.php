<?php

use Webkul\Customer\Models\Customer;
use Webkul\Product\Models\ProductReview;
use Webkul\Sales\Models\Invoice;
use Webkul\Sales\Models\InvoiceItem;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderItem;
use Webkul\Sales\Models\OrderPayment;

use function Pest\Laravel\get;

// ============================================================================
// Index
// ============================================================================

it('should return the customer reporting index page', function () {
    $this->loginAsAdmin();

    get(route('admin.reporting.customers.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.reporting.customers.index.title'))
        ->assertSeeText(trans('admin::app.reporting.customers.index.customers-with-most-orders'))
        ->assertSeeText(trans('admin::app.reporting.customers.index.customers-with-most-reviews'))
        ->assertSeeText(trans('admin::app.reporting.customers.index.customers-with-most-sales'))
        ->assertSeeText(trans('admin::app.reporting.customers.index.top-customer-groups'))
        ->assertSeeText(trans('admin::app.reporting.customers.index.total-customers'));
});

it('should deny guest access to the customer reporting page', function () {
    get(route('admin.reporting.customers.index'))
        ->assertRedirect(route('admin.session.create'));
});

// ============================================================================
// Stats
// ============================================================================

it('should return total customers stats', function () {
    Customer::factory()->count(2)->create();

    $this->loginAsAdmin();

    get(route('admin.reporting.customers.stats', ['type' => 'total-customers']))
        ->assertOk()
        ->assertJsonStructure([
            'statistics' => ['customers' => ['current', 'previous', 'progress']],
        ]);
});

it('should return customers with most reviews stats', function () {
    $product = $this->createSimpleProduct();
    $customer = Customer::factory()->create();

    ProductReview::factory()->count(2)->create([
        'status' => 'approved',
        'customer_id' => $customer->id,
        'name' => $customer->name,
        'product_id' => $product->id,
    ]);

    $this->loginAsAdmin();

    get(route('admin.reporting.customers.stats', ['type' => 'customers-with-most-reviews']))
        ->assertOk()
        ->assertJsonPath('statistics.0.email', $customer->email)
        ->assertJsonPath('statistics.0.reviews', 2);
});

it('should return top customer groups stats', function () {
    Customer::factory()->create();

    $this->loginAsAdmin();

    get(route('admin.reporting.customers.stats', ['type' => 'top-customer-groups']))
        ->assertOk()
        ->assertJsonPath('statistics.0.group_name', 'General');
});

it('should return customers with most orders stats', function () {
    $customer = Customer::factory()->create();

    $order = Order::factory()->create([
        'customer_id' => $customer->id,
        'customer_email' => $customer->email,
        'customer_first_name' => $customer->first_name,
        'customer_last_name' => $customer->last_name,
        'status' => 'completed',
    ]);

    OrderPayment::factory()->create(['order_id' => $order->id]);

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

    $this->loginAsAdmin();

    get(route('admin.reporting.customers.stats', ['type' => 'customers-with-most-orders']))
        ->assertOk()
        ->assertJsonPath('statistics.0.id', $customer->id)
        ->assertJsonPath('statistics.0.email', $customer->email)
        ->assertJsonPath('statistics.0.full_name', $customer->name);
});

it('should return customers with most sales stats', function () {
    $customer = Customer::factory()->create();

    $order = Order::factory()->create([
        'customer_id' => $customer->id,
        'customer_email' => $customer->email,
        'customer_first_name' => $customer->first_name,
        'customer_last_name' => $customer->last_name,
        'status' => 'completed',
    ]);

    OrderPayment::factory()->create(['order_id' => $order->id]);

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

    $this->loginAsAdmin();

    get(route('admin.reporting.customers.stats', ['type' => 'customers-with-most-sales']))
        ->assertOk()
        ->assertJsonPath('statistics.0.id', $customer->id)
        ->assertJsonPath('statistics.0.email', $customer->email)
        ->assertJsonPath('statistics.0.full_name', $customer->name);
});
