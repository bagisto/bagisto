<?php

namespace Webkul\Shop\Product;

class Price extends AbstractProduct
{
    /**
     * Returns the product's minimal price
     *
     * @return float
     */
    public function getMinimalPrice($product)
    {
        // return 0;
        if($product->type == 'configurable') {
            return $product->variants->min('price');
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