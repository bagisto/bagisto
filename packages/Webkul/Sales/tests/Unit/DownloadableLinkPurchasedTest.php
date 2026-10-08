<?php

use Webkul\Sales\Models\DownloadableLinkPurchased;
use Webkul\Sales\Repositories\DownloadableLinkPurchasedRepository;

/**
 * An invoiced order carrying one purchased downloadable link entitled to the given downloads.
 */
function purchasedLink(int $downloadsBought, array $attributes = []): DownloadableLinkPurchased
{
    $order = test()->createOrder();

    test()->invoiceOrder($order);

    return DownloadableLinkPurchased::create(array_merge([
        'name' => 'Manual',
        'product_name' => $order->items->first()->name,
        'type' => 'file',
        'file' => 'downloadable/manual.pdf',
        'file_name' => 'manual.pdf',
        'download_bought' => $downloadsBought,
        'download_used' => 0,
        'download_canceled' => 0,
        'status' => 'available',
        'customer_id' => $order->customer_id,
        'order_id' => $order->id,
        'order_item_id' => $order->items->first()->id,
    ], $attributes));
}

/**
 * Spend one download through the repository.
 */
function consume(DownloadableLinkPurchased $link): bool
{
    return app(DownloadableLinkPurchasedRepository::class)->consumeDownload($link->id);
}

// ============================================================================
// Spending A Download
// ============================================================================

it('should spend one download per request until the entitlement is gone', function () {
    $link = purchasedLink(3);

    expect(consume($link))->toBeTrue()
        ->and(consume($link))->toBeTrue()
        ->and(consume($link))->toBeTrue()
        ->and(consume($link))->toBeFalse()
        ->and($link->refresh()->download_used)->toBe(3);
});

it('should expire the purchased link once its last download is spent', function () {
    $link = purchasedLink(1);

    expect(consume($link))->toBeTrue()
        ->and($link->refresh()->status)->toBe('expired')
        ->and($link->download_used)->toBe(1);
});

it('should leave the link available while downloads remain', function () {
    $link = purchasedLink(2);

    expect(consume($link))->toBeTrue()
        ->and($link->refresh()->status)->toBe('available')
        ->and($link->download_used)->toBe(1);
});

// ============================================================================
// Refusing One
// ============================================================================

it('should refuse a download when the entitlement is already spent', function () {
    $link = purchasedLink(1, ['download_used' => 1]);

    expect(consume($link))->toBeFalse()
        ->and($link->refresh()->download_used)->toBe(1);
});

it('should refuse a download when every remaining one has been canceled', function () {
    $link = purchasedLink(2, ['download_canceled' => 2]);

    expect(consume($link))->toBeFalse()
        ->and($link->refresh()->download_used)->toBe(0);
});

it('should refuse a download for a purchased link that does not exist', function () {
    expect(app(DownloadableLinkPurchasedRepository::class)->consumeDownload(999999))->toBeFalse();
});

// ============================================================================
// Counting From The Stored Value
// ============================================================================

it('should count the download against the stored value, not one read earlier', function () {
    $link = purchasedLink(5);

    DownloadableLinkPurchased::where('id', $link->id)->update(['download_used' => 2]);

    expect(consume($link))->toBeTrue()
        ->and($link->refresh()->download_used)->toBe(3);
});

it('should not spend more downloads than the invoiced quantity entitles', function () {
    $order = test()->createOrder([], [['qty_ordered' => 2]]);

    test()->invoiceOrder($order, [$order->items->first()->id => 1]);

    $link = DownloadableLinkPurchased::create([
        'name' => 'Manual',
        'product_name' => $order->items->first()->name,
        'type' => 'file',
        'file' => 'downloadable/manual.pdf',
        'file_name' => 'manual.pdf',
        'download_bought' => 2,
        'download_used' => 0,
        'download_canceled' => 0,
        'status' => 'available',
        'customer_id' => $order->customer_id,
        'order_id' => $order->id,
        'order_item_id' => $order->items->first()->id,
    ]);

    expect(consume($link))->toBeTrue()
        ->and(consume($link))->toBeFalse()
        ->and($link->refresh()->download_used)->toBe(1);
});
