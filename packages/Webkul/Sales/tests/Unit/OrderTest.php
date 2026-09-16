<?php

use Webkul\Sales\Models\Order;

// ============================================================================
// Available Actions
// ============================================================================

it('should be invoiceable, shippable and cancelable but not refundable while untouched', function () {
    $order = $this->createOrder(items: [['product' => $this->createSimpleProduct(), 'qty_ordered' => 2]]);

    expect($order)
        ->canInvoice()->toBeTrue()
        ->canShip()->toBeTrue()
        ->canCancel()->toBeTrue()
        ->canRefund()->toBeFalse();
});

it('should be refundable and shippable but no longer invoiceable or cancelable once fully invoiced', function () {
    $order = $this->createOrder(items: [['product' => $this->createSimpleProduct(), 'qty_ordered' => 2]]);

    $this->invoiceOrder($order);

    expect($order->refresh())
        ->canInvoice()->toBeFalse()
        ->canCancel()->toBeFalse()
        ->canShip()->toBeTrue()
        ->canRefund()->toBeTrue();
});

it('should not be shippable when it holds only non-stockable items', function () {
    $order = $this->createOrder(items: [['product' => $this->createVirtualProduct()]]);

    expect($order)
        ->haveStockableItems()->toBeFalse()
        ->canShip()->toBeFalse();
});

// ============================================================================
// Reordering
// ============================================================================

it('should not be reorderable when it was placed by a guest', function () {
    $order = $this->createGuestOrder(items: [['product' => $this->createSimpleProduct()]]);

    expect($order->canReorder())->toBeFalse();
});

it('should be reorderable only while its products are still for sale', function () {
    $product = $this->setProductStock($this->createSimpleProduct(), 5);

    $order = $this->createOrder(items: [['product' => $product]]);

    expect($order->canReorder())->toBeTrue();

    $this->setProductStock($product, 0);

    expect($order->refresh()->canReorder())->toBeFalse();
});

// ============================================================================
// Amounts And Labels
// ============================================================================

it('should report the amount still due after a partial invoice', function () {
    $order = $this->createOrder(items: [['product' => $this->createSimpleProduct(), 'qty_ordered' => 2, 'price' => 100]]);

    $this->invoiceOrder($order, [$order->items->first()->id => 1]);

    $order->refresh();

    expect((float) $order->total_due)->toBePrice(100)
        ->and((float) $order->base_total_due)->toBePrice(100);
});

it('should label its status and name its customer', function () {
    $order = $this->createOrder(['status' => Order::STATUS_PENDING_PAYMENT]);

    expect($order)
        ->status_label->toBe('Pending Payment')
        ->customer_full_name->toBe($order->customer_first_name.' '.$order->customer_last_name);
});
