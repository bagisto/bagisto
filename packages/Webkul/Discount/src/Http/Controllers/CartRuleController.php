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
        $validated = Validator::make(request()->all(), [
            'name' => 'required|string',
            'description' => 'string',
            'customer_groups' => 'required|array',
            'channels' => 'required|array',
            'status' => 'required|numeric',
            'use_coupon' => 'numeric',
            'auto_generation' => 'numeric',
            'usage_limit' => 'numeric',
            'per_customer' => 'numeric',
            'action_type' => 'required|string',
            'disc_amount' => 'required|numeric',
            'disc_threshold' => 'required|numeric',
            'free_shipping' => 'required|numeric',
            'apply_to_shipping' => 'required|numeric',
            'code' => 'string',
            'all_conditions' => 'array',
            'label' => 'array'
        ]);

        if ($validated->fails()) {
            return redirect('admin.cart-rule.create')
                    ->withErrors($validated)
                    ->withInput();
        }

        $data = request()->all();

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

        $data['conditions'] = $data['all_conditions'];
        unset($data['all_conditions']);

        if (isset($data['disc_amount'])) {
            $data['actions'] = [
                'action_type' => $data['action_type'],
                'disc_amount' => $data['disc_amount'],
                'disc_thresold' => $data['disc_threshold']
            ];
        }

        $data['actions'] = json_encode($data['actions']);
        $data['conditions'] = json_encode($data['conditions']);

        $data['coupon_usage'] = $data['use_coupon'];
        unset($data['coupon_usage']);

        if (isset($data['auto_generation'])) {
            $coupons['code'] = $data['code'];
            unset($data['code']);
        }

        if (isset($data['prefix'])) {
            $coupons['prefix'] = $data['prefix'];
            unset($data['prefix']);
        }

        if(isset($data['suffix'])) {
            $coupons['suffix'] = $data['suffix'];
            unset($data['suffix']);
        }

        if(isset($data['limit'])) {
            $coupons['limit'] = $data['usage_limit'];
            // unset($data['limit']);
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

                    $ruleLabelCreated = $this->cartRuleLabel->create($label1);
                }
            }
        } else {
            $label2['label'] = $labels['global'];
            $ruleLabelCreated = $this->cartRuleLabel->create($label2);
        }

        if(isset($coupons)) {
            $coupons['cart_rule_id'] = $ruleCreated->id;
            $coupons['usage_per_customer'] = $data['per_customer']; //0 is for unlimited usage

            $couponCreated = $this->cartRuleCoupon->create($coupons);
        }

        if ($ruleCreated && $ruleGroupCreated && $ruleChannelCreated) {
            if ($couponCreated) {
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

    public function update()
    {
        $data = request()->all();

        //assumed default
        $data['limit'] = 10;

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

        $data['conditions'] = $data['all_conditions'];
        unset($data['all_conditions']);

        $data['disc_amount'] = 1;

        if (isset($data['disc_amount'])) {
            $data['actions'] = [
                'action_type' => $data['action_type'],
                'disc_amount' => $data['disc_amount'],
                'disc_thresold' => $data['disc_threshold']
            ];
        }

        $data['actions'] = json_encode($data['actions']);
        $data['conditions'] = json_encode($data['conditions']);

        $data['coupon_usage'] = $data['use_coupon'];
        unset($data['use_coupon']);

        $coupons['code'] = $data['code'];
        unset($data['code']);
        if (isset($data['prefix'])) {
            $coupons['prefix'] = $data['prefix'];
            unset($data['prefix']);
        }

        if(isset($data['suffix'])) {
            $coupons['suffix'] = $data['suffix'];
            unset($data['suffix']);
        }

        if(isset($data['limit'])) {
            $coupons['limit'] = $data['limit'];
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

                    $ruleLabelCreated = $this->cartRuleLabel->create($label1);
                }
            }
        } else {
            $label2['label'] = $labels['global'];
            $ruleLabelCreated = $this->cartRuleLabel->create($label2);
        }

        if(isset($coupons)) {
            $coupons['cart_rule_id'] = $ruleCreated->id;
            $coupons['usage_per_customer'] = $data['per_customer']; //0 is for unlimited usage

            $couponCreated = $this->cartRuleCoupon->create($coupons);
        }

        if ($ruleCreated && $ruleGroupCreated && $ruleChannelCreated) {
            if ($couponCreated) {
                session()->flash('success', trans('admin::app.promotion.status.success-coupon'));
            }

            session()->flash('success', trans('admin::app.promotion.status.success'));
        } else {
            session()->flash('success', trans('admin::app.promotion.status.success'));

            return redirect()->back();
        }

        return redirect()->route($this->_config['route']);
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
