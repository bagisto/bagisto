<?php

namespace Webkul\Category\Listeners;

class Category
{
    /**
     * After create.
     *
     * @param  \Webkul\Category\Models\Category  $category
     * @return void
     */
    public function afterCreate($category)
    {
        $category->updateFullSlug();
    }

    /**
     * After update.
     *
     * @param  \Webkul\Category\Models\Category  $category
     * @return void
     */
    public function afterUpdate($category)
    {
        $category->updateFullSlug();
    }
}
