<?php

namespace Webkul\Attribute\Repositories;

use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Collection;
use Webkul\Attribute\Contracts\Attribute;
use Webkul\Attribute\Contracts\AttributeFamily;
use Webkul\Attribute\Enums\AttributeTypeEnum;
use Webkul\Core\Eloquent\Repository;

class AttributeRepository extends Repository
{
    /**
     * Attributes.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Create a new repository instance.
     *
     * @return void
     */
    public function __construct(
        protected AttributeOptionRepository $attributeOptionRepository,
        Container $container
    ) {
        parent::__construct($container);
    }

    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return Attribute::class;
    }

    /**
     * Create attribute.
     *
     * @return Attribute
     */
    public function create(array $data)
    {
        $data = $this->applyAttributeTypeRules($data);

        $options = $data['options'] ?? [];

        unset($data['options']);

        $attribute = $this->model->create($data);

        if (in_array($attribute->type, [
            AttributeTypeEnum::CHECKBOX->value,
            AttributeTypeEnum::SELECT->value,
            AttributeTypeEnum::MULTISELECT->value,
        ])) {
            foreach ($options as $optionInputs) {
                $this->attributeOptionRepository->create(array_merge([
                    'attribute_id' => $attribute->id,
                ], $optionInputs));
            }
        }

        return $attribute;
    }

    /**
     * Update attribute.
     *
     * @param  int  $id
     * @param  string  $attribute
     * @return Attribute
     */
    public function update(array $data, $id)
    {
        $data = $this->applyAttributeTypeRules($data);

        $attribute = $this->find($id);

        $attribute->update($data);

        if (! in_array($attribute->type, [
            AttributeTypeEnum::CHECKBOX->value,
            AttributeTypeEnum::SELECT->value,
            AttributeTypeEnum::MULTISELECT->value,
        ])) {
            return $attribute;
        }

        if (! isset($data['options'])) {
            return $attribute;
        }

        foreach ($data['options'] as $optionId => $optionInputs) {
            $isNew = $optionInputs['isNew'] == 'true';

            if ($isNew) {
                $this->attributeOptionRepository->create(array_merge([
                    'attribute_id' => $attribute->id,
                ], $optionInputs));
            } else {
                $isDelete = $optionInputs['isDelete'] == 'true';

                if ($isDelete) {
                    $this->attributeOptionRepository->delete($optionId);
                } else {
                    $this->attributeOptionRepository->update($optionInputs, $optionId);
                }
            }
        }

        return $attribute;
    }

    /**
     * Apply attribute type rules to the given data.
     *
     * Configurable attributes cannot vary per channel or locale. Attribute
     * types that don't support filtering are forced to non-filterable.
     * Option-based types don't use per-locale values.
     *
     * @param  array  $data
     * @return array
     */
    public function applyAttributeTypeRules($data)
    {
        if (! empty($data['is_configurable'])) {
            $data['value_per_channel'] = false;
            $data['value_per_locale'] = false;
        }

        if (! in_array($data['type'], [
            AttributeTypeEnum::PRICE->value,
            AttributeTypeEnum::CHECKBOX->value,
            AttributeTypeEnum::SELECT->value,
            AttributeTypeEnum::MULTISELECT->value,
            AttributeTypeEnum::BOOLEAN->value,
        ])) {
            $data['is_filterable'] = false;
        }

        if (in_array($data['type'], [
            AttributeTypeEnum::CHECKBOX->value,
            AttributeTypeEnum::SELECT->value,
            AttributeTypeEnum::MULTISELECT->value,
            AttributeTypeEnum::BOOLEAN->value,
        ])) {
            unset($data['value_per_locale']);
        }

        return $data;
    }

    /**
     * Get filter attributes.
     *
     * @return array
     */
    public function getFilterableAttributes()
    {
        return $this->model->where('is_filterable', 1)->get();
    }

    /**
     * Get product default attributes.
     *
     * @param  array  $codes
     * @return Collection
     */
    public function getProductDefaultAttributes($codes = null)
    {
        $attributeColumns = [
            'id',
            'code',
            'value_per_channel',
            'value_per_locale',
            'type',
            'is_filterable',
            'is_configurable',
        ];

        if (
            ! is_array($codes)
            && ! $codes
        ) {
            return $this->findWhereIn('code', [
                'name',
                'description',
                'short_description',
                'url_key',
                'price',
                'special_price',
                'special_price_from',
                'special_price_to',
                'status',
            ], $attributeColumns);
        }

        if (in_array('*', $codes)) {
            return $this->all($attributeColumns);
        }

        return $this->findWhereIn('code', $codes, $attributeColumns);
    }

    /**
     * Get family attributes.
     *
     * @param  AttributeFamily  $attributeFamily
     * @return Attribute
     */
    public function getFamilyAttributes($attributeFamily)
    {
        if (array_key_exists($attributeFamily->id, $this->attributes)) {
            return $this->attributes[$attributeFamily->id];
        }

        return $this->attributes[$attributeFamily->id] = $attributeFamily->custom_attributes;
    }

    /**
     * Get partials.
     *
     * @return array
     */
    public function getPartial()
    {
        $attributes = $this->model->all();

        $trimmed = [];

        foreach ($attributes as $attribute) {
            if (
                $attribute->code != 'tax_category_id'
                && (
                    in_array($attribute->type, [
                        AttributeTypeEnum::SELECT->value,
                        AttributeTypeEnum::MULTISELECT->value,
                    ])
                    || $attribute->code == 'sku'
                )
            ) {
                array_push($trimmed, [
                    'id' => $attribute->id,
                    'name' => $attribute->admin_name,
                    'type' => $attribute->type,
                    'code' => $attribute->code,
                    'options' => $attribute->options,
                ]);
            }
        }

        return $trimmed;
    }
}
