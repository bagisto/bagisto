<?php

namespace Webkul\Sales\Repositories;

use Illuminate\Container\Container as App;
use Webkul\Core\Eloquent\Repository;

/**
 * ShipmentItem Reposotory
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ShipmentItemRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return Mixed
     */
    function model()
    {
        return 'Webkul\Sales\Contracts\ShipmentItem';
    }

    /**
     * @param array $data
     * @return void
     */
    public function updateProductInventory($data)
    {
        $orderedInventory = $data['product']->ordered_inventories()
                ->where('channel_id', $data['shipment']->order->channel->id)
                ->first();
                
        if ($orderedInventory) {
            if (($orderedQty = $orderedInventory->qty - $data['qty']) < 0) {
                $orderedQty = 0;
            }
                
            $orderedInventory->update([
                    'qty' => $orderedQty
                ]);
        } else {
            $data['product']->ordered_inventories()->create([
                    'qty' => $data['qty'],
                    'product_id' => $data['product']->id,
                    'channel_id' => $data['shipment']->order->channel->id
                ]);
        }

        $inventory = $data['product']->inventories()
                ->where('vendor_id', $data['vendor_id'])
                ->where('inventory_source_id', $data['shipment']->inventory_source_id)
                ->first();

        if (($qty = $inventory->qty - $data['qty']) < 0) {
            $qty = 0;
        }

        $inventory->update([
                'qty' => $qty
            ]);
    }
}