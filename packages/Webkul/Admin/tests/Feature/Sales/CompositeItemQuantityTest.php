<?php

use Illuminate\Support\Facades\Mail;
use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\Sales\Models\InvoiceItem;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderAddress;
use Webkul\Sales\Models\OrderItem;
use Webkul\Sales\Models\OrderPayment;
use Webkul\Sales\Models\RefundItem;
use Webkul\Sales\Repositories\InvoiceRepository;
use Webkul\Sales\Repositories\OrderItemRepository;
use Webkul\Sales\Repositories\RefundRepository;

/**
 * A configurable order ready to be invoiced, whose variant child carries no quantity of its own.
 */
function invoiceableConfigurableOrder(int $orderedQuantity): array
{
    Mail::fake();

    $product = (new ProductFaker)->getConfigurableProductFactory()->create();

    $variant = $product->variants()->first();

    $variant->inventories()->update(['qty' => 50]);

    $order = Order::factory()->create(['status' => Order::STATUS_PENDING]);

    OrderPayment::factory()->create(['order_id' => $order->id]);

    foreach ([OrderAddress::ADDRESS_TYPE_BILLING, OrderAddress::ADDRESS_TYPE_SHIPPING] as $type) {
        OrderAddress::factory()->create([
            'order_id' => $order->id,
            'address_type' => $type,
        ]);
    }

    $parentItem = OrderItem::factory()->create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'type' => $product->type,
        'sku' => $product->sku,
        'name' => $product->name,
        'qty_ordered' => $orderedQuantity,
        'price' => 100,
        'base_price' => 100,
    ]);

    $childItem = OrderItem::factory()->create([
        'order_id' => $order->id,
        'parent_id' => $parentItem->id,
        'product_id' => $variant->id,
        'type' => $variant->type,
        'sku' => $variant->sku,
        'name' => $variant->name,
        'qty_ordered' => 0,
        'price' => 100,
        'base_price' => 100,
    ]);

    return [$order->refresh(), $parentItem->refresh(), $childItem->refresh()];
}

/**
 * Invoice the given quantity of an order's top level item.
 */
function invoiceQuantity(Order $order, OrderItem $item, int $qty): void
{
    app(InvoiceRepository::class)->create([
        'order_id' => $order->id,
        'invoice' => ['items' => [$item->id => $qty]],
    ]);
}

/**
 * Refund the given quantity of an order's top level item.
 */
function refundQuantity(Order $order, OrderItem $item, int $qty): void
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
    [$order, $parentItem, $childItem] = invoiceableConfigurableOrder(3);

    invoiceQuantity($order, $parentItem, 1);

    expect((int) InvoiceItem::where('order_item_id', $childItem->id)->sum('qty'))->toBe(1)
        ->and((int) InvoiceItem::where('order_item_id', $parentItem->id)->sum('qty'))->toBe(1);
});

it('should total a part invoiced configurable variant against the quantity invoiced', function () {
    [$order, $parentItem, $childItem] = invoiceableConfigurableOrder(3);

    invoiceQuantity($order, $parentItem, 1);

    $this->assertPrice(100, (float) InvoiceItem::where('order_item_id', $childItem->id)->sum('total'));
});

// ============================================================================
// Refunding Part Of A Composite Item
// ============================================================================

it('should refund a configurable variant for the quantity refunded, not the whole order', function () {
    [$order, $parentItem, $childItem] = invoiceableConfigurableOrder(3);

    invoiceQuantity($order, $parentItem, 3);

    refundQuantity($order->refresh(), $parentItem, 1);

    expect((int) RefundItem::where('order_item_id', $childItem->id)->sum('qty'))->toBe(1)
        ->and((int) RefundItem::where('order_item_id', $parentItem->id)->sum('qty'))->toBe(1);
});

it('should keep reserved what a part refunded configurable order still owes', function () {
    [$order, $parentItem, $childItem] = invoiceableConfigurableOrder(3);

    app(OrderItemRepository::class)->manageInventory($parentItem);

    invoiceQuantity($order, $parentItem, 3);

    refundQuantity($order->refresh(), $parentItem, 1);

    expect((int) $childItem->product->ordered_inventories()->sum('qty'))->toBe(2);
});
