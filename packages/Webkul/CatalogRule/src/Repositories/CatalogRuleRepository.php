<?php

namespace Webkul\CatalogRule\Repositories;

use Illuminate\Container\Container;
use Webkul\Core\Eloquent\Repository;
use Webkul\Attribute\Repositories\AttributeFamilyRepository;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Tax\Repositories\TaxCategoryRepository;

class CatalogRuleRepository extends Repository
{
    /**
     * Create a new repository instance.
     *
     * @param  \Webkul\Attribute\Repositories\AttributeFamilyRepository  $attributeFamilyRepository
     * @param  \Webkul\Attribute\Repositories\AttributeRepository  $attributeRepository
     * @param  \Webkul\Category\Repositories\CategoryRepository  $categoryRepository
     * @param  \Webkul\Tax\Repositories\TaxCategoryRepository  $taxCategoryRepository
     * @param  \Illuminate\Container\Container  $container
     * @return void
     */
    public function __construct(
        protected AttributeFamilyRepository $attributeFamilyRepository,
        protected AttributeRepository $attributeRepository,
        protected CategoryRepository $categoryRepository,
        protected TaxCategoryRepository $taxCategoryRepository,
        Container $container
    )
    {
        parent::__construct($container);
    }

    /**
     * Specify model class name.
     *
     * @return string
     */
    public function model(): string
    {
        return 'Webkul\CatalogRule\Contracts\CatalogRule';
    }

    /**
     * Create.
     *
     * @param  array  $data
     * @return \Webkul\CatalogRule\Contracts\CatalogRule
     */
    public function create(array $data)
    {
        $data = array_merge($data, [
            'starts_from' => $data['starts_from'] ?: null,
            'ends_till'   => $data['ends_till'] ?: null,
            'status'      => isset($data['status']),
        ]);

        $catalogRule = parent::create($data);

        $catalogRule->channels()->sync($data['channels']);

        $catalogRule->customer_groups()->sync($data['customer_groups']);

        return $catalogRule;
    }

    /**
     * Update.
     *
     * @param  array  $data
     * @param  int  $id
     * @param  string  $attribute
     * @return \Webkul\CatalogRule\Contracts\CatalogRule
     */
    public function update(array $data, $id, $attribute = 'id')
    {
        $data = array_merge($data, [
            'starts_from' => $data['starts_from'] ?: null,
            'ends_till'   => $data['ends_till'] ?: null,
            'status'      => isset($data['status']),
            'conditions'  => $data['conditions'] ?? [],
        ]);

        $catalogRule = $this->find($id);

        parent::update($data, $id, $attribute);

        $catalogRule->channels()->sync($data['channels']);

        $catalogRule->customer_groups()->sync($data['customer_groups']);

        return $catalogRule;
    }

    /**
     * Returns attributes for catalog rule conditions.
     *
     * @return array
     */
    public function getConditionAttributes()
    {
        $attributes = [
            [
                'key'      => 'product',
                'label'    => trans('admin::app.marketing.promotions.catalog-rules.create.product-attribute'),
                'children' => [
                    [
                        'key'     => 'product|category_ids',
                        'type'    => 'multiselect',
                        'label'   => trans('admin::app.marketing.promotions.catalog-rules.create.categories'),
                        'options' => $this->categoryRepository->getCategoryTree(),
                    ], [
                        'key'     => 'product|attribute_family_id',
                        'type'    => 'select',
                        'label'   => trans('admin::app.marketing.promotions.catalog-rules.create.attribute-family'),
                        'options' => $this->getAttributeFamilies(),
                    ],
                ],
            ],
        ];

        foreach ($this->attributeRepository->findWhereNotIn('type', ['textarea', 'image', 'file']) as $attribute) {
            $attributeType = $attribute->type;

            if ($attribute->code == 'tax_category_id') {
                $options = $this->getTaxCategories();
            } else {
                if ($attribute->type === 'select') {
                    $options = $attribute->options()->orderBy('sort_order')->get();
                } else {
                    $options = $attribute->options;
                }
            }

            if ($attribute->validation == 'decimal') {
                $attributeType = 'decimal';
            }

            if ($attribute->validation == 'numeric') {
                $attributeType = 'integer';
            }

            $attributes[0]['children'][] = [
                'key'     => 'product|' . $attribute->code,
                'type'    => $attribute->type,
                'label'   => $attribute->name,
                'options' => $options,
            ];
        }

        return $attributes;
    }

    /**
     * Returns all tax categories.
     *
     * @return array
     */
    public function getTaxCategories()
    {
        $taxCategories = [];

        foreach ($this->taxCategoryRepository->all() as $taxCategory) {
            $taxCategories[] = [
                'id'         => $taxCategory->id,
                'admin_name' => $taxCategory->name,
            ];
        }

        return $taxCategories;
    }

    /**
     * Returns all attribute families.
     *
     * @return array
     */
    public function getAttributeFamilies()
    {
        $attributeFamilies = [];

        foreach ($this->attributeFamilyRepository->all() as $attributeFamily) {
            $attributeFamilies[] = [
                'id'         => $attributeFamily->id,
                'admin_name' => $attributeFamily->name,
            ];
        }

        return $attributeFamilies;
    }
}
