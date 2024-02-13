<?php

namespace Webkul\Product\Repositories;

use Illuminate\Support\Facades\DB;
use Webkul\Attribute\Models\Attribute;
use Webkul\Core\Eloquent\Repository;
use Webkul\Product\Contracts\ProductFlat;

class ProductFlatRepository extends Repository
{
    /**
     * Specify model.
     */
    public function model(): string
    {
        return ProductFlat::class;
    }

    /**
     * Update `product_flat` custom column.
     */
    public function updateAttributeColumn(Attribute $attribute): mixed
    {
        return $this->getModel()
            ->leftJoin('product_attribute_values as v', function ($join) use ($attribute) {
                $join->on('product_flat.id', '=', 'v.product_id')
                    ->on('v.attribute_id', '=', DB::raw($attribute->id));
            })->update(['product_flat.'.$attribute->code => DB::raw($attribute->column_name)]);
    }
}
