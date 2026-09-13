<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Webkul\ImageCache\TemplateRegistry;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductImage;
use Webkul\Product\ProductImage as ProductImages;

use function Pest\Laravel\get;

beforeEach(function () {
    Storage::fake('s3');

    config(['filesystems.default' => 's3']);
});

/**
 * Attach an image to a product, with its file on the store's disk unless told otherwise.
 */
function productImageOnStoreDisk(Product $product, bool $stored = true): ProductImage
{
    $path = 'product/'.$product->id.'/'.fake()->unique()->lexify('????????').'.png';

    if ($stored) {
        Storage::put($path, UploadedFile::fake()->image('image.png', 20, 20)->getContent());
    }

    return ProductImage::query()->create([
        'product_id' => $product->id,
        'type' => 'images',
        'path' => $path,
        'position' => 1,
    ]);
}

// ============================================================================
// Remote Disk
// ============================================================================

it('should serve the urls of a product image through the image cache when the store keeps its files on a remote disk', function () {
    $product = $this->createSimpleProduct();

    $image = productImageOnStoreDisk($product);

    [$urls] = app(ProductImages::class)->getGalleryImages($product->fresh());

    expect(Storage::disk('s3')->exists($image->path))->toBeTrue()
        ->and($urls['small_image_url'])->toBe(url('cache/small/'.$image->path))
        ->and($urls['medium_image_url'])->toBe(url('cache/medium/'.$image->path))
        ->and($urls['large_image_url'])->toBe(url('cache/large/'.$image->path))
        ->and($urls['original_image_url'])->toBe(url('cache/original/'.$image->path));
});

it('should list only the product images whose files are on the remote disk', function () {
    $product = $this->createSimpleProduct();

    productImageOnStoreDisk($product, stored: false);

    $stored = productImageOnStoreDisk($product);

    $images = app(ProductImages::class)->getGalleryImages($product->fresh());

    expect($images)->toHaveCount(1)
        ->and($images[0]['original_image_url'])->toBe(url('cache/original/'.$stored->path));
});

it('should read a cached product image from the remote disk', function () {
    $product = $this->createSimpleProduct();

    $image = productImageOnStoreDisk($product);

    $response = get(image_urls($image->path, TemplateRegistry::PRODUCT_IMAGES)['small_image_url'])->assertOk();

    expect($response->headers->get('Content-Type'))->toStartWith('image/');
});

it('should keep the url of every product image template on the image cache', function () {
    expect(image_urls('product/1/image.png', TemplateRegistry::PRODUCT_IMAGES))
        ->toHaveKeys(['small_image_url', 'medium_image_url', 'large_image_url', 'original_image_url'])
        ->each->toStartWith(url('cache/'));
});
