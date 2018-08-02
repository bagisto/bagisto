<?php 

namespace Webkul\Product\Repositories;
 
use Illuminate\Container\Container as App;
use Webkul\Attribute\Repositories\AttributeRepository;
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
     * AttributeRepository object
     *
     * @var array
     */
    protected $attribute;

    /**
     * @var array
     */
    protected $attributeTypeFields = [
        'text' => 'text_value',
        'textarea' => 'text_value',
        'price' => 'float_value',
        'boolean' => 'boolean_value',
        'select' => 'integer_value',
        'multiselect' => 'text_value',
        'datetime' => 'datetime_time',
        'date' => 'date_value',
    ];

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
        $attribute = $this->attribute->find($data['attribute_id']);

        $data[$this->attributeTypeFields[$attribute->type]] = $data['value'];

        return $this->model->create($data);
    }
}