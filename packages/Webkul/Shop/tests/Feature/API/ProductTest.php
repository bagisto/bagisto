<?php

use Pest\Expectation;

use function Pest\Laravel\getJson;

// ============================================================================
// Product Listing API
// ============================================================================

it('should list only new products when asked for them', function () {
    $product = $this->createSimpleProduct();

    $response = getJson(route('shop.api.products.index', ['new' => 1, 'sort' => 'created_at-desc']))
        ->assertOk()
        ->assertJsonPath('data.0.id', $product->id)
        ->collect();

    expect($response['data'])->each(function (Expectation $product) {
        return $product->is_new->toBeTrue();
    });
});

it('should list only featured products when asked for them', function () {
    $product = $this->createSimpleProduct();

    $response = getJson(route('shop.api.products.index', ['featured' => 1, 'sort' => 'created_at-desc']))
        ->assertOk()
        ->assertJsonPath('data.0.id', $product->id)
        ->collect();

    expect($response['data'])->each(function (Expectation $product) {
        return $product->is_featured->toBeTrue();
    });
});

it('should list the newest product first when sorted by creation date', function () {
    $product = $this->createSimpleProduct();

    getJson(route('shop.api.products.index', ['sort' => 'created_at-desc']))
        ->assertOk()
        ->assertJsonIsArray('data')
        ->assertJsonPath('data.0.id', $product->id)
        ->assertJsonPath('data.0.sku', $product->sku);
});

it('should leave inactive products out of the listing', function () {
    $product = $this->createSimpleProduct([
        'status' => ['boolean_value' => false, 'channel' => core()->getCurrentChannelCode()],
    ]);

    getJson(route('shop.api.products.index', ['sort' => 'created_at-desc']))
        ->assertOk()
        ->assertJsonMissing(['id' => $product->id]);
});
