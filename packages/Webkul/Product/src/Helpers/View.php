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

        $attributes = $product->attribute_family->custom_attributes()->where('attributes.is_visible_on_front', 1)->get();

        $attributeOptionReposotory = app('Webkul\Attribute\Repositories\AttributeOptionRepository');

        foreach ($attributes as $attribute) {
            if ($attribute->type == 'boolean') {
                $value = $product->{$attribute->code} ? 'Yes' : 'No';

                $data[] = [
                    'code' => $attribute->code,
                    'label' => $attribute->name,
                    'value' => $value,
                    'admin_name' => $attribute->admin_name,
                ];
            } else if ($product->{$attribute->code}) {
                $value = $product->{$attribute->code};

                if ($attribute->type == 'select') {
                    $attributeOption = $attributeOptionReposotory->find($value);

                    if ($attributeOption) {
                        $value = $attributeOption->label;
                    }
                } else if ($attribute->type == 'multiselect') {
                    $optionIds = explode(",", $value);

                    if (count($optionIds)) {
                        $attributeOptions = $attributeOptionReposotory->findWhereIn('id', $optionIds);

                        foreach ($attributeOptions as $attributeOption) {
                            if ($attributeOption && $attributeOption->label) {
                                $result[] = $attributeOption->label;
                            }
                        }

                        $value = implode(", ", $result);
                    }
                }

                $data[] = [
                    'code' => $attribute->code,
                    'label' => $attribute->name,
                    'value' => $value,
                    'admin_name' => $attribute->admin_name,
                ];
            }
        }

        return $data;
    }
}