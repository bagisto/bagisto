<?php

namespace Webkul\Product\Repositories;

use Illuminate\Support\Facades\DB;
use Webkul\Core\Eloquent\Repository;
use Webkul\Attribute\Models\Attribute;
use Webkul\Product\Listeners\ProductFlat;

class ProductFlatRepository extends Repository
{
    /**
     * Specify model.
     *
     * @return string
     */
    public function model(): string
    {
        return 'Webkul\Product\Contracts\ProductFlat';
    }

    /**
     * Update `product_flat` custom column.
     *
     * @param  \Webkul\Attribute\Models\Attribute  $attribute
     * @return mixed
     */
    public function updateAttributeColumn(Attribute $attribute)
    {
        return $this->model
            ->leftJoin('product_attribute_values as v', function ($join) use ($attribute) {
                $join->on('product_flat.id', '=', 'v.product_id')
                    ->on('v.attribute_id', '=', DB::raw($attribute->id));
            })->update(['product_flat.' . $attribute->code => DB::raw($attribute->column_name)]);
    }
}
