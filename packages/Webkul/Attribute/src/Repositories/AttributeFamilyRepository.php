<?php

namespace Webkul\Attribute\Repositories;

use Webkul\Core\Eloquent\Repository;
use Illuminate\Support\Facades\Event;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Attribute\Repositories\AttributeGroupRepository;
use Illuminate\Container\Container as App;
use Illuminate\Support\Str;

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
     * @var Object
     */
    protected $attributeRepository;

    /**
     * AttributeGroupRepository object
     *
     * @var Object
     */
    protected $attributeGroupRepository;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Attribute\Repositories\AttributeRepository      $attributeRepository
     * @param  Webkul\Attribute\Repositories\AttributeGroupRepository $attributeGroupRepository
     * @return void
     */
    public function __construct(
        AttributeRepository $attributeRepository,
        AttributeGroupRepository $attributeGroupRepository,
        App $app
    )
    {
        $this->attributeRepository = $attributeRepository;

        $this->attributeGroupRepository = $attributeGroupRepository;

        parent::__construct($app);
    }

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Attribute\Contracts\AttributeFamily';
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        Event::dispatch('catalog.attribute_family.create.before');

        $attributeGroups = isset($data['attribute_groups']) ? $data['attribute_groups'] : [];

        unset($data['attribute_groups']);

        $family = $this->model->create($data);

        foreach ($attributeGroups as $group) {
            $custom_attributes = isset($group['custom_attributes']) ? $group['custom_attributes'] : [];

            unset($group['custom_attributes']);

            $attributeGroup = $family->attribute_groups()->create($group);

            foreach ($custom_attributes as $key => $attribute) {
                if (isset($attribute['id'])) {
                    $attributeModel = $this->attributeRepository->find($attribute['id']);
                } else {
                    $attributeModel = $this->attributeRepository->findOneByField('code', $attribute['code']);
                }

                $attributeGroup->custom_attributes()->save($attributeModel, ['position' => $key + 1]);
            }
        }

        Event::dispatch('catalog.attribute_family.create.after', $family);

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

        Event::dispatch('catalog.attribute_family.update.before', $id);

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

        Event::dispatch('catalog.attribute_family.update.after', $family);

        return $family;
    }

    public function getPartial()
    {
        $attributeFamilies = $this->model->all();

        $trimmed = [];

        foreach ($attributeFamilies as $key => $attributeFamily) {
            if ($attributeFamily->name != null || $attributeFamily->name != "") {
                $trimmed[$key] = [
                    'id'   => $attributeFamily->id,
                    'code' => $attributeFamily->code,
                    'name' => $attributeFamily->name
                ];
            }
        }

        return $trimmed;
    }

    /**
     * @param $id
     * @return void
     */
    public function delete($id)
    {
        Event::dispatch('catalog.attribute_family.delete.before', $id);

        parent::delete($id);

        Event::dispatch('catalog.attribute_family.delete.after', $id);
    }
}