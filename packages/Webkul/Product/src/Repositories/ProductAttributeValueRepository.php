<?php 

namespace Webkul\Product\Repositories;
 
use Webkul\Core\Eloquent\Repository;

/**
 * Product Attribute Value Reposotory
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ProductAttributeValueRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Product\Models\ProductAttributeValue';
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        $product = $this->model->create($data);

        foreach ($data['super_attributes'] as $attributeId => $attribute) {
            $product->super_attributes()->attach($attributeId);
        }

        return $product;
    }
}