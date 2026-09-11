<?php

use Webkul\Category\Models\Category;
use Webkul\Faker\Helpers\Product as ProductFaker;

use function Pest\Laravel\get;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

it('should render a product assigned to the current channel', function () {
    $product = (new ProductFaker)->getSimpleProductFactory()->create();

    get(route('shop.product_or_category.index', $product->url_key))
        ->assertOk();
});

it('should not render a product that is not assigned to the current channel', function () {
    $product = (new ProductFaker)->getSimpleProductFactory()->create();

    $product->channels()->detach();

    get(route('shop.product_or_category.index', $product->url_key))
        ->assertNotFound();
});

it('should add a product assigned to the current channel to the cart', function () {
    $product = (new ProductFaker)->getSimpleProductFactory()->create();

    postJson(route('shop.api.checkout.cart.store'), [
        'product_id' => $product->id,
        'quantity' => 1,
        'is_buy_now' => 0,
    ])->assertOk();
});

it('should not add a product that is not assigned to the current channel to the cart', function () {
    $product = (new ProductFaker)->getSimpleProductFactory()->create();

    $product->channels()->detach();

    postJson(route('shop.api.checkout.cart.store'), [
        'product_id' => $product->id,
        'quantity' => 1,
        'is_buy_now' => 0,
    ])->assertBadRequest();
});

it('should drop a cart item whose product is no longer in the current channel', function () {
    $product = (new ProductFaker)->getSimpleProductFactory()->create();

    postJson(route('shop.api.checkout.cart.store'), [
        'product_id' => $product->id,
        'quantity' => 1,
        'is_buy_now' => 0,
    ])->assertOk();

    $product->channels()->detach();

    getJson(route('shop.api.checkout.cart.index'))
        ->assertOk()
        ->assertJsonPath('data.items', [])
        ->assertJsonPath('data.items_count', 0);
});

it('should render a category that sits under the current channel root', function () {
    $category = Category::factory()->hasTranslations()->create([
        'parent_id' => core()->getCurrentChannel()->root_category_id,
    ]);

    get(route('shop.product_or_category.index', $category->translate('en')->slug))
        ->assertOk();
});

it('should not render a category that sits outside the current channel root', function () {
    $otherRoot = Category::factory()->hasTranslations()->create(['parent_id' => null]);

    $category = Category::factory()->hasTranslations()->create([
        'parent_id' => $otherRoot->id,
    ]);

    get(route('shop.product_or_category.index', $category->translate('en')->slug))
        ->assertNotFound();
});
