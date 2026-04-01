<?php

use Webkul\Customer\Models\Customer;
use Webkul\Customer\Models\Wishlist;
use Webkul\Marketing\Models\SearchTerm;
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

it('should return the product reporting index page', function () {
    $this->loginAsAdmin();

    get(route('admin.reporting.products.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.reporting.products.index.title'))
        ->assertSeeText(trans('admin::app.reporting.products.index.last-search-terms'))
        ->assertSeeText(trans('admin::app.reporting.products.index.products-with-most-reviews'))
        ->assertSeeText(trans('admin::app.reporting.products.index.top-selling-products-by-quantity'))
        ->assertSeeText(trans('admin::app.reporting.products.index.top-selling-products-by-revenue'))
        ->assertSeeText(trans('admin::app.reporting.products.index.top-search-terms'));
});

it('should deny guest access to the product reporting page', function () {
    get(route('admin.reporting.products.index'))
        ->assertRedirect(route('admin.session.create'));
});

// ============================================================================
// Stats
// ============================================================================

it('should return total sold quantities stats', function () {
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
        'type' => $product->type,
        'name' => $product->name,
        'qty_ordered' => 3,
    ]);

    $this->loginAsAdmin();

    get(route('admin.reporting.products.stats', ['type' => 'total-sold-quantities']))
        ->assertOk()
        ->assertJsonStructure([
            'statistics' => ['quantities' => ['current', 'previous', 'progress']],
        ]);
});

it('should return total products added to wishlist stats', function () {
    $product = $this->createSimpleProduct();
    $customer = Customer::factory()->create();

    Wishlist::factory()->create([
        'product_id' => $product->id,
        'customer_id' => $customer->id,
        'channel_id' => core()->getCurrentChannel()->id,
    ]);

    $this->loginAsAdmin();

    get(route('admin.reporting.products.stats', ['type' => 'total-products-added-to-wishlist']))
        ->assertOk()
        ->assertJsonStructure([
            'statistics' => ['wishlist' => ['current', 'previous', 'progress']],
        ]);
});

it('should return top selling products by revenue stats', function () {
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

    $orderItem = OrderItem::factory()->create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'sku' => $product->sku,
        'type' => $product->type,
        'name' => $product->name,
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

    get(route('admin.reporting.products.stats', ['type' => 'top-selling-products-by-revenue']))
        ->assertOk()
        ->assertJsonStructure(['statistics']);
});

it('should return top selling products by quantity stats', function () {
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
        'type' => $product->type,
        'name' => $product->name,
        'qty_ordered' => 5,
    ]);

    $this->loginAsAdmin();

    get(route('admin.reporting.products.stats', ['type' => 'top-selling-products-by-quantity']))
        ->assertOk()
        ->assertJsonStructure(['statistics']);
});

it('should return products with most reviews stats', function () {
    $product = $this->createSimpleProduct();
    $customer = Customer::factory()->create();

    ProductReview::factory()->count(2)->create([
        'status' => 'approved',
        'customer_id' => $customer->id,
        'name' => $customer->name,
        'product_id' => $product->id,
    ]);

    $this->loginAsAdmin();

    get(route('admin.reporting.products.stats', ['type' => 'products-with-most-reviews']))
        ->assertOk()
        ->assertJsonPath('statistics.0.product.id', $product->id)
        ->assertJsonPath('statistics.0.reviews', 2);
});

it('should return last search terms stats', function () {
    $this->createSimpleProduct();

    SearchTerm::factory()->create();

    $this->loginAsAdmin();

    get(route('admin.reporting.products.stats', ['type' => 'last-search-terms']))
        ->assertOk()
        ->assertJsonStructure(['statistics']);
});

it('should return top search terms stats', function () {
    $this->createSimpleProduct();

    SearchTerm::factory()->create();

    $this->loginAsAdmin();

    get(route('admin.reporting.products.stats', ['type' => 'top-search-terms']))
        ->assertOk()
        ->assertJsonStructure(['statistics']);
});

// ============================================================================
// View
// ============================================================================

it('should return the product report view page', function () {
    $this->loginAsAdmin();

    get(route('admin.reporting.products.view', ['type' => 'total-sold-quantities']))
        ->assertOk();
});

// ============================================================================
// Export
// ============================================================================

it('should export the product stats', function () {
    $this->loginAsAdmin();

    get(route('admin.reporting.products.export', ['type' => 'total-sold-quantities', 'period' => 'day', 'format' => 'csv']))
        ->assertOk()
        ->assertDownload();
});
