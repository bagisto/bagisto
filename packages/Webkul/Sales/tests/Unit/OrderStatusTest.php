<?php

use Webkul\Sales\Generators\OrderSequencer;
use Webkul\Sales\Models\Invoice;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Repositories\RefundRepository;

/**
 * Refund the given quantity of every item of an order through the refund pipeline.
 */
function refundOrder(Order $order, int $qty): void
{
    app(RefundRepository::class)->create([
        'order_id' => $order->id,
        'refund' => [
            'items' => $order->items->mapWithKeys(fn ($item) => [$item->id => $qty])->all(),
            'shipping' => 0,
            'adjustment_refund' => 0,
            'adjustment_fee' => 0,
        ],
    ]);
}

// ============================================================================
// Status Lifecycle
// ============================================================================

it('should start out pending', function () {
    expect($this->createOrder()->status)->toBe(Order::STATUS_PENDING);
});

it('should move to processing once part of it is invoiced', function () {
    $order = $this->createOrder(items: [['product' => $this->createSimpleProduct(), 'qty_ordered' => 2]]);

    $this->invoiceOrder($order, [$order->items->first()->id => 1]);

    expect($order->refresh()->status)->toBe(Order::STATUS_PROCESSING);
});

it('should keep processing while shipped items await their invoice', function () {
    $order = $this->createOrder(items: [['product' => $this->setProductStock($this->createSimpleProduct(), 10), 'qty_ordered' => 2]]);

    $this->shipOrder($order);

    expect($order->refresh()->status)->toBe(Order::STATUS_PROCESSING);
});

it('should complete once every item is invoiced and shipped', function () {
    $order = $this->createOrder(items: [['product' => $this->setProductStock($this->createSimpleProduct(), 10), 'qty_ordered' => 2]]);

    $this->invoiceOrder($order);

    $this->shipOrder($order);

    expect($order->refresh()->status)->toBe(Order::STATUS_COMPLETED);
});

it('should complete an order of non-stockable items as soon as it is invoiced', function () {
    $order = $this->createOrder(items: [['product' => $this->createVirtualProduct(), 'qty_ordered' => 2]]);

    $this->invoiceOrder($order);

    expect($order->refresh()->status)->toBe(Order::STATUS_COMPLETED);
});

it('should take the status a caller forces on it', function () {
    $order = $this->createOrder();

    app(OrderRepository::class)->updateOrderStatus($order, Order::STATUS_FRAUD);

    expect($order->refresh()->status)->toBe(Order::STATUS_FRAUD);
});

// ============================================================================
// Cancellation
// ============================================================================

it('should be canceled once every item is canceled', function () {
    $order = $this->createOrder(items: [['product' => $this->createSimpleProduct(), 'qty_ordered' => 2]]);

    expect(app(OrderRepository::class)->cancel($order))->toBeTrue()
        ->and($order->refresh()->status)->toBe(Order::STATUS_CANCELED)
        ->and($order->items->first()->qty_canceled)->toBe(2);
});

it('should not be canceled once it has been invoiced', function () {
    $order = $this->createOrder(items: [['product' => $this->createSimpleProduct()]]);

    $this->invoiceOrder($order);

    expect(app(OrderRepository::class)->cancel($order->refresh()))->toBeFalse()
        ->and($order->refresh()->status)->toBe(Order::STATUS_PROCESSING);
});

// ============================================================================
// Refunds
// ============================================================================

it('should close once everything invoiced has been refunded', function () {
    $order = $this->createOrder(items: [['product' => $this->createSimpleProduct(), 'qty_ordered' => 2, 'price' => 50]]);

    $this->invoiceOrder($order);

    refundOrder($order->refresh(), 2);

    $order->refresh();

    expect($order->status)->toBe(Order::STATUS_CLOSED)
        ->and((float) $order->grand_total_refunded)->toBePrice(100);
});

it('should stay processing after a partial refund', function () {
    $order = $this->createOrder(items: [['product' => $this->createSimpleProduct(), 'qty_ordered' => 2, 'price' => 50]]);

    $this->invoiceOrder($order);

    refundOrder($order->refresh(), 1);

    $order->refresh();

    expect($order->status)->toBe(Order::STATUS_PROCESSING)
        ->and((float) $order->grand_total_refunded)->toBePrice(50);
});

// ============================================================================
// Totals And Numbering
// ============================================================================

it('should hold the invoice totals against the order', function () {
    $order = $this->createOrder(items: [['product' => $this->createSimpleProduct(), 'qty_ordered' => 2, 'price' => 100]]);

    $this->invoiceOrder($order, [$order->items->first()->id => 1], state: Invoice::STATUS_PENDING);

    $order->refresh();

    expect((float) $order->grand_total_invoiced)->toBePrice(100)
        ->and((float) $order->total_due)->toBePrice(100)
        ->and($order->hasOpenInvoice())->toBeTrue();
});

it('should number a new order from the configured prefix, length and suffix', function () {
    $this->setConfig([
        'sales.order_settings.order_number.order_number_prefix' => 'ORD-',
        'sales.order_settings.order_number.order_number_length' => '6',
        'sales.order_settings.order_number.order_number_suffix' => '-EU',
    ]);

    $next = (int) Order::query()->max('id') + 1;

    expect(app(OrderSequencer::class)->resolveGeneratorClass())->toBe(sprintf('ORD-%06d-EU', $next));
});
