<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\Product\Models\ProductVideo;
use Webkul\Product\Repositories\ProductVideoRepository;

use function Pest\Laravel\get;

it('should render the seo drawer on the product videos panel', function () {
    $product = (new ProductFaker)->getSimpleProductFactory()->create();

    $this->loginAsAdmin();

    get(route('admin.catalog.products.edit', $product->id))
        ->assertOk()
        ->assertSee('videos[meta]')
        ->assertSee(trans('admin::app.components.media.videos.seo.title'));
});

it('should rename an existing product video while keeping its extension', function () {
    Storage::fake();

    $product = (new ProductFaker)->getSimpleProductFactory()->create();

    $path = 'product/'.$product->id.'/lk92mdow.mp4';

    Storage::put($path, 'video-contents');

    $video = ProductVideo::create([
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

    $expected = 'product/'.$product->id.'/product-walkthrough.mp4';

    expect($video->fresh()->path)->toBe($expected);

    Storage::assertExists($expected);

    Storage::assertMissing($path);
});

it('should name a newly uploaded video after the requested file name', function () {
    Storage::fake();

    $product = (new ProductFaker)->getSimpleProductFactory()->create();

    app(ProductVideoRepository::class)->upload([
        'videos' => [
            'files' => ['image_0' => UploadedFile::fake()->create('VID_0001.mp4', 8, 'video/mp4')],
            'meta' => ['image_0' => ['file_name' => 'Product Walkthrough']],
        ],
    ], $product, 'videos');

    $video = $product->fresh()->videos->first();

    expect($video->path)->toBe('product/'.$product->id.'/product-walkthrough.mp4');

    Storage::assertExists($video->path);
});

it('should not attempt to store alt text against a video', function () {
    Storage::fake();

    $product = (new ProductFaker)->getSimpleProductFactory()->create();

    app(ProductVideoRepository::class)->upload([
        'videos' => [
            'files' => ['image_0' => UploadedFile::fake()->create('VID_0001.mp4', 8, 'video/mp4')],
            'meta' => ['image_0' => ['alt_text' => 'ignored', 'file_name' => 'Product Walkthrough']],
        ],
    ], $product, 'videos');

    $video = $product->fresh()->videos->first();

    expect($video->path)->toBe('product/'.$product->id.'/product-walkthrough.mp4');

    expect($video->file_name)->toBe('product-walkthrough');
});
