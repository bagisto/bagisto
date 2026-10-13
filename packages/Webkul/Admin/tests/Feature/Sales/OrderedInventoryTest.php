<?php

use Illuminate\Support\Facades\Mail;
use Webkul\Attribute\Models\Attribute;
use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductAttributeValue;
use Webkul\Product\Models\ProductOrderedInventory;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderItem;
use Webkul\Sales\Repositories\OrderItemRepository;
use Webkul\Sales\Repositories\OrderRepository;

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

/**
 * Cancel an order through the order pipeline, holding back the notification it sends.
 */
function cancelOrder(Order $order): void
{
    Mail::fake();

    app(OrderRepository::class)->cancel($order);
}

/**
 * Switch a product's inventory management on or off, as the admin's manage stock checkbox does.
 */
function setManageStock(Product $product, bool $enabled): Product
{
    ProductAttributeValue::query()
        ->where('product_id', $product->id)
        ->where('attribute_id', Attribute::query()->where('code', 'manage_stock')->value('id'))
        ->update(['boolean_value' => $enabled]);

    return Product::find($product->id);
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

// ============================================================================
// Releasing The Reservation On Cancellation
// ============================================================================

it('should release a configurable variant reservation when the order is canceled', function () {
    $parentItem = configurableOrderItem(3);

    $variant = $parentItem->children->first();

    app(OrderItemRepository::class)->manageInventory($parentItem);

    expect(reservedQuantity($variant->product_id))->toBe(3);

    cancelOrder($parentItem->order);

    expect(reservedQuantity($variant->product_id))->toBe(0);
});

it('should release a simple product reservation when the order is canceled', function () {
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

    cancelOrder($order->refresh());

    expect(reservedQuantity($product->id))->toBe(0);
});

it('should leave nothing reserved when a partly shipped configurable order is canceled', function () {
    $parentItem = configurableOrderItem(3);

    $child = $parentItem->children->first();

    app(OrderItemRepository::class)->manageInventory($parentItem);

    $child->product->ordered_inventories()->update(['qty' => 2]);

    $child->update(['qty_shipped' => 1]);

    app(OrderItemRepository::class)->updateProductOrderedInventories($child->refresh());

    expect(reservedQuantity($child->product_id))->toBe(0);
});

// ============================================================================
// Releasing When Inventory Management Is Switched Off
// ============================================================================

it('should release the reservation of a canceled order whose product no longer manages stock', function () {
    $product = (new ProductFaker)->getSimpleProductFactory()->create();

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

    expect(reservedQuantity($product->id))->toBe(3);

    setManageStock($product, false);

    cancelOrder($order->refresh());

    expect(reservedQuantity($product->id))->toBe(0);
});

it('should release a canceled configurable order whose variant no longer manages stock', function () {
    $parentItem = configurableOrderItem(3);

    $variant = $parentItem->children->first();

    app(OrderItemRepository::class)->manageInventory($parentItem);

    setManageStock($variant->product, false);

    cancelOrder($parentItem->order->refresh());

    expect(reservedQuantity($variant->product_id))->toBe(0);
});

it('should reserve and release nothing for a product that never managed stock', function () {
    $product = setManageStock((new ProductFaker)->getSimpleProductFactory()->create(), false);

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

    cancelOrder($order->refresh());

    expect(ProductOrderedInventory::query()->where('product_id', $product->id)->exists())->toBeFalse();
});
