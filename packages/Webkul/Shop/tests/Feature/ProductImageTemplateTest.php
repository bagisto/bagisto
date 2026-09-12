<?php

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Exceptions;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\TestResponse;
use Prettus\Repository\Events\RepositoryEntityUpdated;
use Webkul\Core\Core as BaseCore;
use Webkul\Core\Facades\Core;
use Webkul\Core\Models\Channel;
use Webkul\Core\Repositories\ChannelRepository;
use Webkul\Customer\Models\Customer;
use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\ImageCache\Exceptions\InvalidTemplate;
use Webkul\ImageCache\TemplateRegistry;
use Webkul\ImageCache\Templates\Large;
use Webkul\ImageCache\Templates\Medium;
use Webkul\ImageCache\Templates\Small;
use Webkul\Product\Helpers\SEO;
use Webkul\Product\Models\ProductImage;
use Webkul\Product\Models\ProductReview;
use Webkul\RMA\Enums\DefaultRMAResolution;
use Webkul\RMA\Enums\DefaultRMAStatusEnum;
use Webkul\RMA\Helpers\Helper as RMAHelper;
use Webkul\RMA\Models\RMA;
use Webkul\RMA\Models\RMAItem;
use Webkul\RMA\Models\RMAReason;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderItem;
use Webkul\Shop\Tests\Fixtures\ImageCache\NotATemplate;
use Webkul\Shop\Tests\Fixtures\ImageCache\PosterSmall;
use Webkul\Shop\Tests\Fixtures\ImageCache\ProductCard;
use Webkul\Shop\Tests\Fixtures\ImageCache\SquareProductCard;
use Webkul\Shop\Tests\Fixtures\ImageCache\WideSmall;

use function Pest\Laravel\get;
use function Pest\Laravel\getJson;

beforeEach(function () {
    config(['imagecache.templates' => [
        'small' => Small::class,
        'medium' => Medium::class,
        'large' => Large::class,
    ]]);

    $this->product = (new ProductFaker)->getSimpleProductFactory()->create();

    $this->path = 'product/'.$this->product->id.'/front.png';

    Storage::put($this->path, UploadedFile::fake()->image('front.png', 800, 600)->getContent());

    ProductImage::create([
        'product_id' => $this->product->id,
        'type' => 'images',
        'path' => $this->path,
        'position' => 1,
    ]);
});

afterEach(function () {
    Storage::deleteDirectory('product/'.$this->product->id);
});

/**
 * Register a storefront theme with image templates and a product image list, run by a channel on its own host.
 */
function channelRunningImageTemplates(string $code, ?array $templates, array $productImages = []): Channel
{
    config(['themes.shop.'.$code => array_merge(config('themes.shop.default'), [
        'name' => ucfirst($code),
        'customize' => [
            'image_cache' => [
                'templates' => $templates,
                'product_images' => $productImages,
            ],
        ],
    ])]);

    return Channel::factory()->create([
        'theme' => $code,
        'hostname' => 'http://'.$code.'.test',
    ]);
}

/**
 * The base image array the helper builds for a product on a channel.
 */
function baseImageOn(Channel $channel, $product): array
{
    core()->setCurrentChannel($channel);

    return product_image()->getProductBaseImage($product->fresh());
}

/**
 * Fetch a helper url's image from a channel's host, resolving that host's channel afresh.
 */
function fetchImageOn(Channel $channel, string $url, array $headers = []): TestResponse
{
    Core::clearResolvedInstance(BaseCore::class);

    return get(rtrim($channel->hostname, '/').parse_url($url, PHP_URL_PATH), $headers);
}

/**
 * The width and height of an image response.
 */
function sizeOfImage(TestResponse $response): array
{
    [$width, $height] = getimagesizefromstring($response->getContent());

    return [$width, $height];
}

/**
 * The product's base image as the storefront products api lists it for a guest.
 */
function listedBaseImage($product): array
{
    Core::clearResolvedInstance(BaseCore::class);

    return collect(getJson(route('shop.api.products.index', ['sort' => 'created_at-desc']))->assertOk()->json('data'))
        ->firstWhere('id', $product->id)['base_image'];
}

