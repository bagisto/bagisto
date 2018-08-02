<?php 

namespace Webkul\Product\Repositories;
 
use Illuminate\Container\Container as App;
use Webkul\Core\Eloquent\Repository;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Product\Repositories\ProductAttributeValueRepository;

/**
 * Product Reposotory
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ProductRepository extends Repository
{
    /**
     * AttributeRepository object
     *
     * @var array
     */
    protected $attribute;

    /**
     * ProductAttributeValueRepository object
     *
     * @var array
     */
    protected $attributeValue;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Attribute\Repositories\AttributeRepository             $attribute
     * @param  Webkul\Attribute\Repositories\ProductAttributeValueRepository $attributeValue
     * @return void
     */
    public function __construct(
        AttributeRepository $attribute,
        ProductAttributeValueRepository $attributeValue,
        App $app)
    {
        $this->attribute = $attribute;

        $this->attributeValue = $attributeValue;

        parent::__construct($app);
    }

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

            $super_attributes = [];

            foreach ($data['super_attributes'] as $attributeCode => $attributeOptions) {
                $attribute = $this->attribute->findBy('code', $attributeCode);

                $super_attributes[$attribute->id] = $attributeOptions;

                $product->super_attributes()->attach($attribute->id);
            }

            foreach (array_permutation($super_attributes) as $permutation) {
                $this->createVarient($product, $permutation);
            }
        }

        return $product;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function createVarient($product, $permutation)
    {
        $varient = $this->model->create([
                'parent_id' => $product->id,
                'type' => 'simple',
                'attribute_family_id' => $product->attribute_family_id,
                'sku' => $product->sku . '-varient-' . implode('-', $permutation),
            ]);

        foreach($permutation as $attributeId => $optionId) {
            $this->attributeValue->create([
                    'product_id' => $varient->id,
                    'attribute_id' => $attributeId,
                    'value' => $optionId
                ]);
        }

        return $varient;
    }
}