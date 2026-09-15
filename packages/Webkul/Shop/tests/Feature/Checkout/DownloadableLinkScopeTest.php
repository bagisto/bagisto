<?php

use Webkul\Customer\Models\Customer;
use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\Sales\Models\DownloadableLinkPurchased;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderItem;
use Webkul\Sales\Repositories\DownloadableLinkPurchasedRepository;

use function Pest\Laravel\postJson;

it('should keep only the product\'s own links when a downloadable product is added to the cart', function () {
    $product = (new ProductFaker)->getDownloadableProductFactory()->create();

    $otherProduct = (new ProductFaker)->getDownloadableProductFactory()->create();

    $ownLinkIds = $product->downloadable_links()->pluck('id')->all();

    postJson(route('shop.api.checkout.cart.store'), [
        'product_id' => $product->id,
        'quantity' => 1,
        'links' => [...$ownLinkIds, ...$otherProduct->downloadable_links()->pluck('id')->all()],
    ])->assertOk();

    expect(cart()->getCart()->items->first()->additional['links'])->toBe($ownLinkIds);
});

it('should refuse a downloadable product whose requested links all belong to another product', function () {
    $product = (new ProductFaker)->getDownloadableProductFactory()->create();

    $otherProduct = (new ProductFaker)->getDownloadableProductFactory()->create();

    postJson(route('shop.api.checkout.cart.store'), [
        'product_id' => $product->id,
        'quantity' => 1,
        'links' => $otherProduct->downloadable_links()->pluck('id')->all(),
    ])
        ->assertBadRequest()
        ->assertJsonPath('message', trans('product::app.checkout.cart.missing-links'));
});

it('should only grant the links of an ordered item\'s own product', function () {
    $product = (new ProductFaker)->getDownloadableProductFactory()->create();

    $otherProduct = (new ProductFaker)->getDownloadableProductFactory()->create();

    $order = Order::factory()->create([
        'customer_id' => Customer::factory()->create()->id,
    ]);

    $orderItem = OrderItem::factory()->create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'product_type' => get_class($product),
        'type' => 'downloadable',
        'qty_ordered' => 1,
        'additional' => [
            'links' => [
                ...$product->downloadable_links()->pluck('id')->all(),
                ...$otherProduct->downloadable_links()->pluck('id')->all(),
            ],
        ],
    ]);

    app(DownloadableLinkPurchasedRepository::class)->saveLinks($orderItem);

    expect(DownloadableLinkPurchased::where('order_item_id', $orderItem->id)->pluck('name')->sort()->values()->all())
        ->toBe($product->downloadable_links()->get()->pluck('title')->sort()->values()->all());
});
