<?php

use Webkul\Sales\Models\Invoice;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderTransaction;

use function Pest\Laravel\get;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

/**
 * Create a money transfer order awaiting payment, with an unpaid invoice for the given amount.
 */
function orderAwaitingPayment(float $amount = 100): Order
{
    $order = test()->createOrder([
        'status' => Order::STATUS_PENDING_PAYMENT,
        'payment_method' => 'moneytransfer',
    ], [['product' => test()->createSimpleProduct(), 'price' => $amount]]);

    test()->invoiceOrder($order, state: Invoice::STATUS_PENDING, orderState: Order::STATUS_PENDING_PAYMENT);

    return $order->refresh()->load('invoices');
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

it('should record a full payment, mark the invoice paid and move the order to processing', function () {
    $order = orderAwaitingPayment(100);

    $invoice = $order->invoices->first();

    $this->loginAsAdmin();

    postJson(route('admin.sales.transactions.store'), [
        'invoice_id' => $invoice->id,
        'payment_method' => 'moneytransfer',
        'amount' => 100,
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.sales.transactions.index.create.transaction-saved'));

    $this->assertDatabaseHas('order_transactions', [
        'order_id' => $order->id,
        'invoice_id' => $invoice->id,
        'payment_method' => 'moneytransfer',
        'status' => 'paid',
    ]);

    $this->assertDatabaseHas('invoices', [
        'id' => $invoice->id,
        'state' => Invoice::STATUS_PAID,
    ]);

    $this->assertDatabaseHas('orders', [
        'id' => $order->id,
        'status' => Order::STATUS_PROCESSING,
    ]);
});

it('should complete the order when the invoice it pays was already shipped', function () {
    $order = orderAwaitingPayment(100);

    $this->shipOrder($order);

    $this->loginAsAdmin();

    postJson(route('admin.sales.transactions.store'), [
        'invoice_id' => $order->invoices->first()->id,
        'payment_method' => 'moneytransfer',
        'amount' => 100,
    ])
        ->assertOk();

    $this->assertDatabaseHas('orders', [
        'id' => $order->id,
        'status' => Order::STATUS_COMPLETED,
    ]);
});

it('should leave the invoice unpaid after a partial payment', function () {
    $order = orderAwaitingPayment(100);

    $invoice = $order->invoices->first();

    $this->loginAsAdmin();

    postJson(route('admin.sales.transactions.store'), [
        'invoice_id' => $invoice->id,
        'payment_method' => 'moneytransfer',
        'amount' => 40,
    ])
        ->assertOk();

    $this->assertDatabaseHas('order_transactions', [
        'invoice_id' => $invoice->id,
        'amount' => 40,
    ]);

    $this->assertDatabaseHas('invoices', [
        'id' => $invoice->id,
        'state' => Invoice::STATUS_PENDING,
    ]);

    $this->assertDatabaseHas('orders', [
        'id' => $order->id,
        'status' => Order::STATUS_PENDING_PAYMENT,
    ]);
});

it('should refuse a transaction for an invoice that does not exist', function () {
    $this->loginAsAdmin();

    postJson(route('admin.sales.transactions.store'), [
        'invoice_id' => Invoice::query()->max('id') + 1,
        'payment_method' => 'moneytransfer',
        'amount' => 10,
    ])
        ->assertBadRequest()
        ->assertJsonPath('message', trans('admin::app.sales.transactions.index.create.invoice-missing'));
});

it('should refuse a transaction for an invoice that is already paid', function () {
    $order = $this->createOrder();

    $invoice = $this->invoiceOrder($order);

    $this->loginAsAdmin();

    postJson(route('admin.sales.transactions.store'), [
        'invoice_id' => $invoice->id,
        'payment_method' => 'moneytransfer',
        'amount' => 10,
    ])
        ->assertBadRequest()
        ->assertJsonPath('message', trans('admin::app.sales.transactions.index.create.already-paid'));

    $this->assertDatabaseMissing('order_transactions', ['invoice_id' => $invoice->id]);
});

it('should refuse a transaction that exceeds the invoice total', function () {
    $order = orderAwaitingPayment(100);

    $this->loginAsAdmin();

    postJson(route('admin.sales.transactions.store'), [
        'invoice_id' => $order->invoices->first()->id,
        'payment_method' => 'moneytransfer',
        'amount' => 100.01,
    ])
        ->assertBadRequest()
        ->assertJsonPath('message', trans('admin::app.sales.transactions.index.create.transaction-amount-exceeds'));

    $this->assertDatabaseMissing('order_transactions', ['order_id' => $order->id]);
});

it('should refuse a transaction of a zero or negative amount', function (float $amount) {
    $order = orderAwaitingPayment(100);

    $this->loginAsAdmin();

    postJson(route('admin.sales.transactions.store'), [
        'invoice_id' => $order->invoices->first()->id,
        'payment_method' => 'moneytransfer',
        'amount' => $amount,
    ])
        ->assertBadRequest()
        ->assertJsonPath('message', trans('admin::app.sales.transactions.index.create.transaction-amount-zero'));

    $this->assertDatabaseMissing('order_transactions', ['order_id' => $order->id]);
})->with([
    'zero' => [0],
    'negative' => [-10],
]);

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
    $order = orderAwaitingPayment(100);

    $transaction = OrderTransaction::factory()->create([
        'transaction_id' => md5(uniqid()),
        'type' => 'moneytransfer',
        'payment_method' => 'moneytransfer',
        'status' => 'paid',
        'order_id' => $order->id,
        'invoice_id' => $order->invoices->first()->id,
        'amount' => 100,
    ]);

    $this->loginAsAdmin();

    getJson(route('admin.sales.transactions.view', $transaction->id))
        ->assertOk()
        ->assertJsonPath('data.id', $transaction->id)
        ->assertJsonPath('data.transaction_id', $transaction->transaction_id)
        ->assertJsonPath('data.order_id', $order->id);
});
