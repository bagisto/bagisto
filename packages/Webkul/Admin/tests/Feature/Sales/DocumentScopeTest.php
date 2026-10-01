<?php

use Webkul\Sales\Models\Invoice;
use Webkul\Sales\Models\Refund;
use Webkul\Sales\Models\Shipment;

use function Pest\Laravel\post;

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

// ============================================================================
// Items Of Another Order
// ============================================================================

it('should refuse to invoice an item that belongs to another order', function () {
    $this->loginAsAdmin();

    $order = $this->createOrder();
    $other = $this->createOrder();

    post(route('admin.sales.invoices.store', $order->id), [
        'invoice' => ['items' => [$other->items->first()->id => 1]],
    ]);

    expect(Invoice::count())->toBe(0);
});

it('should refuse to ship an item that belongs to another order', function () {
    $this->loginAsAdmin();

    $order = $this->createOrder();
    $other = $this->createOrder();

    $this->invoiceOrder($order);

    post(route('admin.sales.shipments.store', $order->id), [
        'shipment' => [
            'source' => $this->defaultInventorySourceId(),
            'items' => [$other->items->first()->id => [$this->defaultInventorySourceId() => 1]],
        ],
    ])->assertSessionHas('error');

    expect(Shipment::count())->toBe(0);
});

it('should refuse to refund an item that belongs to another order', function () {
    $this->loginAsAdmin();

    $order = $this->createOrder();
    $other = $this->createOrder();

    $this->invoiceOrder($order);

    post(route('admin.sales.refunds.store', $order->id), scopedRefundPayload([$other->items->first()->id => 1]))
        ->assertSessionHas('error');

    expect(Refund::count())->toBe(0);
});

// ============================================================================
// Refunded Shipping
// ============================================================================

it('should refuse to refund more shipping than the order was invoiced', function () {
    $this->loginAsAdmin();

    $order = $this->createOrder();

    $this->invoiceOrder($order);

    $invoiced = (float) $order->refresh()->base_shipping_invoiced;

    post(route('admin.sales.refunds.store', $order->id), scopedRefundPayload(
        [$order->items->first()->id => 1],
        $invoiced + 1000,
    ))->assertSessionHasErrors('refund.shipping');

    expect(Refund::count())->toBe(0);
});
