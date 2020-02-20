<?php

namespace Webkul\Product\Helpers;

/**
 * Product View Helper
 *
 * @author Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class View extends AbstractProduct
{
    /**
     * Returns the visible custom attributes
     *
     * @param Webkul\Product\Models\Product $product
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

                    if ($attributeOption) {
                        $value = $attributeOption->label ?? null;

                        if (! $value) {
                            continue;
                        }
                    }
                } else if ($attribute->type == 'multiselect' || $attribute->type == 'checkbox') {
                    $lables = [];

                    $attributeOptions = $attributeOptionReposotory->findWhereIn('id', explode(",", $value));

                    foreach ($attributeOptions as $attributeOption) {
                        if ($label = $attributeOption->label) {
                            $lables[] = $label;
                        }
                    }

                    $value = implode(", ", $lables);
                }
            }

            $data[] = [
                'id'         => $attribute->id,
                'code'       => $attribute->code,
                'label'      => $attribute->name,
                'value'      => $value,
                'admin_name' => $attribute->admin_name,
                'type'       => $attribute->type,
            ];
        }

        return $data;
    }
}