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
class SortCriteria extends AbstractProduct implements CriteriaInterface
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
        $params = request()->input();

        if(isset($params['sort'])) {
            if($params['sort'] == 'name' || $params['sort'] == 'price') {
                $attribute = $this->attribute->findOneByField('code', $params['sort']);

                $alias = 'sort_' . $params['sort'];

                $model = $model->leftJoin('product_attribute_values as ' . $alias, function($qb) use($attribute, $alias) {

                    $qb = $this->applyChannelLocaleFilter($attribute, $qb, $alias);

                    $qb->on('products.id', $alias . '.product_id')
                            ->where($alias . '.attribute_id', $attribute->id);
                });

                $model = $model->addSelect($alias . '.' . ProductAttributeValue::$attributeTypeFields[$attribute->type] . ' as ' . $attribute->code);

                $model = $model->orderBy($attribute->code, $params['order']);

            } else {
                $model = $model->orderBy($params['sort'], $params['order']);
            }
        } else {
            $model = $model->orderBy('created_at', 'desc');
        }

        return $model;
    }
}
