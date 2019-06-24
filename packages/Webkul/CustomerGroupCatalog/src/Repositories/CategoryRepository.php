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

        if (array_key_exists($id, $categories))
            return $categories[$id];

        $customer = auth()->guard(request()->has('token') ? 'api' : 'customer')->user();

        $categoryIds = [];
        $showCategories = [];
        $parentCategoryIds = [];

        if (! $customer) {
            $categoryIds = app('Webkul\CustomerGroupCatalog\Repositories\CustomerGroupRepository')->findOneByField('code', 'guest')->categories()->pluck('id');
        } else {
            if ($customer->group) {
                $categories = app('Webkul\CustomerGroupCatalog\Repositories\CustomerGroupRepository')->find($customer->group->id)->categories()->get();

                $categoryIds = app('Webkul\CustomerGroupCatalog\Repositories\CustomerGroupRepository')->find($customer->group->id)->categories()->pluck('id')->toArray();

                foreach ($categories as $category) {
                    $parentCategoryIds[] = $category->id;
                    $parentCategory = $this->getParentCategory($category->parent_id);

                    $result = array_merge($parentCategoryIds, $parentCategory);
                    $count = 0;
                    foreach($result as $cat) {
                        if (in_array($cat, $categoryIds)) {
                            $count++;
                        }
                    }

                    if (count($result) == $count) {
                        $showCategories[] = $category->id;
                    }
                }
            }
        }

        if (count($showCategories)) {
            $categories[$id] = $id
                    ? $this->model::orderBy('position', 'ASC')->where('status', 1)->whereIn('id', $categoryIds)->descendantsOf($id)->toTree()
                    : $this->model::orderBy('position', 'ASC')->where('status', 1)->whereIn('id', $categoryIds)->get()->toTree();

            return $categories[$id];
        } else {
            // $categories[$id] = $id
            //         ? $this->model::orderBy('position', 'ASC')->where('status', 1)->descendantsOf($id)->toTree()
            //         : $this->model::orderBy('position', 'ASC')->where('status', 1)->get()->toTree();

            return [];
        }
    }

    /**
     * get parent category
     *
     * @param integer $id
     * @return array
     */
    public function getParentCategory($parentId) {
        $parentCategories = [];
        $parentCategory = $this->getModel()->where('id', $parentId)->first();

        if ($parentCategory->parent_id != null) {
            $parentCategories[] = $parentCategory->id;
            $this->getParentCategory($parentCategory->parent_id);
        }

        return $parentCategories;
    }
}
