<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Webkul\Product\Models\Product;

use function Pest\Laravel\get;
use function Pest\Laravel\putJson;

/**
 * The payload that updates a product with the given images and videos.
 */
function productMediaPayload(Product $product, array $media): array
{
    return [
        'sku' => $product->sku,
        'url_key' => $product->url_key,
        'name' => fake()->words(3, true),
        'short_description' => fake()->sentence(),
        'description' => fake()->paragraph(),
        'price' => 100,
        'weight' => 1,
        'channel' => core()->getDefaultChannelCode(),
        'locale' => app()->getLocale(),
        ...$media,
    ];
}

/**
 * Update a new product with the given images and videos as an admin, returning the response and the product.
 */
function updateProductMedia(array $media): array
{
    $product = test()->createSimpleProduct();

    test()->loginAsAdmin();

    return [putJson(route('admin.catalog.products.update', $product->id), productMediaPayload($product, $media)), $product];
}

beforeEach(function () {
    Storage::fake();
});

// ============================================================================
// Images
// ============================================================================

it('should refuse a product image of a type the media settings do not allow', function () {
    [$response] = updateProductMedia([
        'images' => ['files' => ['image_0' => UploadedFile::fake()->image('photo.gif')]],
    ]);

    $response->assertJsonValidationErrorFor('images.files.image_0');
});

it('should accept a product image type once the media settings allow it', function () {
    $this->setConfig('file_management.media.images.allowed_extensions', 'gif,png');

    [$response, $product] = updateProductMedia([
        'images' => ['files' => ['image_0' => UploadedFile::fake()->image('photo.gif')]],
    ]);

    $response->assertRedirect()->assertSessionHasNoErrors();

    expect($product->fresh()->images)->toHaveCount(1);
});

it('should refuse a product image larger than the media settings allow', function () {
    $this->setConfig('file_management.media.images.max_size', 100);

    [$response] = updateProductMedia([
        'images' => ['files' => ['image_0' => UploadedFile::fake()->image('photo.png')->size(101)]],
    ]);

    $response->assertJsonValidationErrorFor('images.files.image_0');
});

// ============================================================================
// Videos
// ============================================================================

it('should accept a product video of every default type', function (string $file, string $mime) {
    [$response, $product] = updateProductMedia([
        'videos' => ['files' => ['video_0' => UploadedFile::fake()->create($file, 8, $mime)]],
    ]);

    $response->assertRedirect()->assertSessionHasNoErrors();

    expect($product->fresh()->videos)->toHaveCount(1);
})->with([
    ['clip.mp4', 'video/mp4'],
    ['clip.webm', 'video/webm'],
    ['clip.mov', 'video/quicktime'],
]);

it('should refuse a product video of a type the media settings do not allow', function () {
    [$response] = updateProductMedia([
        'videos' => ['files' => ['video_0' => UploadedFile::fake()->create('clip.mkv', 8, 'video/x-matroska')]],
    ]);

    $response->assertJsonValidationErrorFor('videos.files.video_0');
});

it('should refuse an arbitrary binary sent as a product video', function () {
    [$response] = updateProductMedia([
        'videos' => ['files' => ['video_0' => UploadedFile::fake()->create('clip.mp4', 8, 'application/octet-stream')]],
    ]);

    $response->assertJsonValidationErrorFor('videos.files.video_0');
});

it('should refuse a product video larger than the media settings allow', function () {
    $this->setConfig('file_management.media.videos.max_size', 100);

    [$response] = updateProductMedia([
        'videos' => ['files' => ['video_0' => UploadedFile::fake()->create('clip.mp4', 101, 'video/mp4')]],
    ]);

    $response->assertJsonValidationErrorFor('videos.files.video_0');
});

// ============================================================================
// Edit Page
// ============================================================================

it('should show the configured video size on the product edit page', function () {
    $this->setConfig('file_management.media.videos.max_size', 1536);

    $product = $this->createSimpleProduct();

    $this->loginAsAdmin();

    get(route('admin.catalog.products.edit', $product->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.catalog.products.edit.videos.info', ['size' => '1.5 MB']));
});
