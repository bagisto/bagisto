<?php

namespace Webkul\Product\Contracts\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;
use Webkul\Attribute\Repositories\AttributeRepository;

/**
 * Class MyCriteria.
 *
 * @package namespace App\Criteria;
 */
class NewProductsCriteria implements CriteriaInterface
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
        $attribute = $this->attribute->findOneByField('code', 'new');

        $model = $model->leftJoin('product_attribute_values as filter_new', 'products.id', '=', 'filter_new.product_id');

        $model->where('filter_new.boolean_value', 1)
            ->where('filter_new.attribute_id', $attribute->id);

        return $model;
    }
}
