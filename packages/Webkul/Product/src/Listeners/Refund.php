<?php

namespace Webkul\Product\Listeners;

use Webkul\Product\Helpers\Indexers\Inventory;

class Refund
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
     * After refund is created
     *
     * @param  \Webkul\Sale\Contracts\Refund  $refund
     * @return void
     */
    public function afterCreate($refund)
    {
        $products = [];

        foreach ($refund->all_items as $item) {
            $products[] = $item->product;
        }

        $this->inventoryIndexer->reindexRows($products);
    }
}