it('should give an image the core sizes and the original, exactly as before, when the theme lists nothing', function () {
    $channel = channelRunningImageTemplates('plain', null);

    $image = baseImageOn($channel, $this->product);

    expect(array_keys($image))->toBe(['small_image_url', 'medium_image_url', 'large_image_url', 'original_image_url', 'alt'])
        ->and($image['small_image_url'])->toBe(url('cache/small/'.$this->path))
        ->and($image['medium_image_url'])->toBe(url('cache/medium/'.$this->path))
        ->and($image['large_image_url'])->toBe(url('cache/large/'.$this->path))
        ->and($image['original_image_url'])->toBe(url('cache/original/'.$this->path));
});

it('should give an image a url for a template the theme lists for product images', function () {
    $channel = channelRunningImageTemplates('poster', ['product_card' => ProductCard::class], ['product_card']);

    $image = baseImageOn($channel, $this->product);

    expect(array_keys($image))->toBe(['small_image_url', 'medium_image_url', 'large_image_url', 'product_card_image_url', 'original_image_url', 'alt'])
        ->and($image['product_card_image_url'])->toBe(url('cache/product_card/'.$this->path))
        ->and($image['small_image_url'])->toBe(url('cache/small/'.$this->path));
});

it('should keep templates meant for other images out of product images', function () {
    config(['imagecache.templates.thumb' => WideSmall::class]);

    $channel = channelRunningImageTemplates('poster', [
        'product_card' => ProductCard::class,
        'mobile_banner' => SquareProductCard::class,
    ], ['product_card']);

    expect(baseImageOn($channel, $this->product))
        ->toHaveKey('product_card_image_url')
        ->not->toHaveKey('mobile_banner_image_url')
        ->not->toHaveKey('thumb_image_url');
});

it('should not add a template to product images that the theme registers but does not list', function () {
    $channel = channelRunningImageTemplates('poster', ['product_card' => ProductCard::class]);

    expect(baseImageOn($channel, $this->product))->not->toHaveKey('product_card_image_url')
        ->and(app(TemplateRegistry::class)->has('product_card', 'poster'))->toBeTrue();
});

it('should keep the same urls when a theme only overrides a core template', function () {
    $channel = channelRunningImageTemplates('poster', ['small' => PosterSmall::class], ['small']);

    expect(array_keys(baseImageOn($channel, $this->product)))
        ->toBe(['small_image_url', 'medium_image_url', 'large_image_url', 'original_image_url', 'alt']);
});

it('should give each channel the product images of the theme it runs', function () {
    $poster = channelRunningImageTemplates('poster', ['small' => PosterSmall::class, 'product_card' => ProductCard::class], ['product_card']);

    $square = channelRunningImageTemplates('square', ['product_card' => SquareProductCard::class], ['product_card']);

    $plain = channelRunningImageTemplates('plain', null);

    expect(baseImageOn($poster, $this->product))->toHaveKey('product_card_image_url')
        ->and(baseImageOn($square, $this->product))->toHaveKey('product_card_image_url')
        ->and(baseImageOn($plain, $this->product))->not->toHaveKey('product_card_image_url');
});

it('should serve the helper urls at the sizes each channel theme defines', function () {
    $poster = channelRunningImageTemplates('poster', ['small' => PosterSmall::class, 'product_card' => ProductCard::class], ['product_card']);

    $square = channelRunningImageTemplates('square', ['product_card' => SquareProductCard::class], ['product_card']);

    $plain = channelRunningImageTemplates('plain', null);

    $posterImage = baseImageOn($poster, $this->product);

    $squareImage = baseImageOn($square, $this->product);

    $plainImage = baseImageOn($plain, $this->product);

    expect(sizeOfImage(fetchImageOn($poster, $posterImage['product_card_image_url'])->assertOk()))->toBe([240, 320])
        ->and(sizeOfImage(fetchImageOn($square, $squareImage['product_card_image_url'])->assertOk()))->toBe([200, 200])
        ->and(sizeOfImage(fetchImageOn($poster, $posterImage['small_image_url'])->assertOk()))->toBe([300, 200])
        ->and(sizeOfImage(fetchImageOn($square, $squareImage['medium_image_url'])->assertOk()))->toBe([300, 300])
        ->and(sizeOfImage(fetchImageOn($plain, $plainImage['small_image_url'])->assertOk()))->toBe([100, 100]);

    fetchImageOn($plain, $posterImage['product_card_image_url'])->assertNotFound();
});

