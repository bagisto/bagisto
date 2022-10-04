<?php

namespace Webkul\Velocity\Repositories;

use Webkul\Core\Eloquent\Repository;
use Illuminate\Container\Container;
use Prettus\Repository\Traits\CacheableRepository;

class CategoryRepository extends Repository
{
    use CacheableRepository;

    /**
     * Create a new repository instance.
     *
     * @param  \Webkul\Category\Repositories\CategoryRepository  $categoryRepository
     * @param  \Illuminate\Container\Container  $container
     * @return void
     */
    public function __construct(
        protected CategoryRepository $categoryRepository,
        Container $container
    )
    {
        parent::__construct($container);
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model(): string
    {
        return 'Webkul\Velocity\Contracts\Category';
    }

    /**
     * Return current channel categories
     *
     * @return array
     */
    public function getChannelCategories()
    {
        $results = [];

        $velocityCategories = $this->model->all(['category_id']);

        $categoryMenu = json_decode(json_encode($velocityCategories), true);

        $categories = $this->categoryRepository->getVisibleCategoryTree(core()->getCurrentChannel()->root_category_id);

        if (isset($categories->first()->id)) {
            foreach ($categories as $category) {

                if (
                    ! empty($categoryMenu)
                    && ! in_array($category->id, array_column($categoryMenu, 'category_id'))
                ) {
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