<?php

namespace Webkul\Attribute\Repositories;

use Webkul\Core\Eloquent\Repository;
use Illuminate\Support\Facades\Event;
use Webkul\Attribute\Repositories\AttributeOptionRepository;
use Illuminate\Container\Container as App;

/**
 * Attribute Reposotory
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class AttributeRepository extends Repository
{
    /**
     * AttributeOptionRepository object
     *
     * @var Object
     */
    protected $attributeOptionRepository;

    /**
     * Create a new repository instance.
     *
     * @param  Webkul\Attribute\Repositories\AttributeOptionRepository  $attributeOptionRepository
     * @return void
     */
    public function __construct(
        AttributeOptionRepository $attributeOptionRepository,
        App $app
    )
    {
        $this->attributeOptionRepository = $attributeOptionRepository;

        parent::__construct($app);
    }

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Attribute\Contracts\Attribute';
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        Event::fire('catalog.attribute.create.before');

        $data = $this->validateUserInput($data);

        $options = isset($data['options']) ? $data['options'] : [];
        unset($data['options']);
        $attribute = $this->model->create($data);

        if (in_array($attribute->type, ['select', 'multiselect', 'checkbox']) && count($options)) {
            foreach ($options as $optionInputs) {
                $this->attributeOptionRepository->create(array_merge([
                        'attribute_id' => $attribute->id
                    ], $optionInputs));
            }
        }

        Event::fire('catalog.attribute.create.after', $attribute);

        return $attribute;
    }

    /**
     * @param array $data
     * @param $id
     * @param string $attribute
     * @return mixed
     */
    public function update(array $data, $id, $attribute = "id")
    {
        $data = $this->validateUserInput($data);

        $attribute = $this->find($id);

        Event::fire('catalog.attribute.update.before', $id);

        $attribute->update($data);

        $previousOptionIds = $attribute->options()->pluck('id');

        if (in_array($attribute->type, ['select', 'multiselect', 'checkbox'])) {
            if (isset($data['options'])) {
                foreach ($data['options'] as $optionId => $optionInputs) {
                    if (str_contains($optionId, 'option_')) {
                        $this->attributeOptionRepository->create(array_merge([
                                'attribute_id' => $attribute->id,
                            ], $optionInputs));
                    } else {
                        if (is_numeric($index = $previousOptionIds->search($optionId))) {
                            $previousOptionIds->forget($index);
                        }

                        $this->attributeOptionRepository->update($optionInputs, $optionId);
                    }
                }
            }
        }

        foreach ($previousOptionIds as $optionId) {
            $this->attributeOptionRepository->delete($optionId);
        }

        Event::fire('catalog.attribute.update.after', $attribute);

        return $attribute;
    }

    /**
     * @param $id
     * @return void
     */
    public function delete($id)
    {
        Event::fire('catalog.attribute.delete.before', $id);

        parent::delete($id);

        Event::fire('catalog.attribute.delete.after', $id);
    }

    /**
     * @param array $data
     * @return array
     */
    public function validateUserInput($data)
    {
        if ($data['is_configurable']) {
            $data['value_per_channel'] = $data['value_per_locale'] = 0;
        }

        if (! in_array($data['type'], ['select', 'multiselect', 'price'])) {
            $data['is_filterable'] = 0;
        }

        if (in_array($data['type'], ['select', 'multiselect', 'boolean'])) {
            unset($data['value_per_locale']);
        }

        return $data;
    }

    /**
     * @return array
     */
    public function getFilterAttributes()
    {
        return $this->model->where('is_filterable', 1)->with('options')->get();
    }

    /**
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
                'status'
            ], $attributeColumns);

        if (in_array('*', $codes))
            return $this->all($attributeColumns);

        return $this->findWhereIn('code', $codes, $attributeColumns);
    }

    /**
     * @return Object
     */
    public function getAttributeByCode($code)
    {
        static $attributes = [];

        if (array_key_exists($code, $attributes))
            return $attributes[$code];

        return $attributes[$code] = $this->findOneByField('code', $code);
    }

    /**
     * @return Object
     */
    public function getFamilyAttributes($attributeFamily)
    {
        static $attributes = [];

        if (array_key_exists($attributeFamily->id, $attributes))
            return $attributes[$attributeFamily->id];

        return $attributes[$attributeFamily->id] = $attributeFamily->custom_attributes;
    }

    /**
     * @return Object
     */
    public function getPartial()
    {
        $attributes = $this->model->all();
        $trimmed = array();

        foreach($attributes as $key => $attribute) {
            if ($attribute->code != 'tax_category_id'
                && (
                    $attribute->type == 'select'
                    || $attribute->type == 'multiselect'
                    || $attribute->code == 'sku'
                )) {
                if ($attribute->options()->exists()) {
                    array_push($trimmed, [
                        'id' => $attribute->id,
                        'name' => $attribute->admin_name,
                        'type' => $attribute->type,
                        'code' => $attribute->code,
                        'has_options' => true,
                        'options' => $attribute->options
                    ]);
                } else {
                    array_push($trimmed, [
                        'id' => $attribute->id,
                        'name' => $attribute->admin_name,
                        'type' => $attribute->type,
                        'code' => $attribute->code,
                        'has_options' => false,
                        'options' => null
                    ]);
                }

            }
        }

        return $trimmed;
    }
}