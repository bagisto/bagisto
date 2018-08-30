<?php

namespace Webkul\Product\Product;

use Illuminate\Support\Facades\DB;
use Webkul\Product\Repositories\ProductRepository as Product;
use Webkul\Attribute\Repositories\AttributeRepository as Attribute;

class Collection extends AbstractProduct
{
    /**
     * ProductRepository object
     *
     * @var array
     */
    protected $product;

    /**
     * AttributeRepository object
     *
     * @var array
     */
    protected $attribute;

    /**
     * array object
     *
     * @var array
     */
    protected $attributeToSelect = [
            'name',
            'description',
            'short_description',
            'price',
            'special_price',
            'special_price_from',
            'special_price_to'
        ];

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Product\Repositories\ProductRepository     $product
     * @param  Webkul\Attribute\Repositories\AttributeRepository $attribute
     * @return void
     */
    public function __construct(Product $product, Attribute $attribute)
    {
        $this->product = $product;

        $this->attribute = $attribute;
    }

    /**
     * @param array $attributes
     * @return Void
     */
    public function addAttributesToSelect($attributes)
    {
        $this->attributeToSelect = array_unique(
                array_merge($this->attributeToSelect, $attributes)
            );

        return $this;
    }

    /**
     * @param integer $categoryId
     * @return Collection
     */
    public function getCollection($categoryId = null)
    {
        $qb = $this->product->getModel()
                ->select('products.*')
                ->join('product_categories', 'products.id', '=', 'product_categories.product_id')
                ->where('product_categories.category_id', $categoryId);

        $this->addSelectAttributes($qb);
        
        // foreach (request()->input() as $code => $value) {
        //     $filterAlias = 'filter_' . $code;
                
        //     $qb->leftJoin('product_attribute_values as ' . $filterAlias, 'products.id', '=', $filterAlias . '.product_id');

        //     $qb->where($filterAlias . '.' . ProductAttributeValue::$attributeTypeFields[$attribute->type], $value);
        // }

        // if(0) {
        //     $qb->orderBy('id', 'desc');
        // }
        
        return $qb->paginate(9);
    }
}