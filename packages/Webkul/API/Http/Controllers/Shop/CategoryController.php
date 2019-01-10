<?php

namespace Webkul\API\Http\Controllers\Shop;

use Webkul\API\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Collection;
use Webkul\Category\Repositories\CategoryRepository as Category;

/**
 * Category controller for getting the categories
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CategoryController extends Controller
{
    /**
     * The category implementation.
     *
     * for shop bundle's navigation
     * menu
     */
    protected $category;

    /**
     * Category Repository DI.
     *
     * @param  $category Category Instance
     * @return void
     */
    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    public function get() {
        $categories = [];

        foreach ($this->category->getVisibleCategoryTree() as $category) {
            array_push($categories, collect($category));
        }

        return $categories;
    }

}