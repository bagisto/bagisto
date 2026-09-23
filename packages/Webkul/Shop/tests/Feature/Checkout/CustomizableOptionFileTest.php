<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\TestResponse;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductCustomizableOption;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderItem;
use Webkul\Sales\Repositories\OrderItemRepository;

use function Pest\Laravel\postJson;

/**
 * Create a simple product carrying one optional file option that accepts the given extensions.
 *
 * @return array{0: Product, 1: ProductCustomizableOption}
 */
function productWithFileOption(string $supportedExtensions): array
{
    $product = test()->createSimpleProduct();

    $option = $product->customizable_options()->create([
        'type' => 'file',
        'is_required' => 0,
        'sort_order' => 1,
        'label' => 'Artwork',
        'supported_file_extensions' => $supportedExtensions,
    ]);

    $option->customizable_option_prices()->create([
        'label' => 'Artwork',
        'price' => 0,
        'sort_order' => 1,
    ]);

    return [$product, $option];
}

/**
 * Create an order whose item carries a file option pointing at the given path on the disk.
 *
 * @return array{0: Order, 1: OrderItem}
 */
function orderWithUploadedOptionFile(string $path): array
{
    $order = test()->createOrder(items: [[
        'product' => test()->createSimpleProduct(),
        'additional' => [
            'formatted_customizable_options' => [
                [
                    'id' => 1,
                    'type' => 'file',
                    'label' => ['en' => 'Upload File'],
                    'prices' => [
                        ['label' => $path],
                    ],
                ],
            ],
        ],
    ]]);

    return [$order, $order->items->first()];
}

/**
 * Add a product to the cart with an upload for its file option, returning the response.
 */
function addToCartWithUpload(Product $product, ProductCustomizableOption $option, UploadedFile $upload, array $extra = []): TestResponse
{
    return postJson(route('shop.api.checkout.cart.store'), [
        'product_id' => $product->id,
        'quantity' => 1,
        'customizable_options' => [
            $option->id => [$upload],
        ],
        ...$extra,
    ]);
}

// ============================================================================
// Order Files
// ============================================================================

it('should not relocate a file outside the customer upload directory when managing customizable options', function () {
    Storage::fake();

    Storage::put('products/1/victim.png', 'victim-contents');

    [$order, $orderItem] = orderWithUploadedOptionFile('products/1/victim.png');

    app(OrderItemRepository::class)->manageCustomizableOptions($orderItem);

    Storage::assertExists('products/1/victim.png');

    Storage::assertMissing('orders/'.$order->id.'/victim.png');

    expect($orderItem->fresh()->additional['formatted_customizable_options'][0]['prices'][0]['label'])
        ->toBe('products/1/victim.png');
});

it('should relocate a genuinely uploaded customizable option file into the orders directory', function () {
    Storage::fake();

    Storage::put('carts/42/upload.png', 'upload-contents');

    [$order, $orderItem] = orderWithUploadedOptionFile('carts/42/upload.png');

    app(OrderItemRepository::class)->manageCustomizableOptions($orderItem);

    Storage::assertMissing('carts/42/upload.png');

    Storage::assertExists('orders/'.$order->id.'/upload.png');

    expect($orderItem->fresh()->additional['formatted_customizable_options'][0]['prices'][0]['label'])
        ->toBe('orders/'.$order->id.'/upload.png');
});

// ============================================================================
// Cart Uploads
// ============================================================================

it('should store a customer upload under its accepted extension', function () {
    Storage::fake();

    [$product, $option] = productWithFileOption('jpg,png');

    $upload = $this->uploadedFileWithContents('artwork.jpg', UploadedFile::fake()->image('artwork.jpg', 10, 10)->get());

    $cartId = addToCartWithUpload($product, $option, $upload)
        ->assertOk()
        ->json('data.id');

    $files = Storage::allFiles('carts/'.$cartId);

    expect($files)->toHaveCount(1)
        ->and($files[0])->toEndWith('.jpg');
});

it('should refuse a customer upload whose extension would be served as a page, even when the option lists it', function (string $name) {
    Storage::fake();

    [$product, $option] = productWithFileOption('html,svg,jpg');

    addToCartWithUpload($product, $option, $this->uploadedFileWithContents($name, '<script>alert(1)</script>'))
        ->assertBadRequest()
        ->assertJsonPath('message', trans('product::app.checkout.cart.invalid-file-extension'));

    expect(Storage::allFiles('carts'))->toBeEmpty();
})->with([
    'html' => ['payload.html'],
    'svg' => ['payload.svg'],
]);

it('should refuse a customer upload whose contents would be rendered as a page, whatever its extension', function (string $name, string $contents) {
    Storage::fake();

    [$product, $option] = productWithFileOption('jpg,png,txt');

    addToCartWithUpload($product, $option, $this->uploadedFileWithContents($name, $contents))
        ->assertBadRequest()
        ->assertJsonPath('message', trans('product::app.checkout.cart.invalid-file-extension'));

    expect(Storage::allFiles('carts'))->toBeEmpty();
})->with([
    'markup named as a photo' => ['payload.jpg', '<!DOCTYPE html><html><body>hello</body></html>'],
    'vector image named as a picture' => ['payload.png', '<svg xmlns="http://www.w3.org/2000/svg"></svg>'],
    'xml named as text' => ['payload.txt', '<?xml version="1.0"?><root/>'],
]);

it('should refuse a customer upload with an active or missing extension when the option lists none', function (string $name) {
    Storage::fake();

    [$product, $option] = productWithFileOption('');

    addToCartWithUpload($product, $option, $this->uploadedFileWithContents($name, '<script>alert(1)</script>'))
        ->assertBadRequest();

    expect(Storage::allFiles('carts'))->toBeEmpty();
})->with([
    'a script extension' => ['payload.php'],
    'no extension' => ['payload'],
]);

it('should store a customer upload in the cart it was added to, whatever cart id the request carries', function () {
    Storage::fake();

    [$product, $option] = productWithFileOption('png');

    $upload = $this->uploadedFileWithContents('artwork.png', UploadedFile::fake()->image('artwork.png', 10, 10)->get());

    $cartId = addToCartWithUpload($product, $option, $upload, ['cart_id' => 'elsewhere'])
        ->assertOk()
        ->json('data.id');

    expect(Storage::allFiles('carts/elsewhere'))->toBeEmpty()
        ->and(Storage::allFiles('carts/'.$cartId))->toHaveCount(1);
});
