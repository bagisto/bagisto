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
            if ($product instanceof \Webkul\Product\Models\ProductFlat) {
                $value = $product->product->{$attribute->code};
            } else {
                $value = $product->{$attribute->code};
            }

            if ($attribute->type == 'boolean') {
                $value = $value ? 'Yes' : 'No';
            } else if($value) {
                if ($attribute->type == 'select') {
                    $attributeOption = $attributeOptionReposotory->find($value);
                    if ($attributeOption)
                        $value = $attributeOption->label ?? $attributeOption->admin_name;
                } else if ($attribute->type == 'multiselect') {
                    $lables = [];

                    $attributeOptions = $attributeOptionReposotory->findWhereIn('id', explode(",", $value));

                    foreach ($attributeOptions as $attributeOption) {
                        $lables[] = $attributeOption->label ?? $attributeOption->admin_name;
                    }

                    $value = implode(", ", $lables);
                }
            }

            $data[] = [
                'id' => $attribute->id,
                'code' => $attribute->code,
                'label' => $attribute->name,
                'value' => $value,
                'admin_name' => $attribute->admin_name,
                'type' => $attribute->type,
            ];
        }

        return $data;
    }
}