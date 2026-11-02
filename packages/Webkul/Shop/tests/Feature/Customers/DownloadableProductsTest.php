<?php

use Webkul\Customer\Models\Customer;
use Webkul\Product\Type\Downloadable;
use Webkul\Sales\Models\DownloadableLinkPurchased;
use Webkul\Sales\Models\Invoice;
use Webkul\Sales\Models\InvoiceItem;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderItem;
use Webkul\Sales\Repositories\DownloadableLinkPurchasedRepository;

/**
 * An invoiced order carrying one purchased downloadable link entitled to the given downloads.
 */
function entitledLink(int $downloadsBought, int $orderedQty = 1, int $invoicedQty = 1, array $attributes = []): DownloadableLinkPurchased
{
    $order = Order::factory()->create([
        'customer_id' => Customer::factory()->create()->id,
        'total_qty_ordered' => $orderedQty,
    ]);

    $orderItem = OrderItem::factory()->create([
        'order_id' => $order->id,
        'type' => 'downloadable',
        'qty_ordered' => $orderedQty,
        'qty_invoiced' => $invoicedQty,
        'qty_refunded' => 0,
        'qty_canceled' => 0,
    ]);

    $invoice = Invoice::factory()->create([
        'order_id' => $order->id,
        'total_qty' => $invoicedQty,
    ]);

    InvoiceItem::factory()->create([
        'invoice_id' => $invoice->id,
        'order_item_id' => $orderItem->id,
        'qty' => $invoicedQty,
    ]);

    return DownloadableLinkPurchased::create(array_merge([
        'name' => 'Manual',
        'product_name' => 'Manual',
        'type' => 'file',
        'file' => 'downloadable/manual.pdf',
        'file_name' => 'manual.pdf',
        'download_bought' => $downloadsBought,
        'download_used' => 0,
        'download_canceled' => 0,
        'status' => 'available',
        'customer_id' => $order->customer_id,
        'order_id' => $order->id,
        'order_item_id' => $orderItem->id,
    ], $attributes));
}

/**
 * Spend one download through the repository.
 */
function spendDownload(DownloadableLinkPurchased $link): bool
{
    return app(DownloadableLinkPurchasedRepository::class)->consumeDownload($link->id);
}

// ============================================================================
// Spending A Download
// ============================================================================

it('should spend one download per request until the entitlement is gone', function () {
    $link = entitledLink(3);

    expect(spendDownload($link))->toBeTrue()
        ->and(spendDownload($link))->toBeTrue()
        ->and(spendDownload($link))->toBeTrue()
        ->and(spendDownload($link))->toBeFalse()
        ->and($link->refresh()->download_used)->toBe(3);
});

it('should expire the purchased link once its last download is spent', function () {
    $link = entitledLink(1);

    expect(spendDownload($link))->toBeTrue()
        ->and($link->refresh()->status)->toBe('expired')
        ->and($link->download_used)->toBe(1);
});

it('should leave the link available while downloads remain', function () {
    $link = entitledLink(2);

    expect(spendDownload($link))->toBeTrue()
        ->and($link->refresh()->status)->toBe('available')
        ->and($link->download_used)->toBe(1);
});

// ============================================================================
// Refusing One
// ============================================================================

it('should refuse a download when the entitlement is already spent', function () {
    $link = entitledLink(1, 1, 1, ['download_used' => 1]);

    expect(spendDownload($link))->toBeFalse()
        ->and($link->refresh()->download_used)->toBe(1);
});

it('should refuse a download when every remaining one has been canceled', function () {
    $link = entitledLink(2, 1, 1, ['download_canceled' => 2]);

    expect(spendDownload($link))->toBeFalse()
        ->and($link->refresh()->download_used)->toBe(0);
});

it('should refuse a download for a purchased link that does not exist', function () {
    expect(app(DownloadableLinkPurchasedRepository::class)->consumeDownload(999999))->toBeFalse();
});

// ============================================================================
// Counting From The Stored Value
// ============================================================================

it('should count the download against the stored value, not one read earlier', function () {
    $link = entitledLink(5);

    DownloadableLinkPurchased::where('id', $link->id)->update(['download_used' => 2]);

    expect(spendDownload($link))->toBeTrue()
        ->and($link->refresh()->download_used)->toBe(3);
});

it('should not spend more downloads than the invoiced quantity entitles', function () {
    $link = entitledLink(2, 2, 1);

    expect(spendDownload($link))->toBeTrue()
        ->and(spendDownload($link))->toBeFalse()
        ->and($link->refresh()->download_used)->toBe(1);
});

it('should scale the entitlement to its own item, not the whole order', function () {
    $link = entitledLink(6, 3, 1);

    $order = $link->order;

    $order->update(['total_qty_ordered' => 10]);

    $order->invoices()->first()->update(['total_qty' => 8]);

    expect(spendDownload($link))->toBeTrue()
        ->and(spendDownload($link))->toBeTrue()
        ->and(spendDownload($link))->toBeFalse()
        ->and($link->refresh()->download_used)->toBe(2);
});

// ============================================================================
// Unlimited Downloads
// ============================================================================

it('should keep serving a link bought with no download limit', function () {
    $link = entitledLink(0);

    expect(spendDownload($link))->toBeTrue()
        ->and(spendDownload($link))->toBeTrue()
        ->and(spendDownload($link))->toBeTrue()
        ->and($link->refresh()->download_used)->toBe(3)
        ->and($link->status)->toBe('available');
});

// ============================================================================
// Revoking On Refund Or Cancellation
// ============================================================================

it('should expire the link and give up its unspent downloads once the item is refunded', function () {
    $link = entitledLink(3);

    spendDownload($link);

    $link->order_item->update(['qty_refunded' => 1]);

    app(DownloadableLinkPurchasedRepository::class)->updateStatus($link->order_item->refresh(), 'expired');

    expect($link->refresh()->status)->toBe('expired')
        ->and($link->download_canceled)->toBe(2)
        ->and(spendDownload($link))->toBeFalse();
});

it('should expire an unlimited link once the item is refunded without cancelling a negative amount', function () {
    $link = entitledLink(0);

    spendDownload($link);

    $link->order_item->update(['qty_refunded' => 1]);

    app(DownloadableLinkPurchasedRepository::class)->updateStatus($link->order_item->refresh(), 'expired');

    expect($link->refresh()->status)->toBe('expired')
        ->and($link->download_canceled)->toBe(0);
});

it('should keep the downloads the invoiced share paid for when only the rest of the item is cancelled', function () {
    $link = entitledLink(4, 2, 1);

    $link->order_item->update(['qty_canceled' => 1]);

    app(DownloadableLinkPurchasedRepository::class)->updateStatus($link->order_item->refresh(), 'expired');

    expect($link->refresh()->status)->toBe('available')
        ->and($link->download_canceled)->toBe(2)
        ->and(spendDownload($link))->toBeTrue();
});

// ============================================================================
// One Of Each Link In The Cart
// ============================================================================

it('should always put a downloadable product in the cart once, whatever quantity is asked for', function () {
    expect(app(Downloadable::class)->getQtyRequest(['quantity' => 3])['quantity'])->toBe(1);
});
