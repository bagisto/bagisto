<?php

namespace Webkul\Product\Repositories;

use Illuminate\Support\Facades\Storage;
use Webkul\Core\Eloquent\Repository;

class ProductAttributeValueRepository extends Repository
{
    /**
     * Specify Model class name
     */
    public function model(): string
    {
        return 'Webkul\Product\Contracts\ProductAttributeValue';
    }

    /**
     * Save attribute values
     *
     * @param  array  $data
     * @param  \Webkul\Product\Contracts\Product  $product
     * @param  mixed  $attributes
     * @return void
     */
    public function saveValues($data, $product, $attributes)
    {
        $attributeValuesToInsert = [];

        foreach ($attributes as $attribute) {
            if ($attribute->type === 'boolean') {
                $data[$attribute->code] = ! empty($data[$attribute->code]);
            }

            if (in_array($attribute->type, ['multiselect', 'checkbox'])) {
                $data[$attribute->code] = implode(',', $data[$attribute->code] ?? []);
            }

            if (! isset($data[$attribute->code])) {
                continue;
            }

            if (
                $attribute->type === 'price'
                && empty($data[$attribute->code])
            ) {
                $data[$attribute->code] = null;
            }

            if (
                $attribute->type === 'date'
                && empty($data[$attribute->code])
            ) {
                $data[$attribute->code] = null;
            }

            if (in_array($attribute->type, ['image', 'file'])) {
                $data[$attribute->code] = gettype($data[$attribute->code]) === 'object'
                    ? request()->file($attribute->code)->store('product/'.$product->id)
                    : $data[$attribute->code];
            }

            $attributeValues = $product->attribute_values
                ->where('attribute_id', $attribute->id);

            $channel = $attribute->value_per_channel ? ($data['channel'] ?? core()->getDefaultChannelCode()) : null;

            $locale = $attribute->value_per_locale ? ($data['locale'] ?? core()->getDefaultLocaleCodeFromDefaultChannel()) : null;

            if ($attribute->value_per_channel) {
                if ($attribute->value_per_locale) {
                    $filteredAttributeValues = $attributeValues
                        ->where('channel', $channel)
                        ->where('locale', $locale);
                } else {
                    $filteredAttributeValues = $attributeValues
                        ->where('channel', $channel);
                }
            } else {
                if ($attribute->value_per_locale) {
                    $filteredAttributeValues = $attributeValues
                        ->where('locale', $locale);
                } else {
                    $filteredAttributeValues = $attributeValues;
                }
            }

            $attributeValue = $filteredAttributeValues->first();

            $uniqueId = implode('|', array_filter([
                $channel,
                $locale,
                $product->id,
                $attribute->id,
            ]));

            if (! $attributeValue) {
                $attributeValuesToInsert[] = array_merge($this->getAttributeTypeColumnValues($attribute, $data[$attribute->code]), [
                    'product_id'   => $product->id,
                    'attribute_id' => $attribute->id,
                    'channel'      => $channel,
                    'locale'       => $locale,
                    'unique_id'    => $uniqueId,
                ]);
            } else {
                $previousTextValue = $attributeValue->text_value;

                if (in_array($attribute->type, ['image', 'file'])) {
                    /**
                     * If $data[$attribute->code]['delete'] is not empty, that means someone selected the "delete" option.
                     */
                    if (! empty($data[$attribute->code]['delete'])) {
                        Storage::delete($previousTextValue);

                        $data[$attribute->code] = null;
                    }
                    /**
                     * If $data[$attribute->code] is not equal to the previous one, that means someone has
                     * updated the file or image. In that case, we will remove the previous file.
                     */
                    elseif (
                        ! empty($previousTextValue)
                        && $data[$attribute->code] != $previousTextValue
                    ) {
                        Storage::delete($previousTextValue);
                    }
                }

                $attributeValue = $this->update([
                    $attribute->column_name => $data[$attribute->code],
                    'unique_id'             => $uniqueId,
                ], $attributeValue->id);
            }
        }

        if (! empty($attributeValuesToInsert)) {
            $this->insert($attributeValuesToInsert);
        }
    }

    /**
     * @param  mixed  $attribute
     * @param  mixed  $value
     * @return array
     */
    public function getAttributeTypeColumnValues($attribute, $value)
    {
        $attributeTypeFields = array_fill_keys(array_values($attribute->attributeTypeFields), null);

        $attributeTypeFields[$attribute->column_name] = $value;

        return $attributeTypeFields;
    }

    /**
     * @param  string  $column
     * @param  int  $attributeId
     * @param  int  $productId
     * @param  string  $value
     * @return bool
     */
    public function isValueUnique($productId, $attributeId, $column, $value)
    {
        $count = $this->resetScope()
            ->model
            ->where($column, $value)
            ->where('attribute_id', '=', $attributeId)
            ->where('product_id', '!=', $productId)
            ->count('id');

        return ! $count;
    }
}
