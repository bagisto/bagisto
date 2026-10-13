<?php

use Webkul\Product\Models\ProductOrderedInventory;
use Webkul\Sales\Models\InvoiceItem;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderItem;
use Webkul\Sales\Models\RefundItem;
use Webkul\Sales\Repositories\OrderItemRepository;
use Webkul\Sales\Repositories\RefundRepository;

/**
 * A configurable order whose variant child carries no quantity of its own, returned with the
 * parent and child items the cart would have built.
 */
function configurableDocumentOrder(int $orderedQuantity, float $price = 100): array
{
    $product = test()->createConfigurableProduct();

    $variant = test()->setProductStock($product->variants->first(), 50);

    $order = test()->createOrder(items: [[
        'product' => $product,
        'qty_ordered' => $orderedQuantity,
        'price' => $price,
    ]]);

    $parentItem = $order->items->first();

    $childItem = test()->createOrderItem($order, [
        'product' => $variant,
        'parent_id' => $parentItem->id,
        'qty_ordered' => 0,
        'price' => $price,
    ]);

    return [$order->refresh(), $parentItem->refresh(), $childItem->refresh()];
}

/**
 * Refund the given quantity of one of an order's top level items.
 */
function refundItemQuantity(Order $order, OrderItem $item, int $qty): void
{
    app(RefundRepository::class)->create([
        'order_id' => $order->id,
        'refund' => [
            'items' => [$item->id => $qty],
            'shipping' => 0,
            'adjustment_refund' => 0,
            'adjustment_fee' => 0,
        ],
    ]);
}

// ============================================================================
// Invoicing Part Of A Composite Item
// ============================================================================

it('should invoice a configurable variant for the quantity invoiced, not the whole order', function () {
    [$order, $parentItem, $childItem] = configurableDocumentOrder(3);

    $this->invoiceOrder($order, [$parentItem->id => 1]);

    expect((int) InvoiceItem::where('order_item_id', $childItem->id)->sum('qty'))->toBe(1)
        ->and((int) InvoiceItem::where('order_item_id', $parentItem->id)->sum('qty'))->toBe(1);
});

it('should total a part invoiced configurable variant against the quantity invoiced', function () {
    [$order, $parentItem, $childItem] = configurableDocumentOrder(3);

    $this->invoiceOrder($order, [$parentItem->id => 1]);

    expect((float) InvoiceItem::where('order_item_id', $childItem->id)->sum('total'))->toBePrice(100);
});

// ============================================================================
// Refunding Part Of A Composite Item
// ============================================================================

it('should refund a configurable variant for the quantity refunded, not the whole order', function () {
    [$order, $parentItem, $childItem] = configurableDocumentOrder(3);

    $this->invoiceOrder($order);

    refundItemQuantity($order->refresh(), $parentItem, 1);

    expect((int) RefundItem::where('order_item_id', $childItem->id)->sum('qty'))->toBe(1)
        ->and((int) RefundItem::where('order_item_id', $parentItem->id)->sum('qty'))->toBe(1);
});

it('should keep reserved what a part refunded configurable order still owes', function () {
    [$order, $parentItem, $childItem] = configurableDocumentOrder(3);

    app(OrderItemRepository::class)->manageInventory($parentItem);

    $this->invoiceOrder($order);

    refundItemQuantity($order->refresh(), $parentItem, 1);

    expect((int) ProductOrderedInventory::where('product_id', $childItem->product_id)->sum('qty'))->toBe(2);
});
