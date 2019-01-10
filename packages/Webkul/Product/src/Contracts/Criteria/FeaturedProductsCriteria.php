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
class FeaturedProductsCriteria extends AbstractProduct implements CriteriaInterface
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
        $attribute = $this->attribute->findOneByField('code', 'featured');

        $model = $model->leftJoin('product_attribute_values as filter_featured', 'products.id', '=', 'filter_featured.product_id');

        $model = $this->applyChannelLocaleFilter($attribute, $model, 'filter_featured');

        $model->where('filter_featured.boolean_value', 1)
            ->where('filter_featured.attribute_id', $attribute->id);

        return $model;
    }
}
