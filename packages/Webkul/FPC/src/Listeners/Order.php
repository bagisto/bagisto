<?php

namespace Webkul\FPC\Listeners;

class Order extends Product
{
    /**
     * After an order is created or canceled, drop the pages of the products whose stock it changed.
     *
     * @param  \Webkul\Sales\Contracts\Order  $order
     * @return void
     */
    public function afterCancelOrCreate($order)
    {
        $urls = [];

        foreach ($order->all_items as $item) {
            if (! $item->product) {
                continue;
            }

            $urls = array_merge($urls, $this->getForgettableUrls($item->product));
        }

        $this->forgetPages($urls);
    }
}
