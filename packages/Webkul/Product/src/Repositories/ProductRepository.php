<?php 

namespace Webkul\Product\Repositories;
 
use Webkul\Core\Eloquent\Repository;

/**
 * Product Reposotory
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ProductRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Product\Models\Product';
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        $product = $this->model->create($data);

        if(isset($data['super_attributes'])) {
            foreach ($data['super_attributes'] as $attributeId => $attribute) {
                $product->super_attributes()->attach($attributeId);
            }
        }

        return $product;
    }
}