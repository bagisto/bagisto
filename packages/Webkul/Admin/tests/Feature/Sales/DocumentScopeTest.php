<?php

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

    expect($order->invoices()->count())->toBe(0)
        ->and($other->invoices()->count())->toBe(0);
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

    expect($order->shipments()->count())->toBe(0)
        ->and($other->shipments()->count())->toBe(0);
});

it('should refuse to refund an item that belongs to another order', function () {
    $this->loginAsAdmin();

    $order = $this->createOrder();
    $other = $this->createOrder();

    $this->invoiceOrder($order);

    post(route('admin.sales.refunds.store', $order->id), scopedRefundPayload([$other->items->first()->id => 1]))
        ->assertSessionHas('error');

    expect($order->refunds()->count())->toBe(0)
        ->and($other->refunds()->count())->toBe(0);
});

it('should refuse to refund an item of another order that is itself refundable', function () {
    $this->loginAsAdmin();

    $order = $this->createOrder();
    $other = $this->createOrder();

    $this->invoiceOrder($order);
    $this->invoiceOrder($other);

    $foreignItem = $other->refresh()->items->first();

    post(route('admin.sales.refunds.store', $order->id), scopedRefundPayload([$foreignItem->id => 1]))
        ->assertSessionHas('error');

    expect($order->refunds()->count())->toBe(0)
        ->and($other->refunds()->count())->toBe(0)
        ->and((float) $foreignItem->refresh()->qty_refunded)->toBe(0.0);
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

    expect($order->refunds()->count())->toBe(0);
});
