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

        $attributes = $product->product->attribute_family->custom_attributes()->where('attributes.is_visible_on_front', 1)->get();

        foreach ($attributes as $attribute) {
            $value = $product->{$attribute->code};

            if ($attribute->type == 'boolean') {
                $value = $value ? 'Yes' : 'No';
            } else if ($attribute->type == 'select' || $attribute->type == 'multiselect') {
                $value = $product->{$attribute->code . '_label'};
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