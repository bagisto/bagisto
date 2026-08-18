<?php

namespace Webkul\Product\Listeners;

use Webkul\Product\Helpers\Indexers\Flat as FlatIndexer;

class Category
{
    /**
     * Create a new listener instance.
     *
     * @return void
     */
    public function __construct(protected FlatIndexer $flatIndexer) {}

    /**
     * After a category is updated.
     *
     * @param  \Webkul\Category\Contracts\Category  $category
     * @return void
     */
    public function afterUpdate($category)
    {
        /**
         * Scoped with a sub-select rather than a list of ids, since a category near the root
         * can hold the whole catalog.
         */
        $this->flatIndexer->refreshDerivedColumns(
            fn ($query) => $query
                ->select('product_id')
                ->from('product_categories')
                ->where('category_id', $category->id)
        );
    }

    /**
     * After a category is deleted.
     *
     * @param  int  $categoryId
     * @return void
     */
    public function afterDelete($categoryId)
    {
        /**
         * The category took its `product_categories` rows with it through the foreign key, so
         * which products it held can no longer be asked.
         */
        $this->flatIndexer->refreshDerivedColumns();
    }
}
