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
        $salableInventory = $data['product']->salable_inventories()
                ->where('channel_id', $data['shipment']->order->channel->id)
                ->first();

        $inventory = $data['product']->inventories()
                ->where('inventory_source_id', $data['shipment']->inventory_source_id)
                ->first();

        if (($salableQty = $salableInventory->sold_qty - $data['qty']) < 0) {
            $salableQty = 0;
        }
            
        $salableInventory->update([
                'sold_qty' => $salableQty
            ]);

        if (($qty = $inventory->qty - $data['qty']) < 0) {
            $qty = 0;
        }

        $inventory->update([
                'qty' => $data['qty']
            ]);
    }
}