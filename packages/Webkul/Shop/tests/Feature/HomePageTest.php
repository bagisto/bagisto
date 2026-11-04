<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Webkul\Core\Models\SubscribersList;
use Webkul\Customer\Models\CompareItem;
use Webkul\MagicAI\Facades\MagicAI;
use Webkul\Product\Repositories\SearchRepository;
use Webkul\Shop\Mail\Customer\SubscriptionNotification;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;

// ============================================================================
// Home Page
// ============================================================================

it('should return the home page', function () {
    get(route('shop.home.index'))
        ->assertOk();
});

it('should display the current currency and channel code on the home page', function () {
    $response = get(route('shop.home.index'))->assertOk();

    expect(Str::contains($response->content(), core()->getCurrentChannelCode()))->toBeTruthy();
    expect(Str::contains($response->content(), core()->getCurrentCurrencyCode()))->toBeTruthy();
});

it('should display sign in and sign up buttons for guests', function () {
    $response = get(route('shop.home.index'))->assertOk();

    expect(Str::contains($response->content(), trans('shop::app.components.layouts.header.desktop.bottom.sign-in')))->toBeTruthy();
    expect(Str::contains($response->content(), trans('shop::app.components.layouts.header.desktop.bottom.sign-up')))->toBeTruthy();
});

it('should display navigation links for authenticated customers', function () {
    $this->loginAsCustomer();

    $response = get(route('shop.home.index'))->assertOk();

    expect(Str::contains($response->content(), trans('shop::app.components.layouts.header.desktop.bottom.profile')))->toBeTruthy();
    expect(Str::contains($response->content(), trans('shop::app.components.layouts.header.desktop.bottom.orders')))->toBeTruthy();
    expect(Str::contains($response->content(), trans('shop::app.components.layouts.header.desktop.bottom.logout')))->toBeTruthy();
});

// ============================================================================
// Search
// ============================================================================

it('should return search results for a product', function () {
    $product = $this->createSimpleProduct();

    get(route('shop.search.index', ['query' => $product->name]))
        ->assertOk()
        ->assertSeeText(trans('shop::app.search.title', ['query' => $product->name]));
});

it('should reject an array search query instead of running it as a raw SQL condition', function () {
    config(['responsecache.enabled' => true]);

    $response = get('/search?'.http_build_query([
        'query' => ['a', 'RAW', '1=0 UNION SELECT 1'],
    ]));

    $response->assertRedirect();

    expect($response->baseResponse->getStatusCode())->not->toBe(500);
});

// ============================================================================
// Image Search
// ============================================================================

it('should keep a search image in the folder of the day it was uploaded and answer with its URL', function () {
    Storage::fake();

    $this->freezeTime();

    $this->setConfig('magic_ai.general.settings.enabled', 0);

    $response = postJson(route('shop.search.upload'), [
        'image' => UploadedFile::fake()->image('shoe.png', 20, 10),
    ])
        ->assertOk();

    $stored = Storage::allFiles(SearchRepository::SEARCH_IMAGES_DIRECTORY.'/'.now()->toDateString());

    expect($stored)->toHaveCount(1)
        ->and($response->json('image_url'))->toBe(Storage::url($stored[0]));
});

it('should store an SVG image search upload with its scripts stripped', function () {
    Storage::fake();

    $this->setConfig('magic_ai.general.settings.enabled', 0);

    postJson(route('shop.search.upload'), [
        'image' => UploadedFile::fake()->createWithContent('shoe.svg', '<svg xmlns="http://www.w3.org/2000/svg" onload="alert(1)"><script>alert(2)</script><rect/></svg>'),
    ])->assertOk();

    $stored = Storage::allFiles(SearchRepository::SEARCH_IMAGES_DIRECTORY);

    expect($stored)->toHaveCount(1)
        ->and(Storage::get($stored[0]))
        ->toContain('<rect')
        ->not->toContain('script')
        ->not->toContain('onload');
});

