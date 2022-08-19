<?php

namespace Webkul\Sales\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Sales\Contracts\RefundItem;

class RefundItemRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    function model(): string
    {
        return 'Webkul\Sales\Contracts\RefundItem';
    }

    /**
     * Returns qty to product inventory after order refund
     *
     * @param  \Webkul\Sales\Contracts\Order  $orderItem
     * @param  int  $quantity
     * @return void
     */
    public function returnQtyToProductInventory($orderItem, $quantity)
    {
        if (! $product = $orderItem->product) {
            return;
        }

        if (
            $orderItem->qty_shipped
            && $quantity > $orderItem->qty_ordered - $orderItem->qty_shipped
        ) {
            $nonShippedQty = $orderItem->qty_ordered - $orderItem->qty_shipped;

            if (($totalShippedQtyToRefund = $quantity - $nonShippedQty) > 0) {
                $shipmentItems = $orderItem->parent ? $orderItem->parent->shipment_items : $orderItem->shipment_items;

                foreach ($shipmentItems as $shipmentItem) {
                    if (! $totalShippedQtyToRefund) {
                        break;
                    }

                    if (! $shipmentItem->shipment->inventory_source_id) {
                        continue;
                    }


                    if ($orderItem->parent) {
                        $shippedQty = $orderItem->qty_ordered
                            ? ($orderItem->qty_ordered / $orderItem->parent->qty_ordered) * $shipmentItem->qty
                            : $orderItem->parent->qty_ordered;
                    } else {
                        $shippedQty = $shipmentItem->qty;
                    }
                    
                    $shippedQtyToRefund = $totalShippedQtyToRefund > $shippedQty ? $shippedQty : $totalShippedQtyToRefund;

                    $totalShippedQtyToRefund = $totalShippedQtyToRefund > $shippedQty ? $totalShippedQtyToRefund - $shippedQty : 0;

                    $inventory = $product->inventories()
                        //  ->where('vendor_id', $data['vendor_id'])
                        ->where('inventory_source_id', $shipmentItem->shipment->inventory_source_id)
                        ->first();
            
                    $inventory->update(['qty' => $inventory->qty + $shippedQtyToRefund]);
                }

                $quantity -= $totalShippedQtyToRefund;
            }
        } elseif (
            ! $orderItem->getTypeInstance()->isStockable()
            && $orderItem->getTypeInstance()->showQuantityBox()
        ) {
            $inventory = $orderItem->product->inventories()
                // ->where('vendor_id', $data['vendor_id'])
                ->whereIn('inventory_source_id', $orderItem->order->channel->inventory_sources()->pluck('id'))
                ->orderBy('qty', 'desc')
                ->first();

            if ($inventory) {
                $inventory->update(['qty' => $inventory->qty + $quantity]);
            }
        }

        if ($quantity) {
            $orderedInventory = $product->ordered_inventories()
                ->where('channel_id', $orderItem->order->channel->id)
                ->first();

            if (! $orderedInventory) {
                return;
            }

            if (($qty = $orderedInventory->qty - $quantity) < 0) {
                $qty = 0;
            }

            $orderedInventory->update(['qty' => $qty]);
        }
    }
}