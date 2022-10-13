<?php

namespace Webkul\Product\Listeners;

use Webkul\Product\Helpers\Indexer;

class Order
{
    /**
     * Create a new listener instance.
     *
     * @param  \Webkul\Product\Helpers\Indexer  $indexer
     * @return void
     */
    public function __construct(protected Indexer $indexer)
    {
    }

    /**
     * After order is created
     *
     * @param  \Webkul\Sale\Contracts\Order  $order
     * @return void
     */
    public function afterCreate($order)
    {
        foreach ($order->all_items as $item) {
            $this->indexer->refreshInventory($item->product);
        }
    }

    /**
     * After order is cancelled
     *
     * @param  \Webkul\Sale\Contracts\Order  $order
     * @return void
     */
    public function afterCancel($order)
    {
        foreach ($order->all_items as $item) {
            $this->indexer->refreshInventory($item->product);
        }
    }
}