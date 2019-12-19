<?php

namespace Webkul\CatalogRule\Repositories;

use Illuminate\Container\Container as App;
use Webkul\Core\Eloquent\Repository;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Tax\Repositories\TaxCategoryRepository;

/**
 * CatalogRule Reposotory
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CatalogRuleRepository extends Repository
{
    /**
     * AttributeRepository object
     *
     * @var AttributeRepository
     */
    protected $attributeRepository;

    /**
     * TaxCategoryRepository class
     *
     * @var TaxCategoryRepository
     */
    protected $taxCategoryRepository;

    /**
     * Create a new repository instance.
     *
     * @param  Webkul\Attribute\Repositories\AttributeRepository $attributeRepository
     * @param  Webkul\Tax\Repositories\TaxCategoryRepository     $taxCategoryRepository
     * @param  Illuminate\Container\Container                    $app
     * @return void
     */
    public function __construct(
        AttributeRepository $attributeRepository,
        TaxCategoryRepository $taxCategoryRepository,
        App $app
    )
    {
        $this->attributeRepository = $attributeRepository;

        $this->taxCategoryRepository = $taxCategoryRepository;

        parent::__construct($app);
    }

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\CatalogRule\Contracts\CatalogRule';
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        $data['starts_from'] = $data['starts_from'] ?: null;

        $data['ends_till'] = $data['ends_till'] ?: null;

        $data['status'] = ! isset($data['status']) ? 0 : 1;

        $catalogRule = parent::create($data);

        $catalogRule->channels()->sync($data['channels']);

        $catalogRule->customer_groups()->sync($data['customer_groups']);

        return $catalogRule;
    }

    /**
     * @param array  $data
     * @param array  $id
     * @param string $attribute
     * @return mixed
     */
    public function update(array $data, $id, $attribute = "id")
    {
        $data['starts_from'] = $data['starts_from'] ?: null;

        $data['ends_till'] = $data['ends_till'] ?: null;

        $data['status'] = ! isset($data['status']) ? 0 : 1;

        $data['conditions'] = $data['conditions'] ?? [];

        $catalogRule = $this->find($id);

        parent::update($data, $id, $attribute);

        $catalogRule->channels()->sync($data['channels']);

        $catalogRule->customer_groups()->sync($data['customer_groups']);

        return $catalogRule;
    }

    /**
     * Returns attributes for catalog rule conditions
     *
     * @return array
     */
    public function getConditionAttributes()
    {
        $attributes = [
            [
                'key' => 'product',
                'label' => trans('admin::app.promotions.catalog-rules.product-attribute'),
                'children' => []
            ]
        ];

        foreach ($this->attributeRepository->findWhereNotIn('type', ['textarea', 'image', 'file']) as $attribute) {
            $attributeType = $attribute->type;

            if ($attribute->code == 'tax_category_id') {
                $options = $this->getTaxCategories();
            } else {
                $options = $attribute->options;
            }

            if ($attribute->validation == 'decimal')
                $attributeType = 'decimal';

            if ($attribute->validation == 'numeric')
                $attributeType = 'integer';

            $attributes[0]['children'][] = [
                'key' => 'product|' . $attribute->code,
                'type' => $attribute->type,
                'label' => $attribute->name,
                'options' => $options
            ];
        }

        return $attributes;
    }

    /**
     * Returns all countries
     *
     * @return array
     */
    public function getTaxCategories()
    {
        $taxCategories = [];

        foreach ($this->taxCategoryRepository->all() as $taxCategory) {
            $taxCategories[] = [
                'id' => $taxCategory->id,
                'admin_name' => $taxCategory->name,
            ];
        }

        return $taxCategories;
    }
}