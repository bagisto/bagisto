<?php

namespace Webkul\Attribute\Repositories;

use Illuminate\Container\Container as App;
use Illuminate\Support\Facades\Event;
use Webkul\Attribute\Repositories\AttributeOptionRepository;
use Webkul\Core\Eloquent\Repository;

class AttributeRepository extends Repository
{
    /**
     * Attribute option repository instance.
     *
     * @var \Webkul\Attribute\Repositories\AttributeOptionRepository
     */
    protected $attributeOptionRepository;

    /**
     * Create a new repository instance.
     *
     * @param  \Webkul\Attribute\Repositories\AttributeOptionRepository  $attributeOptionRepository
     * @return void
     */
    public function __construct(
        AttributeOptionRepository $attributeOptionRepository,
        App $app
    ) {
        $this->attributeOptionRepository = $attributeOptionRepository;

        parent::__construct($app);
    }

    /**
     * Specify model class name.
     *
     * @return mixed
     */
    public function model()
    {
        return 'Webkul\Attribute\Contracts\Attribute';
    }

    /**
     * Create attribute.
     *
     * @param  array  $data
     * @return \Webkul\Attribute\Contracts\Attribute
     */
    public function create(array $data)
    {
        Event::dispatch('catalog.attribute.create.before');

        $data = $this->validateUserInput($data);

        $options = isset($data['options']) ? $data['options'] : [];

        unset($data['options']);

        $attribute = $this->model->create($data);

        if (in_array($attribute->type, ['select', 'multiselect', 'checkbox']) && count($options)) {
            foreach ($options as $optionInputs) {
                $this->attributeOptionRepository->create(array_merge([
                    'attribute_id' => $attribute->id,
                ], $optionInputs));
            }
        }

        Event::dispatch('catalog.attribute.create.after', $attribute);

        return $attribute;
    }

    /**
     * Update attribute.
     *
     * @param  array  $data
     * @param  int $id
     * @param  string  $attribute
     * @return \Webkul\Attribute\Contracts\Attribute
     */
    public function update(array $data, $id, $attribute = "id")
    {
        $data = $this->validateUserInput($data);

        $attribute = $this->find($id);

        Event::dispatch('catalog.attribute.update.before', $id);

        $data['enable_wysiwyg'] = ! isset($data['enable_wysiwyg']) ? 0 : 1;

        $attribute->update($data);

        if (in_array($attribute->type, ['select', 'multiselect', 'checkbox'])) {
            if (isset($data['options'])) {
                foreach ($data['options'] as $optionId => $optionInputs) {
                    $isNew = $optionInputs['isNew'] == 'true' ? true : false;

                    if ($isNew) {
                        $this->attributeOptionRepository->create(array_merge([
                            'attribute_id' => $attribute->id,
                        ], $optionInputs));
                    } else {
                        $isDelete = $optionInputs['isDelete'] == 'true' ? true : false;

                        if ($isDelete) {
                            $this->attributeOptionRepository->delete($optionId);
                        } else {
                            $this->attributeOptionRepository->update($optionInputs, $optionId);
                        }
                    }
                }
            }
        }

        Event::dispatch('catalog.attribute.update.after', $attribute);

        return $attribute;
    }

    /**
     * Delete attribute.
     *
     * @param  int  $id
     * @return void
     */
    public function delete($id)
    {
        Event::dispatch('catalog.attribute.delete.before', $id);

        parent::delete($id);

        Event::dispatch('catalog.attribute.delete.after', $id);
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
    public function getFilterAttributes()
    {
        return $this->model->where('is_filterable', 1)->with('options')->get();
    }

    /**
     * Get product default attributes.
     *
     * @param  array  $codes
     * @return array
     */
    public function getProductDefaultAttributes($codes = null)
    {
        $attributeColumns  = ['id', 'code', 'value_per_channel', 'value_per_locale', 'type', 'is_filterable'];

        if (! is_array($codes) && ! $codes)
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
                && ($attribute->type == 'select'
                    || $attribute->type == 'multiselect'
                    || $attribute->code == 'sku')
            ) {
                if ($attribute->options()->exists()) {
                    array_push($trimmed, [
                        'id'          => $attribute->id,
                        'name'        => $attribute->admin_name,
                        'type'        => $attribute->type,
                        'code'        => $attribute->code,
                        'has_options' => true,
                        'options'     => $attribute->options,
                    ]);
                } else {
                    array_push($trimmed, [
                        'id'          => $attribute->id,
                        'name'        => $attribute->admin_name,
                        'type'        => $attribute->type,
                        'code'        => $attribute->code,
                        'has_options' => false,
                        'options'     => null,
                    ]);
                }
            }
        }

        return $trimmed;
    }
}
