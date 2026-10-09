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

// ============================================================================
// A Failed Delivery Costs Nothing
// ============================================================================

/**
 * A purchased link of the given type, bought by the logged in customer.
 */
function purchasedLinkFor(array $linkOverrides): DownloadableLinkPurchased
{
    $customer = test()->loginAsCustomer();

    $product = test()->createDownloadableProduct();

    $order = test()->createOrder(items: [[
        'product' => $product,
        'additional' => ['links' => $product->downloadable_links()->pluck('id')->all()],
    ]], customer: $customer);

    test()->invoiceOrder($order);

    app(DownloadableLinkPurchasedRepository::class)->saveLinks($order->items->first());

    $purchased = DownloadableLinkPurchased::query()
        ->where('customer_id', $customer->id)
        ->firstOrFail();

    $purchased->update(array_merge(['status' => 'available'], $linkOverrides));

    return $purchased->refresh();
}

it('should not spend a download when the file is missing', function () {
    $purchased = purchasedLinkFor([
        'type' => 'file',
        'file' => 'downloadable/does-not-exist.pdf',
    ]);

    get(route('shop.customers.account.downloadable_products.download', ['id' => $purchased->id]))
        ->assertNotFound();

    expect($purchased->refresh()->download_used)->toBe(0);
});

it('should not spend a download when the external url is refused', function () {
    $purchased = purchasedLinkFor([
        'type' => 'url',
        'url' => 'http://127.0.0.1/private-file.pdf',
    ]);

    get(route('shop.customers.account.downloadable_products.download', ['id' => $purchased->id]))
        ->assertNotFound();

    expect($purchased->refresh()->download_used)->toBe(0);
});

it('should refuse an external url that is not http at all', function () {
    $purchased = purchasedLinkFor([
        'type' => 'url',
        'url' => 'file:///etc/passwd',
    ]);

    get(route('shop.customers.account.downloadable_products.download', ['id' => $purchased->id]))
        ->assertNotFound();

    expect($purchased->refresh()->download_used)->toBe(0);
});
