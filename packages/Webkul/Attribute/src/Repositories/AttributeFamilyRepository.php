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
        $family = $this->findOrFail($id);

        $family->update($data);

        return $family;
    }
}