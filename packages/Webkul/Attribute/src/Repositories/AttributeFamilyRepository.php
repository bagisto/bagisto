<?php 

namespace Webkul\Attribute\Repositories;
 
use Webkul\Core\Eloquent\Repository;
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
     * AttributeGroupRepository object
     *
     * @var array
     */
    protected $attributeGroup;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Attribute\Repositories\AttributeGroupRepository  $attributeGroup
     * @return void
     */
    public function __construct(AttributeGroupRepository $attributeGroup, App $app)
    {
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
        $family = $this->model->create($data);

        if(isset($data['attribute_groups'])) {
            foreach ($data['attribute_groups'] as $group) {
                $attributeGroup = $family->attribute_groups()->create($group);

                if(isset($group['attributes'])) {
                    foreach ($group['attributes'] as $attributeId) {
                        $attributeGroup->attributes()->attach($attributeId);
                    }
                }
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
        $family = $this->findOrFail($id);

        $family->update($data);

        $previousAttributeGroupIds = $family->attribute_groups()->pluck('id');
        
        if(isset($data['attribute_groups'])) {
            foreach ($data['attribute_groups'] as $attributeGroupId => $attributeGroupInputs) {
                if (str_contains($attributeGroupId, 'group_')) {
                    $attributeGroup = $family->attribute_groups()->create($attributeGroupInputs);

                    if(isset($attributeGroupInputs['attributes'])) {
                        foreach ($attributeGroupInputs['attributes'] as $attributeId) {
                            $attributeGroup->attributes()->attach($attributeId);
                        }
                    }
                } else {
                    if(($index = $previousAttributeGroupIds->search($attributeGroupId)) >= 0) {
                        $previousAttributeGroupIds->forget($index);
                    }

                    $attributeGroup = $this->attributeGroup->findOrFail($attributeGroupId);
                    $attributeGroup->update($attributeGroupInputs);

                    $attributeIds = $attributeGroup->attributes()->pluck('id');

                    foreach ($attributeGroupInputs['attributes'] as $attributeId) {
                        if(($index = $attributeIds->search($attributeId)) >= 0) {
                            $attributeIds->forget($index);
                        }
                    }

                    foreach ($attributeIds as $attributeId) {
                        $attributeGroup->deattach($attributeId);
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