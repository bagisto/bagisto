<?php

namespace Webkul\Product\Product;

use Illuminate\Support\Facades\DB;
use Webkul\Product\Repositories\ProductRepository as Product;
use Webkul\Attribute\Repositories\AttributeRepository as Attribute;
use Webkul\Product\Models\ProductAttributeValue;

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
    protected $attributesToSelect = [
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
        $this->attributesToSelect = $attributes;
    }

    /**
     * @param integer $categoryId
     * @return Collection
     */
    public function getProductCollection($categoryId = null, $attributeToSelect = '*')
    {
        $qb = $this->product->getModel()
                ->select('products.*')
                ->join('product_categories', 'products.id', '=', 'product_categories.product_id')
                ->where('product_categories.category_id', $categoryId);

        foreach ($this->attributesToSelect as $code) {
            $attribute = $this->attribute->findBy('code', $code);
            
            $productValueAlias = 'pav_' . $attribute->code;

            $qb->leftJoin('product_attribute_values as ' . $productValueAlias, function($leftJoin) use($attribute, $productValueAlias) {

                $leftJoin->on('products.id', $productValueAlias . '.product_id');

                $leftJoin = $this->applyChannelLocaleFilter($attribute, $leftJoin, $productValueAlias)->where($productValueAlias . '.attribute_id', $attribute->id);
            });

            $qb->addSelect($productValueAlias . '.' . ProductAttributeValue::$attributeTypeFields[$attribute->type] . ' as ' . $code);
        }
        
        foreach (request()->input() as $code => $value) {
            $filterAlias = 'filter_' . $code;
                
            $qb->leftJoin('product_attribute_values as ' . $filterAlias, 'products.id', '=', $filterAlias . '.product_id');

            $qb->where($filterAlias . '.' . ProductAttributeValue::$attributeTypeFields[$attribute->type], $value);
        }

        // if(0) {
        //     $qb->orderBy('id', 'desc');
        // }
        
        return $qb->paginate(9);
    }
}