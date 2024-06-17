<?php

namespace Webkul\CartRule\Repositories;

use Illuminate\Container\Container;
use Illuminate\Support\Facades\DB;
use Webkul\Attribute\Repositories\AttributeFamilyRepository;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Core\Eloquent\Repository;
use Webkul\Core\Repositories\CountryRepository;
use Webkul\Core\Repositories\CountryStateRepository;
use Webkul\Tax\Repositories\TaxCategoryRepository;

class CartRuleRepository extends Repository
{
    /**
     * Create a new repository instance.
     *
     * @return void
     */
    public function __construct(
        protected AttributeFamilyRepository $attributeFamilyRepository,
        protected AttributeRepository $attributeRepository,
        protected CategoryRepository $categoryRepository,
        protected CartRuleCouponRepository $cartRuleCouponRepository,
        protected TaxCategoryRepository $taxCategoryRepository,
        protected CountryRepository $countryRepository,
        protected CountryStateRepository $countryStateRepository,
        Container $container
    ) {
        parent::__construct($container);
    }

    /**
     * Specify Model class name
     */
    public function model(): string
    {
        return 'Webkul\CartRule\Contracts\CartRule';
    }

    /**
     * @return \Webkul\CartRule\Contracts\CartRule
     */
    public function create(array $data)
    {
        $data['starts_from'] = $data['starts_from'] ?: null;

        $data['ends_till'] = $data['ends_till'] ?: null;

        $data['status'] = isset($data['status']);

        $cartRule = parent::create($data);

        $cartRule->channels()->sync($data['channels']);

        $cartRule->customer_groups()->sync($data['customer_groups']);

        if (
            $data['coupon_type']
            && ! $data['use_auto_generation']
        ) {
            $this->cartRuleCouponRepository->create([
                'cart_rule_id'       => $cartRule->id,
                'code'               => $data['coupon_code'],
                'usage_limit'        => $data['uses_per_coupon'] ?? 0,
                'usage_per_customer' => $data['usage_per_customer'] ?? 0,
                'is_primary'         => 1,
                'expired_at'         => $data['ends_till'] ?? null,
            ]);
        }

        return $cartRule;
    }

    /**
     * @param  int  $id
     * @return \Webkul\CartRule\Contracts\CartRule
     */
    public function update(array $data, $id)
    {
        $data = array_merge($data, [
            'starts_from' => $data['starts_from'] ?: null,
            'ends_till'   => $data['ends_till'] ?: null,
            'status'      => isset($data['status']),
            'conditions'  => $data['conditions'] ?? [],
        ]);

        $cartRule = $this->find($id);

        parent::update($data, $id);

        $cartRule->channels()->sync($data['channels']);

        $cartRule->customer_groups()->sync($data['customer_groups']);

        if (! $data['coupon_type']) {
            $cartRuleCoupon = $this->cartRuleCouponRepository->deleteWhere(['is_primary' => 1, 'cart_rule_id' => $cartRule->id]);
        } else {
            if (! $data['use_auto_generation']) {
                $cartRuleCoupon = $this->cartRuleCouponRepository->findOneWhere([
                    'is_primary'   => 1,
                    'cart_rule_id' => $cartRule->id,
                ]);

                if ($cartRuleCoupon) {
                    $this->cartRuleCouponRepository->update([
                        'code'               => $data['coupon_code'],
                        'usage_limit'        => $data['uses_per_coupon'] ?? 0,
                        'usage_per_customer' => $data['usage_per_customer'] ?? 0,
                        'expired_at'         => $data['ends_till'] ?? null,
                    ], $cartRuleCoupon->id);
                } else {
                    $this->cartRuleCouponRepository->create([
                        'cart_rule_id'       => $cartRule->id,
                        'code'               => $data['coupon_code'],
                        'usage_limit'        => $data['uses_per_coupon'] ?? 0,
                        'usage_per_customer' => $data['usage_per_customer'] ?? 0,
                        'is_primary'         => 1,
                        'expired_at'         => $data['ends_till'] ?? null,
                    ]);
                }
            } else {
                $this->cartRuleCouponRepository->deleteWhere([
                    'is_primary'   => 1,
                    'cart_rule_id' => $cartRule->id,
                ]);

                $this->cartRuleCouponRepository->where('cart_rule_id', $cartRule->id)->update([
                    'usage_limit'        => $data['uses_per_coupon'] ?? 0,
                    'usage_per_customer' => $data['usage_per_customer'] ?? 0,
                    'expired_at'         => $data['ends_till'] ?? null,
                ]);
            }
        }

        return $cartRule;
    }

