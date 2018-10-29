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
class SearchByCategoryCriteria extends AbstractProduct implements CriteriaInterface
{
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

        //->leftJoin('category_translations', 'categories.id', '=', 'category_translations.id')->addSelect('category_translations.name')

        $model = $model->leftJoin('product_categories', 'products.id', '=', 'product_categories.product_id')->leftJoin('categories', 'product_categories.category_id', '=', 'categories.id')->leftJoin('category_translations', 'categories.id', '=', 'category_translations.id');

        return $model;
    }
}
