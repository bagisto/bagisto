<?php

namespace Webkul\CustomerGroupCatalog\Repositories;

use Webkul\Core\Eloquent\Repository;

/**
 * CustomerGroup Reposotory
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CustomerGroupRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\CustomerGroupCatalog\Models\CustomerGroup';
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        $group = parent::create($data);

        if (isset($data['products'])) {
            $group->products()->sync($data['products']);
        } else {
            $group->products()->sync([]);
        }

        if (isset($data['categories'])) {
            $group->categories()->sync($data['categories']);
        } else {
            $group->categories()->sync([]);
        }

        return $group;
    }

    /**
     * @param array $data
     * @param $id
     * @param string $attribute
     * @return mixed
     */
    public function update(array $data, $id, $attribute = "id")
    {
        parent::update($data, $id, $attribute);

        $group = $this->find($id);

        if (isset($data['products'])) {
            $group->products()->sync($data['products']);
        } else {
            $group->products()->sync([]);
        }

        if (isset($data['categories'])) {
            $group->categories()->sync($data['categories']);
        } else {
            $group->categories()->sync([]);
        }

        return ;
    }

    /**
     * @return Collection
     */
    public function getProducts($group)
    {
        $products = [];

        foreach ($this->find($group->id)->products as $product) {
            $products[] = ['id' => $product->id, 'name' => $product->name];
        }

        return $products;
    }

    /**
     * @return Collection
     */
    public function getCategories($group)
    {
        $categories = [];

        foreach ($this->find($group->id)->categories as $category) {
            $categories[] = ['id' => $category->id, 'name' => $category->name];
        }

        return $categories;
    }
}