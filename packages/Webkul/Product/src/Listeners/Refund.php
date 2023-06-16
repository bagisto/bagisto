<?php

namespace Webkul\Product\Listeners;

use Webkul\Product\Jobs\Indexers\Inventory;

class Refund
{
    /**
     * Create a new listener instance.
     *
     * @return void
     */
    public function __construct()
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

        foreach ($refund->items as $item) {
            $products[] = $item->product->id;
        }

        Inventory::dispatch(
            collect($products),
            'reindexRows'
        );
    }
}
