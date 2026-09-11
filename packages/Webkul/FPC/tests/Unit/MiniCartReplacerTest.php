<?php

use Illuminate\Http\Response;
use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Models\Cart as CartModel;
use Webkul\FPC\Replacers\MiniCartReplacer;

/**
 * A rendered page, carrying the seed the view wrote for whoever it was rendered for.
 */
function renderedMiniCartPage(string $seed): Response
{
    return new Response("let miniCart = {$seed};", 200, ['Content-Type' => 'text/html']);
}

/**
 * Stand the cart facade up with the given items for the current request.
 */
function cartHolding(array $items): void
{
    $cart = new CartModel;

    $cart->setRelation('items', collect($items));

    Cart::shouldReceive('getCart')->andReturn($cart);
}

beforeEach(function () {
    $this->replacer = new MiniCartReplacer;
});

it('seeds an empty cart into a cached page when the visitor has no items', function () {
    cartHolding([]);

    $response = renderedMiniCartPage("'<bagisto-response-cache-mini-cart>'");

    $this->replacer->replaceInCachedResponse($response);

    expect($response->getContent())->toBe('let miniCart = {"items_qty":0,"items":[]};');
});

it('seeds nothing into a cached page when the visitor has items, so the page fetches its own cart', function () {
    cartHolding(['an item']);

    $response = renderedMiniCartPage("'<bagisto-response-cache-mini-cart>'");

    $this->replacer->replaceInCachedResponse($response);

    expect($response->getContent())->toBe('let miniCart = null;');
});

it('hands one visitor no trace of another visitor cart', function () {
    cartHolding(['an item']);

    $response = renderedMiniCartPage("'<bagisto-response-cache-mini-cart>'");

    $this->replacer->replaceInCachedResponse($response);

    expect($response->getContent())->not->toContain('bagisto-response-cache-mini-cart');
});

it('leaves a response carrying no marker untouched', function () {
    cartHolding([]);

    $response = new Response('nothing to replace here', 200, ['Content-Type' => 'text/html']);

    $this->replacer->replaceInCachedResponse($response);

    expect($response->getContent())->toBe('nothing to replace here');
});
