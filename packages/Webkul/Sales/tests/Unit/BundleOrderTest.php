<?php

use Webkul\Attribute\Models\Attribute;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductAttributeValue;
use Webkul\Product\Models\ProductOrderedInventory;
use Webkul\Sales\Repositories\OrderItemRepository;

/**
 * A bundle order in the shape the cart builds it: the parent carries the bundle and each option
 * product is a child item carrying the quantity that option contributes.
 */
function bundleOrder(int $parentQuantity = 2, array $childQuantities = [2, 4], int $stock = 50): array
{
    $bundle = test()->createBundleProduct();

    $order = test()->createOrder(items: [[
        'product' => $bundle,
        'qty_ordered' => $parentQuantity,
    ]]);

    $parentItem = $order->items->first();

    $children = [];

    foreach ($bundle->bundle_options->first()->bundle_option_products as $index => $optionProduct) {
        $children[] = test()->createOrderItem($order, [
            'product' => test()->setProductStock($optionProduct->product, $stock),
            'parent_id' => $parentItem->id,
            'qty_ordered' => $childQuantities[$index],
        ]);
    }

    return [$order->refresh(), $parentItem->refresh(), $children];
}

/**
 * The quantity reserved against a product across every channel.
 */
function reservedForBundle(Product $product): int
{
    return (int) ProductOrderedInventory::query()
        ->where('product_id', $product->id)
        ->sum('qty');
}

/**
 * Turn a product's stock management on or off, as the admin's manage stock checkbox does.
 */
function manageStockForBundleOption(Product $product, bool $enabled): Product
{
    ProductAttributeValue::query()
        ->where('product_id', $product->id)
        ->where('attribute_id', Attribute::query()->where('code', 'manage_stock')->value('id'))
        ->update(['boolean_value' => $enabled]);

    return Product::find($product->id);
}

// ============================================================================
// Reserving A Bundle's Inventory
// ============================================================================

it('should reserve each bundle option against its own product for its own quantity', function () {
    [, $parentItem, $children] = bundleOrder(parentQuantity: 2, childQuantities: [2, 4]);

    app(OrderItemRepository::class)->manageInventory($parentItem);

    expect(reservedForBundle($children[0]->product))->toBe(2)
        ->and(reservedForBundle($children[1]->product))->toBe(4);
});

it('should reserve nothing against the bundle itself, which holds no stock of its own', function () {
    [, $parentItem] = bundleOrder();

    app(OrderItemRepository::class)->manageInventory($parentItem);

    expect(reservedForBundle($parentItem->product))->toBe(0);
});

it('should fall back to the bundle quantity for an option carrying none of its own', function () {
    [, $parentItem, $children] = bundleOrder(parentQuantity: 3, childQuantities: [0, 0]);

    app(OrderItemRepository::class)->manageInventory($parentItem);

    expect(reservedForBundle($children[0]->product))->toBe(3)
        ->and(reservedForBundle($children[1]->product))->toBe(3);
});

it('should reserve nothing for a bundle option that does not manage stock', function () {
    [, $parentItem, $children] = bundleOrder(parentQuantity: 2, childQuantities: [2, 4]);

    manageStockForBundleOption($children[0]->product, false);

    app(OrderItemRepository::class)->manageInventory($parentItem->refresh());

    expect(reservedForBundle($children[0]->product))->toBe(0)
        ->and(reservedForBundle($children[1]->product))->toBe(4);
});

// ============================================================================
// Releasing It Again
// ============================================================================

it('should release what a bundle option reserved once nothing was shipped', function () {
    [, $parentItem, $children] = bundleOrder(parentQuantity: 2, childQuantities: [2, 4]);

    $repository = app(OrderItemRepository::class);

    $repository->manageInventory($parentItem);

    foreach ($children as $child) {
        $repository->updateProductOrderedInventories($child);
    }

    expect(reservedForBundle($children[0]->product))->toBe(0)
        ->and(reservedForBundle($children[1]->product))->toBe(0);
});
