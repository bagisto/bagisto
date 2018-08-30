<?php

namespace Webkul\Product\Product;

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
            if($attribute->is_visible_on_front) {
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