<?php

use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\Product\Models\ProductOrderedInventory;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderItem;
use Webkul\Sales\Repositories\OrderItemRepository;

/**
 * A configurable order item whose variant child carries no quantity of its own, which is the
 * shape the cart builds: the parent holds the quantity and the child holds the stock.
 */
function configurableOrderItem(int $orderedQuantity): OrderItem
{
    $product = (new ProductFaker)->getConfigurableProductFactory()->create();

    $variant = $product->variants()->first();

    $order = Order::factory()->create(['status' => Order::STATUS_PENDING]);

    $parentItem = OrderItem::factory()->create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'type' => $product->type,
        'sku' => $product->sku,
        'name' => $product->name,
        'qty_ordered' => $orderedQuantity,
    ]);

    OrderItem::factory()->create([
        'order_id' => $order->id,
        'parent_id' => $parentItem->id,
        'product_id' => $variant->id,
        'type' => $variant->type,
        'sku' => $variant->sku,
        'name' => $variant->name,
        'qty_ordered' => 0,
    ]);

    return $parentItem->refresh();
}

/**
 * The quantity reserved against a product in its order's channel.
 */
function reservedQuantity(int $productId): int
{
    return (int) ProductOrderedInventory::query()
        ->where('product_id', $productId)
        ->value('qty');
}

// ============================================================================
// Reserving Against A Composite Item's Child
// ============================================================================

it('should reserve the ordered quantity against a configurable variant', function () {
    $parentItem = configurableOrderItem(3);

    $variant = $parentItem->children->first();

    app(OrderItemRepository::class)->manageInventory($parentItem);

    expect(reservedQuantity($variant->product_id))->toBe(3);
});

it('should reserve a composite child own quantity when it carries one, as a bundle child does', function () {
    $parentItem = configurableOrderItem(3);

    $child = $parentItem->children->first();

    $child->update(['qty_ordered' => 5]);

    app(OrderItemRepository::class)->manageInventory($parentItem->refresh());

    expect(reservedQuantity($child->product_id))->toBe(5);
});

it('should reserve nothing against the configurable parent, which holds no stock', function () {
    $parentItem = configurableOrderItem(3);

    app(OrderItemRepository::class)->manageInventory($parentItem);

    expect(reservedQuantity($parentItem->product_id))->toBe(0);
});

// ============================================================================
// Adding To An Existing Reservation
// ============================================================================

it('should add to a reservation the product already holds', function () {
    $product = (new ProductFaker)->getSimpleProductFactory()->create();

    $product->ordered_inventories()->create([
        'qty' => 2,
        'product_id' => $product->id,
        'channel_id' => 1,
    ]);

    $order = Order::factory()->create(['status' => Order::STATUS_PENDING]);

    $orderItem = OrderItem::factory()->create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'type' => $product->type,
        'sku' => $product->sku,
        'name' => $product->name,
        'qty_ordered' => 3,
    ]);

    app(OrderItemRepository::class)->manageInventory($orderItem->refresh());

    expect(reservedQuantity($product->id))->toBe(5);
});

// ============================================================================
// Reserving Against A Simple Item
// ============================================================================

it('should reserve the ordered quantity against a simple product', function () {
    $product = (new ProductFaker)->getSimpleProductFactory()->create();

    $order = Order::factory()->create(['status' => Order::STATUS_PENDING]);

    $orderItem = OrderItem::factory()->create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'type' => $product->type,
        'sku' => $product->sku,
        'name' => $product->name,
        'qty_ordered' => 2,
    ]);

    app(OrderItemRepository::class)->manageInventory($orderItem->refresh());

    expect(reservedQuantity($product->id))->toBe(2);
});
