<?php

use Webkul\Customer\Models\Customer;
use Webkul\Sales\Models\Invoice;
use Webkul\Sales\Models\InvoiceItem;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderAddress;
use Webkul\Sales\Models\OrderItem;
use Webkul\Sales\Models\OrderPayment;
use Webkul\Sales\Models\OrderTransaction;

use function Pest\Laravel\get;
use function Pest\Laravel\postJson;

/**
 * Create an order with a pending invoice for transaction tests.
 */
function createOrderWithPendingInvoice(): array
{
    $customer = Customer::factory()->create();

    $order = Order::factory()->create([
        'customer_id' => $customer->id,
        'customer_email' => $customer->email,
        'customer_first_name' => $customer->first_name,
        'customer_last_name' => $customer->last_name,
        'status' => 'pending_payment',
    ]);

    OrderPayment::factory()->create([
        'order_id' => $order->id,
        'method' => 'moneytransfer',
    ]);

    $orderItem = OrderItem::factory()->create([
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

    $invoice = Invoice::factory()->create([
        'order_id' => $order->id,
        'state' => 'pending',
        'grand_total' => $orderItem->price,
        'base_grand_total' => $orderItem->base_price,
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

    return ['order' => $order, 'invoice' => $invoice];
}

// ============================================================================
// Index
// ============================================================================

it('should return the transactions index page', function () {
    $this->loginAsAdmin();

    get(route('admin.sales.transactions.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.sales.transactions.index.title'));
});

it('should deny guest access to the transactions index page', function () {
    get(route('admin.sales.transactions.index'))
        ->assertRedirect(route('admin.session.create'));
});

// ============================================================================
// Store
// ============================================================================

it('should store a transaction for a pending invoice', function () {
    $data = createOrderWithPendingInvoice();

    $this->loginAsAdmin();

    postJson(route('admin.sales.transactions.store'), [
        'invoice_id' => $data['invoice']->id,
        'payment_method' => 'moneytransfer',
        'amount' => $data['invoice']->grand_total,
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.sales.transactions.index.create.transaction-saved'));

    $this->assertDatabaseHas('order_transactions', [
        'order_id' => $data['order']->id,
        'invoice_id' => $data['invoice']->id,
        'status' => 'paid',
    ]);
});

it('should fail validation when required fields are missing on store', function () {
    $this->loginAsAdmin();

    postJson(route('admin.sales.transactions.store'))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('invoice_id')
        ->assertJsonValidationErrorFor('payment_method')
        ->assertJsonValidationErrorFor('amount');
});

// ============================================================================
// View
// ============================================================================

it('should return the transaction details', function () {
    $data = createOrderWithPendingInvoice();

    $transaction = OrderTransaction::factory()->create([
        'transaction_id' => md5(uniqid()),
        'type' => 'moneytransfer',
        'payment_method' => 'moneytransfer',
        'status' => 'paid',
        'order_id' => $data['order']->id,
        'invoice_id' => $data['invoice']->id,
        'amount' => $data['invoice']->grand_total,
    ]);

    $this->loginAsAdmin();

    get(route('admin.sales.transactions.view', $transaction->id))
        ->assertOk();
});
