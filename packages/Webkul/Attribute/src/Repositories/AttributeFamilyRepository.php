<?php

namespace Webkul\Attribute\Repositories;

use Illuminate\Container\Container;
use Illuminate\Support\Str;
use Webkul\Core\Eloquent\Repository;

class AttributeFamilyRepository extends Repository
{
    /**
     * Create a new repository instance.
     *
     * @return void
     */
    public function __construct(
        protected AttributeRepository $attributeRepository,
        protected AttributeGroupRepository $attributeGroupRepository,
        Container $container
    ) {
        parent::__construct($container);
    }

    /**
     * Specify Model class name
     */
    public function model(): string
    {
        return 'Webkul\Attribute\Contracts\AttributeFamily';
    }

    /**
     * @return \Webkul\Attribute\Contracts\AttributeFamily
     */
    public function create(array $data)
    {
        $attributeGroups = $data['attribute_groups'] ?? [];

        unset($data['attribute_groups']);

        $family = parent::create($data);

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
     * @param  int  $id
     * @param  string  $attribute
     * @return \Webkul\Attribute\Contracts\AttributeFamily
     */
    public function update(array $data, $id, $attribute = 'id')
    {
        $family = parent::update($data, $id, $attribute);

        $previousAttributeGroupIds = $family->attribute_groups()->pluck('id');

        foreach ($data['attribute_groups'] ?? [] as $attributeGroupId => $attributeGroupInputs) {
            if (Str::contains($attributeGroupId, 'group_')) {
                $attributeGroup = $family->attribute_groups()->create($attributeGroupInputs);

                if (empty($attributeGroupInputs['custom_attributes'])) {
                    continue;
                }

                foreach ($attributeGroupInputs['custom_attributes'] as $attributeInputs) {
                    $attribute = $this->attributeRepository->find($attributeInputs['id']);

                    $attributeGroup->custom_attributes()->save($attribute, [
                        'position' => $attributeInputs['position'],
                    ]);
                }
            } else {
                if (is_numeric($index = $previousAttributeGroupIds->search($attributeGroupId))) {
                    $previousAttributeGroupIds->forget($index);
                }

                $attributeGroup = $this->attributeGroupRepository->update($attributeGroupInputs, $attributeGroupId);

                $previousAttributeIds = $attributeGroup->custom_attributes()->get()->pluck('id');

                foreach ($attributeGroupInputs['custom_attributes'] ?? [] as $attributeInputs) {
                    if (is_numeric($index = $previousAttributeIds->search($attributeInputs['id']))) {
                        $previousAttributeIds->forget($index);

                        $attributeGroup->custom_attributes()->updateExistingPivot($attributeInputs['id'], [
                            'position' => $attributeInputs['position'],
                        ]);
                    } else {
                        $attribute = $this->attributeRepository->find($attributeInputs['id']);

                        $attributeGroup->custom_attributes()->save($attribute, [
                            'position' => $attributeInputs['position'],
                        ]);
                    }
                }

                if ($previousAttributeIds->count()) {
                    $attributeGroup->custom_attributes()->detach($previousAttributeIds);
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
                || $attributeFamily->name != ''
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

    /**
     * Get all the comparable attributes which belongs to attribute family.
     */
    public function getComparableAttributesBelongsToFamily()
    {
        return $this->attributeRepository
            ->with(['options', 'options.translations'])
            ->join('attribute_group_mappings', 'attribute_group_mappings.attribute_id', '=', 'attributes.id')
            ->select('attributes.*')
            ->where('attributes.is_comparable', 1)
            ->whereNotIn('code', ['name', 'price'])
            ->distinct()
            ->get();
    }
}
