<?php

namespace Webkul\Product\Contracts\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Product\Helpers\AbstractProduct;

/**
 * Class MyCriteria.
 *
 * @package namespace App\Criteria;
 */
class ActiveProductCriteria extends AbstractProduct implements CriteriaInterface
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
        foreach (['status', 'visible_individually'] as $code) {
            $attribute = $this->attribute->findOneByField('code', $code);

            $alias = 'filter_' . $attribute->code;

            $model = $model->leftJoin('product_attribute_values as ' . $alias, 'products.id', '=', $alias . '.product_id');

            $model = $this->applyChannelLocaleFilter($attribute, $model, $alias);

            $model->where($alias . '.boolean_value', 1)
                ->where($alias . '.attribute_id', $attribute->id);
        }

        return $model;
    }
}
