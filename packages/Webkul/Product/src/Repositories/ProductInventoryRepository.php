<?php

namespace Webkul\Product\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Webkul\Core\Eloquent\Repository;

class ProductInventoryRepository extends Repository
{
    /**
     * Specify Model class name.
     *
     * @return string
     */
    function model(): string
    {
        return 'Webkul\Product\Contracts\ProductInventory';
    }

    /**
     * @param  array  $data
     * @param  Webkul\Product\Contracts\Product  $product
     * @return void
     */
    public function saveInventories(array $data, $product)
    {
        if (! isset($data['inventories'])) {
            return;
        }

        foreach ($data['inventories'] as $inventorySourceId => $qty) {
            $this->updateOrCreate([
                'product_id'          => $product->id,
                'inventory_source_id' => $inventorySourceId,
                'vendor_id'           => $data['vendor_id'] ?? 0,
            ], [
                'qty' => $qty ?? 0,
            ]);
        }
    }

    /**
     * Get stock threshold.
     */
    public function getStockThreshold(): Collection
    {
        return $this->getModel()
            ->with('product', 'product.attribute_family', 'product.attribute_values', 'product.images')
            ->select('*', DB::raw('SUM(qty) as total_qty'))
            ->groupBy('product_id')
            ->orderBy('total_qty', 'ASC')
            ->limit(5)
            ->get();
    }
}
