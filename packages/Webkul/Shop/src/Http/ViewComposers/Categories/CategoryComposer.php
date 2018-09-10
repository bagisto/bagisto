<?php

namespace Webkul\Shop\Http\ViewComposers\Categories;

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

    public function compose(View $view)
    {
        $collected_cat = array();
        $categories = $this->category->getCategoryTree();
        foreach ($categories as $category) {
            array_push($collected_cat, collect($category));
        }
        $view->with('categories', $collected_cat);
    }
}
