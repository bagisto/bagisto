<?php

use Webkul\Attribute\Models\Attribute;
use Webkul\Attribute\Models\AttributeFamily;
use Webkul\Category\Models\Category;
use Webkul\Category\Models\CategoryTranslation;
use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\Inventory\Models\InventorySource;
use Webkul\Product\Helpers\Indexers\Flat as FlatIndexer;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductAttributeValue;
use Webkul\Product\Models\ProductFlat;
use Webkul\Product\Models\ProductImage;
use Webkul\Product\Models\ProductInventory;

function makeProduct(): Product
{
    return (new ProductFaker)->getSimpleProductFactory()->create();
}

function reindex(Product $product): void
{
    app(FlatIndexer::class)->refresh($product->fresh());
}

function refreshDerived(Product $product): void
{
    app(FlatIndexer::class)->refreshDerivedColumns([$product->id]);
}

function flatColumn(Product $product, string $column)
{
    return ProductFlat::query()
        ->where('product_id', $product->id)
        ->value($column);
}

function addInventory(Product $product, int $qty): void
{
    ProductInventory::create([
        'product_id' => $product->id,
        'inventory_source_id' => InventorySource::factory()->create()->id,
        'qty' => $qty,
        'vendor_id' => 0,
    ]);
}

function addImage(Product $product, string $name, int $position): void
{
    ProductImage::create([
        'product_id' => $product->id,
        'type' => 'images',
        'path' => 'product/'.$product->id.'/'.$name,
        'position' => $position,
    ]);
}

function addCategory(Product $product, string $name): Category
{
    $category = Category::factory()->create();

    CategoryTranslation::factory()->create([
        'category_id' => $category->id,
        'name' => $name,
        'locale' => 'en',
    ]);

    $product->categories()->attach($category->id);

    return $category;
}

it('should total the quantity across every inventory source', function () {
    $product = makeProduct();

    $existing = (int) $product->inventories->sum('qty');

    addInventory($product, 40);

    addInventory($product, 2);

    refreshDerived($product);

    expect((int) flatColumn($product, 'quantity'))->toBe($existing + 42);
});

it('should leave the quantity empty for a product that keeps no stock of its own', function () {
    $product = makeProduct();

    ProductInventory::query()->where('product_id', $product->id)->delete();

    refreshDerived($product);

    expect(flatColumn($product, 'quantity'))->toBeNull();
});

it('should follow a change in stock', function () {
    $product = makeProduct();

    ProductInventory::query()->where('product_id', $product->id)->delete();

    addInventory($product, 5);

    refreshDerived($product);

    expect((int) flatColumn($product, 'quantity'))->toBe(5);

    ProductInventory::query()->where('product_id', $product->id)->update(['qty' => 9]);

    refreshDerived($product);

    expect((int) flatColumn($product, 'quantity'))->toBe(9);
});

it('should count the images of a product', function () {
    $product = makeProduct();

    addImage($product, 'one.webp', 1);

    addImage($product, 'two.webp', 2);

    refreshDerived($product);

    expect((int) flatColumn($product, 'images_count'))->toBe(2);
});

it('should count no images for a product without any', function () {
    $product = makeProduct();

    refreshDerived($product);

    expect((int) flatColumn($product, 'images_count'))->toBe(0);
});

it('should hold the first image by position as the base image', function () {
    $product = makeProduct();

    addImage($product, 'second.webp', 2);

    addImage($product, 'first.webp', 1);

    refreshDerived($product);

    expect(flatColumn($product, 'base_image'))->toBe('product/'.$product->id.'/first.webp');
});

it('should leave the base image empty for a product without images', function () {
    $product = makeProduct();

    refreshDerived($product);

    expect(flatColumn($product, 'base_image'))->toBeNull();
});

it('should hold the name of the attribute family', function () {
    $product = makeProduct();

    refreshDerived($product);

    expect(flatColumn($product, 'attribute_family_name'))
        ->toBe($product->attribute_family->name);
});

it('should follow a renamed attribute family', function () {
    $product = makeProduct();

    AttributeFamily::query()
        ->where('id', $product->attribute_family_id)
        ->update(['name' => 'Renamed Family']);

    refreshDerived($product);

    expect(flatColumn($product, 'attribute_family_name'))->toBe('Renamed Family');
});

it('should list every category a product is filed under', function () {
    $product = makeProduct();

    addCategory($product, 'Alpha Category');

    addCategory($product, 'Beta Category');

    refreshDerived($product);

    expect(flatColumn($product, 'category_name'))->toBe('Alpha Category, Beta Category');
});

it('should leave the categories empty for an uncategorised product', function () {
    $product = makeProduct();

    refreshDerived($product);

    expect(flatColumn($product, 'category_name'))->toBeNull();
});

it('should follow a renamed category', function () {
    $product = makeProduct();

    $category = addCategory($product, 'Before Rename');

    refreshDerived($product);

    expect(flatColumn($product, 'category_name'))->toBe('Before Rename');

    CategoryTranslation::query()
        ->where('category_id', $category->id)
        ->where('locale', 'en')
        ->update(['name' => 'After Rename']);

    refreshDerived($product);

    expect(flatColumn($product, 'category_name'))->toBe('After Rename');
});

it('should take the manage stock flag from the attribute default when a product never set it', function () {
    $product = makeProduct();

    $attribute = Attribute::query()->where('code', 'manage_stock')->first();

    ProductAttributeValue::query()
        ->where('product_id', $product->id)
        ->where('attribute_id', $attribute->id)
        ->delete();

    reindex($product);

    expect((int) flatColumn($product, 'manage_stock'))->toBe((int) $attribute->default_value);
});

it('should hold the manage stock flag a product turned off', function () {
    $product = makeProduct();

    $attribute = Attribute::query()->where('code', 'manage_stock')->first();

    ProductAttributeValue::query()->updateOrCreate([
        'product_id' => $product->id,
        'attribute_id' => $attribute->id,
        'channel' => core()->getDefaultChannelCode(),
    ], [
        'boolean_value' => 0,
        'unique_id' => core()->getDefaultChannelCode().'|'.$product->id.'|'.$attribute->id,
    ]);

    reindex($product);

    expect((int) flatColumn($product, 'manage_stock'))->toBe(0);
});
