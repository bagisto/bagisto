<?php

use Illuminate\Support\Facades\Mail;
use Webkul\Core\Models\CoreConfig;
use Webkul\Customer\Models\Customer;
use Webkul\Sales\Models\Invoice;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderAddress;
use Webkul\Sales\Models\OrderItem;
use Webkul\Sales\Models\OrderPayment;
use Webkul\Shop\Mail\Order\InvoicedNotification;

use function Pest\Laravel\get;
use function Pest\Laravel\postJson;

/**
 * Create an invoiceable order with a real product for qty tracking.
 */
function createInvoiceableOrder(): array
{
    $customer = Customer::factory()->create();

    $order = Order::factory()->create([
        'customer_id' => $customer->id,
        'customer_email' => $customer->email,
        'customer_first_name' => $customer->first_name,
        'customer_last_name' => $customer->last_name,
        'status' => 'pending',
    ]);

    OrderPayment::factory()->create([
        'order_id' => $order->id,
        'method' => 'cashondelivery',
    ]);

    $orderItem = OrderItem::factory()->create([
        'order_id' => $order->id,
        'product_id' => null,
        'sku' => fake()->uuid(),
        'type' => 'simple',
        'name' => fake()->words(3, true),
        'qty_ordered' => 2,
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

    return ['order' => $order, 'orderItem' => $orderItem];
}

// ============================================================================
// Index
// ============================================================================

it('should return the invoices index page', function () {
    $this->loginAsAdmin();

    get(route('admin.sales.invoices.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.sales.invoices.index.title'));
});

it('should deny guest access to the invoices index page', function () {
    get(route('admin.sales.invoices.index'))
        ->assertRedirect(route('admin.session.create'));
});

// ============================================================================
// Store
// ============================================================================

it('should store an invoice for an order', function () {
    $data = createInvoiceableOrder();

    $this->loginAsAdmin();

    postJson(route('admin.sales.invoices.store', $data['order']->id), [
        'invoice' => [
            'items' => [$data['orderItem']->id => 1],
        ],
    ])
        ->assertRedirect();

    $this->assertDatabaseHas('invoices', [
        'order_id' => $data['order']->id,
    ]);
});

it('should store an invoice and send email notification', function () {
    Mail::fake();

    CoreConfig::factory()->create([
        'code' => 'emails.general.notifications.emails.general.notifications.new_invoice',
        'value' => 1,
    ]);

    $data = createInvoiceableOrder();

    $this->loginAsAdmin();

    postJson(route('admin.sales.invoices.store', $data['order']->id), [
        'invoice' => [
            'items' => [$data['orderItem']->id => 1],
        ],
    ])
        ->assertRedirect();

    Mail::assertQueued(InvoicedNotification::class);
});

it('should fail validation when invoice items are missing on store', function () {
    $data = createInvoiceableOrder();

    $this->loginAsAdmin();

    postJson(route('admin.sales.invoices.store', $data['order']->id))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('invoice.items');
});

it('should fail validation when invoice item quantity is not numeric', function () {
    $data = createInvoiceableOrder();

    $this->loginAsAdmin();

    postJson(route('admin.sales.invoices.store', $data['order']->id), [
        'invoice' => [
            'items' => [$data['orderItem']->id => 'invalid'],
        ],
    ])
        ->assertUnprocessable();
});

// ============================================================================
// View
// ============================================================================

it('should return the invoice view page', function () {
    $data = createInvoiceableOrder();

    $this->loginAsAdmin();

    postJson(route('admin.sales.invoices.store', $data['order']->id), [
        'invoice' => [
            'items' => [$data['orderItem']->id => 1],
        ],
    ]);

    $invoice = Invoice::where('order_id', $data['order']->id)->first();

    get(route('admin.sales.invoices.view', $invoice->id))
        ->assertOk();
});

// ============================================================================
// Print
// ============================================================================

it('should download the invoice PDF', function () {
    $data = createInvoiceableOrder();

    $this->loginAsAdmin();

    postJson(route('admin.sales.invoices.store', $data['order']->id), [
        'invoice' => [
            'items' => [$data['orderItem']->id => 1],
        ],
    ]);

    $invoice = Invoice::where('order_id', $data['order']->id)->first();

    get(route('admin.sales.invoices.print', $invoice->id))
        ->assertOk();
});
