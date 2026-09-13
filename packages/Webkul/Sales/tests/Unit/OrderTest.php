<?php

use Webkul\Sales\Models\Order;

it('can be invoiced, shipped and canceled but not refunded while untouched', function () {
    $order = $this->createOrder(items: [['product' => $this->createSimpleProduct(), 'qty_ordered' => 2]]);

    expect($order)
        ->canInvoice()->toBeTrue()
        ->canShip()->toBeTrue()
        ->canCancel()->toBeTrue()
        ->canRefund()->toBeFalse();
});

it('can be refunded and shipped but no longer invoiced or canceled once fully invoiced', function () {
    $order = $this->createOrder(items: [['product' => $this->createSimpleProduct(), 'qty_ordered' => 2]]);

    $this->invoiceOrder($order);

    expect($order->refresh())
        ->canInvoice()->toBeFalse()
        ->canCancel()->toBeFalse()
        ->canShip()->toBeTrue()
        ->canRefund()->toBeTrue();
});

it('can not be shipped when it holds only non-stockable items', function () {
    $order = $this->createOrder(items: [['product' => $this->createVirtualProduct()]]);

    expect($order)
        ->haveStockableItems()->toBeFalse()
        ->canShip()->toBeFalse();
});

it('can not be reordered when it was placed by a guest', function () {
    $order = $this->createGuestOrder(items: [['product' => $this->createSimpleProduct()]]);

    expect($order->canReorder())->toBeFalse();
});

it('can be reordered while its products are still for sale', function () {
    $product = $this->setProductStock($this->createSimpleProduct(), 5);

    $order = $this->createOrder(items: [['product' => $product]]);

    expect($order->canReorder())->toBeTrue();

    $this->setProductStock($product, 0);

    expect($order->refresh()->canReorder())->toBeFalse();
});

it('reports the amount still due after a partial invoice', function () {
    $order = $this->createOrder(items: [['product' => $this->createSimpleProduct(), 'qty_ordered' => 2, 'price' => 100]]);

    $this->invoiceOrder($order, [$order->items->first()->id => 1]);

    $order->refresh();

    expect((float) $order->total_due)->toBePrice(100)
        ->and((float) $order->base_total_due)->toBePrice(100);
});

it('labels its status and names its customer', function () {
    $order = $this->createOrder(['status' => Order::STATUS_PENDING_PAYMENT]);

    expect($order)
        ->status_label->toBe('Pending Payment')
        ->customer_full_name->toBe($order->customer_first_name.' '.$order->customer_last_name);
});
