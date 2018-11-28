<?php

namespace Webkul\Shop\Http\ViewComposers;

use Illuminate\View\View;
use Illuminate\Support\Collection;
use Webkul\Category\Repositories\CategoryRepository as Category;


/**
 * Category List Composer on Navigation Menu
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CategoryComposer
{
    /**
     * The category implementation.
     *
     * for shop bundle's navigation
     * menu
     */
    protected $category;

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $categories = [];

        foreach ($this->category->getVisibleCategoryTree() as $category) {
            if($category->slug)
                array_push($categories, $category);
        }

        $view->with('categories', $categories);
    }
}
