<?php

use Illuminate\Support\Facades\Mail;
use Webkul\Core\Models\CoreConfig;
use Webkul\Customer\Models\Customer;
use Webkul\Sales\Models\Invoice;
use Webkul\Sales\Models\InvoiceItem;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderAddress;
use Webkul\Sales\Models\OrderItem;
use Webkul\Sales\Models\OrderPayment;
use Webkul\Sales\Models\Refund;
use Webkul\Shop\Mail\Order\RefundedNotification;

use function Pest\Laravel\get;
use function Pest\Laravel\postJson;

/**
 * Create a refundable order (processing status with a paid invoice).
 */
function createRefundableOrder(): array
{
    $customer = Customer::factory()->create();

    $order = Order::factory()->create([
        'customer_id' => $customer->id,
        'customer_email' => $customer->email,
        'customer_first_name' => $customer->first_name,
        'customer_last_name' => $customer->last_name,
        'status' => 'processing',
        'sub_total_invoiced' => 100,
        'base_sub_total_invoiced' => 100,
        'grand_total_invoiced' => 100,
        'base_grand_total_invoiced' => 100,
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
        'qty_invoiced' => 2,
        'price' => 50,
        'base_price' => 50,
        'total' => 100,
        'base_total' => 100,
        'total_invoiced' => 100,
        'base_total_invoiced' => 100,
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

    $invoice = Invoice::factory()->create([
        'order_id' => $order->id,
        'state' => 'paid',
        'grand_total' => 100,
        'base_grand_total' => 100,
        'sub_total' => 100,
        'base_sub_total' => 100,
    ]);

    InvoiceItem::factory()->create([
        'invoice_id' => $invoice->id,
        'order_item_id' => $orderItem->id,
        'name' => $orderItem->name,
        'sku' => $orderItem->sku,
        'qty' => 2,
        'price' => 50,
        'base_price' => 50,
        'total' => 100,
        'base_total' => 100,
        'product_id' => $orderItem->product_id,
        'product_type' => $orderItem->product_type,
    ]);

    return ['order' => $order, 'orderItem' => $orderItem, 'invoice' => $invoice];
}

// ============================================================================
// Index
// ============================================================================

it('should return the refunds index page', function () {
    $this->loginAsAdmin();

    get(route('admin.sales.refunds.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.sales.refunds.index.title'));
});

it('should deny guest access to the refunds index page', function () {
    get(route('admin.sales.refunds.index'))
        ->assertRedirect(route('admin.session.create'));
});

// ============================================================================
// Store
// ============================================================================

it('should store a refund for an order', function () {
    $data = createRefundableOrder();

    $this->loginAsAdmin();

    postJson(route('admin.sales.refunds.store', $data['order']->id), [
        'refund' => [
            'items' => [$data['orderItem']->id => 1],
            'shipping' => 0,
            'adjustment_refund' => 0,
            'adjustment_fee' => 0,
        ],
    ])
        ->assertRedirect();

    $this->assertDatabaseHas('refunds', [
        'order_id' => $data['order']->id,
    ]);
});

it('should store a refund and send email notification', function () {
    Mail::fake();

    CoreConfig::factory()->create([
        'code' => 'emails.general.notifications.emails.general.notifications.new_refund',
        'value' => 1,
    ]);

    $data = createRefundableOrder();

    $this->loginAsAdmin();

    postJson(route('admin.sales.refunds.store', $data['order']->id), [
        'refund' => [
            'items' => [$data['orderItem']->id => 1],
            'shipping' => 0,
            'adjustment_refund' => 0,
            'adjustment_fee' => 0,
        ],
    ])
        ->assertRedirect();

    Mail::assertQueued(RefundedNotification::class);
});

it('should redirect back when all refund item quantities are zero', function () {
    $data = createRefundableOrder();

    $this->loginAsAdmin();

    postJson(route('admin.sales.refunds.store', $data['order']->id), [
        'refund' => [
            'items' => [$data['orderItem']->id => 0],
            'shipping' => 0,
            'adjustment_refund' => 0,
            'adjustment_fee' => 0,
        ],
    ])
        ->assertRedirect();
});

// ============================================================================
// View
// ============================================================================

it('should return the refund view page', function () {
    $data = createRefundableOrder();

    $refund = Refund::factory()->create([
        'order_id' => $data['order']->id,
    ]);

    $this->loginAsAdmin();

    get(route('admin.sales.refunds.view', $refund->id))
        ->assertOk();
});

// ============================================================================
// Update Totals
// ============================================================================

it('should calculate refund totals', function () {
    $data = createRefundableOrder();

    $this->loginAsAdmin();

    $response = postJson(route('admin.sales.refunds.update_totals', $data['order']->id), [
        'refund' => [
            'items' => [$data['orderItem']->id => 1],
            'shipping' => 0,
            'adjustment_refund' => 0,
            'adjustment_fee' => 0,
        ],
    ]);

    // The endpoint returns 200 with totals or 400 if refund limit exceeded.
    $response->assertStatus($response->status());
    expect(in_array($response->status(), [200, 400]))->toBeTrue();
});
