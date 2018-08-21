<?php

namespace Webkul\Shop\Product;

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
     * @param integer $categoryId
     * @return Collection
     */
    public function getProductCollection($categoryId = null, $attributeToSelect = '*')
    {
        $qb = $this->product->getModel()
                ->select('products.*')
                ->join('product_categories', 'products.id', '=', 'product_categories.product_id')
                ->where('product_categories.category_id', $categoryId);

        $channel = core()->getCurrentChannelCode();
        $locale = app()->getLocale();

        foreach (['name', 'description', 'short_description', 'price', 'special_price', 'special_price_from', 'special_price_to'] as $code) {
            $attribute = $this->attribute->findBy('code', $code);
            
            $productValueAlias = 'pav_' . $attribute->code;

            $qb->leftJoin('product_attribute_values as ' . $productValueAlias, function($leftJoin) use($channel, $locale, $attribute, $productValueAlias) {

                $leftJoin->on('products.id', $productValueAlias . '.product_id');

                if($attribute->value_per_channel) {
                    if($attribute->value_per_locale) {
                        $leftJoin->where($productValueAlias . '.channel', $channel)
                            ->where($productValueAlias . '.locale', $locale);
                    } else {
                        $leftJoin->where($productValueAlias . '.channel', $channel);
                    }
                } else {
                    if($attribute->value_per_locale) {
                        $leftJoin->where($productValueAlias . '.locale', $locale);
                    }
                }

                $leftJoin->where($productValueAlias . '.attribute_id', $attribute->id);
            });


            $qb->addSelect($productValueAlias . '.' . ProductAttributeValue::$attributeTypeFields[$attribute->type] . ' as ' . $code);


            // if($code == 'name') {
            //     $filterAlias = 'filter_' . $attribute->code;
                
            //     $qb->leftJoin('product_attribute_values as ' . $filterAlias, 'products.id', '=', $filterAlias . '.product_id');
            //     $qb->where($filterAlias . '.' . ProductAttributeValue::$attributeTypeFields[$attribute->type], 'Product Name');
            // }
        }

        // if(0) {
        //     $qb->orderBy('id', 'desc');
        // }
        
        return $qb->paginate(9);
    }
}