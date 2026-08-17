<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductImage;
use Webkul\Product\Repositories\ProductImageRepository;

use function Pest\Laravel\get;
use function Pest\Laravel\putJson;

function makeProductWithStoredImage(): array
{
    $product = (new ProductFaker)->getSimpleProductFactory()->create();

    $path = 'product/'.$product->id.'/hf83ndkq.webp';

    Storage::put($path, 'image-contents');

    $image = ProductImage::create([
        'product_id' => $product->id,
        'type' => 'images',
        'path' => $path,
        'position' => 1,
    ]);

    return [$product, $image];
}

function productUpdatePayload(Product $product, array $images): array
{
    return [
        'sku' => $product->sku,
        'url_key' => $product->url_key,
        'name' => fake()->words(3, true),
        'short_description' => fake()->sentence(),
        'description' => fake()->paragraph(),
        'price' => 100,
        'weight' => 1,
        'channel' => core()->getCurrentChannelCode(),
        'locale' => app()->getLocale(),
        'images' => $images,
    ];
}

it('should render the seo drawer on the product edit page', function () {
    [$product] = makeProductWithStoredImage();

    $this->loginAsAdmin();

    get(route('admin.catalog.products.edit', $product->id))
        ->assertOk()
        ->assertSee('images[meta]')
        ->assertSee(trans('admin::app.components.media.images.seo.title'))
        ->assertSee(trans('admin::app.components.media.images.seo.alt-text'))
        ->assertSee(trans('admin::app.components.media.images.seo.file-name'));
});

it('should not render the seo drawer where it is not enabled', function () {
    $this->loginAsAdmin();

    get(route('admin.settings.channels.create'))
        ->assertOk()
        ->assertDontSee('images[meta]');
});

it('should save the alt text of an existing image', function () {
    Storage::fake();

    [$product, $image] = makeProductWithStoredImage();

    $this->loginAsAdmin();

    putJson(route('admin.catalog.products.update', $product->id), productUpdatePayload($product, [
        'files' => [$image->id => ''],
        'meta' => [$image->id => ['alt_text' => 'Blue running shoe from the side']],
    ]))->assertRedirect(route('admin.catalog.products.index'));

    expect($image->fresh()->alt_text)->toBe('Blue running shoe from the side');
});

it('should rename the file of an existing image', function () {
    Storage::fake();

    [$product, $image] = makeProductWithStoredImage();

    $originalPath = $image->path;

    $this->loginAsAdmin();

    putJson(route('admin.catalog.products.update', $product->id), productUpdatePayload($product, [
        'files' => [$image->id => ''],
        'meta' => [$image->id => ['file_name' => 'Blue Running Shoe Side']],
    ]))->assertRedirect(route('admin.catalog.products.index'));

    $expected = 'product/'.$product->id.'/blue-running-shoe-side.webp';

    expect($image->fresh()->path)->toBe($expected);

    Storage::assertExists($expected);

    Storage::assertMissing($originalPath);
});

it('should reject an alt text longer than the column allows', function () {
    Storage::fake();

    [$product, $image] = makeProductWithStoredImage();

    $this->loginAsAdmin();

    putJson(route('admin.catalog.products.update', $product->id), productUpdatePayload($product, [
        'files' => [$image->id => ''],
        'meta' => [$image->id => ['alt_text' => str_repeat('a', 256)]],
    ]))->assertUnprocessable();
});

it('should keep the alt text of each locale apart', function () {
    Storage::fake();

    [$product, $image] = makeProductWithStoredImage();

    $repository = app(ProductImageRepository::class);

    $payload = fn (string $altText) => [
        'images' => [
            'files' => [$image->id => ''],
            'meta' => [$image->id => ['alt_text' => $altText]],
        ],
    ];

    app()->setLocale('en');
    $repository->upload($payload('Blue running shoe'), $product, 'images');

    app()->setLocale('fr');
    $repository->upload($payload('Chaussure de course bleue'), $product, 'images');

    $image = $image->fresh();

    expect($image->translate('en')->alt_text)->toBe('Blue running shoe');

    expect($image->translate('fr')->alt_text)->toBe('Chaussure de course bleue');

    app()->setLocale('en');
});

it('should name a newly uploaded image after the requested file name', function () {
    Storage::fake();

    $product = (new ProductFaker)->getSimpleProductFactory()->create();

    app(ProductImageRepository::class)->upload([
        'images' => [
            'files' => ['image_0' => UploadedFile::fake()->image('DSC_0001.jpg', 20, 20)],
            'meta' => ['image_0' => ['alt_text' => 'Blue running shoe', 'file_name' => 'Blue Running Shoe']],
        ],
    ], $product, 'images');

    $image = $product->fresh()->images->first();

    expect($image->path)->toBe('product/'.$product->id.'/blue-running-shoe.webp');

    expect($image->alt_text)->toBe('Blue running shoe');

    Storage::assertExists($image->path);
});

it('should fall back to a random name for an upload without a requested file name', function () {
    Storage::fake();

    $product = (new ProductFaker)->getSimpleProductFactory()->create();

    app(ProductImageRepository::class)->upload([
        'images' => [
            'files' => ['image_0' => UploadedFile::fake()->image('DSC_0001.jpg', 20, 20)],
        ],
    ], $product, 'images');

    $image = $product->fresh()->images->first();

    expect($image->file_name)->toHaveLength(40);

    expect($image->path)->toEndWith('.webp');
});

it('should expose the alt text to the storefront', function () {
    [$product, $image] = makeProductWithStoredImage();

    $image->translateOrNew(app()->getLocale())->alt_text = 'Blue running shoe from the side';

    $image->save();

    $galleryImages = product_image()->getGalleryImages($product->fresh());

    expect($galleryImages[0]['alt'])->toBe('Blue running shoe from the side');
});

it('should fall back to the product name when an image has no alt text', function () {
    [$product] = makeProductWithStoredImage();

    $galleryImages = product_image()->getGalleryImages($product->fresh());

    expect($galleryImages[0]['alt'])->toBe($product->name);
});
