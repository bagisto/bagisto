<?php

namespace Webkul\Sales\Repositories;

use Illuminate\Container\Container;
use Illuminate\Support\Facades\DB;
use Webkul\Core\Eloquent\Repository;
use Webkul\Product\Repositories\ProductDownloadableLinkRepository;
use Webkul\Sales\Contracts\DownloadableLinkPurchased;
use Webkul\Sales\Contracts\OrderItem;

class DownloadableLinkPurchasedRepository extends Repository
{
    /**
     * Create a new repository instance.
     *
     * @return void
     */
    public function __construct(
        protected ProductDownloadableLinkRepository $productDownloadableLinkRepository,
        Container $container
    ) {
        parent::__construct($container);
    }

    /**
     * Specify the model class name.
     */
    public function model(): string
    {
        return DownloadableLinkPurchased::class;
    }

    /**
     * Save the links bought with an ordered downloadable item, skipping any that is not one of its product's links.
     *
     * @param  OrderItem  $orderItem
     * @return void
     */
    public function saveLinks($orderItem)
    {
        if (! $this->isValidDownloadableProduct($orderItem)) {
            return;
        }

        foreach ($orderItem->additional['links'] as $linkId) {
            $productDownloadableLink = $this->productDownloadableLinkRepository->findOneWhere([
                'id' => $linkId,
                'product_id' => $orderItem->product_id,
            ]);

            if (! $productDownloadableLink) {
                continue;
            }

            $this->create([
                'name' => $productDownloadableLink->title,
                'product_name' => $orderItem->name,
                'url' => $productDownloadableLink->url,
                'file' => $productDownloadableLink->file,
                'file_name' => $productDownloadableLink->file_name,
                'type' => $productDownloadableLink->type,
                'download_bought' => $productDownloadableLink->downloads * $orderItem->qty_ordered,
                'status' => 'pending',
                'customer_id' => $orderItem->order->customer_id,
                'order_id' => $orderItem->order_id,
                'order_item_id' => $orderItem->id,
            ]);
        }
    }

    /**
     * Update the status of the links bought with an ordered item.
     *
     * @param  OrderItem  $orderItem
     * @param  string  $status
     * @return void
     */
    public function updateStatus($orderItem, $status)
    {
        $purchasedLinks = $this->findByField('order_item_id', $orderItem->id);

        foreach ($purchasedLinks as $purchasedLink) {
            if ($status != 'expired') {
                $this->update([
                    'status' => $status,
                ], $purchasedLink->id);

                continue;
            }

            $this->update([
                'status' => $status,
                'download_canceled' => $this->getRevokedDownloads($purchasedLink),
            ], $purchasedLink->id);
        }
    }

    /**
     * Spend one of the purchased link's downloads, under a row lock so that concurrent requests
     * cannot each spend the same one.
     */
    public function consumeDownload(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $purchasedLink = $this->getModel()
                ->newQuery()
                ->lockForUpdate()
                ->find($id);

            if (! $purchasedLink) {
                return false;
            }

            if (! $this->hasUnlimitedDownloads($purchasedLink)) {
                if ($purchasedLink->download_used >= $this->getInvoicedDownloads($purchasedLink)) {
                    return false;
                }

                if ($this->getRemainingDownloads($purchasedLink) <= 0) {
                    return false;
                }
            }

            $purchasedLink->increment('download_used');

            if (
                ! $this->hasUnlimitedDownloads($purchasedLink)
                && $this->getRemainingDownloads($purchasedLink) <= 0
            ) {
                $purchasedLink->update(['status' => 'expired']);
            }

            return true;
        });
    }

    /**
     * Does the purchased link carry no download limit at all? A link bought with no allowance
     * recorded against it is unlimited, which is what a zero on the product's link means.
     */
    protected function hasUnlimitedDownloads(DownloadableLinkPurchased $purchasedLink): bool
    {
        return ! $purchasedLink->download_bought;
    }

    /**
     * The downloads a revoked link gives up, which is whatever it had not already spent.
     */
    protected function getRevokedDownloads(DownloadableLinkPurchased $purchasedLink): int
    {
        if ($this->hasUnlimitedDownloads($purchasedLink)) {
            return 0;
        }

        return max(0, $purchasedLink->download_bought - $purchasedLink->download_used);
    }

    /**
     * The number of downloads the invoiced quantity of the link's own ordered item entitles the
     * customer to, so a partly invoiced item grants only the share it has paid for.
     */
    protected function getInvoicedDownloads(DownloadableLinkPurchased $purchasedLink): float
    {
        $orderItem = $purchasedLink->order_item;

        if (
            ! $orderItem
            || ! $orderItem->qty_ordered
        ) {
            return 0;
        }

        $invoicedQty = 0;

        foreach ($orderItem->invoice_items as $invoiceItem) {
            $invoicedQty = $invoicedQty + $invoiceItem->qty;
        }

        return $invoicedQty * ($purchasedLink->download_bought / $orderItem->qty_ordered);
    }

    /**
     * The number of downloads left on the purchased link.
     */
    protected function getRemainingDownloads(DownloadableLinkPurchased $purchasedLink): int
    {
        return $purchasedLink->download_bought - ($purchasedLink->download_used + $purchasedLink->download_canceled);
    }

    /**
     * Whether the ordered item is a downloadable product carrying links.
     *
     * @param  OrderItem  $orderItem
     */
    private function isValidDownloadableProduct($orderItem): bool
    {
        if (
            stristr($orderItem->type, 'downloadable') !== false
            && isset($orderItem->additional['links'])
        ) {
            return true;
        }

        return false;
    }
}
