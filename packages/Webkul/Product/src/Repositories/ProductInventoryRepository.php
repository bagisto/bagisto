<?php 

namespace Webkul\Product\Repositories;
 
use Illuminate\Container\Container as App;
use Webkul\Core\Eloquent\Repository;

/**
 * Product Inventory Reposotory
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ProductInventoryRepository extends Repository
{    
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Product\Models\ProductInventory';
    }

    /**
     * @param array $inventories
     * @param mixed $product
     * @return mixed
     */
    public function saveInventories(array $data, $product)
    {
        $inventorySourceIds = $product->inventory_sources->pluck('id');

        if(isset($data['inventories'])) {
            foreach($data['inventories'] as $inventorySourceId => $qty) {
                if(is_null($qty))
                    continue;
                    
                $productInventory = $this->findWhere([
                        'product_id' => $product->id,
                        'inventory_source_id' => $inventorySourceId,
                    ])->first();

                if($productInventory) {
                    if(is_numeric($index = $inventorySourceIds->search($inventorySourceId))) {
                        $inventorySourceIds->forget($index);
                    }

                    $this->update(['qty' => $qty], $productInventory->id);
                } else {
                    $this->create([
                            'qty' => $qty,
                            'product_id' => $product->id,
                            'inventory_source_id' => $inventorySourceId,
                        ]);
                }
            }
        }

        if($inventorySourceIds->count()) {
            $product->inventory_sources()->detach($inventorySourceIds);
        }
    }
}