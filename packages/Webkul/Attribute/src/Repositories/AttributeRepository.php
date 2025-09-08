<?php

namespace Webkul\Attribute\Repositories;

use Illuminate\Container\Container;
use Webkul\Attribute\Contracts\Attribute;
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
     * @return \Webkul\Attribute\Contracts\Attribute
     */
    public function create(array $data)
    {
        $data = $this->validateUserInput($data);

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
     * @return \Webkul\Attribute\Contracts\Attribute
     */
    public function update(array $data, $id)
    {
        $data = $this->validateUserInput($data);

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
     * Validate user input.
     *
     * @param  array  $data
     * @return array
     */
    public function validateUserInput($data)
    {
        if (isset($data['is_configurable'])) {
            $data['value_per_channel'] = $data['value_per_locale'] = 0;
        }

        if (! in_array($data['type'], [
            AttributeTypeEnum::CHECKBOX->value,
            AttributeTypeEnum::SELECT->value,
            AttributeTypeEnum::MULTISELECT->value,
            AttributeTypeEnum::BOOLEAN->value,
        ])) {
            $data['is_filterable'] = 0;
        }

        if (in_array($data['type'], [
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
     * @return \Illuminate\Database\Eloquent\Collection
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
     * @param  \Webkul\Attribute\Contracts\AttributeFamily  $attributeFamily
     * @return \Webkul\Attribute\Contracts\Attribute
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
                    'id'      => $attribute->id,
                    'name'    => $attribute->admin_name,
                    'type'    => $attribute->type,
                    'code'    => $attribute->code,
                    'options' => $attribute->options,
                ]);
            }
        }

        return $trimmed;
    }
}
