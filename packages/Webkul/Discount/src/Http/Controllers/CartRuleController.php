<?php

namespace Webkul\Discount\Http\Controllers;

use Webkul\Attribute\Repositories\AttributeRepository as Attribute;
use Webkul\Attribute\Repositories\AttributeFamilyRepository as AttributeFamily;
use Webkul\Category\Repositories\CategoryRepository as Category;
use Webkul\Product\Repositories\ProductFlatRepository as Product;
use Webkul\Discount\Repositories\CatalogRuleRepository as CatalogRule;
use Webkul\Discount\Repositories\CartRuleRepository as CartRule;
use Webkul\Checkout\Repositories\CartRepository as Cart;
use Webkul\Discount\Repositories\CartRuleLabelsRepository as CartRuleLabels;
use Webkul\Discount\Repositories\CartRuleCouponsRepository as CartRuleCoupons;
use Validator;

/**
 * Cart Rule controller
 *
 * @author Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CartRuleController extends Controller
{
    /**
     * Initialize _config, a default request parameter with route
     */
    protected $_config;

    /**
     * Attribute $attribute
     */
    protected $attribute;

    /**
     * AttributeFamily $attributeFamily
     */
    protected $attributeFamily;

    /**
     * Category $category
     */
    protected $category;

    /**
     * Product $product
     */
    protected $product;

    /**
     * Property for Cart rule application
     */
    protected $appliedConfig;

    /**
     * To hold Cart repository instance
     */
    protected $cartRule;

    /**
     * To hold Rule Label repository instance
     */
    protected $cartRuleLabel;

    /**
     * To hold Coupons Repository instance
     */
    protected $cartRuleCoupon;

    /**
     * To hold the cart repository instance
     */
    protected $cart;

    public function __construct(Attribute $attribute, AttributeFamily $attributeFamily, Category $category, Product $product, CatalogRule $catalogRule, CartRule $cartRule, CartRuleCoupons $cartRuleCoupon, CartRuleLabels $cartRuleLabel)
    {
        $this->_config = request('_config');
        $this->attribute = $attribute;
        $this->attributeFamily = $attributeFamily;
        $this->category = $category;
        $this->product = $product;
        $this->cartRule = $cartRule;
        $this->cartRuleCoupon = $cartRuleCoupon;
        $this->cartRuleLabel = $cartRuleLabel;
        $this->appliedConfig = config('pricerules.cart');
    }

    public function index()
    {
        return view($this->_config['view']);
    }

    public function create()
    {
        return view($this->_config['view'])->with('cart_rule', [$this->appliedConfig, $this->fetchOptionableAttributes(), $this->getStatesAndCountries()]);
    }

    public function store()
    {
        $data = request()->all();

        $validated = Validator::make($data, [
            'name' => 'required|string',
            'description' => 'string',
            'customer_groups' => 'required|array',
            'channels' => 'required|array',
            'status' => 'required|boolean',
            'use_coupon' => 'boolean|required',
            // 'auto_generation' => 'boolean|sometimes',
            'usage_limit' => 'numeric|min:0',
            'per_customer' => 'numeric|min:0',
            'action_type' => 'required|string',
            'disc_amount' => 'required|numeric',
            'disc_quantity' => 'numeric',
            'disc_threshold' => 'numeric',
            'free_shipping' => 'required|boolean',
            'apply_to_shipping' => 'required|boolean',
            'code' => 'string|required_if:auto_generation,0',
            'all_conditions' => 'sometimes|nullable',
            'label' => 'array|nullable'
        ]);

        if ($validated->fails()) {
            session()->flash('error', 'Validation failed');
            return redirect()->route('admin.cart-rule.create')
                    ->withErrors($validated)
                    ->withInput();
        }

        if ($data['starts_from'] == "" || $data['ends_till'] == "") {
            $data['starts_from'] = null;
            $data['ends_till'] = null;
        }

        unset($data['_token']);

        $channels = $data['channels'];
        unset($data['channels']);

        $customer_groups = $data['customer_groups'];
        unset($data['customer_groups']);
        unset($data['criteria']);

        $labels = $data['label'];
        unset($data['label']);
        unset($data['cart_attributes']);
        unset($data['attributes']);

        // unset($data['all_conditions']);

        if (isset($data['disc_amount']) && $data['action_type'] == config('pricerules.cart.validations.2')) {
                $data['actions'] = [
                    'action_type' => $data['action_type'],
                    'disc_amount' => $data['disc_amount'],
                    'disc_threshold' => $data['disc_threshold']
                ];

            $data['disc_quantity'] = $data['disc_amount'];
        } else {
            $data['actions'] = [
                'action_type' => $data['action_type'],
                'disc_amount' => $data['disc_amount'],
                'disc_quantity' => $data['disc_quantity']
            ];
        }

        $data['actions'] = json_encode($data['actions']);

        if (! isset($data['all_conditions'])) {
            $data['conditions'] = null;
        } else {
            $data['conditions'] = json_encode($data['all_conditions']);
            unset($data['all_conditions']);
        }

        if ($data['use_coupon']) {
            // if (isset($data['auto_generation']) && $data['auto_generation']) {
            $data['auto_generation'] = 0;

            $coupons['code'] = $data['code'];
            unset($data['code']);
            // } else {
            //     $data['auto_generation'] = 1;
            // }

            // if (isset($data['prefix'])) {
            //     $coupons['prefix'] = $data['prefix'];
            //     unset($data['prefix']);
            // }

            // if (isset($data['suffix'])) {
            //     $coupons['suffix'] = $data['suffix'];
            //     unset($data['suffix']);
            // }
        }

        if(isset($data['limit'])) {
            $coupons['usage_limit'] = $data['limit'];
            unset($data['limit']);
        }

        $ruleCreated = $this->cartRule->create($data);

        $ruleGroupCreated = $this->cartRule->CustomerGroupSync($customer_groups, $ruleCreated);
        $ruleChannelCreated = $this->cartRule->ChannelSync($channels, $ruleCreated);

        if (isset($labels['global'])) {
            foreach (core()->getAllChannels() as $channel) {
                $label1['channel_id'] = $channel->id;
                foreach($channel->locales as $locale) {
                    $label1['locale_id'] = $locale->id;
                    $label1['label'] = $labels['global'];
                    $label1['cart_rule_id'] = $ruleCreated->id;

                    $ruleLabelCreated = $this->cartRuleLabel->create($label1);
                }
            }
        } else {
            $label2['label'] = $labels['global'];
            $label2['cart_rule_id'] = $ruleCreated->id;
            $ruleLabelCreated = $this->cartRuleLabel->create($label2);
        }

        if(isset($coupons)) {
            $coupons['cart_rule_id'] = $ruleCreated->id;
            $coupons['usage_per_customer'] = $data['per_customer']; //0 is for unlimited usage

            $couponCreated = $this->cartRuleCoupon->create($coupons);
        }

        if ($ruleCreated && $ruleGroupCreated && $ruleChannelCreated) {
            if (isset($couponCreated) && $couponCreated) {
                session()->flash('success', trans('admin::app.promotion.status.success-coupon'));
            }

            session()->flash('success', trans('admin::app.promotion.status.success'));
        } else {
            session()->flash('success', trans('admin::app.promotion.status.success'));

            return redirect()->back();
        }

        return redirect()->route($this->_config['redirect']);
    }

    public function edit($id)
    {
        $cart_rule = $this->cartRule->find($id);

        return view($this->_config['view'])->with('cart_rule', [$this->appliedConfig, $this->fetchOptionableAttributes(), $this->getStatesAndCountries(), $cart_rule]);
    }

    public function update($id)
    {
        $types = config('price_rules.cart.validations');

        $validated = Validator::make(request()->all(), [
            'name' => 'required|string',
            'description' => 'string',
            'customer_groups' => 'required|array',
            'channels' => 'required|array',
            'status' => 'required|boolean',
            'use_coupon' => 'boolean|required',
            'usage_limit' => 'numeric|min:0',
            'per_customer' => 'numeric|min:0',
            'action_type' => 'required|string',
            'disc_amount' => 'required|numeric',
            'disc_quantity' => 'required|numeric',
            'disc_threshold' => 'required|numeric',
            'free_shipping' => 'required|boolean',
            'apply_to_shipping' => 'required|boolean',
            'code' => 'string|required_if:user_coupon,1',
            'all_conditions' => 'present',
            'label' => 'array|nullable'
        ]);

        if ($validated->fails()) {
            session()->flash('error', 'Validation failed');
            return redirect()->route('admin.cart-rule.create')
                ->withErrors($validated)
                ->withInput();
        }

        $data = request()->all();

        dd($data);

        if ($data['starts_from'] == "" || $data['ends_till'] == "") {
            $data['starts_from'] = null;
            $data['ends_till'] = null;
        }

        unset($data['_token']);

        $channels = $data['channels'];
        unset($data['channels']);

        $customer_groups = $data['customer_groups'];
        unset($data['customer_groups']);
        unset($data['criteria']);

        if (isset($data['label'])) {
            $labels = $data['label'];
            unset($data['label']);
        }

        unset($data['cart_attributes']);
        unset($data['attributes']);

        if (isset($data['disc_amount']) && $data['action_type'] == config('pricerules.cart.validations.2')) {
            $data['actions'] = [
                'action_type' => $data['action_type'],
                'disc_amount' => $data['disc_amount'],
                'disc_threshold' => $data['disc_threshold']
            ];

            $data['disc_quantity'] = $data['disc_amount'];
        } else {
            $data['actions'] = [
                'action_type' => $data['action_type'],
                'disc_amount' => $data['disc_amount'],
                'disc_quantity' => $data['disc_quantity']
            ];
        }

        $data['actions'] = json_encode($data['actions']);
        if (! isset($data['all_conditions'])) {
            $data['conditions'] = null;
        } else {
            $data['conditions'] = json_encode($data['all_conditions']);
        }

        unset($data['all_conditions']);

        if ($data['use_coupon']) {
            // if (isset($data['auto_generation']) && $data['auto_generation']) {
            $data['auto_generation'] = 0;

            $coupons['code'] = $data['code'];
            unset($data['code']);
            // } else {
            //     $data['auto_generation'] = 1;
            // }

            // if (isset($data['prefix'])) {
            //     $coupons['prefix'] = $data['prefix'];
            //     unset($data['prefix']);
            // }

            // if (isset($data['suffix'])) {
            //     $coupons['suffix'] = $data['suffix'];
            //     unset($data['suffix']);
            // }
        }

        if (isset($data['limit'])) {
            $coupons['usage_limit'] = $data['limit'];
            unset($data['limit']);
        }

        $ruleUpdated = $this->cartRule->update($data, $id);

        $ruleGroupUpdated = $this->cartRule->CustomerGroupSync($customer_groups, $ruleUpdated);
        $ruleChannelUpdated = $this->cartRule->ChannelSync($channels, $ruleUpdated);

        // if (isset($labels['global'])) {
        //     foreach (core()->getAllChannels() as $channel) {
        //         $label1['channel_id'] = $channel->id;
        //         foreach ($channel->locales as $locale) {
        //             $label1['locale_id'] = $locale->id;
        //             $label1['label'] = $labels['global'];

        //             $ruleLabelUpdated = $this->cartRuleLabel->create($label1);
        //         }
        //     }
        // } else {
        //     $label2['label'] = $labels['global'];
        //     $ruleLabelUpdated = $this->cartRuleLabel->create($label2);
        // }

        if (isset($coupons)) {
            $coupons['cart_rule_id'] = $ruleUpdated->id;
            $coupons['usage_per_customer'] = $data['per_customer']; //0 is for unlimited usage

            $couponUpdated = $ruleUpdated->coupons->update($coupons);
        }

        if ($ruleUpdated && $ruleGroupUpdated && $ruleChannelUpdated) {
            if (isset($couponUpdated) && $couponUpdated) {
                session()->flash('info', trans('admin::app.promotion.status.success-coupon'));
            }

            session()->flash('info', trans('admin::app.promotion.status.update-success'));
        } else {
            session()->flash('info', trans('admin::app.promotion.status.update-success'));

            return redirect()->back();
        }

        return redirect()->route($this->_config['redirect']);
    }

    public function destroy($id)
    {
        $cartRule = $this->cartRule->findOrFail($id);

        if ($cartRule->delete()) {
            session()->flash('success', trans('admin::app.promotion.status.delete-success'));

            return response()->json(['message' => true], 200);
        } else {
            session()->flash('success', trans('admin::app.promotion.status.delete-failed'));

            return response()->json(['message' => false], 400);
        }
    }

    public function getStatesAndCountries()
    {
        $countries = core()->countries()->toArray();
        $states = core()->groupedStatesByCountries();

        return [
            'countries' => $countries,
            'states' => $states
        ];
    }

    public function fetchOptionableAttributes()
    {
        $attributesWithOptions = array();

        foreach($this->attribute->all() as $attribute) {
            if (($attribute->type == 'select' || $attribute->type == 'multiselect')  && $attribute->code != 'tax_category_id') {
                $attributesWithOptions[$attribute->admin_name] = $attribute->options->toArray();
            }
        }

        return $attributesWithOptions;
    }
}
