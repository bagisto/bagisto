<?php

use Illuminate\Support\Facades\Mail;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\Refund;
use Webkul\Shop\Mail\Order\RefundedNotification;

use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\postJson;

/**
 * Create an invoiced order for the given quantity of a simple product at the given unit price.
 */
function invoicedOrder(int $qty = 2, float $price = 50): Order
{
    $order = test()->createOrder(items: [['product' => test()->createSimpleProduct(), 'qty_ordered' => $qty, 'price' => $price]]);

    test()->invoiceOrder($order);

    return $order->refresh()->load('items');
}

/**
 * The refund payload for the given quantity of an order item.
 */
function refundPayload(int $orderItemId, int $qty, array $overrides = []): array
{
    return [
        'refund' => array_merge([
            'items' => [$orderItemId => $qty],
            'shipping' => 0,
            'adjustment_refund' => 0,
            'adjustment_fee' => 0,
        ], $overrides),
    ];
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

it('should refund the invoiced quantity, record it on the item and close the order when nothing is left', function () {
    $order = invoicedOrder(qty: 2, price: 50);

    $item = $order->items->first();

    $this->loginAsAdmin();

    postJson(route('admin.sales.refunds.store', $order->id), refundPayload($item->id, 2))
        ->assertRedirect(route('admin.sales.orders.view', $order->id))
        ->assertSessionHas('success', trans('admin::app.sales.refunds.create.create-success'));

    $refund = Refund::query()->where('order_id', $order->id)->firstOrFail();

    expect($refund->total_qty)->toBe(2)
        ->and((float) $refund->sub_total)->toBePrice(100)
        ->and((float) $refund->grand_total)->toBePrice(100);

    $this->assertDatabaseHas('refund_items', [
        'refund_id' => $refund->id,
        'order_item_id' => $item->id,
        'qty' => 2,
    ]);

    $this->assertDatabaseHas('order_items', [
        'id' => $item->id,
        'qty_refunded' => 2,
    ]);

    $order->refresh();

    expect($order->status)->toBe(Order::STATUS_CLOSED)
        ->and((float) $order->grand_total_refunded)->toBePrice(100);
});

it('should keep the order open after a partial refund', function () {
    $order = invoicedOrder(qty: 2, price: 50);

    $this->loginAsAdmin();

    postJson(route('admin.sales.refunds.store', $order->id), refundPayload($order->items->first()->id, 1))
        ->assertRedirect(route('admin.sales.orders.view', $order->id));

    $this->assertDatabaseHas('order_items', [
        'order_id' => $order->id,
        'qty_refunded' => 1,
    ]);

    $order->refresh();

    expect($order->status)->toBe(Order::STATUS_PROCESSING)
        ->and((float) $order->grand_total_refunded)->toBePrice(50);
});

it('should subtract an adjustment fee from the refund total', function () {
    $order = invoicedOrder(qty: 2, price: 50);

    $this->loginAsAdmin();

    postJson(route('admin.sales.refunds.store', $order->id), refundPayload($order->items->first()->id, 1, [
        'adjustment_fee' => 5,
    ]))
        ->assertRedirect(route('admin.sales.orders.view', $order->id));

    $refund = Refund::query()->where('order_id', $order->id)->firstOrFail();

    expect((float) $refund->base_adjustment_fee)->toBePrice(5)
        ->and((float) $refund->grand_total)->toBePrice(45);

    expect((float) $order->refresh()->grand_total_refunded)->toBePrice(45);
});

it('should return the refunded quantity of a shipped product to its inventory source', function () {
    $product = $this->createSimpleProduct();

    $product->inventories()->update(['qty' => 10]);

    $order = $this->createOrder(items: [['product' => $product, 'qty_ordered' => 2, 'price' => 50]]);

    $this->invoiceOrder($order);

    $this->shipOrder($order);

    $this->assertDatabaseHas('product_inventories', [
        'product_id' => $product->id,
        'qty' => 8,
    ]);

    $this->loginAsAdmin();

    postJson(route('admin.sales.refunds.store', $order->id), refundPayload($order->items->first()->id, 2))
        ->assertRedirect(route('admin.sales.orders.view', $order->id));

    $this->assertDatabaseHas('product_inventories', [
        'product_id' => $product->id,
        'qty' => 10,
    ]);
});

it('should refuse a refund of more than the quantity left to refund', function () {
    $order = invoicedOrder(qty: 2);

    $this->loginAsAdmin();

    post(route('admin.sales.refunds.store', $order->id), refundPayload($order->items->first()->id, 3))
        ->assertRedirect()
        ->assertSessionHas('error', trans('admin::app.sales.refunds.create.invalid-qty'));

    $this->assertDatabaseMissing('refunds', ['order_id' => $order->id]);
});

it('should refuse a refund that exceeds the amount still refundable', function () {
    $order = invoicedOrder(qty: 2, price: 50);

    $this->loginAsAdmin();

    post(route('admin.sales.refunds.store', $order->id), refundPayload($order->items->first()->id, 1, [
        'adjustment_refund' => 1000,
    ]))
        ->assertRedirect()
        ->assertSessionHas('error', trans('admin::app.sales.refunds.create.refund-limit-error', [
            'amount' => core()->formatBasePrice(1050),
        ]));

    $this->assertDatabaseMissing('refunds', ['order_id' => $order->id]);
});

it('should refuse a refund that adds up to nothing', function () {
    $order = invoicedOrder();

    $this->loginAsAdmin();

    post(route('admin.sales.refunds.store', $order->id), refundPayload($order->items->first()->id, 0))
        ->assertRedirect()
        ->assertSessionHas('error', trans('admin::app.sales.refunds.create.invalid-refund-amount-error'));

    $this->assertDatabaseMissing('refunds', ['order_id' => $order->id]);
});

it('should refuse to refund an order that has not been invoiced', function () {
    $order = $this->createOrder();

    $this->loginAsAdmin();

    post(route('admin.sales.refunds.store', $order->id), refundPayload($order->items->first()->id, 1))
        ->assertRedirect()
        ->assertSessionHas('error', trans('admin::app.sales.refunds.create.creation-error'));

    $this->assertDatabaseMissing('refunds', ['order_id' => $order->id]);
});

it('should store a refund and send email notification', function () {
    Mail::fake();

    $this->setConfig('emails.general.notifications.emails.general.notifications.new_refund', 1);

    $order = invoicedOrder();

    $this->loginAsAdmin();

    postJson(route('admin.sales.refunds.store', $order->id), refundPayload($order->items->first()->id, 1))
        ->assertRedirect();

    $this->assertDatabaseHas('refunds', ['order_id' => $order->id]);

    Mail::assertQueued(RefundedNotification::class, fn (RefundedNotification $mail) => $mail->hasTo($order->customer_email));
});

it('should fail validation when a refund item quantity is not numeric', function () {
    $order = invoicedOrder();

    $this->loginAsAdmin();

    postJson(route('admin.sales.refunds.store', $order->id), refundPayload($order->items->first()->id, 1, [
        'items' => [$order->items->first()->id => 'invalid'],
    ]))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('refund.items.'.$order->items->first()->id);
});

// ============================================================================
// View
// ============================================================================

it('should return the refund view page', function () {
    $order = invoicedOrder();

    $this->loginAsAdmin();

    postJson(route('admin.sales.refunds.store', $order->id), refundPayload($order->items->first()->id, 1));

    $refund = Refund::query()->where('order_id', $order->id)->firstOrFail();

    get(route('admin.sales.refunds.view', $refund->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.sales.refunds.view.title', ['refund_id' => $refund->id]))
        ->assertSeeText($order->customer_email);
});

// ============================================================================
// Update Totals
// ============================================================================

it('should total up a refund before it is created', function () {
    $order = invoicedOrder(qty: 2, price: 50);

    $this->loginAsAdmin();

    postJson(route('admin.sales.refunds.update_totals', $order->id), [
        'items' => [$order->items->first()->id => 1],
        'shipping' => 0,
        'adjustment_refund' => 10,
        'adjustment_fee' => 5,
    ])
        ->assertOk()
        ->assertJsonPath('subtotal.price', 50)
        ->assertJsonPath('grand_total.price', 55)
        ->assertJsonPath('grand_total.formatted_price', core()->formatBasePrice(55));
});

it('should reject totals for more than the quantity left to refund', function () {
    $order = invoicedOrder(qty: 2);

    $this->loginAsAdmin();

    postJson(route('admin.sales.refunds.update_totals', $order->id), [
        'items' => [$order->items->first()->id => 3],
        'shipping' => 0,
        'adjustment_refund' => 0,
        'adjustment_fee' => 0,
    ])
        ->assertBadRequest()
        ->assertJsonPath('message', trans('admin::app.sales.refunds.create.invalid-qty'));
});
