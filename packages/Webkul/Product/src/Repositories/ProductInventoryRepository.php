<?php

namespace Webkul\Product\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Product\Contracts\Product;
use Webkul\Product\Contracts\ProductInventory;

class ProductInventoryRepository extends Repository
{
    /**
     * Specify Model class name.
     */
    public function model(): string
    {
        return ProductInventory::class;
    }

    /**
     * Save inventories.
     */
    public function saveInventories(array $data, Product $product): void
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
}
