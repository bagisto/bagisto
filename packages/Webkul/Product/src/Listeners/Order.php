<?php

namespace Webkul\Product\Listeners;

use Webkul\Product\Helpers\Indexers\Flat as FlatIndexer;
use Webkul\Product\Jobs\UpdateCreateInventoryIndex as UpdateCreateInventoryIndexJob;

class Order
{
    /**
     * Create a new listener instance.
     *
     * @return void
     */
    public function __construct(protected FlatIndexer $flatIndexer) {}

    /**
     * After order is created.
     *
     * @param  \Webkul\Sale\Contracts\Order  $order
     * @return void
     */
    public function afterCancelOrCreate($order)
    {
        $productIds = $order->all_items
            ->pluck('product_id')
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        $this->flatIndexer->refreshDerivedColumns($productIds);

        UpdateCreateInventoryIndexJob::dispatch($productIds);
    }
}
