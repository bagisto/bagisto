<?php

namespace Webkul\Product\Listeners;

use Webkul\Product\Helpers\Indexers\Inventory;

class Order
{
    /**
     * Create a new listener instance.
     *
     * @param  \Webkul\Product\Helpers\Indexers\Inventory  $inventoryIndexer
     * @return void
     */
    public function __construct(protected Inventory $inventoryIndexer)
    {
    }

    /**
     * After order is created
     *
     * @param  \Webkul\Sale\Contracts\Order  $order
     * @return void
     */
    public function afterCancelOrCreate($order)
    {
        $products = [];

        foreach ($order->all_items as $item) {
            $products[] = $item->product;
        }

        $this->inventoryIndexer->reindexRows($products);
    }
}