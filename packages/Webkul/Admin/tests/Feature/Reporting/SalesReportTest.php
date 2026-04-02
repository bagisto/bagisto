<?php

use Carbon\Carbon;
use Webkul\Checkout\Models\Cart;
use Webkul\Checkout\Models\CartItem;
use Webkul\Customer\Models\Customer;
use Webkul\Sales\Models\Invoice;
use Webkul\Sales\Models\InvoiceItem;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderItem;
use Webkul\Sales\Models\OrderPayment;

use function Pest\Laravel\get;

/**
 * Create an order with an invoice for reporting tests.
 */
function createOrderWithInvoice(array $orderOverrides = [], array $invoiceOverrides = []): Order
{
    $paymentMethod = $orderOverrides['payment_method'] ?? 'cashondelivery';

    unset($orderOverrides['payment_method']);

    $customer = Customer::factory()->create();

    $order = Order::factory()->create(array_merge([
        'customer_id' => $customer->id,
        'customer_email' => $customer->email,
        'customer_first_name' => $customer->first_name,
        'customer_last_name' => $customer->last_name,
        'status' => 'completed',
    ], $orderOverrides));

    OrderPayment::factory()->create([
        'order_id' => $order->id,
        'method' => $paymentMethod,
    ]);

    $orderItem = OrderItem::factory()->create([
        'order_id' => $order->id,
        'product_id' => null,
        'sku' => fake()->uuid(),
        'type' => 'simple',
        'name' => fake()->words(3, true),
    ]);

    $invoice = Invoice::factory()->create(array_merge([
        'order_id' => $order->id,
        'state' => 'paid',
    ], $invoiceOverrides));

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

it('should return the sales reporting index page', function () {
    $this->loginAsAdmin();

    get(route('admin.reporting.sales.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.reporting.sales.index.title'))
        ->assertSeeText(trans('admin::app.reporting.sales.index.refunds'))
        ->assertSeeText(trans('admin::app.reporting.sales.index.total-sales'));
});

it('should deny guest access to the sales reporting page', function () {
    get(route('admin.reporting.sales.index'))
        ->assertRedirect(route('admin.session.create'));
});

// ============================================================================
// Stats
// ============================================================================

it('should return total sales stats', function () {
    $order = createOrderWithInvoice();

    $this->loginAsAdmin();

    get(route('admin.reporting.sales.stats', ['type' => 'total-sales']))
        ->assertOk()
        ->assertJsonPath('statistics.sales.progress', 100)
        ->assertJsonPath('statistics.over_time.current.30.count', 1)
        ->assertJsonPath('statistics.sales.formatted_total', core()->formatBasePrice($order->grand_total));
});

it('should return abandoned carts stats', function () {
    $product = $this->createSimpleProduct();
    $customer = Customer::factory()->create();

    $cart = Cart::factory()->create([
        'customer_id' => $customer->id,
        'customer_first_name' => $customer->first_name,
        'customer_last_name' => $customer->last_name,
        'customer_email' => $customer->email,
        'is_guest' => false,
        'is_active' => 1,
        'created_at' => Carbon::now()->subMonth()->toDateString(),
    ]);

    CartItem::factory()->create([
        'cart_id' => $cart->id,
        'product_id' => $product->id,
        'sku' => $product->sku,
        'quantity' => 1,
        'name' => $product->name,
        'price' => $product->price,
        'base_price' => $product->price,
        'total' => $product->price,
        'base_total' => $product->price,
        'weight' => $product->weight ?? 0,
        'type' => $product->type,
    ]);

    $this->loginAsAdmin();

    get(route('admin.reporting.sales.stats', [
        'start' => Carbon::now()->subMonth()->toDateString(),
        'type' => 'abandoned-carts',
    ]))
        ->assertOk()
        ->assertJsonStructure([
            'statistics' => ['sales', 'carts', 'rate', 'products'],
        ]);
});

it('should return total orders stats', function () {
    createOrderWithInvoice();

    $this->loginAsAdmin();

    get(route('admin.reporting.sales.stats', ['type' => 'total-orders']))
        ->assertOk()
        ->assertJsonStructure([
            'statistics' => ['orders' => ['current', 'previous', 'progress']],
        ]);
});

it('should return average sales stats', function () {
    createOrderWithInvoice();

    $this->loginAsAdmin();

    get(route('admin.reporting.sales.stats', ['type' => 'average-sales']))
        ->assertOk()
        ->assertJsonPath('statistics.over_time.current.30.count', 1);
});

it('should return shipping collected stats', function () {
    createOrderWithInvoice(['shipping_amount' => 10, 'base_shipping_amount' => 10]);

    $this->loginAsAdmin();

    get(route('admin.reporting.sales.stats', ['type' => 'shipping-collected']))
        ->assertOk()
        ->assertJsonStructure([
            'statistics' => ['shipping_collected' => ['current', 'previous', 'progress']],
        ]);
});

it('should return tax collected stats', function () {
    createOrderWithInvoice(['tax_amount' => 5, 'base_tax_amount' => 5]);

    $this->loginAsAdmin();

    get(route('admin.reporting.sales.stats', ['type' => 'tax-collected']))
        ->assertOk()
        ->assertJsonStructure([
            'statistics' => ['tax_collected' => ['current', 'previous', 'progress']],
        ]);
});

it('should return refunds stats', function () {
    createOrderWithInvoice();

    $this->loginAsAdmin();

    get(route('admin.reporting.sales.stats', ['type' => 'refunds']))
        ->assertOk()
        ->assertJsonStructure(['statistics']);
});

it('should return top payment methods stats', function () {
    createOrderWithInvoice(['payment_method' => 'cashondelivery']);

    $this->loginAsAdmin();

    get(route('admin.reporting.sales.stats', ['type' => 'top-payment-methods']))
        ->assertOk()
        ->assertJsonStructure(['statistics']);
});

// ============================================================================
// View
// ============================================================================

it('should return the sales report view page', function () {
    createOrderWithInvoice();

    $this->loginAsAdmin();

    get(route('admin.reporting.sales.view', ['type' => 'total-sales']))
        ->assertOk();
});

// ============================================================================
// Export
// ============================================================================

it('should export the sales stats', function () {
    createOrderWithInvoice();

    $this->loginAsAdmin();

    get(route('admin.reporting.sales.export', ['type' => 'total-sales', 'period' => 'day', 'format' => 'csv']))
        ->assertOk()
        ->assertDownload();
});
