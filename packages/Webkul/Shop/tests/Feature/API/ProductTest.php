<?php

use Pest\Expectation;

use function Pest\Laravel\getJson;

// ============================================================================
// Product Listing API
// ============================================================================

it('should return new products listing', function () {
    $this->createSimpleProduct();

    $response = getJson(route('shop.api.products.index', ['new' => 1]))
        ->assertOk()
        ->collect();

    expect($response['data'])->each(function (Expectation $product) {
        return $product->is_new->toBeTrue();
    });
});

it('should return featured products listing', function () {
    $this->createSimpleProduct();

    $response = getJson(route('shop.api.products.index', ['featured' => 1]))
        ->assertOk()
        ->collect();

    expect($response['data'])->each(function (Expectation $product) {
        return $product->is_featured->toBeTrue();
    });
});

it('should return all products listing', function () {
    $product = $this->createSimpleProduct();

    getJson(route('shop.api.products.index'))
        ->assertOk()
        ->assertJsonIsArray('data')
        ->assertJsonFragment(['id' => $product->id]);
});
