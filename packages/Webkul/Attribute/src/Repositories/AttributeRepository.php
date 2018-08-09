<?php 

namespace Webkul\Attribute\Repositories;
 
use Webkul\Core\Eloquent\Repository;
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
     * @var array
     */
    protected $attributeOption;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Attribute\Repositories\AttributeOptionRepository  $attributeOption
     * @return void
     */
    public function __construct(AttributeOptionRepository $attributeOption, App $app)
    {
        $this->attributeOption = $attributeOption;

        parent::__construct($app);
    }

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Attribute\Models\Attribute';
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        $options = isset($data['options']) ? $data['options'] : [];
        unset($data['options']);
        $attribute = $this->model->create($data);

        if(in_array($attribute->type, ['select', 'multiselect', 'checkbox']) && count($options)) {
            foreach ($options as $option) {
                $attribute->options()->create($option);
            }
        }

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
        $attribute = $this->findOrFail($id);

        $attribute->update($data);

        $previousOptionIds = $attribute->options()->pluck('id');

        if(in_array($attribute->type, ['select', 'multiselect', 'checkbox'])) {
            if(isset($data['options'])) {
                foreach ($data['options'] as $optionId => $optionInputs) {
                    if (str_contains($optionId, 'option_')) {
                        $attribute->options()->create($optionInputs);
                    } else {
                        if(is_numeric($index = $previousOptionIds->search($optionId))) {
                            $previousOptionIds->forget($index);
                        }

                        $this->attributeOption->update($optionInputs, $optionId);
                    }
                }
            }
        }

        foreach ($previousOptionIds as $optionId) {
            $this->attributeOption->delete($optionId);
        }

        return $attribute;
    }
}