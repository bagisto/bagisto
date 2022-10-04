<?php

namespace Webkul\Product\Repositories;

use Webkul\Core\Eloquent\Repository;

class ProductAttributeValueRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    function model(): string
    {
        return 'Webkul\Product\Contracts\ProductAttributeValue';
    }

    /**
     * @param  string  $column
     * @param  int  $attributeId
     * @param  int  $productId
     * @param  string  $value
     * @return boolean
     */
    public function isValueUnique($productId, $attributeId, $column, $value)
    {
        $count = $this->resetScope()
            ->model
            ->where($column, $value)
            ->where('attribute_id', '=', $attributeId)
            ->where('product_id', '!=', $productId)
            ->count('id');

        return ! $count;
    }
}