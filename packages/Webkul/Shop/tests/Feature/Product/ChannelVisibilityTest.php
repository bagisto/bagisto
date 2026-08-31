<?php

it('should render a product assigned to the current channel', function () {
    $product = $this->createSimpleProduct();

    $this->get(route('shop.product_or_category.index', $product->url_key))
        ->assertOk();
});

it('should not render a product that is not assigned to the current channel', function () {
    $product = $this->createSimpleProduct();

    $product->channels()->detach();

    $this->get(route('shop.product_or_category.index', $product->url_key))
        ->assertNotFound();
});

it('should add a product assigned to the current channel to the cart', function () {
    $product = $this->createSimpleProduct();

    $this->addProductToCart($product->id)->assertOk();
});

it('should not add a product that is not assigned to the current channel to the cart', function () {
    $product = $this->createSimpleProduct();

    $product->channels()->detach();

    $this->addProductToCart($product->id)->assertBadRequest();
});

it('should drop a cart item whose product is no longer in the current channel', function () {
    $product = $this->createSimpleProduct();

    $this->addProductToCart($product->id)->assertOk();

    $product->channels()->detach();

    $this->getJson(route('shop.api.checkout.cart.index'))
        ->assertOk()
        ->assertJsonPath('data.items', [])
        ->assertJsonPath('data.items_count', 0);
});