it('should refuse an SVG image search upload that cannot be read and store nothing', function () {
    Storage::fake();

    postJson(route('shop.search.upload'), [
        'image' => UploadedFile::fake()->createWithContent('shoe.svg', '<svg><rect></svg>'),
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['image' => trans('core::app.errors.invalid-svg')]);

    expect(Storage::allFiles(SearchRepository::SEARCH_IMAGES_DIRECTORY))->toBeEmpty();
});

it('should leave an SVG image search upload to the browser classifier when the AI is on', function () {
    Storage::fake();

    $this->setConfig([
        'magic_ai.general.settings.enabled' => 1,
        'magic_ai.storefront_features.image_search.enabled' => 1,
    ]);

    MagicAI::shouldReceive('analyzeImage')->never();

    postJson(route('shop.search.upload'), [
        'image' => UploadedFile::fake()->createWithContent('shoe.svg', '<svg xmlns="http://www.w3.org/2000/svg"><rect/></svg>'),
    ])
        ->assertOk()
        ->assertJsonPath('engine', 'tensorflow');
});

it('should send a raster image search upload to the AI when it is on', function () {
    Storage::fake();

    $this->setConfig([
        'magic_ai.general.settings.enabled' => 1,
        'magic_ai.storefront_features.image_search.enabled' => 1,
    ]);

    MagicAI::shouldReceive('analyzeImage')->once()->andReturn('red shoe');

    postJson(route('shop.search.upload'), [
        'image' => UploadedFile::fake()->image('shoe.png', 20, 10),
    ])
        ->assertOk()
        ->assertJsonPath('engine', 'ai')
        ->assertJsonPath('keywords', 'red shoe');
});

// ============================================================================
// Newsletter Subscription
// ============================================================================

it('should subscribe to the newsletter', function () {
    postJson(route('shop.subscription.store'), [
        'email' => $email = fake()->safeEmail(),
    ])
        ->assertRedirect();

    $this->assertDatabaseHas('subscribers_list', [
        'email' => $email,
        'is_subscribed' => true,
    ]);
});

it('should subscribe and send notification email', function () {
    Mail::fake();

    postJson(route('shop.subscription.store'), [
        'email' => fake()->safeEmail(),
    ])
        ->assertRedirect();

    Mail::assertQueued(SubscriptionNotification::class);
});

it('should fail validation when email is missing on subscribe', function () {
    postJson(route('shop.subscription.store'))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('email');
});

it('should unsubscribe via token', function () {
    $subscriber = SubscribersList::factory()->create();

    get(route('shop.subscription.destroy', ['token' => $subscriber->token]))
        ->assertRedirect();

    $this->assertDatabaseMissing('subscribers_list', ['id' => $subscriber->id]);
});

// ============================================================================
// Compare
// ============================================================================

it('should add a product to the compare list', function () {
    $product = $this->createSimpleProduct();

    $this->loginAsCustomer();

    postJson(route('shop.api.compare.store'), [
        'product_id' => $product->id,
    ])
        ->assertOk()
        ->assertSeeText(trans('shop::app.compare.item-add-success'));
});

it('should fail validation when product_id is missing on compare store', function () {
    $this->loginAsCustomer();

    postJson(route('shop.api.compare.store'))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('product_id');
});

it('should remove a product from the compare list', function () {
    $product = $this->createSimpleProduct();
    $customer = $this->loginAsCustomer();

    CompareItem::factory()->create([
        'customer_id' => $customer->id,
        'product_id' => $product->id,
    ]);

    deleteJson(route('shop.api.compare.destroy'), [
        'product_id' => $product->id,
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.compare.remove-success'));
});

it('should remove all products from the compare list', function () {
    $customer = $this->loginAsCustomer();

    $products = collect([
        $this->createSimpleProduct(),
        $this->createSimpleProduct(),
    ]);

    foreach ($products as $product) {
        CompareItem::factory()->create([
            'customer_id' => $customer->id,
            'product_id' => $product->id,
        ]);
    }

    deleteJson(route('shop.api.compare.destroy_all'))
        ->assertOk()
        ->assertJsonPath('data.message', trans('shop::app.compare.remove-all-success'));
});
