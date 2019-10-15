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
     * @param OrdreItem $orderItem
     * @param integer    $quantity
     * @return void
     */
    public function returnQtyToProductInventory($orderItem, $quantity)
    {
        if (! $product = $orderItem->product)
            return;

        if ($orderItem->qty_shipped && $quantity > $orderItem->qty_ordered - $orderItem->qty_shipped) {
            $nonShippedQty = $orderItem->qty_ordered - $orderItem->qty_shipped;

            if (($totalShippedQtyToRefund = $quantity - $nonShippedQty) > 0) {
                foreach ($orderItem->shipment_items as $shipmentItem) {
                    if (! $totalShippedQtyToRefund)
                        break;

                    if (! $shipmentItem->shipment->inventory_source_id)
                        continue;

                    $shippedQtyToRefund = $totalShippedQtyToRefund > $shipmentItem->qty ? $shipmentItem->qty : $totalShippedQtyToRefund;

                    $totalShippedQtyToRefund = $totalShippedQtyToRefund > $shipmentItem->qty ? $totalShippedQtyToRefund - $shipmentItem->qty : 0;

                    $inventory = $product->inventories()
                            // ->where('vendor_id', $data['vendor_id'])
                            ->where('inventory_source_id', $shipmentItem->shipment->inventory_source_id)
                            ->first();
            
                    $inventory->update(['qty' => $inventory->qty + $shippedQtyToRefund]);
                }

                $quantity -= $totalShippedQtyToRefund;
            }
        }

        if ($quantity) {
            $orderedInventory = $product->ordered_inventories()
                    ->where('channel_id', $orderItem->order->channel->id)
                    ->first();

            if (! $orderedInventory)
                return;

            if (($qty = $orderedInventory->qty - $quantity) < 0)
                $qty = 0;

            $orderedInventory->update(['qty' => $qty]);
        }
    }
}