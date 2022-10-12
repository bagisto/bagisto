<?php

namespace Webkul\Product\Listeners;

use Webkul\Product\Helpers\Indexer;

class Refund
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
     * After refund is created
     *
     * @param  \Webkul\Sale\Contracts\Refund  $refund
     * @return void
     */
    public function afterCreate($refund)
    {
        foreach ($refund->order->all_items as $item) {
            $this->indexer->refreshInventory($item->product);
        }
    }
}
