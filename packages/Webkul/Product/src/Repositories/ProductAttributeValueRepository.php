<?php

namespace Webkul\Product\Repositories;

use Illuminate\Container\Container as App;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Core\Eloquent\Repository;
use Webkul\Product\Models\ProductAttributeValue;

/**
 * Product Attribute Value Reposotory
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ProductAttributeValueRepository extends Repository
{
    /**
     * AttributeRepository object
     *
     * @var array
     */
    protected $attribute;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Attribute\Repositories\AttributeRepository $attribute
     * @return void
     */
    public function __construct(AttributeRepository $attribute, App $app)
    {
        $this->attribute = $attribute;

        parent::__construct($app);
    }

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
        if(isset($data['attribute_id'])) {
            $attribute = $this->attribute->find($data['attribute_id']);
        } else {
            $attribute = $this->attribute->findOneByField('code', $data['attribute_code']);
        }

        if(!$attribute)
            return;

        $data[ProductAttributeValue::$attributeTypeFields[$attribute->type]] = $data['value'];

        return $this->model->create($data);
    }

    /**
     * @param string $column
     * @param int    $attributeId
     * @param int    $productId
     * @param string $value
     * @return boolean
     */
    public function isValueUnique($productId, $attributeId, $column, $value)
    {
        $result = $this->resetScope()->model->where($column, $value)->where('attribute_id', '=', $attributeId)->where('product_id', '!=', $productId)->get();

        return $result->count() ? false : true;
    }
}