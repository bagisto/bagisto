<?php

namespace Webkul\Product\Contracts\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class MyCriteria.
 *
 * @package namespace App\Criteria;
 */
class FilterByCategoryCriteria implements CriteriaInterface
{
    /**
     * @var integer
     */
    protected $categoryId;

    /**
     * @param  integer $categoryId
     * @return void
     */
    public function __construct($categoryId)
    {
        $this->categoryId = $categoryId;
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
        $model = $model->leftJoin('product_categories', 'products.id', '=', 'product_categories.product_id')
                ->where('product_categories.category_id', $this->categoryId);

        return $model;
    }
}
