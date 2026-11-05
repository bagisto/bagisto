<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Webkul\Core\Facades\Core;
use Webkul\Product\Models\ProductVideo;
use Webkul\Product\Repositories\ProductVideoRepository;

use function Pest\Laravel\get;
use function Pest\Laravel\putJson;

// ============================================================================
// Edit Page
// ============================================================================

it('should render the SEO drawer on the product videos panel', function () {
    $product = $this->createSimpleProduct();

    $this->loginAsAdmin();

    get(route('admin.catalog.products.edit', $product->id))
        ->assertOk()
        ->assertSee('videos[meta]')
        ->assertSee(trans('admin::app.components.media.videos.seo.title'));
});

it('should show the configured limit on the product videos panel', function () {
    $this->setConfig('catalog.products.attribute.file_attribute_upload_size', 1024);

    $product = $this->createSimpleProduct();

    $this->loginAsAdmin();

    get(route('admin.catalog.products.edit', $product->id))
        ->assertOk()
        ->assertSee(trans('admin::app.catalog.products.edit.videos.info', ['size' => '1024 KB']));
});

// ============================================================================
// Upload Limit
// ============================================================================

it('should work out the video limit from the configuration and what PHP accepts', function (string $configured, int $php, int $expected) {
    Core::shouldReceive('getConfigData')->andReturn($configured);

    Core::shouldReceive('getMaxUploadSize')->andReturn($php);

    expect(product_video()->getMaxUploadSize())->toBe($expected);
})->with([
    'nothing configured defaults to ten megabytes' => ['', 1048576, 10240],
    'a configured limit above the default' => ['51200', 1048576, 51200],
    'a configured limit below the default' => ['1024', 1048576, 1024],
    'nothing configured with php accepting less' => ['', 2048, 2048],
    'a configured limit php does not accept' => ['51200', 8192, 8192],
]);

// ============================================================================
// Renaming
// ============================================================================

it('should rename an existing product video while keeping its extension', function () {
    Storage::fake();

    $product = $this->createSimpleProduct();

    $path = 'products/'.$product->id.'/lk92mdow.mp4';

    Storage::put($path, 'video-contents');

    $video = ProductVideo::query()->create([
        'product_id' => $product->id,
        'type' => 'videos',
        'path' => $path,
        'position' => 1,
    ]);

    app(ProductVideoRepository::class)->upload([
        'videos' => [
            'files' => [$video->id => ''],
            'meta' => [$video->id => ['file_name' => 'Product Walkthrough']],
        ],
    ], $product, 'videos');

    $expected = 'products/'.$product->id.'/product-walkthrough.mp4';

    expect($video->fresh()->path)->toBe($expected);

    Storage::assertExists($expected);

    Storage::assertMissing($path);
});

// ============================================================================
// Uploads
// ============================================================================

it('should say a picked file of the wrong type is not a video', function () {
    $product = $this->createSimpleProduct();

    $this->loginAsAdmin();

    putJson(route('admin.catalog.products.update', $product->id), [
        'videos' => ['files' => ['video_0' => UploadedFile::fake()->create('clip.mp4', 1, 'text/plain')]],
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['videos.files.video_0' => trans('admin::app.components.media.videos.not-allowed-error', ['types' => product_video()->getAllowedExtensions()->implode(', ')])]);
});

it('should say a video over the configured limit is too large, naming the limit', function () {
    $this->setConfig('catalog.products.attribute.file_attribute_upload_size', 1024);

    $product = $this->createSimpleProduct();

    $this->loginAsAdmin();

    putJson(route('admin.catalog.products.update', $product->id), [
        'videos' => ['files' => ['video_0' => UploadedFile::fake()->create('clip.mp4', 1100, 'video/mp4')]],
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['videos.files.video_0' => 'The Video may not be greater than 1024 kilobytes']);
});

it('should name a newly uploaded video after the requested file name', function () {
    Storage::fake();

    $product = $this->createSimpleProduct();

    app(ProductVideoRepository::class)->upload([
        'videos' => [
            'files' => ['image_0' => UploadedFile::fake()->create('VID_0001.mp4', 8, 'video/mp4')],
            'meta' => ['image_0' => ['file_name' => 'Product Walkthrough']],
        ],
    ], $product, 'videos');

    $video = $product->fresh()->videos->first();

    expect($video->path)->toBe('products/'.$product->id.'/product-walkthrough.mp4');

    Storage::assertExists($video->path);
});

it('should not attempt to store alt text against a video', function () {
    Storage::fake();

    $product = $this->createSimpleProduct();

    app(ProductVideoRepository::class)->upload([
        'videos' => [
            'files' => ['image_0' => UploadedFile::fake()->create('VID_0001.mp4', 8, 'video/mp4')],
            'meta' => ['image_0' => ['alt_text' => 'ignored', 'file_name' => 'Product Walkthrough']],
        ],
    ], $product, 'videos');

    $video = $product->fresh()->videos->first();

    expect($video->path)->toBe('products/'.$product->id.'/product-walkthrough.mp4')
        ->and($video->file_name)->toBe('product-walkthrough');
});
