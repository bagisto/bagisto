<?php

use Illuminate\Support\Facades\Storage;
use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\Product\Helpers\Indexers\Flat as FlatIndexer;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductFlat;
use Webkul\Product\Models\ProductImage;

use function Pest\Laravel\getJson;

function makeProductWithImages(): Product
{
    $product = (new ProductFaker)->getSimpleProductFactory()->create();

    ProductImage::create([
        'product_id' => $product->id,
        'type' => 'images',
        'path' => 'product/'.$product->id.'/first-uploaded.webp',
        'position' => 1,
    ]);

    ProductImage::create([
        'product_id' => $product->id,
        'type' => 'images',
        'path' => 'product/'.$product->id.'/second-uploaded.webp',
        'position' => 2,
    ]);

    app(FlatIndexer::class)->refreshDerivedColumns([$product->id]);

    return $product;
}

function baseImageOf(Product $product): ?string
{
    return ProductFlat::query()
        ->where('product_id', $product->id)
        ->value('base_image');
}

function reorderImages(Product $product): void
{
    $images = ProductImage::query()
        ->where('product_id', $product->id)
        ->orderBy('position')
        ->get();

    $images[0]->update(['position' => 2]);

    $images[1]->update(['position' => 1]);

    app(FlatIndexer::class)->refreshDerivedColumns([$product->id]);
}

it('should hold the first image by position as the base image', function () {
    $product = makeProductWithImages();

    expect(baseImageOf($product))->toBe('product/'.$product->id.'/first-uploaded.webp');
});

it('should follow the image order rather than the order the images were uploaded', function () {
    $product = makeProductWithImages();

    reorderImages($product);

    expect(baseImageOf($product))->toBe('product/'.$product->id.'/second-uploaded.webp');
});

it('should agree with the base image the product itself reports', function () {
    $product = makeProductWithImages();

    reorderImages($product);

    expect(baseImageOf($product))->toBe($product->fresh()->images->first()->path);
});

it('should show the reordered image in the products listing', function () {
    $product = makeProductWithImages();

    reorderImages($product);

    $this->loginAsAdmin();

    getJson(route('admin.catalog.products.index', [
        'filters' => ['product_id' => [$product->id]],
    ]), ['X-Requested-With' => 'XMLHttpRequest'])
        ->assertOk()
        ->assertJsonPath('records.0.base_image', Storage::url('product/'.$product->id.'/second-uploaded.webp'));
});
