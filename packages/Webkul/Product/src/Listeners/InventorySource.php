<?php

namespace Webkul\Product\Listeners;

use Webkul\Product\Helpers\Indexers\Flat as FlatIndexer;

class InventorySource
{
    /**
     * Create a new listener instance.
     *
     * @return void
     */
    public function __construct(protected FlatIndexer $flatIndexer) {}

    /**
     * After an inventory source is deleted.
     *
     * @param  int  $inventorySourceId
     * @return void
     */
    public function afterDelete($inventorySourceId)
    {
        /**
         * The source took its `product_inventories` rows with it through the foreign key, so
         * which products it stocked can no longer be asked and every quantity is recounted.
         */
        $this->flatIndexer->refreshDerivedColumns();
    }
}
