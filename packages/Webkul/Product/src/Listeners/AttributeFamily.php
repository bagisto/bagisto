<?php

namespace Webkul\Product\Listeners;

use Webkul\Product\Helpers\Indexers\Flat as FlatIndexer;

class AttributeFamily
{
    /**
     * Create a new listener instance.
     *
     * @return void
     */
    public function __construct(protected FlatIndexer $flatIndexer) {}

    /**
     * After an attribute family is updated.
     *
     * @param  \Webkul\Attribute\Contracts\AttributeFamily  $attributeFamily
     * @return void
     */
    public function afterUpdate($attributeFamily)
    {
        /**
         * Read off `products` rather than off `product_flat`, which is the table being updated
         * and so cannot be selected from in the same statement.
         */
        $this->flatIndexer->refreshDerivedColumns(
            fn ($query) => $query
                ->select('id')
                ->from('products')
                ->where('attribute_family_id', $attributeFamily->id)
        );
    }
}
