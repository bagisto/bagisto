<?php

namespace Webkul\Sales\Repositories;

use Illuminate\Container\Container as App;
use Webkul\Core\Eloquent\Repository;
use Webkul\Sales\Contracts\RefundItem;

/**
 * Refund Item Reposotory
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */

class RefundItemRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return Mixed
     */

    function model()
    {
        return RefundItem::class;
    }

    /**
     * Returns qty to product inventory after order refund
     *
     * @param RefundItem $refundItem
     * @return void
     */
    public function returnQtyToProductInventory($refundItem)
    {
        return;
        if (! $product = $orderItem->product)
            return;

        $orderedInventory = $product->ordered_inventories()
                ->where('channel_id', $orderItem->order->channel->id)
                ->first();

        if (! $orderedInventory)
            return ;

        if (($qty = $orderedInventory->qty - $quantity) < 0)
            $qty = 0;

        $orderedInventory->update([
                'qty' => $qty
            ]);
    }
}