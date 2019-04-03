<?php

namespace Webkul\Sales\Repositories;

use Illuminate\Container\Container as App;
use Webkul\Core\Eloquent\Repository;

/**
 * Order Item Inventory Reposotory
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */

class OrderItemInventoryRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return Mixed
     */

    function model()
    {
        return 'Webkul\Sales\Contracts\OrderItemInventory';
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        $orderItem = $data['orderItem'];

        $orderedQuantity = $orderItem->qty_ordered;

        $product = $orderItem->type == 'configurable' ? $orderItem->child->product : $orderItem->product;

        if (!$product) {
            return ;
        }
        $inventories = $product->inventory_sources()->orderBy('priority', 'asc')->get();

        foreach ($inventories as $inventorySource) {
            if (! $orderedQuantity)
                break;

            $sourceQuantity = $inventorySource->pivot->qty;

            if (! $inventorySource->status || !$sourceQuantity)
                continue;

            if ($sourceQuantity >= $orderedQuantity) {
                $orderItemQuantity = $orderedQuantity;

                $sourceQuantity -= $orderItemQuantity;

                $orderedQuantity = 0;
            } else {
                $orderItemQuantity = $sourceQuantity;

                $sourceQuantity = 0;

                $orderedQuantity -= $orderItemQuantity;
            }

            $this->model->create([
                    'qty' => $orderItemQuantity,
                    'order_item_id' => $orderItem->id,
                    'inventory_source_id' => $inventorySource->id,
                ]);

            $inventorySource->pivot->update([
                    'qty' => $sourceQuantity
                ]);
        }

    }
}