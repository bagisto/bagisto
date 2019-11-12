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
        return 'Webkul\Product\Contracts\ProductInventory';
    }

    /**
     * @param array $data
     * @param mixed $product
     * @return mixed
     */
    public function saveInventories(array $data, $product)
    {
        if (isset($data['inventories'])) {
            foreach ($data['inventories'] as $inventorySourceId => $qty) {
                $qty = is_null($qty) ? 0 : $qty;

                $productInventory = $this->findOneWhere([
                        'product_id' => $product->id,
                        'inventory_source_id' => $inventorySourceId,
                        'vendor_id' => isset($data['vendor_id']) ? $data['vendor_id'] : 0
                    ]);

                if ($productInventory) {
                    $productInventory->qty = $qty;

                    $productInventory->save();
                } else {
                    $this->create([
                            'qty' => $qty,
                            'product_id' => $product->id,
                            'inventory_source_id' => $inventorySourceId,
                            'vendor_id' => isset($data['vendor_id']) ? $data['vendor_id'] : 0
                        ]);
                }
            }
        }
    }
}