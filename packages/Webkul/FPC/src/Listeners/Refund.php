<?php

namespace Webkul\FPC\Listeners;

use Spatie\ResponseCache\Facades\ResponseCache;

class Refund extends Product
{
    /**
     * After refund is created
     *
     * @param  \Webkul\Sale\Contracts\Refund  $refund
     * @return void
     */
    public function afterCreate($refund)
    {
        foreach ($refund->items as $item) {
            if (! $item->product) {
                continue;
            }

            $urls = $this->getForgettableUrls($item->product);

            ResponseCache::forget($urls);
        }
    }
}
