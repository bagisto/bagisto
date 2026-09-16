<?php

use Webkul\Checkout\Facades\Cart;
use Webkul\Sales\Models\DownloadableLinkPurchased;
use Webkul\Sales\Repositories\DownloadableLinkPurchasedRepository;

use function Pest\Laravel\postJson;

// ============================================================================
// Cart
// ============================================================================

it('should keep only the product\'s own links when a downloadable product is added to the cart', function () {
    $product = $this->createDownloadableProduct();

    $otherProduct = $this->createDownloadableProduct();

    $ownLinkIds = $product->downloadable_links()->pluck('id')->all();

    postJson(route('shop.api.checkout.cart.store'), [
        'product_id' => $product->id,
        'quantity' => 1,
        'links' => [...$ownLinkIds, ...$otherProduct->downloadable_links()->pluck('id')->all()],
    ])->assertOk();

    expect(Cart::getCart()->items->first()->additional['links'])->toBe($ownLinkIds);
});

it('should refuse a downloadable product whose requested links all belong to another product', function () {
    $product = $this->createDownloadableProduct();

    $otherProduct = $this->createDownloadableProduct();

    postJson(route('shop.api.checkout.cart.store'), [
        'product_id' => $product->id,
        'quantity' => 1,
        'links' => $otherProduct->downloadable_links()->pluck('id')->all(),
    ])
        ->assertBadRequest()
        ->assertJsonPath('message', trans('product::app.checkout.cart.missing-links'));

    $this->assertDatabaseMissing('cart_items', ['product_id' => $product->id]);
});

// ============================================================================
// Purchased Links
// ============================================================================

it('should only grant the links of an ordered item\'s own product', function () {
    $product = $this->createDownloadableProduct();

    $otherProduct = $this->createDownloadableProduct();

    $order = $this->createOrder(items: [[
        'product' => $product,
        'additional' => [
            'links' => [
                ...$product->downloadable_links()->pluck('id')->all(),
                ...$otherProduct->downloadable_links()->pluck('id')->all(),
            ],
        ],
    ]]);

    $orderItem = $order->items->first();

    app(DownloadableLinkPurchasedRepository::class)->saveLinks($orderItem);

    expect(DownloadableLinkPurchased::query()->where('order_item_id', $orderItem->id)->pluck('name')->sort()->values()->all())
        ->toBe($product->downloadable_links()->get()->pluck('title')->sort()->values()->all());
});
