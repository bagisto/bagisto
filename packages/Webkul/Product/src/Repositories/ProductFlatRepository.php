<?php

namespace Webkul\Product\Repositories;

use Illuminate\Container\Container;
use Webkul\Core\Eloquent\Repository;
use Webkul\Attribute\Repositories\AttributeRepository;

class ProductFlatRepository extends Repository
{
    /**
     * Create a new repository instance.
     *
     * @param  \Webkul\Attribute\Repositories\AttributeRepository  $attributeRepository
     * @param  \Illuminate\Container\Container  $container
     * @return void
     */
    public function __construct(
        protected AttributeRepository $attributeRepository,
        Container $container
    )
    {
        parent::__construct($container);
    }

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
     * @param  \Webkul\Attribute\Models\Attribute $attribute
     * @param  \Webkul\Product\Listeners\ProductFlat $listener
     * @return object
     */
    public function updateAttributeColumn(
        \Webkul\Attribute\Models\Attribute $attribute,
        \Webkul\Product\Listeners\ProductFlat $listener
    ) {
        return $this->model
            ->leftJoin('product_attribute_values as v', function ($join) use ($attribute) {
                $join->on('product_flat.id', '=', 'v.product_id')
                    ->on('v.attribute_id', '=', \DB::raw($attribute->id));
            })->update(['product_flat.' . $attribute->code => \DB::raw($listener->attributeTypeFields[$attribute->type] . '_value')]);
    }

    /**
     * Maximum price of category product.
     *
     * @param  \Webkul\Category\Contracts\Category  $category
     * @return float
     */
    public function getCategoryProductMaximumPrice($category = null)
    {
        static $loadedCategoryMaxPrice = [];

        if (! $category) {
            return $this->model->max('max_price');
        }

        if (array_key_exists($category->id, $loadedCategoryMaxPrice)) {
            return $loadedCategoryMaxPrice[$category->id];
        }

        return $loadedCategoryMaxPrice[$category->id] = $this->model
            ->leftJoin('product_categories', 'product_flat.product_id', 'product_categories.product_id')
            ->where('product_categories.category_id', $category->id)
            ->max('max_price');
    }

    /**
     * Handle category product max price.
     *
     * @param  \Webkul\Category\Contracts\Category  $category
     * @return float
     */
    public function handleCategoryProductMaximumPrice($category)
    {
        $maxPrice = 0;

        if (isset($category)) {
            $maxPrice = core()->convertPrice($this->getCategoryProductMaximumPrice($category));
        }

        return $maxPrice;
    }
}
