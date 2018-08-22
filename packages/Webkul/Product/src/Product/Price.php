<?php

namespace Webkul\Product\Product;

use Webkul\Attribute\Repositories\AttributeRepository as Attribute;
use Webkul\Product\Models\ProductAttributeValue;
use Webkul\Product\Models\Product;

class Price extends AbstractProduct
{
    /**
     * AttributeRepository object
     *
     * @var array
     */
    protected $attribute;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Attribute\Repositories\AttributeRepository $attribute
     * @return void
     */
    public function __construct(Attribute $attribute)
    {
        $this->attribute = $attribute;
    }

    /**
     * Returns the product's minimal price
     *
     * @param Product $product
     * @return float
     */
    public function getMinimalPrice($product)
    {
        if($product->type == 'configurable') {
            return $this->getVariantMinPrice($product);
        } else {
            if($this->haveSpecialPrice($product)) {
                return $product->special_price;
            } else {
                return $product->price;
            }
        }
    }

    /**
     * Returns the product's minimal price
     *
     * @param Product $product
     * @return float
     */
    public function getVariantMinPrice($product)
    {
        static $attribute;

        if(!$attribute)
            $attribute = $this->attribute->findOneByField('code', 'price');

        $qb = ProductAttributeValue::join('products', 'product_attribute_values.product_id', '=', 'products.id')
            ->join('attributes', 'product_attribute_values.attribute_id', '=', 'attributes.id')
            ->where('products.parent_id', $product->id)
            ->where('attributes.code', 'price')
            ->addSelect('product_attribute_values.*');

        $this->applyChannelLocaleFilter($attribute, $qb);

        return $qb->min('product_attribute_values.' . ProductAttributeValue::$attributeTypeFields['price']);
    }

    /**
     * Returns the product's minimal price
     *
     * @param Product $product
     * @return float
     */
    public function getSpecialPrice($product)
    {
        if($this->haveSpecialPrice($product)) {
                return $product->special_price;
        } else {
            return $product->price;
        }
    }

    /**
     * @param Product $product
     * @return boolean
     */
    public function haveSpecialPrice($product)
    {
        if(is_null($product->special_price) || !$product->special_price)
            return false;

        if (core()->isChannelDateInInterval($product->special_price_from, $product->special_price_to)) {
            return true;
        }

        return false;
    }
}