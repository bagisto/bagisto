<?php

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Webkul\Core\Models\SubscribersList;
use Webkul\Customer\Models\CompareItem;
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
