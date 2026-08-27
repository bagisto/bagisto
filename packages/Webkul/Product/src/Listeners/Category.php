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
         * can hold the whole catalog. Its descendants are taken along, as they read through the
         * category that moved or was renamed.
         */
        $this->flatIndexer->refreshDerivedColumns(
            fn ($query) => $query
                ->select('product_categories.product_id')
                ->from('product_categories')
                ->join('categories', 'categories.id', '=', 'product_categories.category_id')
                ->whereBetween('categories._lft', [$category->_lft, $category->_rgt])
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