    /**
     * Returns attributes for cart rule conditions.
     *
     * @return array
     */
    public function getConditionAttributes()
    {
        $attributes = [
            [
                'key'      => 'cart',
                'label'    => trans('admin::app.marketing.promotions.cart-rules.create.cart-attribute'),
                'children' => [
                    [
                        'key'   => 'cart|base_sub_total',
                        'type'  => 'price',
                        'label' => trans('admin::app.marketing.promotions.cart-rules.create.subtotal'),
                    ], [
                        'key'   => 'cart|items_qty',
                        'type'  => 'integer',
                        'label' => trans('admin::app.marketing.promotions.cart-rules.create.total-items-qty'),
                    ], [
                        'key'     => 'cart|payment_method',
                        'type'    => 'select',
                        'options' => $this->getPaymentMethods(),
                        'label'   => trans('admin::app.marketing.promotions.cart-rules.create.payment-method'),
                    ], [
                        'key'     => 'cart|shipping_method',
                        'type'    => 'select',
                        'options' => $this->getShippingMethods(),
                        'label'   => trans('admin::app.marketing.promotions.cart-rules.create.shipping-method'),
                    ], [
                        'key'   => 'cart|postcode',
                        'type'  => 'text',
                        'label' => trans('admin::app.marketing.promotions.cart-rules.create.shipping-postcode'),
                    ], [
                        'key'     => 'cart|state',
                        'type'    => 'select',
                        'options' => $this->groupedStatesByCountries(),
                        'label'   => trans('admin::app.marketing.promotions.cart-rules.create.shipping-state'),
                    ], [
                        'key'     => 'cart|country',
                        'type'    => 'select',
                        'options' => $this->getCountries(),
                        'label'   => trans('admin::app.marketing.promotions.cart-rules.create.shipping-country'),
                    ],
                ],
            ], [
                'key'      => 'cart_item',
                'label'    => trans('admin::app.marketing.promotions.cart-rules.create.cart-item-attribute'),
                'children' => [
                    [
                        'key'   => 'cart_item|base_price',
                        'type'  => 'price',
                        'label' => trans('admin::app.marketing.promotions.cart-rules.create.price-in-cart'),
                    ], [
                        'key'   => 'cart_item|quantity',
                        'type'  => 'integer',
                        'label' => trans('admin::app.marketing.promotions.cart-rules.create.qty-in-cart'),
                    ], [
                        'key'   => 'cart_item|base_total_weight',
                        'type'  => 'decimal',
                        'label' => trans('admin::app.marketing.promotions.cart-rules.create.total-weight'),
                    ], [
                        'key'   => 'cart_item|base_total',
                        'type'  => 'price',
                        'label' => trans('admin::app.marketing.promotions.cart-rules.create.subtotal'),
                    ], [
                        'key'   => 'cart_item|additional',
                        'type'  => 'text',
                        'label' => trans('admin::app.marketing.promotions.cart-rules.create.additional'),
                    ],
                ],
            ], [
                'key'      => 'product',
                'label'    => trans('admin::app.marketing.promotions.cart-rules.create.product-attribute'),
                'children' => [
                    [
                        'key'     => 'product|category_ids',
                        'type'    => 'multiselect',
                        'label'   => trans('admin::app.marketing.promotions.cart-rules.create.categories'),
                        'options' => $categories = $this->categoryRepository->getCategoryTree(),
                    ], [
                        'key'     => 'product|children::category_ids',
                        'type'    => 'multiselect',
                        'label'   => trans('admin::app.marketing.promotions.cart-rules.create.children-categories'),
                        'options' => $categories,
                    ], [
                        'key'     => 'product|parent::category_ids',
                        'type'    => 'multiselect',
                        'label'   => trans('admin::app.marketing.promotions.cart-rules.create.parent-categories'),
                        'options' => $categories,
                    ], [
                        'key'     => 'product|attribute_family_id',
                        'type'    => 'select',
                        'label'   => trans('admin::app.marketing.promotions.cart-rules.create.attribute-family'),
                        'options' => $this->getAttributeFamilies(),
                    ],
                ],
            ],
        ];

        $tempAttributes = $this->attributeRepository->with([
            'translations',
            'options',
            'options.translations',
        ])->findWhereNotIn('type', [
            'textarea',
            'image',
            'file',
        ]);

        foreach ($tempAttributes as $attribute) {
            $attributeType = $attribute->type;

            if ($attribute->code == 'tax_category_id') {
                $options = $this->getTaxCategories();
            } else {
                $options = $attribute->options;
            }

            if ($attribute->validation == 'decimal') {
                $attributeType = 'decimal';
            } elseif ($attribute->validation == 'numeric') {
                $attributeType = 'integer';
            }

            $attributes[2]['children'][] = [
                'key'     => 'product|'.$attribute->code,
                'type'    => $attribute->type,
                'label'   => $attribute->name,
                'options' => $options,
            ];

            $attributes[2]['children'][] = [
                'key'     => 'product|children::'.$attribute->code,
                'type'    => $attribute->type,
                'label'   => trans('admin::app.marketing.promotions.cart-rules.create.attribute-name-children-only', ['attribute_name' => $attribute->name]),
                'options' => $options,
            ];

            $attributes[2]['children'][] = [
                'key'     => 'product|parent::'.$attribute->code,
                'type'    => $attribute->type,
                'label'   => trans('admin::app.marketing.promotions.cart-rules.create.attribute-name-parent-only', ['attribute_name' => $attribute->name]),
                'options' => $options,
            ];
        }

        return $attributes;
    }

    /**
     * Returns all payment methods.
     *
     * @return array
     */
    public function getPaymentMethods()
    {
        $methods = [];

        foreach (config('payment_methods') as $paymentMethod) {
            $object = app($paymentMethod['class']);

            $methods[] = [
                'id'         => $object->getCode(),
                'admin_name' => $object->getTitle(),
            ];
        }

        return $methods;
    }

    /**
     * Returns all shipping methods.
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
                'admin_name' => $object->getTitle(),
            ];
        }

        return $methods;
    }

    /**
     * Returns all countries.
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

    /**
     * Returns all countries.
     *
     * @return array
     */
    public function getCountries()
    {
        $countries = [];

        foreach (DB::table('countries')->get() as $country) {
            $countries[] = [
                'id'         => $country->code,
                'admin_name' => $country->name,
            ];
        }

        return $countries;
    }

    /**
     * Retrieve all grouped states by country code.
     *
     * @return array
     */
    public function groupedStatesByCountries()
    {
        $collection = [];

        $countries = DB::table('countries')->get();

        $countriesStates = DB::table('country_states')->get();

        foreach ($countries as $country) {
            $states = $countriesStates->where('country_id', $country->id);

            if (! count($states)) {
                continue;
            }

            $collection[] = [
                'id'         => $country->code,
                'admin_name' => $country->name,
                'states'     => $states,
            ];
        }

        return $collection;
    }
}
