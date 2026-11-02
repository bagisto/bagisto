<?php

use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderItem;

/**
 * An order whose currency is worth twice the base currency, so a figure in one currency can
 * never be mistaken for the same figure in the other.
 */
function dualCurrencyOrder(int $qty = 2, float $price = 200, float $basePrice = 100): Order
{
    $order = Order::factory()->create([
        'status' => Order::STATUS_PENDING,
        'order_currency_code' => 'EUR',
        'grand_total' => $price * $qty,
        'base_grand_total' => $basePrice * $qty,
        'grand_total_invoiced' => 0,
        'base_grand_total_invoiced' => 0,
    ]);

    OrderItem::factory()->create([
        'order_id' => $order->id,
        'qty_ordered' => $qty,
        'qty_canceled' => 0,
        'price' => $price,
        'base_price' => $basePrice,
        'total' => $price * $qty,
        'base_total' => $basePrice * $qty,
    ]);

    return $order->refresh();
}

// ============================================================================
// Total Due
// ============================================================================

it('should owe each currency its own grand total while nothing is invoiced', function () {
    $order = dualCurrencyOrder();

    expect((float) $order->total_due)->toBe(400.0)
        ->and((float) $order->base_total_due)->toBe(200.0);
});

it('should take the invoiced amount off what is owed, in each currency', function () {
    $order = dualCurrencyOrder();

    $order->forceFill([
        'grand_total_invoiced' => 200,
        'base_grand_total_invoiced' => 100,
    ])->save();

    expect((float) $order->refresh()->total_due)->toBe(200.0)
        ->and((float) $order->base_total_due)->toBe(100.0);
});

it('should discount a cancelled quantity at the price of the currency being asked for', function () {
    $order = dualCurrencyOrder();

    $order->items->first()->forceFill(['qty_canceled' => 1])->save();

    expect((float) $order->refresh()->total_due)->toBe(200.0)
        ->and((float) $order->base_total_due)->toBe(100.0);
});

it('should owe nothing on a cancelled order', function () {
    $order = dualCurrencyOrder();

    $order->forceFill(['status' => Order::STATUS_CANCELED])->save();

    expect((float) $order->refresh()->total_due)->toBe(0.0)
        ->and((float) $order->base_total_due)->toBe(0.0);
});
