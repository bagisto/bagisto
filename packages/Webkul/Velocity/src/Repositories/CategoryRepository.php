<?php

namespace Webkul\Velocity\Repositories;

use Illuminate\Container\Container as App;
use Webkul\Core\Eloquent\Repository;
use Webkul\Category\Repositories\CategoryRepository as Category;

/**
 * Category Reposotory
 *
 * @author    Vivek Sharma <viveksh047@webkul.com>
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CategoryRepository extends Repository
{   
   /**
    * Category Repository object
    *
    * @var array
    */
    protected $category;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Category\Repositories\CategoryRepository $category
     * @return void
     */
    public function __construct(
        Category $category,
        App $app
        )
    {
        $this->category = $category;

        parent::__construct($app);
    }

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Velocity\Models\Category';
    }

    public function getChannelCategories()
    {
        $results = [];

        $velocityCategories = $this->model->all(['category_id']);

        $categoryMenues = json_decode(json_encode($velocityCategories), true);
        
        $categories = $this->category->getVisibleCategoryTree(core()->getCurrentChannel()->root_category_id);

        if (isset($categories->first()->id)) {
            foreach ($categories as $category) {
                
                if (! empty($categoryMenues) && !in_array($category->id, array_column($categoryMenues, 'category_id'))) {
                    $results[] = [
                        'id'   => $category->id,
                        'name' => $category->name,
                    ];
                }
            }
        }
        return $results;
    }
}