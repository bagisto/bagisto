<?php

namespace Webkul\Shop\Http\ViewComposers\Categories;

use Illuminate\View\View;
use Illuminate\Support\Collection;

use Webkul\Category\Repositories\CategoryRepository as Category;

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
        dd(count($collected_cat[0]['children']));
        $view->with('categories', $collected_cat);
    }
}
