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
        return 'Webkul\Sales\Contracts\DownloadableLinkPurchased';
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
            if ($status == 'expired') {
                if (count($purchasedLink->order_item->invoice_items) > 0) {
                    $totalInvoiceQty = 0;

                    foreach ($purchasedLink->order_item->invoice_items as $invoice_item) {
                        $totalInvoiceQty = $totalInvoiceQty + $invoice_item->qty;
                    }

                    $orderedQty = $purchasedLink->order_item->qty_ordered;
                    $totalInvoiceQty = $totalInvoiceQty * ($purchasedLink->download_bought / $orderedQty);

                    $this->update([
                        'status' => $purchasedLink->download_used == $totalInvoiceQty ? $status : $purchasedLink->status,
                        'download_canceled' => $purchasedLink->download_bought - $totalInvoiceQty,
                    ], $purchasedLink->id);
                } else {
                    $this->update([
                        'status' => $status,
                        'download_canceled' => $purchasedLink->download_bought,
                    ], $purchasedLink->id);
                }
            } else {
                $this->update([
                    'status' => $status,
                ], $purchasedLink->id);
            }
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

            if ($purchasedLink->download_used >= $this->getInvoicedDownloads($purchasedLink)) {
                return false;
            }

            if ($this->getRemainingDownloads($purchasedLink) <= 0) {
                return false;
            }

            $purchasedLink->increment('download_used');

            if ($this->getRemainingDownloads($purchasedLink) <= 0) {
                $purchasedLink->update(['status' => 'expired']);
            }

            return true;
        });
    }

    /**
     * The number of downloads the invoiced quantity entitles the customer to.
     */
    protected function getInvoicedDownloads(DownloadableLinkPurchased $purchasedLink): float
    {
        $totalInvoiceQty = 0;

        if (isset($purchasedLink->order->invoices)) {
            foreach ($purchasedLink->order->invoices as $invoice) {
                $totalInvoiceQty = $totalInvoiceQty + $invoice->total_qty;
            }
        }

        return $totalInvoiceQty * ($purchasedLink->download_bought / $purchasedLink->order->total_qty_ordered);
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
