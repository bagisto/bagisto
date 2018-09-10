<?php

namespace Webkul\Attribute\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Attribute\Repositories\AttributeGroupRepository;
use Illuminate\Container\Container as App;

/**
 * Attribute Reposotory
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class AttributeFamilyRepository extends Repository
{
    /**
     * AttributeRepository object
     *
     * @var array
     */
    protected $attribute;

    /**
     * AttributeGroupRepository object
     *
     * @var array
     */
    protected $attributeGroup;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Attribute\Repositories\AttributeRepository      $attribute
     * @param  Webkul\Attribute\Repositories\AttributeGroupRepository $attributeGroup
     * @return void
     */
    public function __construct(AttributeRepository $attribute, AttributeGroupRepository $attributeGroup, App $app)
    {
        $this->attribute = $attribute;

        $this->attributeGroup = $attributeGroup;

        parent::__construct($app);
    }

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Attribute\Models\AttributeFamily';
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        $attributeGroups = isset($data['attribute_groups']) ? $data['attribute_groups'] : [];
        unset($data['attribute_groups']);
        $family = $this->model->create($data);

        foreach ($attributeGroups as $group) {
            $custom_attributes = isset($group['custom_attributes']) ? $group['custom_attributes'] : [];
            unset($group['custom_attributes']);
            $attributeGroup = $family->attribute_groups()->create($group);

            foreach ($custom_attributes as $key => $attribute) {
                if(isset($attribute['id'])) {
                    $attributeModel = $this->attribute->find($attribute['id']);
                } else {
                    $attributeModel = $this->attribute->findOneByField('code', $attribute['code']);
                }
                
                $attributeGroup->custom_attributes()->save($attributeModel, ['position' => $key + 1]);
            }
        }

        return $family;
    }

    /**
     * @param array $data
     * @param $id
     * @param string $attribute
     * @return mixed
     */
    public function update(array $data, $id, $attribute = "id")
    {
        $family = $this->find($id);

        $family->update($data);

        $previousAttributeGroupIds = $family->attribute_groups()->pluck('id');

        if(isset($data['attribute_groups'])) {
            foreach ($data['attribute_groups'] as $attributeGroupId => $attributeGroupInputs) {
                if (str_contains($attributeGroupId, 'group_')) {
                    $attributeGroup = $family->attribute_groups()->create($attributeGroupInputs);

                    if(isset($attributeGroupInputs['custom_attributes'])) {
                        foreach ($attributeGroupInputs['custom_attributes'] as $key => $attribute) {
                            $attributeModel = $this->attribute->find($attribute['id']);
                            $attributeGroup->custom_attributes()->save($attributeModel, ['position' => $key + 1]);
                        }
                    }
                } else {
                    if(is_numeric($index = $previousAttributeGroupIds->search($attributeGroupId))) {
                        $previousAttributeGroupIds->forget($index);
                    }

                    $attributeGroup = $this->attributeGroup->find($attributeGroupId);
                    $attributeGroup->update($attributeGroupInputs);

                    $attributeIds = $attributeGroup->custom_attributes()->get()->pluck('id');

                    if(isset($attributeGroupInputs['custom_attributes'])) {
                        foreach ($attributeGroupInputs['custom_attributes'] as $key => $attribute) {
                            if(is_numeric($index = $attributeIds->search($attribute['id']))) {
                                $attributeIds->forget($index);
                            } else {
                                $attributeModel = $this->attribute->find($attribute['id']);
                                $attributeGroup->custom_attributes()->save($attributeModel, ['position' => $key + 1]);
                            }
                        }
                    }

                    if($attributeIds->count()) {
                        $attributeGroup->custom_attributes()->detach($attributeIds);
                    }
                }
            }
        }

        foreach ($previousAttributeGroupIds as $attributeGroupId) {
            $this->attributeGroup->delete($attributeGroupId);
        }

        return $family;
    }
}