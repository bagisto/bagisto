<?php

namespace Webkul\Product\Contracts\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;
use Webkul\Product\Models\ProductAttributeValue;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Product\Product\AbstractProduct;

/**
 * Class MyCriteria.
 *
 * @package namespace App\Criteria;
 */
class FilterByAttributesCriteria extends AbstractProduct implements CriteriaInterface
{
    /**
     * @var AttributeRepository
     */
    protected $attribute;

    /**
     * @param  Webkul\Attribute\Repositories\AttributeRepository $attribute
     * @return void
     */
    public function __construct(AttributeRepository $attribute)
    {
        $this->attribute = $attribute;
    }

    /**
     * Apply criteria in query repository
     *
     * @param string              $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        $model = $model->leftJoin('products as variants', 'products.id', '=', 'variants.parent_id');
        
        $model = $model->where(function($query1) use($model) {
            $aliases = [
                    'products' => 'filter_',
                    'variants' => 'variant_filter_'
                ];

            foreach($aliases as $table => $alias) {
                $query1 = $query1->orWhere(function($query2) use($model, $table, $alias) {

                    foreach ($this->attribute->getProductDefaultAttributes(array_keys(request()->input())) as $code => $attribute) {
                        $aliasTemp = $alias . $attribute->code;

                        $model = $model->leftJoin('product_attribute_values as ' . $aliasTemp, $table . '.id', '=', $aliasTemp . '.product_id');

                        $query2 = $this->applyChannelLocaleFilter($attribute, $query2, $aliasTemp);

                        $column = ProductAttributeValue::$attributeTypeFields[$attribute->type];
                        
                        $temp = explode(',', request()->get($attribute->code));
                        if($attribute->type != 'price') {
                            $query2 = $query2->where($aliasTemp . '.attribute_id', $attribute->id);

                            $query2 = $query2->where(function($query3) use($aliasTemp, $column, $temp) {
                                foreach($temp as $code => $filterValue) {
                                    $query3 = $query3->orWhere($aliasTemp . '.' . $column, $filterValue);
                                }
                            });
                        } else {
                            $query2 = $query2->where($aliasTemp . '.' . $column, '>=', current($temp))
                                    ->where($aliasTemp . '.' . $column, '<=', end($temp))
                                    ->where($aliasTemp . '.attribute_id', $attribute->id);
                        }
                    }
                });
            }
        });

        return $model;
    }
}