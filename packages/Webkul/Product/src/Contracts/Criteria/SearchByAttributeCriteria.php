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
class SearchByAttributeCriteria extends AbstractProduct implements CriteriaInterface
{
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
        return $model = $model->leftJoin('product_attribute_values as pav', 'products.id', '=', 'pav.product_id')->where('attribute_id', '=', 75)->where('products.parent_id', '=', null);
    }
}