<?php

namespace Webkul\Discount\Helpers;

use Webkul\Product\Repositories\ProductRepository as Product;
use Webkul\Product\Repositories\ProductFlatRepository as ProductFlat;
use Webkul\Attribute\Repositories\AttributeRepository as Attribute;
use Webkul\Core\Repositories\LocaleRepository as Locale;

class FindProducts
{
    /**
     * To hold the product repository instance
     */
    protected $product;

    /**
     * To hold the attribute repository instance
     */
    protected $attribute;

    public function __construct(Product $product, Attribute $attribute, ProductFlat $productFlat, Locale $locale)
    {
        $this->product = $product;

        $this->attribute = $attribute;

        $this->productFlat = $productFlat;

        $this->locale = $locale;
    }

    public function findByConditions($conditions)
    {
        /**
         * Empty collection instance to hold all the products that satisfy the conditions
         */
        $products = array();

        foreach ($conditions as $condition) {
            $attribute = $this->attribute->findOneByField('code', $condition->attribute);
            dump($attribute->type);
            if ($condition->type == 'select' || $condition->type == 'multiselect') {
                $values = $condition->value;
                $attributeValues = array();

                foreach ($condition->options as $option) {
                    foreach ($values as $value) {
                        if ($value == $option->id) {
                            array_push($attributeValues, $option);
                        }
                    }
                }

                $defaultChannelCode = core()->getDefaultChannel()->code;
                $defaultLocaleCode = $this->locale->find(core()->getDefaultChannel()->default_locale_id)->code;

                foreach ($attributeValues as $attributeValue) {
                    $productFound = $this->productFlat->findByField([$attribute->code => $attributeValue->id, 'locale' => $defaultLocaleCode, 'channel' => $defaultChannelCode]);

                    if ($productFound->count()) {
                        array_push($products, $productFound->toArray());
                    }
                }
            } else {
            }
        }

        dd(array_flatten($products, 1));
    }
}