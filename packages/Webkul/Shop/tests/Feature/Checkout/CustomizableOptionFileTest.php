<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Webkul\Checkout\Models\Cart;
use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderItem;
use Webkul\Sales\Repositories\OrderItemRepository;

use function Pest\Laravel\postJson;

/**
 * Create a simple product carrying one optional file option that accepts the given extensions.
 */
function makeProductWithFileOption(string $supportedExtensions): array
{
    $product = (new ProductFaker)->getSimpleProductFactory()->create();

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
 * Build a real upload, since a fake one holds a stream the cart item cannot encode, in a temporary file
 * removed when the run ends.
 */
function makeCustomerUpload(string $name, string $contents): UploadedFile
{
    static $handles = [];

    $handles[] = $handle = tmpfile();

    fwrite($handle, $contents);

    return new UploadedFile(stream_get_meta_data($handle)['uri'], $name, null, null, true);
}

it('should not relocate files outside the customer upload directory when managing customizable options', function () {
    Storage::fake();

    /**
     * A file that does not belong to the customer (e.g. a product image) sitting on the disk.
     */
    Storage::put('product/1/victim.png', 'victim-contents');

    $product = (new ProductFaker)->getSimpleProductFactory()->create();

    $order = Order::factory()->create();

    $orderItem = OrderItem::factory()->create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'product_type' => get_class($product),
        'additional' => [
            'formatted_customizable_options' => [
                [
                    'id' => 1,
                    'type' => 'file',
                    'label' => ['en' => 'Upload File'],
                    'prices' => [
                        ['label' => 'product/1/victim.png'],
                    ],
                ],
            ],
        ],
    ]);

    app(OrderItemRepository::class)->manageCustomizableOptions($orderItem);

    Storage::assertExists('product/1/victim.png');
    Storage::assertMissing('orders/'.$order->id.'/victim.png');

    expect($orderItem->fresh()->additional['formatted_customizable_options'][0]['prices'][0]['label'])
        ->toBe('product/1/victim.png');
});

it('should relocate genuinely uploaded customizable option files into the orders directory', function () {
    Storage::fake();

    $cartId = 42;

    Storage::put("carts/{$cartId}/upload.png", 'upload-contents');

    $product = (new ProductFaker)->getSimpleProductFactory()->create();

    $order = Order::factory()->create();

    $orderItem = OrderItem::factory()->create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'product_type' => get_class($product),
        'additional' => [
            'formatted_customizable_options' => [
                [
                    'id' => 1,
                    'type' => 'file',
                    'label' => ['en' => 'Upload File'],
                    'prices' => [
                        ['label' => "carts/{$cartId}/upload.png"],
                    ],
                ],
            ],
        ],
    ]);

    app(OrderItemRepository::class)->manageCustomizableOptions($orderItem);

    Storage::assertMissing("carts/{$cartId}/upload.png");
    Storage::assertExists('orders/'.$order->id.'/upload.png');

    expect($orderItem->fresh()->additional['formatted_customizable_options'][0]['prices'][0]['label'])
        ->toBe('orders/'.$order->id.'/upload.png');
});

it('should store a customer upload under its accepted extension, whatever its contents look like', function () {
    Storage::fake();

    [$product, $option] = makeProductWithFileOption('jpg,png');

    postJson(route('shop.api.checkout.cart.store'), [
        'product_id' => $product->id,
        'quantity' => 1,
        'customizable_options' => [
            $option->id => [makeCustomerUpload('payload.jpg', '<script>alert(1)</script>')],
        ],
    ])->assertOk();

    $files = Storage::allFiles('carts/'.Cart::latest('id')->first()->id);

    expect($files)->toHaveCount(1);

    expect($files[0])->toEndWith('.jpg');
});

it('should refuse a customer upload whose extension would be served as a page, even when the option lists it', function () {
    Storage::fake();

    [$product, $option] = makeProductWithFileOption('html,svg,jpg');

    foreach (['payload.html', 'payload.svg'] as $name) {
        postJson(route('shop.api.checkout.cart.store'), [
            'product_id' => $product->id,
            'quantity' => 1,
            'customizable_options' => [
                $option->id => [makeCustomerUpload($name, '<script>alert(1)</script>')],
            ],
        ])
            ->assertBadRequest()
            ->assertJsonPath('message', trans('product::app.checkout.cart.invalid-file-extension'));
    }

    expect(Storage::allFiles('carts'))->toBeEmpty();
});

it('should refuse a customer upload with an active or missing extension when the option lists none', function () {
    Storage::fake();

    [$product, $option] = makeProductWithFileOption('');

    foreach (['payload.php', 'payload'] as $name) {
        postJson(route('shop.api.checkout.cart.store'), [
            'product_id' => $product->id,
            'quantity' => 1,
            'customizable_options' => [
                $option->id => [makeCustomerUpload($name, '<script>alert(1)</script>')],
            ],
        ])->assertBadRequest();
    }

    expect(Storage::allFiles('carts'))->toBeEmpty();
});

it('should store a customer upload in the cart it was added to, whatever cart id the request carries', function () {
    Storage::fake();

    [$product, $option] = makeProductWithFileOption('png');

    postJson(route('shop.api.checkout.cart.store'), [
        'product_id' => $product->id,
        'quantity' => 1,
        'cart_id' => 'elsewhere',
        'customizable_options' => [
            $option->id => [makeCustomerUpload('artwork.png', UploadedFile::fake()->image('artwork.png', 10, 10)->get())],
        ],
    ])->assertOk();

    expect(Storage::allFiles('carts/elsewhere'))->toBeEmpty();

    expect(Storage::allFiles('carts/'.Cart::latest('id')->first()->id))->toHaveCount(1);
});
