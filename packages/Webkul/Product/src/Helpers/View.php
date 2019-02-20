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
            if ($attribute->type == 'boolean') {
                $value = $product->{$attribute->code};
                if ($attribute->is_visible_on_front ) {
                    if ($value == 1) {
                        $value = 'Yes';
                    } else {
                        $value = 'No';
                    }

                    $data[] = [
                        'code' => $attribute->code,
                        'label' => $attribute->name,
                        'value' => $value,
                        ];
                }
            } else if ($attribute->is_visible_on_front && $product->{$attribute->code}) {
                $value = $product->{$attribute->code};

                if ($attribute->type == 'select') {
                    $attributeOption = $attributeOptionReposotory->find($value);

                    if ($attributeOption) {
                        $value = $attributeOption->translate(app()->getLocale())->label;
                    }
                }

                if ($attribute->type == 'multiselect') {
                    $values = explode(",", $value);

                    $result = [];
                    foreach ($values as $value) {
                        $attributeOption = $attributeOptionReposotory->find($value);

                        if ($attributeOption) {
                            $value = $attributeOption->translate(app()->getLocale())->label;
                            $result[] = $value;
                        }
                    }

                    $value = implode(",", $result);
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