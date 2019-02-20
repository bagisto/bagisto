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

        $attributeOptionReposotory = app('Webkul\Attribute\Repositories\AttributeOptionRepository');

        foreach ($attributes as $attribute) {
            if ($attribute->is_visible_on_front && $product->{$attribute->code}) {
                $value = $product->{$attribute->code};

                if (($attribute->type == 'select') || ($attribute->type == 'multiselect')) {
                    $attributeOption = $attributeOptionReposotory->find($value);

                    if ($attributeOption) {
                        $value = $attributeOption->translate(app()->getLocale())->label;
                    }
                }

                $data[] = [
                    'code' => $attribute->code,
                    'label' => $attribute->name,
                    'value' => $value,
                    ];
            }
        }

        return $data;
    }
}