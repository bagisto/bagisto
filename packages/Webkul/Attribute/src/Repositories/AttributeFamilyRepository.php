<?php

namespace Webkul\Attribute\Repositories;

use Illuminate\Container\Container;
use Illuminate\Support\Str;
use Webkul\Core\Eloquent\Repository;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Attribute\Repositories\AttributeGroupRepository;

class AttributeFamilyRepository extends Repository
{
    /**
     * Create a new repository instance.
     *
     * @param  \Webkul\Attribute\Repositories\AttributeRepository  $attributeRepository
     * @param  \Webkul\Attribute\Repositories\AttributeGroupRepository  $attributeGroupRepository
     * @param  \Illuminate\Container\Container  $container
     * @return void
     */
    public function __construct(
        protected AttributeRepository $attributeRepository,
        protected AttributeGroupRepository $attributeGroupRepository,
        Container $container
    )
    {
        parent::__construct($container);
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model(): string
    {
        return 'Webkul\Attribute\Contracts\AttributeFamily';
    }

    /**
     * @param  array  $data
     * @return \Webkul\Attribute\Contracts\AttributeFamily
     */
    public function create(array $data)
    {
        $attributeGroups = $data['attribute_groups'] ?? [];

        unset($data['attribute_groups']);

        $family = $this->model->create($data);

        foreach ($attributeGroups as $group) {
            $customAttributes = $group['custom_attributes'] ?? [];

            unset($group['custom_attributes']);

            $attributeGroup = $family->attribute_groups()->create($group);

            foreach ($customAttributes as $key => $attribute) {
                if (isset($attribute['id'])) {
                    $attributeModel = $this->attributeRepository->find($attribute['id']);
                } else {
                    $attributeModel = $this->attributeRepository->findOneByField('code', $attribute['code']);
                }

                $attributeGroup->custom_attributes()->save($attributeModel, ['position' => $key + 1]);
            }
        }

        return $family;
    }

    /**
     * @param  array  $data
     * @param  int  $id
     * @param  string  $attribute
     * @return \Webkul\Attribute\Contracts\AttributeFamily
     */
    public function update(array $data, $id, $attribute = "id")
    {
        $family = $this->find($id);
        
        $family->update($data);

        $previousAttributeGroupIds = $family->attribute_groups()->pluck('id');

        if (isset($data['attribute_groups'])) {
            foreach ($data['attribute_groups'] as $attributeGroupId => $attributeGroupInputs) {
                if (Str::contains($attributeGroupId, 'group_')) {
                    $attributeGroup = $family->attribute_groups()->create($attributeGroupInputs);

                    if (isset($attributeGroupInputs['custom_attributes'])) {
                        foreach ($attributeGroupInputs['custom_attributes'] as $key => $attribute) {
                            $attributeModel = $this->attributeRepository->find($attribute['id']);

                            $attributeGroup->custom_attributes()->save($attributeModel, ['position' => $key + 1]);
                        }
                    }
                } else {
                    if (is_numeric($index = $previousAttributeGroupIds->search($attributeGroupId))) {
                        $previousAttributeGroupIds->forget($index);
                    }

                    $attributeGroup = $this->attributeGroupRepository->find($attributeGroupId);

                    $attributeGroup->update($attributeGroupInputs);

                    $attributeIds = $attributeGroup->custom_attributes()->get()->pluck('id');

                    if (isset($attributeGroupInputs['custom_attributes'])) {
                        foreach ($attributeGroupInputs['custom_attributes'] as $key => $attribute) {
                            if (is_numeric($index = $attributeIds->search($attribute['id']))) {
                                $attributeIds->forget($index);
                            } else {
                                $attributeModel = $this->attributeRepository->find($attribute['id']);

                                $attributeGroup->custom_attributes()->save($attributeModel, ['position' => $key + 1]);
                            }
                        }
                    }

                    if ($attributeIds->count()) {
                        $attributeGroup->custom_attributes()->detach($attributeIds);
                    }
                }
            }
        }

        foreach ($previousAttributeGroupIds as $attributeGroupId) {
            $this->attributeGroupRepository->delete($attributeGroupId);
        }

        return $family;
    }


    /**
     * @return array
     */
    public function getPartial()
    {
        $attributeFamilies = $this->model->all();

        $trimmed = [];

        foreach ($attributeFamilies as $key => $attributeFamily) {
            if (
                $attributeFamily->name != null
                || $attributeFamily->name != ""
            ) {
                $trimmed[$key] = [
                    'id'   => $attributeFamily->id,
                    'code' => $attributeFamily->code,
                    'name' => $attributeFamily->name,
                ];
            }
        }

        return $trimmed;
    }
}