it('should never reuse one theme image for another theme that defines the same template differently', function () {
    $poster = channelRunningImageTemplates('poster', ['product_card' => ProductCard::class], ['product_card']);

    $square = channelRunningImageTemplates('square', ['product_card' => SquareProductCard::class], ['product_card']);

    $url = baseImageOn($poster, $this->product)['product_card_image_url'];

    expect(baseImageOn($square, $this->product)['product_card_image_url'])->toBe($url);

    $fromPoster = fetchImageOn($poster, $url)->assertOk();

    $fromSquare = fetchImageOn($square, $url, ['If-None-Match' => $fromPoster->headers->get('Etag')])->assertOk();

    expect($fromSquare->headers->get('Etag'))->not->toBe($fromPoster->headers->get('Etag'))
        ->and(sizeOfImage($fromSquare))->toBe([200, 200]);
});

it('should give a product without an image a placeholder for every product image template', function () {
    $channel = channelRunningImageTemplates('poster', ['product_card' => ProductCard::class], ['product_card']);

    $imageless = (new ProductFaker)->getSimpleProductFactory()->create();

    $image = baseImageOn($channel, $imageless);

    expect(array_keys($image))->toBe(['small_image_url', 'medium_image_url', 'large_image_url', 'product_card_image_url', 'original_image_url', 'alt'])
        ->and($image['product_card_image_url'])->toBe($image['large_image_url'])
        ->and($image['small_image_url'])->toContain('small-product-placeholder');
});

it('should carry the theme product images into the storefront product api', function () {
    config(['themes.shop.'.core()->getDefaultChannel()->theme.'.customize.image_cache' => [
        'templates' => ['product_card' => ProductCard::class],
        'product_images' => ['product_card'],
    ]]);

    $baseImage = listedBaseImage($this->product);

    expect($baseImage)->toHaveKey('product_card_image_url')
        ->and($baseImage['product_card_image_url'])->toEndWith('/cache/product_card/'.$this->path)
        ->and($baseImage['small_image_url'])->toEndWith('/cache/small/'.$this->path);
});

it('should not serve a cached product listing built for the theme a channel ran before', function () {
    $channel = core()->getDefaultChannel();

    config(['themes.shop.'.$channel->theme.'.customize.image_cache' => [
        'templates' => ['product_card' => ProductCard::class],
        'product_images' => ['product_card'],
    ]]);

    channelRunningImageTemplates('plain', null);

    expect(listedBaseImage($this->product))->toHaveKey('product_card_image_url');

    $channel->theme = 'plain';

    $channel->save();

    Event::dispatch(new RepositoryEntityUpdated(app(ChannelRepository::class), $channel));

    expect(listedBaseImage($this->product))->not->toHaveKey('product_card_image_url');
});

it('should give admin requests the core product images only, whatever theme the channel runs', function () {
    $channel = channelRunningImageTemplates('poster', ['product_card' => ProductCard::class], ['product_card']);

    core()->setCurrentChannel($channel);

    app()->instance('request', Request::create('http://poster.test/'.config('app.admin_url').'/sales/orders'));

    expect(app(TemplateRegistry::class)->currentTheme())->toBeNull()
        ->and(array_keys(product_image()->getProductBaseImage($this->product->fresh())))
        ->toBe(['small_image_url', 'medium_image_url', 'large_image_url', 'original_image_url', 'alt']);

    app()->instance('request', Request::create('http://poster.test/'));

    expect(app(TemplateRegistry::class)->currentTheme())->toBe('poster');
});

