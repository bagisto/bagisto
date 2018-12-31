<?php

namespace Webkul\Product\Helpers;

class View extends AbstractProduct
{
    /**
     * Returns the visible custom attributes
     *
    * @param Product $product
     * @return integer
     */
    public function getAdditionalData($product)
    {
        $data = [];

        $attributes = $product->attribute_family->custom_attributes;

        foreach($attributes as $attribute) {
            if($attribute->is_visible_on_front && $product->{$attribute->code}) {
                $data[] = [
                    'code' => $attribute->code,
                    'label' => $attribute->name,
                    'value' => $product->{$attribute->code},
                ];
            }
        }

        return $data;
    }
}