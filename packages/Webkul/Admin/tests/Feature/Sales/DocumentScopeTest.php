<?php

use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\Sales\Models\Invoice;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderItem;
use Webkul\Sales\Models\Refund;
use Webkul\Sales\Models\Shipment;

use function Pest\Laravel\post;

/**
 * An order carrying one item, ready to be invoiced.
 */
function orderAwaitingInvoice(): Order
{
    $product = (new ProductFaker)->getSimpleProductFactory()->create();

    $order = Order::factory()->create(['status' => Order::STATUS_PENDING]);

    OrderItem::factory()->create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'type' => $product->type,
        'sku' => $product->sku,
        'name' => $product->name,
        'qty_ordered' => 2,
        'qty_invoiced' => 0,
        'qty_shipped' => 0,
        'qty_refunded' => 0,
        'qty_canceled' => 0,
    ]);

    return $order->refresh();
}

/**
 * Mark the order's item invoiced, which is what shipping and refunding require.
 */
function markInvoiced(Order $order): Order
{
    $order->items->first()->update(['qty_invoiced' => 2]);

    $order->update([
        'base_grand_total_invoiced' => $order->base_grand_total,
        'base_shipping_invoiced' => $order->base_shipping_amount,
    ]);

    return $order->refresh();
}

/**
 * The fields the refund form posts alongside the quantities.
 */
function scopedRefundPayload(array $items, float $shipping = 0): array
{
    return [
        'refund' => [
            'items' => $items,
            'shipping' => $shipping,
            'adjustment_refund' => 0,
            'adjustment_fee' => 0,
        ],
    ];
}

beforeEach(function () {
    $this->loginAsAdmin();
});

// ============================================================================
// Items Of Another Order
// ============================================================================

it('should refuse to invoice an item that belongs to another order', function () {
    $order = orderAwaitingInvoice();
    $other = orderAwaitingInvoice();

    post(route('admin.sales.invoices.store', $order->id), [
        'invoice' => ['items' => [$other->items->first()->id => 1]],
    ])->assertSessionHas('error');

    expect(Invoice::whereIn('order_id', [$order->id, $other->id])->count())->toBe(0);
});

it('should refuse to ship an item that belongs to another order', function () {
    $order = markInvoiced(orderAwaitingInvoice());
    $other = orderAwaitingInvoice();

    post(route('admin.sales.shipments.store', $order->id), [
        'shipment' => [
            'source' => 1,
            'items' => [$other->items->first()->id => [1 => 1]],
        ],
    ])->assertSessionHas('error');

    expect(Shipment::whereIn('order_id', [$order->id, $other->id])->count())->toBe(0);
});

it('should refuse to refund an item that belongs to another order', function () {
    $order = markInvoiced(orderAwaitingInvoice());
    $other = orderAwaitingInvoice();

    post(route('admin.sales.refunds.store', $order->id), scopedRefundPayload([$other->items->first()->id => 1]));

    expect(Refund::whereIn('order_id', [$order->id, $other->id])->count())->toBe(0);
});

// ============================================================================
// Refunded Shipping
// ============================================================================

it('should refuse to refund more shipping than the order was invoiced', function () {
    $order = markInvoiced(orderAwaitingInvoice());

    post(route('admin.sales.refunds.store', $order->id), scopedRefundPayload(
        [$order->items->first()->id => 1],
        (float) $order->base_shipping_invoiced + 1000,
    ))->assertSessionHasErrors('refund.shipping');

    expect(Refund::where('order_id', $order->id)->count())->toBe(0);
});
