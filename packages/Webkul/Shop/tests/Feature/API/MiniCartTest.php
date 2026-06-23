<?php

use function Pest\Laravel\get;
use function Pest\Laravel\getJson;

it('returns a null cart without recalculating totals when the cart is empty', function () {
    getJson(route('shop.api.checkout.cart.index'))
        ->assertOk()
        ->assertJsonPath('data', null);
});

it('seeds the mini-cart with an empty cart on the home page so no cart request is made', function () {
    get(route('shop.home.index'))
        ->assertOk()
        ->assertSee('"items_qty":0', false);
});
