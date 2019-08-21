<?php

namespace Webkul\Discount\Http\Controllers;

use Webkul\Discount\Repositories\CartRuleRepository as CartRule;
use Webkul\Category\Repositories\CategoryRepository as Category;
use Webkul\Attribute\Repositories\AttributeRepository as Attribute;
use Webkul\Discount\Helpers\Cart\ConvertXToProductId as ConvertX;
use Webkul\Discount\Repositories\CartRuleLabelsRepository as CartRuleLabels;
use Webkul\Discount\Repositories\CartRuleCouponsRepository as CartRuleCoupons;

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
     * To hold category repository instance
     */
    protected $category;

    /**
     * To hold attribute repository instance
     */
    protected $attribute;

    /**
     * To hold the cart repository instance
     */
    protected $cart;

    /**
     * Convert X To Product ID.
     *
     */
    protected $convertX;

    public function __construct(
        CartRule $cartRule,
        CartRuleCoupons $cartRuleCoupon,
        CartRuleLabels $cartRuleLabel,
        Attribute $attribute,
        Category $category,
        ConvertX $convertX
    )
    {
        $this->_config = request('_config');

        $this->cartRule = $cartRule;

        $this->cartRuleCoupon = $cartRuleCoupon;

        $this->cartRuleLabel = $cartRuleLabel;

        $this->attribute = $attribute;

        $this->category = $category;

        $this->convertX = $convertX;

        $this->appliedConfig = config('pricerules.cart');
    }

    /**
     * Loads the cart rule index page
     *
     * @return view
     */
    public function index()
    {
        return view($this->_config['view']);
    }

    /**
     * Loads the cart rule create page
     *
     * @return view
     */
    public function create()
    {
        return view($this->_config['view'])->with('cart_rule', [$this->appliedConfig, $this->category->getPartial(), $this->getStatesAndCountries(), $this->attribute->getPartial()]);
    }

    /**
     * Stores the response from cart rule store page to datastore
     *
     * @return redirect
     */
    public function store()
    {
        $this->validate(request(), [
            'name' => 'required|string|unique:cart_rules,name',
            'description' => 'string',
            'customer_groups' => 'required|array',
            'channels' => 'required|array',
            'status' => 'required|boolean',
            'use_coupon' => 'boolean|required',
            // 'usage_limit' => 'numeric|min:0',
            // 'per_customer' => 'numeric|min:0',
            'action_type' => 'required|string',
            'disc_amount' => 'required|numeric',
            'disc_quantity' => 'numeric',
            // 'disc_threshold' => 'numeric',
            'free_shipping' => 'required|boolean',
            'apply_to_shipping' => 'required|boolean',
            'code' => 'string|required_if:auto_generation,0',
            'all_conditions' => 'sometimes|nullable',
            'label' => 'array|nullable'
        ]);

        $data = request()->all();

        $attribute_conditions = $data['all_attributes'];
        unset($data['all_attributes']);

        // unset token
        unset($data['_token']);

        // check if starts_from is null
        if ($data['starts_from'] == "") {
            $data['starts_from'] = null;
        }

        // check if end_till is null
        if ($data['ends_till'] == "") {
            $data['ends_till'] = null;
        }

        // customer groups
        $customer_groups = $data['customer_groups'];

        // unset customer groups
        unset($data['customer_groups']);

        // unset criteria
        unset($data['criteria']);

        // channels
        $channels = $data['channels'];

        // unset channels
        unset($data['channels']);

        // make labels
        $labels = $data['label'];

        // unset labels
        unset($data['label']);

        // prepare json object from actions
        if (isset($data['disc_amount']) && $data['action_type'] == config('pricerules.cart.validations.2')) {
            $data['actions'] = [
                'action_type' => $data['action_type'],
                'disc_amount' => $data['disc_amount'],
                'disc_threshold' => $data['disc_threshold']
            ];

            $data['disc_quantity'] = $data['disc_amount'];
        } else {
            if (! isset($attribute_conditions) || $attribute_conditions == "[]" || $attribute_conditions == "") {
                $data['uses_attribute_conditions'] = 0;

                $data['actions'] = [
                    'action_type' => $data['action_type'],
                    'disc_amount' => $data['disc_amount'],
                    'disc_quantity' => $data['disc_quantity']
                ];
            } else {
                $data['uses_attribute_conditions'] = 1;

                $data['actions'] = [
                    'action_type' => $data['action_type'],
                    'disc_amount' => $data['disc_amount'],
                    'disc_quantity' => $data['disc_quantity'],
                    'attribute_conditions' => $attribute_conditions
                ];
            }
        }

        // prepare json object from conditions
        $data['actions'] = json_encode($data['actions']);

        // check if all
        if (! isset($data['all_conditions']) || $data['all_conditions'] == "[]" || $data['all_conditions'] == "") {
            $data['conditions'] = null;
        } else {
            $data['conditions'] = json_encode($data['all_conditions']);
        }

        // unset cart_attributes from conditions
        unset($data['cart_attributes']);

        // unset attributes from conditions
        unset($data['attributes']);

        // unset all_conditions from conditions
        unset($data['all_conditions']);

        // prepare coupons if coupons are used
        if ($data['use_coupon']) {
            // if (isset($data['auto_generation']) && $data['auto_generation']) {
            // auto generation is off for now
            $data['auto_generation'] = 0;

            // save the coupon used in coupon section
            $coupons['code'] = $data['code'];

            $couponExists = $this->cartRuleCoupon->findWhere([
                'code' => $coupons['code']
            ]);

            if ($couponExists->count()) {
                session()->flash('warning', trans('admin::app.promotion.status.duplicate-coupon'));

                return redirect()->back();
            }

            // set coupon usage per customer same as per_customer limit which is disabled for now
            // $coupons['usage_per_customer'] = $data['per_customer']; //0 is for unlimited usage
            // unset coupon code from coupon section
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

            // $coupons['limit'] = 0;
        }

        // per coupon usage limit
        // if(isset($data['usage_limit'])) {
        //     $coupons['limit'] = $data['usage_limit'];
        // }

        // create a cart rule
        $ruleCreated = $this->cartRule->create($data);

        // can execute convert x here after when the rule is updated
        if (isset($attribute_conditions) && $attribute_conditions != "[]" && $attribute_conditions != "") {
            $this->convertX->convertX($ruleCreated->id, $attribute_conditions);
        }

        // create customer groups for cart rule
        $ruleGroupCreated = $this->cartRule->CustomerGroupSync($customer_groups, $ruleCreated);

        // create customer groups for channels
        $ruleChannelCreated = $this->cartRule->ChannelSync($channels, $ruleCreated);

        // prepare labels
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

        // create coupon if present
        if (isset($coupons)) {
            $coupons['cart_rule_id'] = $ruleCreated->id;
            $couponCreated = $this->cartRuleCoupon->create($coupons);
        }

        if ($ruleCreated && $ruleChannelCreated && $ruleGroupCreated) {
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

    /**
     * Loads the cart rule edit page
     *
     * @return view
     */
    public function edit($id)
    {
        $cart_rule = $this->cartRule->find($id);

        return view($this->_config['view'])->with('cart_rule', [
            $this->appliedConfig,
            $this->category->getPartial(),
            $this->getStatesAndCountries(),
            $cart_rule,
            $this->attribute->getPartial()
        ]);
    }

    /**
     * Collects the response from cart rule update page and update datastore
     *
     * @return redirect
     */
    public function update($id)
    {
        $this->validate(request(), [
            'name' => 'required|string|unique:cart_rules,name,'.$id,
            'description' => 'string',
            'customer_groups' => 'required|array',
            'channels' => 'required|array',
            'status' => 'required|boolean',
            'use_coupon' => 'boolean|required',
            // 'usage_limit' => 'numeric|min:0',
            // 'per_customer' => 'numeric|min:0',
            'action_type' => 'required|string',
            'disc_amount' => 'required|numeric',
            'disc_quantity' => 'required|numeric',
            // 'disc_threshold' => 'required|numeric',
            'free_shipping' => 'required|boolean',
            'apply_to_shipping' => 'required|boolean',
            'code' => 'string|required_if:user_coupon,1',
            'all_conditions' => 'present',
            'label' => 'array|nullable'
        ]);

        // collecting request in $data
        $data = request()->all();

        $attribute_conditions = $data['all_attributes'];
        unset($data['all_attributes']);

        // unset request token from $data
        unset($data['_token']);

        // check if starts_from is null
        if ($data['starts_from'] == "") {
            $data['starts_from'] = null;
        }

        // check if end_till is null
        if ($data['ends_till'] == "") {
            $data['ends_till'] = null;
        }

        // set channels
        $channels = $data['channels'];

        // unset the channels from $data
        unset($data['channels']);

        // set customer_groups
        $customer_groups = $data['customer_groups'];

        // unset customer groups
        unset($data['customer_groups']);

        // set labels and unset them from $data
        if (isset($data['label'])) {
            $labels = $data['label'];

            unset($data['label']);
        }

        // unset cart_attributes and attributes from $data
        unset($data['cart_attributes']);
        unset($data['attributes']);

        // prepare actions from data for json action
        if (isset($data['disc_amount']) && $data['action_type'] == config('pricerules.cart.validations.2')) {
            $data['actions'] = [
                'action_type' => $data['action_type'],
                'disc_amount' => $data['disc_amount'],
            ];

            $data['disc_quantity'] = $data['disc_amount'];
        } else {
            if (! isset($attribute_conditions) || $attribute_conditions == "[]" || $attribute_conditions == "") {
                $data['uses_attribute_conditions'] = 0;

                $data['actions'] = [
                    'action_type' => $data['action_type'],
                    'disc_amount' => $data['disc_amount'],
                    'disc_quantity' => $data['disc_quantity']
                ];
            } else {
                $data['uses_attribute_conditions'] = 1;

                $data['actions'] = [
                    'action_type' => $data['action_type'],
                    'disc_amount' => $data['disc_amount'],
                    'disc_quantity' => $data['disc_quantity'],
                    'attribute_conditions' => $attribute_conditions
                ];
            }
        }

        // encode php array to json for actions
        $data['actions'] = json_encode($data['actions']);

        // Prepares conditions from all conditions
        if (! isset($data['all_conditions']) || $data['all_conditions'] == "[]" || $data['all_conditions'] == "") {
            $data['conditions'] = null;
        } else {
            $data['conditions'] = json_encode($data['all_conditions']);
        }

        unset($data['match_criteria']);

        // unset all_conditions from conditions
        unset($data['all_conditions']);

        // set coupons from $data
        if ($data['use_coupon']) {
            // if (isset($data['auto_generation']) && $data['auto_generation']) {
            $data['auto_generation'] = 0;

            $coupons['code'] = $data['code'];

            $couponExists = $this->cartRuleCoupon->findWhere([
                'code' => $coupons['code']
            ]);

            if ($couponExists->count()) {
                if ($couponExists->first()->cart_rule_id != $id) {
                    session()->flash('warning', trans('admin::app.promotion.status.duplicate-coupon'));

                    return redirect()->back();
                }
            }

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
            // $coupons['limit'] = 0;
        }

        // if (isset($data['usage_limit'])) {
        //     $coupons['limit'] = $data['usage_limit'];
        // }

        // update cart rule
        $ruleUpdated = $this->cartRule->update($data, $id);

        if (isset($attribute_conditions) && $attribute_conditions != "[]" && $attribute_conditions != "") {
            // can execute convert X here after when the rule is updated
            $this->convertX->convertX($ruleUpdated->id, $attribute_conditions);
        } else {
            $ruleUpdated->update([
                'product_ids' => null
            ]);
        }

        // update customer groups for cart rule
        $ruleGroupUpdated = $this->cartRule->CustomerGroupSync($customer_groups, $ruleUpdated);

        // update customer groups for cart rule
        $ruleChannelUpdated = $this->cartRule->ChannelSync($channels, $ruleUpdated);

        // update labels
        $labelsUpdated = $this->cartRule->LabelSync($labels, $ruleUpdated);

        // check coupons set conditions
        if (isset($coupons)) {
            // $coupons['usage_per_customer'] = $data['per_customer']; //0 is for unlimited usage
            $coupons['cart_rule_id'] = $ruleUpdated->id;

            if ($ruleUpdated->coupons == null) {
                $couponCreatedOrUpdated = $this->cartRuleCoupon->create($coupons);
            } else {
                $couponCreatedOrUpdated = $ruleUpdated->coupons->update($coupons);
            }
        } else {
            if ($ruleUpdated->coupons != null) {
                $ruleUpdated->coupons->delete();
            }
        }

        if ($ruleUpdated && $ruleGroupUpdated && $ruleChannelUpdated) {
            if (isset($couponCreatedOrUpdated) && $couponCreatedOrUpdated) {
                session()->flash('success', trans('admin::app.promotion.status.success-coupon'));
            }

            session()->flash('success', trans('admin::app.promotion.status.update-success'));
        } else {
            session()->flash('success', trans('admin::app.promotion.status.update-success'));

            return redirect()->back();
        }

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Deletes the cart rule
     *
     * @return JSON
     */
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

    /**
     * Get Countries and states list from core helpers
     *
     * @return Array
     */
    public function getStatesAndCountries()
    {
        $countries = core()->countries()->toArray();
        $states = core()->groupedStatesByCountries();

        return [
            'countries' => $countries,
            'states' => $states
        ];
    }
}