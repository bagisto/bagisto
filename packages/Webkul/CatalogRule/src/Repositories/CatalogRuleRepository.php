<?php

namespace Webkul\CatalogRule\Repositories;

use Illuminate\Container\Container as App;
use Illuminate\Support\Facades\Event;
use Webkul\Attribute\Repositories\AttributeFamilyRepository;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Core\Eloquent\Repository;
use Webkul\Tax\Repositories\TaxCategoryRepository;

class CatalogRuleRepository extends Repository
{
    /**
     * Attribute family repository instance.
     *
     * @var \Webkul\Attribute\Repositories\AttributeFamilyRepository
     */
    protected $attributeFamilyRepository;

    /**
     * Attribute repository instance.
     *
     * @var \Webkul\Attribute\Repositories\AttributeRepository
     */
    protected $attributeRepository;

    /**
     * Category repository instance.
     *
     * @var \Webkul\Category\Repositories\CategoryRepository
     */
    protected $categoryRepository;

    /**
     * Tax category repository instance.
     *
     * @var \Webkul\CaTaxtegory\Repositories\axCategoryRepository
     */
    protected $taxCategoryRepository;

    /**
     * Create a new repository instance.
     *
     * @param  \Webkul\Attribute\Repositories\AttributeFamilyRepository  $attributeFamilyRepository
     * @param  \Webkul\Attribute\Repositories\AttributeRepository  $attributeRepository
     * @param  \Webkul\Category\Repositories\CategoryRepository  $categoryRepository
     * @param  \Webkul\Tax\Repositories\TaxCategoryRepository  $taxCategoryRepository
     * @param  \Illuminate\Container\Container  $app
     * @return void
     */
    public function __construct(
        AttributeFamilyRepository $attributeFamilyRepository,
        AttributeRepository $attributeRepository,
        CategoryRepository $categoryRepository,
        TaxCategoryRepository $taxCategoryRepository,
        App $app
    ) {
        $this->attributeFamilyRepository = $attributeFamilyRepository;

        $this->attributeRepository = $attributeRepository;

        $this->categoryRepository = $categoryRepository;

        $this->taxCategoryRepository = $taxCategoryRepository;

        parent::__construct($app);
    }

    /**
     * Specify model class name.
     *
     * @return mixed
     */
    public function model()
    {
        return \Webkul\CatalogRule\Contracts\CatalogRule::class;
    }

    /**
     * Create.
     *
     * @param  array  $data
     * @return \Webkul\CatalogRule\Contracts\CatalogRule
     */
    public function create(array $data)
    {
        Event::dispatch('promotions.catalog_rule.create.before');

        $data['starts_from'] = $data['starts_from'] ?: null;

        $data['ends_till'] = $data['ends_till'] ?: null;

        $data['status'] = ! isset($data['status']) ? 0 : 1;

        $catalogRule = parent::create($data);

        $catalogRule->channels()->sync($data['channels']);

        $catalogRule->customer_groups()->sync($data['customer_groups']);

        Event::dispatch('promotions.catalog_rule.create.after', $catalogRule);

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
        Event::dispatch('promotions.catalog_rule.update.before', $id);

        $data['starts_from'] = $data['starts_from'] ?: null;

        $data['ends_till'] = $data['ends_till'] ?: null;

        $data['status'] = ! isset($data['status']) ? 0 : 1;

        $data['conditions'] = $data['conditions'] ?? [];

        $catalogRule = $this->find($id);

        parent::update($data, $id, $attribute);

        $catalogRule->channels()->sync($data['channels']);

        $catalogRule->customer_groups()->sync($data['customer_groups']);

        Event::dispatch('promotions.catalog_rule.update.after', $catalogRule);

        return $catalogRule;
    }

    /**
     * Delete.
     *
     * @param  $id
     * @return int
     */
    public function delete($id)
    {
        Event::dispatch('promotions.catalog_rule.delete.before', $id);

        parent::delete($id);

        Event::dispatch('promotions.catalog_rule.delete.after', $id);
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
                'label'    => trans('admin::app.promotions.catalog-rules.product-attribute'),
                'children' => [
                    [
                        'key'     => 'product|category_ids',
                        'type'    => 'multiselect',
                        'label'   => trans('admin::app.promotions.catalog-rules.categories'),
                        'options' => $this->categoryRepository->getCategoryTree(),
                    ], [
                        'key'     => 'product|attribute_family_id',
                        'type'    => 'select',
                        'label'   => trans('admin::app.promotions.catalog-rules.attribute_family'),
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