it('should leave out an unusable template or a product image name that is not registered', function () {
    Exceptions::fake();

    $channel = channelRunningImageTemplates('broken', [
        'product/card' => ProductCard::class,
        'original' => PosterSmall::class,
        'logo' => ProductCard::class,
        'thumb' => NotATemplate::class,
        'card' => ProductCard::class,
    ], ['product/card', 'original', 'thumb', 'missing', 42, 'card', 'card']);

    expect(array_keys(baseImageOn($channel, $this->product)))
        ->toBe(['small_image_url', 'medium_image_url', 'large_image_url', 'card_image_url', 'original_image_url', 'alt']);

    Exceptions::assertReported(fn (InvalidTemplate $exception) => str_contains($exception->getMessage(), '[missing]'));

    Exceptions::assertReported(fn (InvalidTemplate $exception) => str_contains($exception->getMessage(), '[thumb]'));
});

it('should give the product rich snippet the full size urls of the product image helper', function () {
    expect(app(SEO::class)->getProductImages($this->product->fresh()))->toBe([url('cache/original/'.$this->path)]);
});

it('should give the product rich snippet no images for a product without a stored one', function () {
    $product = (new ProductFaker)->getSimpleProductFactory()->create();

    expect(app(SEO::class)->getProductImages($product))->toBe([]);
});

it('should show a customer review with the product image of the product image helper', function () {
    $customer = Customer::factory()->create();

    ProductReview::factory()->create([
        'product_id' => $this->product->id,
        'customer_id' => $customer->id,
    ]);

    $this->loginAsCustomer($customer);

    get(route('shop.customers.account.reviews.index'))
        ->assertOk()
        ->assertSee(url('cache/medium/'.$this->path))
        ->assertDontSee('storage/'.$this->path);
});

it('should show the item of an rma request with the product image of the product image helper', function () {
    $customer = Customer::factory()->create();

    $order = Order::factory()->create([
        'customer_id' => $customer->id,
        'customer_email' => $customer->email,
        'customer_first_name' => $customer->first_name,
        'customer_last_name' => $customer->last_name,
    ]);

    $orderItem = OrderItem::factory()->create([
        'order_id' => $order->id,
        'product_id' => $this->product->id,
        'sku' => $this->product->sku,
        'type' => $this->product->type,
        'name' => $this->product->name,
    ]);

    $rma = RMA::create([
        'order_id' => $order->id,
        'rma_status_id' => DefaultRMAStatusEnum::PENDING->value,
    ]);

    RMAItem::create([
        'rma_id' => $rma->id,
        'order_item_id' => $orderItem->id,
        'rma_reason_id' => RMAReason::create(['title' => 'Damaged', 'status' => 1, 'position' => 1])->id,
        'quantity' => 1,
        'resolution' => DefaultRMAResolution::RETURN->value,
    ]);

    $this->loginAsCustomer($customer);

    get(route('shop.customers.account.rma.view', $rma->id))
        ->assertOk()
        ->assertSee(url('cache/small/'.$this->path))
        ->assertDontSee('storage/'.$this->path);
});

it('should hand the rma form each order item with the product image of the product image helper', function () {
    $customer = Customer::factory()->create();

    $order = Order::factory()->create(['customer_id' => $customer->id]);

    $this->mock(RMAHelper::class)
        ->shouldReceive('getOrderItems')
        ->with($order->id)
        ->andReturn(new Collection([
            (new OrderItem)->forceFill(['product_id' => $this->product->id, 'base_image' => $this->path]),
            (new OrderItem)->forceFill(['product_id' => null, 'base_image' => null]),
        ]));

    $this->loginAsCustomer($customer);

    getJson(route('shop.customers.account.rma.get-order-items', $order->id))
        ->assertOk()
        ->assertJsonPath('0.base_image_url', url('cache/small/'.$this->path))
        ->assertJsonPath('1.base_image_url', null);
});
