<?php

namespace Webkul\Attribute\Repositories;

use Illuminate\Container\Container;
use Webkul\Attribute\Contracts\Attribute;
use Webkul\Core\Eloquent\Repository;

class AttributeRepository extends Repository
{
    /**
     * Create a new repository instance.
     *
     * @param  \Webkul\Attribute\Repositories\AttributeOptionRepository  $attributeOptionRepository
     * @param  \Illuminate\Container\Container  $container
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
     *
     * @return string
     */
    public function model(): string
    {
        return Attribute::class;
    }

    /**
     * Create attribute.
     *
     * @param  array  $data
     * @return \Webkul\Attribute\Contracts\Attribute
     */
    public function create(array $data)
    {
        $data = $this->validateUserInput($data);

        $options = $data['options'] ?? [];

        unset($data['options']);

        $attribute = $this->model->create($data);

        if (in_array($attribute->type, ['select', 'multiselect', 'checkbox'])) {
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
     * @param  array  $data
     * @param  int  $id
     * @param  string  $attribute
     * @return \Webkul\Attribute\Contracts\Attribute
     */
    public function update(array $data, $id, $attribute = 'id')
    {
        $data = $this->validateUserInput($data);

        $attribute = $this->find($id);

        $data['enable_wysiwyg'] = isset($data['enable_wysiwyg']);

        $attribute->update($data);

        if (! in_array($attribute->type, ['select', 'multiselect', 'checkbox'])) {
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
        if ($data['is_configurable']) {
            $data['value_per_channel'] = $data['value_per_locale'] = 0;
        }

        if (! in_array($data['type'], ['select', 'multiselect', 'price', 'checkbox'])) {
            $data['is_filterable'] = 0;
        }

        if (in_array($data['type'], ['select', 'multiselect', 'boolean'])) {
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
        return $this->model->with(['options', 'options.translations'])->where('is_filterable', 1)->get();
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
     * Get attribute by code.
     *
     * @param  string  $code
     * @return \Webkul\Attribute\Contracts\Attribute
     */
    public function getAttributeByCode($code)
    {
        static $attributes = [];

        if (array_key_exists($code, $attributes)) {
            return $attributes[$code];
        }

        return $attributes[$code] = $this->findOneByField('code', $code);
    }

    /**
     * Get attribute by id.
     *
     * @param  int  $id
     * @return \Webkul\Attribute\Contracts\Attribute
     */
    public function getAttributeById($id)
    {
        static $attributes = [];

        if (array_key_exists($id, $attributes)) {
            return $attributes[$id];
        }

        return $attributes[$id] = $this->find($id);
    }

    /**
     * Get family attributes.
     *
     * @param  \Webkul\Attribute\Contracts\AttributeFamily  $attributeFamily
     * @return \Webkul\Attribute\Contracts\Attribute
     */
    public function getFamilyAttributes($attributeFamily)
    {
        static $attributes = [];

        if (array_key_exists($attributeFamily->id, $attributes)) {
            return $attributes[$attributeFamily->id];
        }

        return $attributes[$attributeFamily->id] = $attributeFamily->custom_attributes;
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

        foreach ($attributes as $key => $attribute) {
            if (
                $attribute->code != 'tax_category_id'
                && (
                    in_array($attribute->type, ['select', 'multiselect'])
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
