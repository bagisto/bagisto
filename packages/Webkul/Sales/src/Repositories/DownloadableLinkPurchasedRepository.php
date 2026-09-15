<?php

namespace Webkul\Sales\Repositories;

use Illuminate\Container\Container;
use Webkul\Core\Eloquent\Repository;
use Webkul\Product\Repositories\ProductDownloadableLinkRepository;
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
