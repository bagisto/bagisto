<?php

namespace Webkul\Product\Helpers;

use Webkul\Attribute\Repositories\AttributeRepository as Attribute;
use Webkul\Product\Models\ProductAttributeValue;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductFlat;

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
        static $price = [];

        if(array_key_exists($product->id, $price))
            return $price[$product->id];

        if ($product->type == 'configurable') {
            return $price[$product->id] = $this->getVariantMinPrice($product);
        } else {
            if ($this->haveSpecialPrice($product)) {
                return $price[$product->id] = $product->special_price;
            }

            return $price[$product->id] = $product->price;
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
        static $price = [];

        if (array_key_exists($product->id, $price))
            return $price[$product->id];

        if ($product instanceof ProductFlat) {
            $productId = $product->product_id;
        } else {
            $productId = $product->id;
        }

        $variantSpecialPrice = $variantRegularPrice = [];

        foreach ($product->variants as $productVariant) {
            if ($this->haveSpecialPrice($productVariant)) {
                $variantSpecialPrice[] = $this->getSpecialPrice($productVariant);
            }
            $variantRegularPrice[] = $productVariant->price;
        }

        if (count($variantSpecialPrice) > 1)
            if (min($variantSpecialPrice) < min($variantRegularPrice))
                return $price[$product->id] = min($variantSpecialPrice);

            return $price[$product->id] = min($variantRegularPrice);

        return $price[$product->id] = min($variantRegularPrice);
    }

    /**
     * Returns the product's minimal price
     *
     * @param Product $product
     * @return float
     */
    public function getSpecialPrice($product)
    {
        static $price = [];

        if(array_key_exists($product->id, $price))
            return $price[$product->id];

        if ($this->haveSpecialPrice($product)) {
            return $price[$product->id] = $product->special_price;
        } else {
            return $price[$product->id] = $product->price;
        }
    }

    /**
     * @param Product $product
     * @return boolean
     */
    public function haveSpecialPrice($product)
    {
        if (is_null($product->special_price) || ! (float) $product->special_price)
            return false;

        if (core()->isChannelDateInInterval($product->special_price_from, $product->special_price_to)) {
            return true;
        }

        return false;
    }
}