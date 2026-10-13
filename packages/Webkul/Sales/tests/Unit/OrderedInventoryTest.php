<?php

use Webkul\Attribute\Models\Attribute;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductAttributeValue;
use Webkul\Product\Models\ProductOrderedInventory;
use Webkul\Sales\Exceptions\InsufficientInventoryException;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderItem;
use Webkul\Sales\Repositories\OrderItemRepository;
use Webkul\Sales\Repositories\OrderRepository;

/**
 * A configurable order item whose variant child carries no quantity of its own, which is the
 * shape the cart builds: the parent holds the quantity and the child holds the stock.
 */
function configurableOrderItem(int $orderedQuantity, int $variantStock = 50): OrderItem
{
    $product = test()->createConfigurableProduct();

    $variant = test()->setProductStock($product->variants->first(), $variantStock);

    $order = test()->createOrder(items: [[
        'product' => $product,
        'qty_ordered' => $orderedQuantity,
    ]]);

    $parentItem = $order->items->first();

    test()->createOrderItem($order, [
        'product' => $variant,
        'parent_id' => $parentItem->id,
        'qty_ordered' => 0,
    ]);

    return $parentItem->refresh();
}

/**
 * The quantity reserved against a product across the channels it is carried by.
 */
function reservedQuantity(Product $product): int
{
    return (int) ProductOrderedInventory::query()
        ->where('product_id', $product->id)
        ->sum('qty');
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

    app(OrderItemRepository::class)->manageInventory($parentItem);

    expect(reservedQuantity($parentItem->children->first()->product))->toBe(3);
});

it('should reserve a composite child own quantity when it carries one, as a bundle child does', function () {
    $parentItem = configurableOrderItem(3);

    $child = $parentItem->children->first();

    $child->update(['qty_ordered' => 5]);

    app(OrderItemRepository::class)->manageInventory($parentItem->refresh());

    expect(reservedQuantity($child->product))->toBe(5);
});

it('should reserve nothing against the configurable parent, which holds no stock', function () {
    $parentItem = configurableOrderItem(3);

    app(OrderItemRepository::class)->manageInventory($parentItem);

    expect(reservedQuantity($parentItem->product))->toBe(0);
});

// ============================================================================
// Adding To An Existing Reservation
// ============================================================================

it('should add to a reservation the product already holds', function () {
    $product = $this->setProductStock($this->createSimpleProduct(), 50);

    $product->ordered_inventories()->create([
        'qty' => 2,
        'product_id' => $product->id,
        'channel_id' => core()->getDefaultChannel()->id,
    ]);

    $order = $this->createOrder(items: [['product' => $product, 'qty_ordered' => 3]]);

    app(OrderItemRepository::class)->manageInventory($order->items->first());

    expect(reservedQuantity($product))->toBe(5);
});

// ============================================================================
// Refusing An Order The Stock Cannot Cover
// ============================================================================

it('should refuse a configurable order for more units than the variant has in stock', function () {
    $parentItem = configurableOrderItem(3, variantStock: 2);

    expect(fn () => app(OrderItemRepository::class)->manageInventory($parentItem))
        ->toThrow(InsufficientInventoryException::class)
        ->and(reservedQuantity($parentItem->children->first()->product))->toBe(0);
});

// ============================================================================
// Reserving Against A Simple Item
// ============================================================================

it('should reserve the ordered quantity against a simple product', function () {
    $product = $this->setProductStock($this->createSimpleProduct(), 50);

    $order = $this->createOrder(items: [['product' => $product, 'qty_ordered' => 2]]);

    app(OrderItemRepository::class)->manageInventory($order->items->first());

    expect(reservedQuantity($product))->toBe(2);
});

// ============================================================================
// Releasing The Reservation On Cancellation
// ============================================================================

it('should release a configurable variant reservation when the order is canceled', function () {
    $parentItem = configurableOrderItem(3);

    $variant = $parentItem->children->first()->product;

    app(OrderItemRepository::class)->manageInventory($parentItem);

    expect(reservedQuantity($variant))->toBe(3);

    app(OrderRepository::class)->cancel($parentItem->order);

    expect(reservedQuantity($variant))->toBe(0);
});

it('should release a simple product reservation when the order is canceled', function () {
    $product = $this->setProductStock($this->createSimpleProduct(), 50);

    $order = $this->createOrder(items: [['product' => $product, 'qty_ordered' => 2]]);

    app(OrderItemRepository::class)->manageInventory($order->items->first());

    app(OrderRepository::class)->cancel($order->refresh());

    expect(reservedQuantity($product))->toBe(0);
});

it('should leave nothing reserved when a partly shipped configurable order is canceled', function () {
    $parentItem = configurableOrderItem(3);

    $child = $parentItem->children->first();

    app(OrderItemRepository::class)->manageInventory($parentItem);

    $child->product->ordered_inventories()->update(['qty' => 2]);

    $child->update(['qty_shipped' => 1]);

    app(OrderItemRepository::class)->updateProductOrderedInventories($child->refresh());

    expect(reservedQuantity($child->product))->toBe(0);
});

// ============================================================================
// Releasing When Inventory Management Is Switched Off
// ============================================================================

it('should release the reservation of a canceled order whose product no longer manages stock', function () {
    $product = $this->setProductStock($this->createSimpleProduct(), 50);

    $order = $this->createOrder(items: [['product' => $product, 'qty_ordered' => 3]]);

    app(OrderItemRepository::class)->manageInventory($order->items->first());

    expect(reservedQuantity($product))->toBe(3);

    setManageStock($product, false);

    app(OrderRepository::class)->cancel($order->refresh());

    expect(reservedQuantity($product))->toBe(0);
});

it('should release a canceled configurable order whose variant no longer manages stock', function () {
    $parentItem = configurableOrderItem(3);

    $variant = $parentItem->children->first()->product;

    app(OrderItemRepository::class)->manageInventory($parentItem);

    setManageStock($variant, false);

    app(OrderRepository::class)->cancel($parentItem->order->refresh());

    expect(reservedQuantity($variant))->toBe(0);
});

it('should reserve and release nothing for a product that never managed stock', function () {
    $product = setManageStock($this->setProductStock($this->createSimpleProduct(), 50), false);

    $order = $this->createOrder(items: [['product' => $product, 'qty_ordered' => 3]]);

    app(OrderItemRepository::class)->manageInventory($order->items->first());

    app(OrderRepository::class)->cancel($order->refresh());

    expect(ProductOrderedInventory::query()->where('product_id', $product->id)->exists())->toBeFalse();
});
