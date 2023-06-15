<?php

namespace Webkul\Product\Listeners;

use Webkul\Product\Jobs\Indexers\Inventory;

class Order
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
     * After order is created
     *
     * @param  \Webkul\Sale\Contracts\Order  $order
     * @return void
     */
    public function afterCancelOrCreate($order)
    {
        $products = [];

        foreach ($order->all_items as $item) {
            $products[] = $item->product->id;
        }
        
        Inventory::dispatch(
            collect($products),
            'reindexRows'
        );
    }
}