<?php

use Webkul\Sales\Models\Order;

/**
 * An order placed in a currency worth twice the base currency, so a figure in one can never be
 * mistaken for the same figure in the other.
 */
function orderInNonBaseCurrency(int $qty = 2, float $price = 200, float $basePrice = 100): Order
{
    return test()->createOrder([
        'order_currency_code' => 'EUR',
        'grand_total' => $price * $qty,
        'base_grand_total' => $basePrice * $qty,
    ], [[
        'qty_ordered' => $qty,
        'price' => $price,
        'base_price' => $basePrice,
        'total' => $price * $qty,
        'base_total' => $basePrice * $qty,
    ]]);
}

// ============================================================================
// Nothing Invoiced Or Cancelled
// ============================================================================

it('should owe the whole order while nothing has been invoiced', function () {
    $order = $this->createOrder(items: [['product' => $this->createSimpleProduct(), 'qty_ordered' => 2]]);

    expect((float) $order->total_due)->toBePrice((float) $order->grand_total)
        ->and((float) $order->base_total_due)->toBePrice((float) $order->base_grand_total);
});

it('should owe each currency its own grand total', function () {
    $order = orderInNonBaseCurrency();

    expect((float) $order->total_due)->toBePrice(400)
        ->and((float) $order->base_total_due)->toBePrice(200);
});

// ============================================================================
// Invoiced Amounts
// ============================================================================

it('should take the invoiced amount off what is owed, in each currency', function () {
    $order = orderInNonBaseCurrency();

    $order->forceFill([
        'grand_total_invoiced' => 200,
        'base_grand_total_invoiced' => 100,
    ])->save();

    expect((float) $order->refresh()->total_due)->toBePrice(200)
        ->and((float) $order->base_total_due)->toBePrice(100);
});

it('should owe nothing once the whole order has been invoiced through the pipeline', function () {
    $order = $this->createOrder(items: [['product' => $this->createSimpleProduct(), 'qty_ordered' => 2]]);

    $this->invoiceOrder($order);

    expect((float) $order->refresh()->total_due)->toBePrice(0)
        ->and((float) $order->base_total_due)->toBePrice(0);
});

// ============================================================================
// Cancelled Quantities
// ============================================================================

it('should discount a cancelled quantity at the price of the currency being asked for', function () {
    $order = orderInNonBaseCurrency();

    $order->items->first()->forceFill(['qty_canceled' => 1])->save();

    expect((float) $order->refresh()->total_due)->toBePrice(200)
        ->and((float) $order->base_total_due)->toBePrice(100);
});

it('should discount a cancelled quantity on a single currency order', function () {
    $order = $this->createOrder(items: [['product' => $this->createSimpleProduct(['price' => ['float_value' => 100]]), 'qty_ordered' => 3]]);

    $order->items->first()->forceFill(['qty_canceled' => 1])->save();

    expect((float) $order->refresh()->total_due)->toBePrice(200)
        ->and((float) $order->base_total_due)->toBePrice(200);
});

it('should owe nothing on a cancelled order', function () {
    $order = orderInNonBaseCurrency();

    $order->forceFill(['status' => Order::STATUS_CANCELED])->save();

    expect((float) $order->refresh()->total_due)->toBePrice(0)
        ->and((float) $order->base_total_due)->toBePrice(0);
});

// ============================================================================
// Partly Invoiced And Partly Cancelled
// ============================================================================

it('should take both the invoiced and the cancelled quantity off what is owed', function () {
    $order = orderInNonBaseCurrency(qty: 4);

    $order->forceFill([
        'grand_total_invoiced' => 200,
        'base_grand_total_invoiced' => 100,
    ])->save();

    $order->items->first()->forceFill(['qty_canceled' => 1])->save();

    expect((float) $order->refresh()->total_due)->toBePrice(400)
        ->and((float) $order->base_total_due)->toBePrice(200);
});

it('should sum the cancelled quantities of every item it carries', function () {
    $order = $this->createOrder([
        'grand_total' => 600,
        'base_grand_total' => 600,
    ], [
        ['qty_ordered' => 2, 'price' => 100, 'base_price' => 100, 'total' => 200, 'base_total' => 200],
        ['qty_ordered' => 2, 'price' => 200, 'base_price' => 200, 'total' => 400, 'base_total' => 400],
    ]);

    foreach ($order->items as $item) {
        $item->forceFill(['qty_canceled' => 1])->save();
    }

    expect((float) $order->refresh()->total_due)->toBePrice(300)
        ->and((float) $order->base_total_due)->toBePrice(300);
});
