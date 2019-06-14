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
        $finalPrice = [];

        if (array_key_exists($product->id, $price))
            return $price[$product->id];

        if ($product instanceof ProductFlat) {
            $productId = $product->product_id;
        } else {
            $productId = $product->id;
        }

        $qb = ProductFlat::join('products', 'product_flat.product_id', '=', 'products.id')
            ->where('products.parent_id', $productId);

        $result = $qb
            ->distinct()
            ->selectRaw('IF( product_flat.special_price_from IS NOT NULL
            AND product_flat.special_price_to IS NOT NULL , IF( NOW( ) >= product_flat.special_price_from
            AND NOW( ) <= product_flat.special_price_to, IF( product_flat.special_price IS NULL OR product_flat.special_price = 0 , product_flat.price, LEAST( product_flat.special_price, product_flat.price ) ) , product_flat.price ) , IF( product_flat.special_price_from IS NULL , IF( product_flat.special_price_to IS NULL , IF( product_flat.special_price IS NULL OR product_flat.special_price = 0 , product_flat.price, LEAST( product_flat.special_price, product_flat.price ) ) , IF( NOW( ) <= product_flat.special_price_to, IF( product_flat.special_price IS NULL OR product_flat.special_price = 0 , product_flat.price, LEAST( product_flat.special_price, product_flat.price ) ) , product_flat.price ) ) , IF( product_flat.special_price_to IS NULL , IF( NOW( ) >= product_flat.special_price_from, IF( product_flat.special_price IS NULL OR product_flat.special_price = 0 , product_flat.price, LEAST( product_flat.special_price, product_flat.price ) ) , product_flat.price ) , product_flat.price ) ) ) AS final_price')
            ->where('product_flat.channel', core()->getCurrentChannelCode())
            ->where('product_flat.locale', app()->getLocale())
            ->get();

        foreach ($result as $price) {
            $finalPrice[] = $price->final_price;
        }

        return $price[$product->id] = min($finalPrice);
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