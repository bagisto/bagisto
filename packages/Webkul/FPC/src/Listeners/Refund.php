<?php

namespace Webkul\FPC\Listeners;

class Refund extends Product
{
    /**
     * After a refund is created, drop the pages of the products whose stock it returned.
     *
     * @param  \Webkul\Sales\Contracts\Refund  $refund
     * @return void
     */
    public function afterCreate($refund)
    {
        $urls = [];

        foreach ($refund->items as $item) {
            if (! $item->product) {
                continue;
            }

            $urls = array_merge($urls, $this->getForgettableUrls($item->product));
        }

        $this->forgetPages($urls);
    }
}
