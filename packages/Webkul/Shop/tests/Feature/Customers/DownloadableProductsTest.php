<?php

use Webkul\Sales\Models\DownloadableLinkPurchased;
use Webkul\Sales\Repositories\DownloadableLinkPurchasedRepository;

use function Pest\Laravel\get;

// ============================================================================
// Listing
// ============================================================================

it('should return the downloadable products page', function () {
    $this->loginAsCustomer();

    get(route('shop.customers.account.downloadable_products.index'))->assertOk();
});

// ============================================================================
// Downloading
// ============================================================================

it('should not find a purchased link that does not exist', function () {
    $this->loginAsCustomer();

    get(route('shop.customers.account.downloadable_products.download', ['id' => 999999]))
        ->assertNotFound();
});

it('should not find the purchased link of another customer', function () {
    $product = $this->createDownloadableProduct();

    $order = $this->createOrder(items: [[
        'product' => $product,
        'additional' => ['links' => $product->downloadable_links()->pluck('id')->all()],
    ]]);

    app(DownloadableLinkPurchasedRepository::class)->saveLinks($order->items->first());

    $purchased = DownloadableLinkPurchased::query()->firstOrFail();

    $this->loginAsCustomer();

    get(route('shop.customers.account.downloadable_products.download', ['id' => $purchased->id]))
        ->assertNotFound();
});
