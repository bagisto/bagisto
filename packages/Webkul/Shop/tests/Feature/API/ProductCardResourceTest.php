<?php

use Webkul\Faker\Helpers\Product as ProductFaker;

use function Pest\Laravel\getJson;

it('omits the heavy description and gallery fields from the products listing', function () {
    (new ProductFaker)->getSimpleProductFactory()->create();

    $product = getJson(route('shop.api.products.index'))
        ->assertOk()
        ->json('data.0');

    expect($product)
        ->not->toHaveKey('description')
        ->not->toHaveKey('images');
});

it('keeps the fields the product card needs in the listing', function () {
    (new ProductFaker)->getSimpleProductFactory()->create();

    $product = getJson(route('shop.api.products.index'))
        ->assertOk()
        ->json('data.0');

    expect($product)
        ->toHaveKeys([
            'id',
            'name',
            'url_key',
            'base_image',
            'is_new',
            'is_saleable',
            'is_wishlist',
            'price_html',
            'ratings',
            'reviews',
        ]);
});
