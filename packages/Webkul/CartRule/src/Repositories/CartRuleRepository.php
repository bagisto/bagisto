<?php

namespace Webkul\CartRule\Repositories;

use Illuminate\Container\Container as App;
use Webkul\Core\Eloquent\Repository;
use Webkul\Attribute\Repositories\AttributeFamilyRepository;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Tax\Repositories\TaxCategoryRepository;
use Webkul\Core\Repositories\CountryRepository;
use Webkul\Core\Repositories\CountryStateRepository;

/**
 * CartRule Reposotory
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CartRuleRepository extends Repository
{
    /**
     * AttributeFamilyRepository object
     *
     * @var AttributeFamilyRepository
     */
    protected $attributeFamilyRepository;

    /**
     * AttributeRepository object
     *
     * @var AttributeRepository
     */
    protected $attributeRepository;

    /**
     * CategoryRepository class
     *
     * @var CategoryRepository
     */
    protected $categoryRepository;

    /**
     * CartRuleCouponRepository object
     *
     * @var CartRuleCouponRepository
     */
    protected $cartRuleCouponRepository;

    /**
     * TaxCategoryRepository class
     *
     * @var TaxCategoryRepository
     */
    protected $taxCategoryRepository;

    /**
     * CountryRepository class
     *
     * @var CountryRepository
     */
    protected $countryRepository;

    /**
     * CountryStateRepository class
     *
     * @var CountryStateRepository
     */
    protected $countryStateRepository;

    /**
     * Create a new repository instance.
     *
     * @param  Webkul\Attribute\Repositories\AttributeFamilyRepository $attributeFamilyRepository
     * @param  Webkul\Attribute\Repositories\AttributeRepository       $attributeRepository
     * @param  Webkul\Category\Repositories\CategoryRepository         $categoryRepository
     * @param  Webkul\CartRule\Repositories\CartRuleCouponRepository   $cartRuleCouponRepository
     * @param  Webkul\Tax\Repositories\TaxCategoryRepository           $taxCategoryRepository
     * @param  Webkul\Core\Repositories\CountryRepository              $countryRepository
     * @param  Webkul\Core\Repositories\CountryStateRepository         $countryStateRepository
     * @param  Illuminate\Container\Container                          $app
     * @return void
     */
    public function __construct(
        AttributeFamilyRepository $attributeFamilyRepository,
        AttributeRepository $attributeRepository,
        CategoryRepository $categoryRepository,
        CartRuleCouponRepository $cartRuleCouponRepository,
        TaxCategoryRepository $taxCategoryRepository,
        CountryRepository $countryRepository,
        CountryStateRepository $countryStateRepository,
        App $app
    )
    {
        $this->attributeFamilyRepository = $attributeFamilyRepository;

        $this->attributeRepository = $attributeRepository;

        $this->categoryRepository = $categoryRepository;

        $this->cartRuleCouponRepository = $cartRuleCouponRepository;

        $this->taxCategoryRepository = $taxCategoryRepository;

        $this->countryRepository = $countryRepository;

        $this->countryStateRepository = $countryStateRepository;

        parent::__construct($app);
    }

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\CartRule\Contracts\CartRule';
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

        $cartRule = parent::create($data);

        $cartRule->channels()->sync($data['channels']);

        $cartRule->customer_groups()->sync($data['customer_groups']);

        if ($data['coupon_type'] && ! $data['use_auto_generation']) {
            $this->cartRuleCouponRepository->create([
                    'cart_rule_id'       => $cartRule->id,
                    'code'               => $data['coupon_code'],
                    'usage_limit'        => $data['usage_per_customer'] ?? 0,
                    'usage_per_customer' => $data['usage_per_customer'] ?? 0,
                    'is_primary'         => 1,
                    'expired_at'         => $data['ends_till'] ?: null
                ]);
        }

        return $cartRule;
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

        $cartRule = $this->find($id);

        parent::update($data, $id, $attribute);

        $cartRule->channels()->sync($data['channels']);

        $cartRule->customer_groups()->sync($data['customer_groups']);

        if ($data['coupon_type']) {
            if (! $data['use_auto_generation']) {
                $cartRuleCoupon = $this->cartRuleCouponRepository->findOneWhere(['is_primary' => 1, 'cart_rule_id' => $cartRule->id]);

                if ($cartRuleCoupon) {
                    $this->cartRuleCouponRepository->update([
                            'code'               => $data['coupon_code'],
                            'usage_limit'        => $data['uses_per_coupon'] ?? 0,
                            'usage_per_customer' => $data['usage_per_customer'] ?? 0,
                            'expired_at'         => $data['ends_till'] ?: null
                        ], $cartRuleCoupon->id);
                } else {
                    $this->cartRuleCouponRepository->create([
                            'cart_rule_id'       => $cartRule->id,
                            'code'               => $data['coupon_code'],
                            'usage_limit'        => $data['uses_per_coupon'] ?? 0,
                            'usage_per_customer' => $data['usage_per_customer'] ?? 0,
                            'is_primary'         => 1,
                            'expired_at'         => $data['ends_till'] ?: null
                        ]);
                }
            } else {
                $this->cartRuleCouponRepository->deleteWhere(['is_primary' => 1, 'cart_rule_id' => $cartRule->id]);

                $this->cartRuleCouponRepository->getModel()->where('cart_rule_id', $cartRule->id)->update([
                        'usage_limit'        => $data['uses_per_coupon'] ?? 0,
                        'usage_per_customer' => $data['usage_per_customer'] ?? 0,
                        'expired_at'         => $data['ends_till'] ?: null
                    ]);
            }
        } else {
            $cartRuleCoupon = $this->cartRuleCouponRepository->deleteWhere(['is_primary' => 1, 'cart_rule_id' => $cartRule->id]);
        }

        return $cartRule;
    }

    /**
     * Returns attributes for cart rule conditions
     *
     * @return array
     */
    public function getConditionAttributes()
    {
        $attributes = [
            [
                'key'      => 'cart',
                'label'    => trans('admin::app.promotions.cart-rules.cart-attribute'),
                'children' => [
                    [
                        'key'   => 'cart|base_sub_total',
                        'type'  => 'price',
                        'label' => trans('admin::app.promotions.cart-rules.subtotal')
                    ], [
                        'key'   => 'cart|items_qty',
                        'type'  => 'integer',
                        'label' => trans('admin::app.promotions.cart-rules.total-items-qty')
                    ], [
                        'key'     => 'cart|payment_method',
                        'type'    => 'select',
                        'options' => $this->getPaymentMethods(),
                        'label'   => trans('admin::app.promotions.cart-rules.payment-method')
                    ], [
                        'key'     => 'cart|shipping_method',
                        'type'    => 'select',
                        'options' => $this->getShippingMethods(),
                        'label'   => trans('admin::app.promotions.cart-rules.shipping-method')
                    ], [
                        'key'   => 'cart|postcode',
                        'type'  => 'text',
                        'label' => trans('admin::app.promotions.cart-rules.shipping-postcode')
                    ], [
                        'key'     => 'cart|state',
                        'type'    => 'select',
                        'options' => $this->groupedStatesByCountries(),
                        'label'   => trans('admin::app.promotions.cart-rules.shipping-state')
                    ], [
                        'key'     => 'cart|country',
                        'type'    => 'select',
                        'options' => $this->getCountries(),
                        'label'   => trans('admin::app.promotions.cart-rules.shipping-country')
                    ]
                ]
            ], [
                'key'      => 'cart_item',
                'label'    => trans('admin::app.promotions.cart-rules.cart-item-attribute'),
                'children' => [
                    [
                        'key'   => 'cart_item|base_price',
                        'type'  => 'price',
                        'label' => trans('admin::app.promotions.cart-rules.price-in-cart')
                    ], [
                        'key'   => 'cart_item|quantity',
                        'type'  => 'integer',
                        'label' => trans('admin::app.promotions.cart-rules.qty-in-cart')
                    ], [
                        'key'   => 'cart_item|base_total_weight',
                        'type'  => 'decimal',
                        'label' => trans('admin::app.promotions.cart-rules.total-weight')
                    ], [
                        'key'   => 'cart_item|base_total',
                        'type'  => 'price',
                        'label' => trans('admin::app.promotions.cart-rules.subtotal')
                    ]
                ]
            ], [
                'key'      => 'product',
                'label'    => trans('admin::app.promotions.cart-rules.product-attribute'),
                'children' => [
                    [
                        'key'     => 'product|category_ids',
                        'type'    => 'multiselect',
                        'label'   => trans('admin::app.promotions.cart-rules.categories'),
                        'options' => $categories = $this->categoryRepository->getCategoryTree()
                    ], [
                        'key'     => 'product|children::category_ids',
                        'type'    => 'multiselect',
                        'label'   => trans('admin::app.promotions.cart-rules.children-categories'),
                        'options' => $categories
                    ], [
                        'key'     => 'product|parent::category_ids',
                        'type'    => 'multiselect',
                        'label'   => trans('admin::app.promotions.cart-rules.parent-categories'),
                        'options' => $categories
                    ], [
                        'key'     => 'product|attribute_family_id',
                        'type'    => 'select',
                        'label'   => trans('admin::app.promotions.cart-rules.attribute_family'),
                        'options' => $this->getAttributeFamilies()
                    ]
                ]
            ]
        ];

        foreach ($this->attributeRepository->findWhereNotIn('type', ['textarea', 'image', 'file']) as $attribute) {
            $attributeType = $attribute->type;

            if ($attribute->code == 'tax_category_id') {
                $options = $this->getTaxCategories();
            } else {
                $options = $attribute->options;
            }

            if ($attribute->validation == 'decimal') {
                $attributeType = 'decimal';
            }

            if ($attribute->validation == 'numeric') {
                $attributeType = 'integer';
            }

            $attributes[2]['children'][] = [
                'key'     => 'product|' . $attribute->code,
                'type'    => $attribute->type,
                'label'   => $attribute->name,
                'options' => $options
            ];

            $attributes[2]['children'][] = [
                'key'     => 'product|children::' . $attribute->code,
                'type'    => $attribute->type,
                'label'   => trans('admin::app.promotions.cart-rules.attribute-name-children-only', ['attribute_name' => $attribute->name]),
                'options' => $options
            ];

            $attributes[2]['children'][] = [
                'key'     => 'product|parent::' . $attribute->code,
                'type'    => $attribute->type,
                'label'   => trans('admin::app.promotions.cart-rules.attribute-name-parent-only', ['attribute_name' => $attribute->name]),
                'options' => $options
            ];
        }

        return $attributes;
    }

    /**
     * Returns all payment methods
     *
     * @return array
     */
    public function getPaymentMethods()
    {
        $methods = [];

        foreach (config('paymentmethods') as $paymentMethod) {
            $object = app($paymentMethod['class']);

            $methods[] = [
                'id'         => $object->getCode(),
                'admin_name' => $object->getTitle()
            ];
        }

        return $methods;
    }

    /**
     * Returns all shipping methods
     *
     * @return array
     */
    public function getShippingMethods()
    {
        $methods = [];

        foreach (config('carriers') as $shippingMethod) {
            $object = app($shippingMethod['class']);

            $methods[] = [
                'id'         => $object->getCode(),
                'admin_name' => $object->getTitle()
            ];
        }

        return $methods;
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
                'id'         => $taxCategory->id,
                'admin_name' => $taxCategory->name,
            ];
        }

        return $taxCategories;
    }

    /**
     * Returns all attribute families
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

    /**
     * Returns all countries
     *
     * @return array
     */
    public function getCountries()
    {
        $countries = [];

        foreach ($this->countryRepository->all() as $country) {
            $countries[] = [
                'id'         => $country->code,
                'admin_name' => $country->name,
            ];
        }

        return $countries;
    }

    /**
     * Retrieve all grouped states by country code
     *
     * @return array
     */
    public function groupedStatesByCountries()
    {
        $collection = [];

        foreach ($this->countryRepository->all() as $country) {
            $countryStates = $this->countryStateRepository->findWhere(
                    ['country_id' => $country->id],
                    ['code', 'default_name as admin_name']
                )->toArray();

            if (! count($countryStates)) {
                continue;
            }

            $collection[] = [
                'id'         => $country->code,
                'admin_name' => $country->name,
                'states'     => $countryStates
            ];
        }

        return $collection;
    }
}