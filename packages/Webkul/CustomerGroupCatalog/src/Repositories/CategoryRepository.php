<?php

namespace Webkul\CustomerGroupCatalog\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Category\Repositories\CategoryRepository as BaseCategoryRepository;

/**
 * Category Reposotory
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CategoryRepository extends BaseCategoryRepository
{
    /**
     * Search Category
     *
     * @return Collection
     */
    public function search($term)
    {
        return $this->getModel()->join('category_translations', function ($join) {
                $join->on('categories.id', 'category_translations.category_id')
                    ->where('category_translations.locale', app()->getLocale())
                    ->where('category_translations.name', 'like', '%' . urldecode(request()->input('query')) . '%');
            })
            ->select('categories.*')
            ->groupBy('categories.id')
            ->get();
    }

    /**
     * get visible category tree
     *
     * @param integer $id
     * @return mixed
     */
    public function getVisibleCategoryTree($id = null)
    {
        static $categories = [];

        if(array_key_exists($id, $categories))
            return $categories[$id];

        $customer = auth()->guard(request()->has('token') ? 'api' : 'customer')->user();
    
        $categoryIds = [];

        if (! $customer) {
            $categoryIds = app('Webkul\CustomerGroupCatalog\Repositories\CustomerGroupRepository')->findOneByField('code', 'guest')->categories()->pluck('id');
        } else {
            if ($customer->group) {
                $categoryIds = app('Webkul\CustomerGroupCatalog\Repositories\CustomerGroupRepository')->find($customer->group->id)->categories()->pluck('id');
            }
        }

        if (count($categoryIds)) {
            $categories[$id] = $id
                    ? $this->model::orderBy('position', 'ASC')->where('status', 1)->whereIn('id', $categoryIds)->descendantsOf($id)->toTree()
                    : $this->model::orderBy('position', 'ASC')->where('status', 1)->whereIn('id', $categoryIds)->get()->toTree();
        } else {
            $categories[$id] = $id
                    ? $this->model::orderBy('position', 'ASC')->where('status', 1)->descendantsOf($id)->toTree()
                    : $this->model::orderBy('position', 'ASC')->where('status', 1)->get()->toTree();
        }

        return $categories[$id];
    }
}