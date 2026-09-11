<?php

use Spatie\ResponseCache\Facades\ResponseCache;

use function Pest\Laravel\get;
use function Pest\Laravel\getJson;

it('returns a null cart without recalculating totals when the cart is empty', function () {
    getJson(route('shop.api.checkout.cart.index'))
        ->assertOk()
        ->assertJsonPath('data', null);
});

it('seeds the mini-cart with an empty cart on the home page so no cart request is made', function () {
    config(['responsecache.enabled' => false]);

    get(route('shop.home.index'))
        ->assertOk()
        ->assertSee('"items_qty":0', false);
});

it('leaves a marker on a page the cache will store, so one visitor cart never reaches another', function () {
    config(['responsecache.enabled' => true]);

    ResponseCache::clear();

    get(route('shop.home.index'))
        ->assertOk()
        ->assertSee("let miniCart = '<bagisto-response-cache-mini-cart>'", false)
        ->assertDontSee('"items_qty":0', false);
});
