<?php

namespace Webkul\Product\Repositories;

use Illuminate\Container\Container as App;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Core\Eloquent\Repository;
use Webkul\Product\Models\ProductAttributeValueProxy;

class ProductAttributeValueRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
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
        $result = $this->resetScope()->model->where($column, $value)->where('attribute_id', '=', $attributeId)->where('product_id', '!=', $productId)->get();

        return $result->count() ? false : true;
    }
}