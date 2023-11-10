<?php

namespace Webkul\Marketing\Listeners;

use Webkul\CMS\Repositories\CategoryRepository;

class Category
{
    /**
     * Create a new listener instance.
     *
     * @return void
     */
    public function __construct(protected CategoryRepository $categoryRepository)
    {
    }

    /**
     * Before category is created
     *
     * @return void
     */
    public function beforeCreate()
    {
    }

    /**
     * After category is updated
     *
     * @param  \Webkul\Category\Contracts\Category  $category
     * @return void
     */
    public function afterUpdate($category)
    {
    }

    /**
     * Before category is deleted
     *
     * @param  int  $id
     * @return void
     */
    public function beforeDelete($category)
    {
    }
}
