<?php 

namespace Webkul\Product\Repositories;
 
use Illuminate\Container\Container as App;
use Webkul\Core\Eloquent\Repository;

/**
 * Product Salable Inventory Reposotory
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ProductSalableInventoryRepository extends Repository
{    
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Product\Models\ProductSalableInventory';
    }

    /**
     * @param mixed $product
     * @return mixed
     */
    public function saveInventories($product)
    {
        foreach (core()->getAllChannels() as $channel) {
            $inventorySourceIds = $channel->inventory_sources()->pluck('inventory_source_id');

            $salableQty = 0;

            foreach ($product->inventories()->get() as $productInventory) {
                if(is_numeric($index = $inventorySourceIds->search($productInventory->inventory_source->id))) {
                    $salableQty += $productInventory->qty;
                }
            }

            $salableInventory = $this->findOneWhere([
                    'product_id' => $product->id,
                    'channel_id' => $channel->id,
                ]);

            if($salableInventory) {
                $salableQty -= $salableInventory->sold_qty;

                if ($salableQty < 0)
                    $salableQty = 0;

                $this->update(['qty' => $salableQty], $salableInventory->id);
            } else {
                $this->create([
                        'qty' => $salableQty,
                        'product_id' => $product->id,
                        'channel_id' => $channel->id,
                    ]);
            }
        }
    }
}