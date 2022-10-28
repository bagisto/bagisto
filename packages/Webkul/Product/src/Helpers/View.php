<?php

namespace Webkul\Product\Helpers;

use Webkul\Attribute\Repositories\AttributeOptionRepository;

class View
{
    /**
     * Returns the visible custom attributes
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return void|array
     */
    public function getAdditionalData($product)
    {
        $data = [];

        $attributes = $product->attribute_family->custom_attributes()->where('attributes.is_visible_on_front', 1)->get();

        $attributeOptionRepository = app(AttributeOptionRepository::class);

        foreach ($attributes as $attribute) {
            $value = $product->{$attribute->code};

            if ($attribute->type == 'boolean') {
                $value = $value ? 'Yes' : 'No';
            } elseif($value) {
                if ($attribute->type == 'select') {
                    $attributeOption = $attributeOptionRepository->find($value);

                    if ($attributeOption) {
                        $value = $attributeOption->label ?? null;

                        if (! $value) {
                            continue;
                        }
                    }
                } elseif (
                    $attribute->type == 'multiselect'
                    || $attribute->type == 'checkbox'
                ) {
                    $labels = [];

                    $attributeOptions = $attributeOptionRepository->findWhereIn('id', explode(",", $value));

                    foreach ($attributeOptions as $attributeOption) {
                        if ($label = $attributeOption->label) {
                            $labels[] = $label;
                        }
                    }

                    $value = implode(", ", $labels);
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